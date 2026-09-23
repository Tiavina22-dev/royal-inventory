# Etat V1 du nouveau logiciel

Date: 2026-09-19

## Ce qui a ete cree

Une nouvelle application PHP/MySQL a ete creee dans `nouveau_site/`.

Fichiers principaux:

- `nouveau_site/index.php`
- `nouveau_site/login.php`
- `nouveau_site/logout.php`
- `nouveau_site/config/config.php`
- `nouveau_site/config/legacy_routes.php`
- `nouveau_site/app/*`
- `nouveau_site/app/Services/SalesService.php`
- `nouveau_site/app/Services/StockService.php`
- `nouveau_site/pages/*`
- `nouveau_site/actions/*`
- `nouveau_site/public/assets/css/app.css`
- `nouveau_site/public/assets/js/app.js`
- `nouveau_site/README.md`

`ancien_code/` n'a pas ete modifie.

## Ce qui fonctionne

- Structure moderne separee: configuration, services, actions, pages, assets.
- Authentification avec la table ancienne `user`.
- Protection CSRF sur les actions POST.
- Requetes preparees pour les nouvelles requetes.
- Echappement HTML via `e()`.
- Navigation superieure: `VENTE | STOCK | MVT | GERER | OUTILS`.
- Menu lateral responsive avec tous les modules demandes.
- Interface adaptee desktop, tablette et telephone.
- Passerelle "Ancienne interface" avec chemins centralises.

Module VENTE:

- Demarrage d'une vente.
- Recuperation des points de vente depuis `shop` ou `mvt`.
- Recherche d'articles dans `produit`.
- Panier dans `commande`.
- Modification quantite/prix/note.
- Suppression ligne panier.
- Annulation panier.
- Validation dans `mvt` et `recap_vente`.
- Historique des ventes.
- Details d'une vente.
- Recu A4 avec impression navigateur.

Autres modules:

- STOCK: lecture du stock courant.
- MVT: lecture des derniers mouvements.
- ARTICLES: lecture et creation simple.
- COMMANDES: lecture des paniers temporaires.
- RETOURS: lecture des retours en preparation.
- DEPENSES: lecture des depenses.
- CONTROLE: lecture des rectifications en cours.
- RAPPORTS: synthese ventes par point de vente.
- FACTURES: lecture des factures validees.
- UTILISATEURS: lecture des utilisateurs.
- PARAMETRES: configuration et points de vente detectes.

## Ce qui depend encore de la vraie base

- Verification des colonnes exactes et contraintes SQL.
- Confirmation que `produit` accepte la creation simple avec les colonnes utilisees.
- Verification des valeurs de `shop`.
- Confirmation des droits utilisateurs existants.
- Validation du calcul `recap_vente` face a l'ancien logiciel.
- Presence ou non de Dompdf pour un export PDF reel.

## Risques

- Les mots de passe de l'ancien logiciel sont en clair; la V1 les lit pour compatibilite mais ne corrige pas encore ce point.
- Les colonnes exactes n'ont pas ete verifiees par schema SQL.
- Le flux Andrefana reste volontairement non connecte.
- Les operations stock/facture/controle peuvent changer plusieurs tables; elles sont laissees en attente pour eviter une mauvaise ecriture.
- L'ancienne base peut contenir des donnees avec formats de dates heterogenes.

## Tests a effectuer

1. Lancer l'application avec XAMPP.
2. Tester connexion avec un utilisateur `Permission='Y'`.
3. Tester recherche produit.
4. Demarrer une vente simple.
5. Ajouter, modifier, supprimer des lignes panier.
6. Valider la vente.
7. Comparer dans MySQL:
   - `mvt.type_de_mvt='vente'`;
   - `mvt.qt` negatif;
   - `recap_vente.no_activite`;
   - panier `commande` vide pour l'utilisateur;
   - depenses temporaires supprimees si presentes.
8. Imprimer le recu A4.
9. Tester responsive mobile.
10. Comparer une vente V1 avec une vente creee par l'ancien logiciel.

