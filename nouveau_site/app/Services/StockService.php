<?php

declare(strict_types=1);

final class StockService
{
    private Database $db;
    private Repository $repository;

    public function __construct(Database $db, Repository $repository)
    {
        $this->db = $db;
        $this->repository = $repository;
    }

    public function currentStock(string $query = '', string $shop = '', int $limit = 80): array
    {
        $limit = max(1, min($limit, 200));
        $params = ['OFF'];
        $where = 'm.status != ?';

        if ($shop !== '') {
            $where .= ' AND m.nom_client_fournisseur = ?';
            $params[] = $shop;
        }

        $having = '';
        if ($query !== '') {
            $having = 'HAVING nom_x LIKE ? OR reference_x LIKE ? OR CAST(id_x AS CHAR) LIKE ?';
            $like = '%' . $query . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return $this->db->fetchAll(
            "SELECT p.id_x, p.reference_x, p.nom_x, p.prix_de_vente, p.prix_fournisseur,
                    SUM(CASE WHEN m.type_de_mvt = 'stock' THEN m.qt ELSE 0 END) AS total_stock,
                    SUM(CASE WHEN m.type_de_mvt = 'vente' THEN m.qt ELSE 0 END) AS total_vente,
                    SUM(m.qt) AS restant
             FROM mvt m
             INNER JOIN produit p ON p.id_x = m.id_x
             WHERE {$where}
             GROUP BY p.id_x, p.reference_x, p.nom_x, p.prix_de_vente, p.prix_fournisseur
             {$having}
             ORDER BY p.nom_x
             LIMIT {$limit}",
            $params
        );
    }

    public function recentStockMoves(int $limit = 40): array
    {
        $limit = max(1, min($limit, 100));
        return $this->db->fetchAll(
            "SELECT m.*, p.reference_x, p.nom_x
             FROM mvt m
             LEFT JOIN produit p ON p.id_x = m.id_x
             WHERE m.type_de_mvt = 'stock'
             ORDER BY m.id_mvt DESC
             LIMIT {$limit}"
        );
    }

    public function currentContext(string $username): ?array
    {
        return $this->db->fetch(
            'SELECT stock_client_name, stock_numero_commande, stock_description_date FROM user WHERE User_Name = ?',
            [$username]
        );
    }

    public function preparedLines(string $username, string $operation): array
    {
        $marker = $this->operationMarker($operation);
        return $this->db->fetchAll(
            "SELECT sp.*, p.reference_x, p.nom_x, p.prix_fournisseur
             FROM stock_prep sp
             LEFT JOIN produit p ON p.id_x = sp.id_x
             WHERE sp.description_date LIKE ? AND sp.user_stock_prep = ?
             ORDER BY sp.id_stock_prep ASC",
            ['%' . $marker . '%', $username]
        );
    }

    public function startOperation(string $username, string $pointOfSale, string $date, string $operation): int
    {
        $pointOfSale = trim($pointOfSale);
        if ($pointOfSale === '') {
            throw new InvalidArgumentException('Point de vente obligatoire.');
        }

        $prefix = $operation === 'out' ? 'Reduit/Retirer' : 'Inventaire/Ajout';
        $description = today_description($date, $prefix);

        return (int) $this->db->transaction(function () use ($username, $pointOfSale, $description) {
            $memoNext = (int) $this->db->scalar('SELECT COALESCE(valeur_memo, 0) + 1 FROM memo WHERE id_memo = 2');
            $mvtMax = (int) $this->db->scalar("SELECT COALESCE(MAX(numero_commande_stock), 0) FROM mvt WHERE type_de_mvt = 'stock'");
            $prepMax = (int) $this->db->scalar('SELECT COALESCE(MAX(numero_stock_prep), 0) FROM stock_prep');
            $next = max($memoNext, $mvtMax + 1, $prepMax + 1);

            $this->db->execute(
                'UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 2',
                [$pointOfSale, $next, $description]
            );
            $this->db->execute(
                'UPDATE user SET stock_client_name = ?, stock_numero_commande = ?, stock_description_date = ? WHERE User_Name = ?',
                [$pointOfSale, $next, $description, $username]
            );

            return $next;
        });
    }

    public function addPreparedLine(string $username, array $data): void
    {
        $operation = (string) ($data['operation'] ?? 'in');
        $context = $this->currentContext($username);
        if (!$context || empty($context['stock_numero_commande']) || empty($context['stock_client_name'])) {
            throw new RuntimeException('Demarre d abord un contexte stock.');
        }

        $qty = (float) ($data['qt'] ?? 0);
        if ($qty <= 0) {
            throw new InvalidArgumentException('Quantite obligatoire et positive.');
        }

        $productId = (int) ($data['id_x'] ?? 0);
        $product = $this->repository->product($productId);
        if (!$product) {
            throw new InvalidArgumentException('Article introuvable.');
        }

        $price = (float) ($data['prix_de_vente'] ?? 0);
        $clientPrice = $operation === 'in' ? (float) ($data['prix_client'] ?? $price) : 0.0;
        $supplierPrice = (float) ($data['prix_fournisseur'] ?? ($product['prix_fournisseur'] ?? 0));
        $note = trim((string) ($data['note_stock'] ?? ''));

        $marker = $this->operationMarker($operation);
        if (stripos((string) $context['stock_description_date'], $marker) === false) {
            throw new RuntimeException('Le contexte stock ne correspond pas a cette operation.');
        }

        $this->db->transaction(function () use ($context, $productId, $qty, $price, $clientPrice, $note, $username, $operation, $supplierPrice, $product): void {
            $this->db->execute(
                'INSERT INTO stock_prep(numero_stock_prep, nom_du_client, description_date, id_x, qt, prix_de_vente, prix_client, state, note, user_stock_prep)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    (int) $context['stock_numero_commande'],
                    (string) $context['stock_client_name'],
                    (string) $context['stock_description_date'],
                    $productId,
                    $qty,
                    $price,
                    $clientPrice,
                    'preparing',
                    $note,
                    $username,
                ]
            );

            if ($operation === 'in') {
                $oldSupplierPrice = (float) ($product['prix_fournisseur'] ?? 0);
                if ($supplierPrice !== $oldSupplierPrice) {
                    $this->db->execute('UPDATE produit SET prix_fournisseur = ? WHERE id_x = ?', [$supplierPrice, $productId]);
                    $this->db->execute(
                        'INSERT INTO history(after_change, before_change, details, responsable, type) VALUES (?, ?, ?, ?, ?)',
                        [
                            " | PU Fournisseur : <span class ='text-danger'><b>" . $supplierPrice . '</b></span></br>',
                            ' | PU Fournisseur : ' . $oldSupplierPrice . '</br>',
                            (string) $productId,
                            $username,
                            'Prix',
                        ]
                    );
                }
            }
        });
    }

    public function removePreparedLine(string $username, int $lineId): void
    {
        $this->db->execute('DELETE FROM stock_prep WHERE id_stock_prep = ? AND user_stock_prep = ?', [$lineId, $username]);
    }

    public function cancelPrepared(string $username, string $operation): void
    {
        $marker = $this->operationMarker($operation);
        $this->db->execute(
            'DELETE FROM stock_prep WHERE description_date LIKE ? AND user_stock_prep = ?',
            ['%' . $marker . '%', $username]
        );
    }

    public function validatePrepared(string $username, string $operation): int
    {
        $marker = $this->operationMarker($operation);
        $lines = $this->preparedLines($username, $operation);
        if (!$lines) {
            throw new RuntimeException('Aucune ligne stock a valider.');
        }

        return (int) $this->db->transaction(function () use ($lines, $username, $operation, $marker): int {
            $numero = (int) $lines[0]['numero_stock_prep'];
            $no = 0;
            foreach ($lines as $line) {
                $no++;
                $product = $this->repository->product((int) $line['id_x']);
                if (!$product) {
                    throw new RuntimeException('Article introuvable pendant la validation stock.');
                }

                $point = (string) $line['nom_du_client'];
                $price = (float) $line['prix_de_vente'];
                $qty = (float) $line['qt'];
                if ($operation === 'out') {
                    $qty *= -1;
                }

                $this->db->execute(
                    'INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, prix_client, nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock, note, user_mvt, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        'stock',
                        (int) $line['id_x'],
                        $qty,
                        $price,
                        (float) ($line['prix_client'] ?? 0),
                        $point,
                        (string) $line['description_date'],
                        $numero,
                        $numero . '-' . $no,
                        (string) ($line['note'] ?? ''),
                        (string) $line['user_stock_prep'],
                        $this->journalDate((string) $line['description_date']),
                    ]
                );

                if ($operation === 'in') {
                    $this->updateOfficialPrice($product, $point, $price, (string) $line['user_stock_prep']);
                }
            }

            $this->db->execute(
                'DELETE FROM stock_prep WHERE description_date LIKE ? AND user_stock_prep = ?',
                ['%' . $marker . '%', $username]
            );

            return $numero;
        });
    }

    public function addProduct(array $data, string $username): int
    {
        $name = trim((string) ($data['nom_x'] ?? ''));
        $reference = strtoupper(trim((string) ($data['reference_x'] ?? '')));
        if ($name === '' || $reference === '') {
            throw new InvalidArgumentException('Nom et reference sont obligatoires.');
        }

        $this->db->execute(
            'INSERT INTO produit(nom_x, reference_x, prix_de_vente, prix_fournisseur, note_x, user_x)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $name,
                $reference,
                (float) ($data['prix_de_vente'] ?? 0),
                (float) ($data['prix_fournisseur'] ?? 0),
                trim((string) ($data['note_x'] ?? '')),
                $username,
            ]
        );

        return (int) $this->db->pdo()->lastInsertId();
    }

    private function operationMarker(string $operation): string
    {
        return $operation === 'out' ? 'Retirer' : 'Ajout';
    }

    private function journalDate(string $description): ?string
    {
        if (!preg_match('/([0-9]{2,4}[\/-][0-9]{2,4}[\/-][0-9]{2,4})/', $description, $matches)) {
            return null;
        }

        $date = str_replace('/', '-', $matches[1]);
        $dt = DateTime::createFromFormat('d-m-y', $date);
        return $dt ? $dt->format('Y-m-d') : null;
    }

    private function updateOfficialPrice(array $product, string $point, float $price, string $username): void
    {
        $productId = (int) $product['id_x'];
        $map = [
            'Ambato_Tantely' => ['pu_ambato_tantely', 'note_prix_tantely', 'difference_prix_tantely'],
            'Amparafa' => ['pu_aparafa', 'note_prix_amparafa', 'difference_prix_amparafa'],
            'Soalazaina' => ['pu_soalazaina', 'note_prix_soalazaina', 'difference_prix_soalazaina'],
        ];

        [$priceColumn, $noteColumn, $diffColumn] = $map[$point] ?? ['prix_de_vente', 'note_prix', 'difference_prix'];
        $oldPrice = (float) ($product[$priceColumn] ?? 0);
        $difference = $price - $oldPrice;
        $notePrice = $difference != 0.0 ? 'NEW' : (string) ($product[$noteColumn] ?? '');

        $this->db->execute(
            "UPDATE produit SET {$priceColumn} = ?, note_x = ?, user_x = ?, {$noteColumn} = ?, {$diffColumn} = ? WHERE id_x = ?",
            [$price, 'latest', $username, $notePrice, $difference != 0.0 ? $difference : (float) ($product[$diffColumn] ?? 0), $productId]
        );
    }
}
