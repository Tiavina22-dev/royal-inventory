<?php

declare(strict_types=1);

final class SalesService
{
    private Database $db;
    private Repository $repository;

    public function __construct(Database $db, Repository $repository)
    {
        $this->db = $db;
        $this->repository = $repository;
    }

    public function currentContext(string $username): ?array
    {
        return $this->db->fetch(
            'SELECT User_Name, numero_commande, point_de_vente, description_date FROM user WHERE User_Name = ?',
            [$username]
        );
    }

    public function startSale(string $username, string $pointOfSale, string $date, string $note = ''): int
    {
        $this->assertStandardSalePoint($pointOfSale);
        $description = today_description($date, 'Journal');

        return (int) $this->db->transaction(function () use ($username, $pointOfSale, $description) {
            $memoNext = (int) $this->db->scalar('SELECT COALESCE(valeur_memo, 0) + 1 FROM memo WHERE id_memo = 1');
            $mvtMax = (int) $this->db->scalar("SELECT COALESCE(MAX(numero_commande_stock), 0) FROM mvt WHERE type_de_mvt = 'vente'");
            $cmdMax = (int) $this->db->scalar('SELECT COALESCE(MAX(numero_commande), 0) FROM commande');
            $next = max($memoNext, $mvtMax + 1, $cmdMax + 1);

            $this->db->execute(
                'UPDATE memo SET note_memo = ?, valeur_memo = ?, description_date = ? WHERE id_memo = 1',
                [$pointOfSale, $next, $description]
            );

            $this->db->execute(
                'UPDATE user SET point_de_vente = ?, numero_commande = ?, description_date = ? WHERE User_Name = ?',
                [$pointOfSale, $next, $description, $username]
            );

            return $next;
        });
    }

    public function cart(string $username): array
    {
        return $this->db->fetchAll(
            'SELECT c.id_commande, c.numero_commande, c.nom_du_client, c.description_date, c.id_x,
                    c.qt, c.prix_de_vente, c.prix_client, c.note_commande,
                    (c.prix_de_vente) * c.qt AS sous_total,
                    (c.prix_client) * c.qt AS sous_total_client,
                    p.reference_x, p.nom_x
             FROM commande c
             INNER JOIN produit p ON p.id_x = c.id_x
             WHERE c.user = ?
             ORDER BY c.id_commande ASC',
            [$username]
        );
    }

    public function addCartLine(string $username, int $productId, float $qty, float $price, float $clientPrice, string $note): void
    {
        if ($qty <= 0) {
            throw new InvalidArgumentException('La quantite doit etre superieure a zero.');
        }

        $context = $this->currentContext($username);
        if (!$context || empty($context['numero_commande']) || empty($context['point_de_vente'])) {
            throw new RuntimeException('Demarre une vente avant d ajouter un article.');
        }
        $this->assertStandardSalePoint((string) $context['point_de_vente']);

        if (!$this->repository->product($productId)) {
            throw new RuntimeException('Article introuvable.');
        }

        $this->db->execute(
            'INSERT INTO commande(numero_commande, nom_du_client, description_date, id_x, qt, prix_de_vente, prix_client, state, note_commande, user)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $context['numero_commande'],
                (string) $context['point_de_vente'],
                (string) $context['description_date'],
                $productId,
                $qty,
                $price,
                $clientPrice,
                'preparing',
                $note,
                $username,
            ]
        );
    }

    public function updateCartLine(string $username, int $lineId, float $qty, float $price, float $clientPrice, string $note): void
    {
        if ($qty <= 0) {
            throw new InvalidArgumentException('La quantite doit etre superieure a zero.');
        }

        $this->db->execute(
            'UPDATE commande SET qt = ?, prix_de_vente = ?, prix_client = ?, note_commande = ? WHERE id_commande = ? AND user = ?',
            [$qty, $price, $clientPrice, $note, $lineId, $username]
        );
    }

    public function removeCartLine(string $username, int $lineId): void
    {
        $this->db->execute('DELETE FROM commande WHERE id_commande = ? AND user = ?', [$lineId, $username]);
    }

    public function cancelSale(string $username): void
    {
        $context = $this->currentContext($username);
        $activityNo = (int) ($context['numero_commande'] ?? 0);

        $this->db->transaction(function () use ($username, $activityNo) {
            $this->db->execute('DELETE FROM commande WHERE user = ?', [$username]);
            if ($activityNo > 0) {
                $this->db->execute('DELETE FROM depense WHERE activity_no = ?', [$activityNo]);
            }
        });
    }

    public function totals(array $cart, string $pointOfSale = ''): array
    {
        $royal = 0.0;
        $client = 0.0;
        foreach ($cart as $line) {
            $qty = (float) $line['qt'];
            $royal += ((float) $line['prix_de_vente']) * $qty;
            $client += ((float) $line['prix_client']) * $qty;
        }

        return [
            'royal' => $royal,
            'client' => $client,
            'difference' => $this->usesClientDifference($pointOfSale) ? $client - $royal : 0.0,
            'lines' => count($cart),
        ];
    }

    public function validateSale(string $username, array $payload): int
    {
        $cart = $this->cart($username);
        if (!$cart) {
            throw new RuntimeException('Aucune ligne de vente a valider.');
        }

        $context = $this->currentContext($username);
        $numero = (int) ($context['numero_commande'] ?? $cart[0]['numero_commande']);
        $point = (string) ($context['point_de_vente'] ?? $cart[0]['nom_du_client']);
        $description = (string) ($context['description_date'] ?? $cart[0]['description_date']);
        $this->assertStandardSalePoint($point);
        if (stripos($description, 'ANDREFANA') !== false || stripos($description, 'MORARANO') !== false) {
            throw new RuntimeException('Ce cas de vente doit temporairement passer par l ancienne interface: flux Andrefana/Morarano non connecte.');
        }
        $dateMysql = $this->extractMysqlDate($description);
        $totals = $this->totals($cart, $point);

        $versement = (float) ($payload['versement'] ?? $totals['royal']);
        $resolution = -abs((float) ($payload['resolution'] ?? 0));
        $mihoatra = abs((float) ($payload['mihoatra'] ?? 0));
        $noteGeneral = trim((string) ($payload['note_general'] ?? ''));
        $path = trim((string) ($payload['path'] ?? ''));
        $cPoint = trim((string) ($payload['point'] ?? ''));

        $depenses = $this->depensesForActivity($numero);
        $history = $this->buildHistory($cart, $totals, $depenses, $point, $versement);

        $this->db->transaction(function () use ($cart, $username, $numero, $point, $description, $dateMysql, $versement, $resolution, $mihoatra, $history, $noteGeneral, $path, $cPoint, $totals) {
            $i = 0;
            foreach ($cart as $line) {
                $i++;
                $this->db->execute(
                    'INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, prix_client, nom_client_fournisseur, description_date,
                                      numero_commande_stock, ref_commande_stock, note, user_mvt, Date_du_Journal_mvt)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                    [
                        'vente',
                        (int) $line['id_x'],
                        -abs((float) $line['qt']),
                        (float) $line['prix_de_vente'],
                        (float) $line['prix_client'],
                        $point,
                        $description,
                        $numero,
                        $numero . '-' . $i,
                        (string) $line['note_commande'],
                        $username,
                        $dateMysql,
                    ]
                );
            }

            $this->db->execute(
                'INSERT INTO recap_vente(no_activite, nb_ligne, Montant, difference_aparafa, resolution, mihoatra, history,
                                          note_general, c_point, directory, status, Date_du_Journal, responsable)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $numero,
                    count($cart),
                    $versement,
                    $totals['difference'],
                    $resolution,
                    $mihoatra,
                    $history,
                    $noteGeneral,
                    $cPoint,
                    $path,
                    'NON_RESOLU',
                    $dateMysql,
                    'No_Change',
                ]
            );

            $this->db->execute('DELETE FROM commande WHERE user = ?', [$username]);
        });

        return $numero;
    }

    public function recentSales(int $limit = 30): array
    {
        $limit = max(1, min($limit, 100));
        return $this->db->fetchAll(
            "SELECT m.numero_commande_stock, m.nom_client_fournisseur, m.description_date,
                    MAX(m.Date_du_Journal_mvt) AS Date_du_Journal_mvt,
                    COUNT(*) AS nb_lignes,
                    r.Montant, r.difference_aparafa, r.resolution, r.mihoatra, r.note_general
             FROM mvt m
             LEFT JOIN recap_vente r ON r.no_activite = m.numero_commande_stock
             WHERE m.type_de_mvt = 'vente'
             GROUP BY m.numero_commande_stock, m.nom_client_fournisseur, m.description_date, r.Montant, r.difference_aparafa, r.resolution, r.mihoatra, r.note_general
             ORDER BY MAX(m.id_mvt) DESC
             LIMIT {$limit}"
        );
    }

    public function saleDetails(int $saleNo): array
    {
        return $this->db->fetchAll(
            'SELECT m.*, p.reference_x, p.nom_x, p.note_x
             FROM mvt m
             LEFT JOIN produit p ON p.id_x = m.id_x
             WHERE m.type_de_mvt = ? AND m.numero_commande_stock = ?
             ORDER BY m.id_mvt DESC',
            ['vente', $saleNo]
        );
    }

    public function recap(int $saleNo): ?array
    {
        return $this->db->fetch('SELECT * FROM recap_vente WHERE no_activite = ?', [$saleNo]);
    }

    private function depensesForActivity(int $activityNo): array
    {
        $rows = $this->db->fetchAll('SELECT * FROM depense WHERE activity_no = ?', [$activityNo]);
        $royal = 0.0;
        $seller = 0.0;
        foreach ($rows as $row) {
            $royal += (float) ($row['montant'] ?? 0);
            $seller += (float) ($row['depense_aparafa'] ?? 0);
        }
        return ['rows' => $rows, 'royal' => $royal, 'seller' => $seller, 'total' => $royal + $seller];
    }

    private function buildHistory(array $cart, array $totals, array $depenses, string $point, float $versement): string
    {
        $history = '';
        $i = 0;
        foreach ($cart as $line) {
            $i++;
            $price = $line['prix_de_vente'];
            $client = ((float) $line['prix_client']) + 0;
            $history .= '=> ' . $i . ' # ' . $line['nom_x'] . ' | ' . $line['note_commande'] .
                ' # qt: ' . $line['qt'] .
                ' # PU_ROY: ' . $price .
                ' # PU_CLI: ' . $client .
                ' # MT_ROY: ' . $line['sous_total'] .
                ' # ' . strtoupper($point) . ' : ' . $line['sous_total_client'] .
                ' # BC: ' . (($client - $price) * $line['qt']) . ' #' . "\r";
        }

        return $history . "\r" .
            '----------------------------------------------------------------' . "\r" .
            '----------TL VENTE:' . $totals['royal'] . ' Ar----------' . "\r" .
            '----------------------------------------------------------------' . "\r" .
            '-----------------SPECIAL ' . strtoupper($point) . '------------------' . "\r" .
            ' IVAROTANY TL:' . $totals['client'] . ' Ar | BENEFICE:' . $totals['difference'] . ' Ar | NALAINY:' . $depenses['seller'] . "\r" .
            '------------------------DEPENSE---------------------------' . "\r" .
            'DEPENSE ROYAL:' . $depenses['royal'] . ' Ar | VOLA NALAIN NY ' . strtoupper($point) . ':' . $depenses['seller'] . ' Ar | TL DEPENSE:' . $depenses['total'] . ' Ar' . "\r" .
            '-----------------------------------------------------------------' . "\r" .
            '--------NET POUR ROYAL:' . $versement . ' Ar-------' . "\r" .
            '-----------------------------------------------------------------';
    }

    private function usesClientDifference(string $pointOfSale): bool
    {
        return in_array(strtolower($pointOfSale), ['amparafa', 'soalazaina', 'bejofo'], true);
    }

    private function assertStandardSalePoint(string $pointOfSale): void
    {
        if (in_array(strtolower($pointOfSale), ['andrefana', 'morarano'], true)) {
            throw new RuntimeException('Ce point de vente doit temporairement passer par l ancienne interface: flux specifique non connecte.');
        }
    }

    private function extractMysqlDate(string $description): ?string
    {
        if (!preg_match("'([0-9]{2,4}[#/\\-]{1,2}[0-9]{2,4}[#/\\-]{1,2}[0-9]{2,4})'", $description, $match)) {
            return date('Y-m-d');
        }

        $date = str_replace(['#', '/', ' '], ['-', '-', ''], $match[1]);
        foreach (['d-m-y', 'd-m-Y', 'Y-m-d'] as $format) {
            $dt = DateTime::createFromFormat($format, $date);
            if ($dt instanceof DateTime) {
                return $dt->format('Y-m-d');
            }
        }

        return date('Y-m-d');
    }
}
