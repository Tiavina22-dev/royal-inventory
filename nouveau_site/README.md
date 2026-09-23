# Royal Inventory V1

Nouvelle application PHP/MySQL dans `nouveau_site/`, construite pour remplacer progressivement l'interface de l'ancien logiciel sans modifier `ancien_code/`.

## Architecture

- `index.php`: routeur principal des pages authentifiees.
- `login.php`, `logout.php`: connexion/deconnexion avec la table `user` existante.
- `config/config.php`: configuration application et MySQL.
- `config/legacy_routes.php`: correspondances centralisees vers l'ancienne interface.
- `app/`: noyau PHP, securite, connexion PDO, services metier.
- `app/Services/SalesService.php`: logique VENTE connectee.
- `app/Services/StockService.php`: lectures stock et creation simple article.
- `pages/`: vues par module.
- `actions/`: traitements POST avec CSRF.
- `partials/`: layout commun.
- `public/assets/`: CSS et JavaScript.

## Configuration MySQL

Par defaut, l'application utilise:

- hote: `127.0.0.1`
- port: `3306`
- base: `gestion_stock`
- utilisateur: `root`
- mot de passe: vide

Ces valeurs peuvent etre surchargees par variables d'environnement:

- `RI_DB_HOST`
- `RI_DB_PORT`
- `RI_DB_NAME`
- `RI_DB_USER`
- `RI_DB_PASS`
- `RI_DB_CHARSET`
- `RI_LEGACY_BASE_URL`

## Lancement avec XAMPP

1. Demarrer Apache et MySQL/MariaDB dans XAMPP.
2. Verifier que la base ancienne `gestion_stock` est importee.
3. Placer le dossier projet dans un emplacement servi par Apache, ou configurer un alias vers `nouveau_site/`.
4. Ouvrir `http://localhost/.../nouveau_site/login.php`.
5. Se connecter avec un utilisateur existant de la table `user` ayant `Permission = 'Y'`.

## Modules disponibles

- Tableau de bord
- Vente
- Stock
- Mouvements
- Articles
- Commandes
- Retours
- Depenses
- Controle
- Rapports / Analyse
- Factures / Recus
- Utilisateurs
- Parametres
- Ancienne interface

## Fonctions reellement connectees

- Authentification via table `user`.
- Lecture des points de vente depuis `shop`, avec fallback depuis `mvt`.
- Recherche articles depuis `produit`.
- VENTE:
  - demarrage de vente par mise a jour du contexte `user`;
  - ajout au panier `commande`;
  - modification/suppression de lignes panier;
  - annulation panier avec suppression des depenses liees a l'activite;
  - validation vers `mvt` avec `type_de_mvt='vente'`;
  - creation `recap_vente`;
  - suppression du panier `commande`;
  - historique ventes et details;
  - recu imprimable A4.
- STOCK:
  - lecture stock actuel depuis `mvt` + `produit`;
  - derniers mouvements stock.
- MVT:
  - derniers mouvements de `mvt`.
- ARTICLES:
  - lecture des articles;
  - creation simple d'article dans `produit`.
- COMMANDES, RETOURS, DEPENSES, CONTROLE, FACTURES, UTILISATEURS:
  - lecture connectee des tables anciennes.
- Ancienne interface:
  - liens centralises dans `config/legacy_routes.php`.

## Fonctions encore en attente

Ces operations existent dans l'interface mais ne sont pas encore connectees car elles sont critiques ou comportent des variantes a tester:

- validation stock complete;
- retrait/reduit stock;
- validation controle/inventaire;
- validation retours;
- validation facture avec mise a jour `prix_fournisseur`;
- gestion detaillee des permissions;
- modification/suppression definitive des mouvements depuis la nouvelle interface;
- export PDF reel.

Pour PDF, l'impression navigateur fonctionne. L'export PDF affiche un message tant qu'une bibliotheque compatible comme Dompdf n'est pas installee et branchee.

## Methode de test

1. Tester d'abord sur une copie de la base ancienne.
2. Se connecter avec un utilisateur autorise.
3. Creer une vente sur un petit panier.
4. Verifier apres validation:
   - lignes `mvt` avec `type_de_mvt='vente'`;
   - quantites negatives;
   - ligne `recap_vente`;
   - suppression des lignes `commande` de l'utilisateur;
   - recu imprimable.
5. Comparer le resultat avec une vente creee depuis l'ancien logiciel.
6. Tester affichage telephone/tablette avec les outils responsive du navigateur.

