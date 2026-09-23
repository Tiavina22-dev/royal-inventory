<?php

declare(strict_types=1);

final class Repository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function shops(): array
    {
        try {
            $shops = $this->db->fetchAll('SELECT short_name, long_name FROM shop ORDER BY long_name');
            if ($shops) {
                return $shops;
            }
        } catch (Throwable $e) {
        }

        try {
            return $this->db->fetchAll(
                'SELECT DISTINCT nom_client_fournisseur AS short_name, nom_client_fournisseur AS long_name
                 FROM mvt
                 WHERE nom_client_fournisseur IS NOT NULL AND nom_client_fournisseur <> ""
                 ORDER BY nom_client_fournisseur'
            );
        } catch (Throwable $e) {
            return [];
        }
    }

    public function products(string $query = '', int $limit = 30): array
    {
        $limit = max(1, min($limit, 100));
        if ($query === '') {
            return $this->db->fetchAll(
                "SELECT id_x, reference_x, nom_x, prix_de_vente, pu_aparafa, pu_ambato_tantely, pu_soalazaina, prix_fournisseur, note_x, img_path_x
                 FROM produit
                 ORDER BY nom_x
                 LIMIT {$limit}"
            );
        }

        $like = '%' . $query . '%';
        return $this->db->fetchAll(
            "SELECT id_x, reference_x, nom_x, prix_de_vente, pu_aparafa, pu_ambato_tantely, pu_soalazaina, prix_fournisseur, note_x, img_path_x
             FROM produit
             WHERE nom_x LIKE ? OR reference_x LIKE ? OR CAST(id_x AS CHAR) LIKE ?
             ORDER BY nom_x
             LIMIT {$limit}",
            [$like, $like, $like]
        );
    }

    public function product(int $id): ?array
    {
        return $this->db->fetch('SELECT * FROM produit WHERE id_x = ?', [$id]);
    }

    public function dashboardCounts(): array
    {
        $counts = [
            'products' => 0,
            'sales' => 0,
            'stock_moves' => 0,
            'invoices' => 0,
            'shops' => 0,
        ];

        try {
            $counts['products'] = (int) $this->db->scalar('SELECT COUNT(*) FROM produit');
            $counts['sales'] = (int) $this->db->scalar("SELECT COUNT(DISTINCT numero_commande_stock) FROM mvt WHERE type_de_mvt = 'vente'");
            $counts['stock_moves'] = (int) $this->db->scalar("SELECT COUNT(DISTINCT numero_commande_stock) FROM mvt WHERE type_de_mvt = 'stock'");
            $counts['invoices'] = (int) $this->db->scalar("SELECT COUNT(DISTINCT numero_commande_stock) FROM mvt WHERE type_de_mvt = 'facture'");
            $counts['shops'] = (int) $this->db->scalar('SELECT COUNT(*) FROM shop');
        } catch (Throwable $e) {
        }

        return $counts;
    }

    public function recentMovements(int $limit = 12): array
    {
        $limit = max(1, min($limit, 50));
        try {
            return $this->db->fetchAll(
                "SELECT m.id_mvt, m.type_de_mvt, m.nom_client_fournisseur, m.description_date, m.numero_commande_stock,
                        m.qt, m.prix_unitaire, m.Date_du_Journal_mvt, p.reference_x, p.nom_x
                 FROM mvt m
                 LEFT JOIN produit p ON p.id_x = m.id_x
                 ORDER BY m.id_mvt DESC
                 LIMIT {$limit}"
            );
        } catch (Throwable $e) {
            return [];
        }
    }
}
