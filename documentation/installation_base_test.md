# Installation de la base MySQL de TEST

Ce guide concerne uniquement la base `gestion_stock_test`.

Ne pas importer ce fichier sur une base de production.

## Importer `schema_test.sql`

1. Ouvrir phpMyAdmin.
2. Cliquer sur la base `gestion_stock_test`.
3. Ouvrir l'onglet **Importer**.
4. Choisir le fichier `documentation/schema_test.sql`.
5. Laisser le format sur **SQL**.
6. Lancer l'import.

Le script cree les tables avec `CREATE TABLE IF NOT EXISTS` et ajoute seulement des donnees fictives marquees `TEST`.

## Compte de test cree

- Utilisateur: `test_admin`
- Mot de passe: `test123`
- Permission: `Y`

Ce compte est uniquement pour `gestion_stock_test`.

## Connecter `nouveau_site` a la base test

Le fichier lu par le nouveau site est:

- `nouveau_site/config/config.php`

La configuration actuelle utilise ces variables d'environnement si elles existent:

- `RI_DB_HOST`
- `RI_DB_PORT`
- `RI_DB_NAME`
- `RI_DB_USER`
- `RI_DB_PASS`
- `RI_DB_CHARSET`

Parametres a utiliser pour la base de test:

```text
RI_DB_HOST=127.0.0.1
RI_DB_PORT=3306
RI_DB_NAME=gestion_stock_test
RI_DB_USER=root
RI_DB_PASS=
RI_DB_CHARSET=utf8mb4
```

Si les identifiants MySQL locaux sont differents, remplacer seulement `RI_DB_USER` et `RI_DB_PASS`.

Ne pas modifier `ancien_code/`.

## Methode simple avec XAMPP / Apache

Option recommandee: definir les variables d'environnement pour cette installation de test, puis redemarrer Apache.

Si aucune variable d'environnement n'est definie, `nouveau_site/config/config.php` revient par defaut a:

```text
database=gestion_stock
username=root
password=
```

Il faut donc forcer `RI_DB_NAME=gestion_stock_test` avant de tester le nouveau site, sinon il cherchera la base `gestion_stock`.

## Tables creees

Le script cree 19 tables:

1. `user`
2. `shop`
3. `memo`
4. `produit`
5. `commande`
6. `vente_calc`
7. `stock_prep`
8. `stock_prep_calc`
9. `mvt`
10. `mvt_calc`
11. `mvt_history`
12. `recap_vente`
13. `recap_vente_calc`
14. `depense`
15. `history`
16. `checking`
17. `chat`
18. `contact`
19. `tbl_events`

## Incertitudes documentees

Le code historique ne contient pas de dump SQL complet. Les types ci-dessous sont donc reconstruits par compatibilite avec les requetes trouvees:

- les montants et prix sont en `DECIMAL(15,2)`;
- les quantites sont en `DECIMAL(15,3)`;
- les notes, historiques et motifs sont en `TEXT` ou `MEDIUMTEXT`;
- les dates historiques textuelles restent en `VARCHAR`, car l'ancien code stocke souvent des libelles comme `Journal du 01/01/26`;
- les colonnes `date_time` utilisent `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`, car l'ancien code les lit souvent sans les renseigner dans les `INSERT`;
- les relations ne sont pas forcees par des cles etrangeres pour rester compatibles avec l'ancien code, qui supprime et copie des lignes librement.

Tables annexes non creees: les modules tres secondaires de l'ancien code peuvent referencer d'autres tables selon les ecrans reactives plus tard. Pour tester le nouveau logiciel et les flux principaux VENTE/STOCK/recap, les tables ci-dessus couvrent les dependances detectees.

## Verification apres import

Dans phpMyAdmin, verifier que la base `gestion_stock_test` contient les tables ci-dessus, puis ouvrir `nouveau_site/login.php` et se connecter avec:

```text
test_admin / test123
```

Ne pas lancer de vente sur la base de production.
