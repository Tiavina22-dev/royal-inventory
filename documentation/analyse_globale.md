# Analyse globale de l'ancien logiciel

Date d'analyse: 2026-09-19

Perimetre analyse: `ancien_code/ancien-code`, `ancien_code/autres-codes`, `ancien_code/codes`, `ancien_code/modernisation`, `ancien_code/modernisation-logiciel`, avec rappel synthetique du module `ancien_code/vente` deja analyse.

Contraintes respectees:

- Aucun fichier de `ancien_code/` n'a ete modifie.
- Aucun fichier n'a ete cree dans `nouveau_site/`.
- Le module VENTE n'a pas ete reanalyse en detail, sauf pour comprendre les dependances avec les autres modules.

## Vue d'ensemble

L'ancien logiciel est une application PHP procedural autour d'une base MySQL `gestion_stock`. Les modules ne sont pas separes par classes ou API: les pages PHP melangent affichage HTML, requetes SQL, calculs metier, formulaires et redirections.

Le schema fonctionnel dominant est:

1. Une page demarre une activite et memorise le contexte dans `memo` ou `user`.
2. L'utilisateur ajoute des lignes dans une table temporaire: `commande`, `vente_calc` ou `stock_prep`.
3. Un script `valider_*` transforme les lignes temporaires en mouvements definitifs dans `mvt` ou `mvt_calc`.
4. Les tables temporaires sont supprimees par `anuler_*` ou `DELETE`.
5. Les modifications ulterieures ecrivent souvent dans `history`; les suppressions de mouvements copient vers `mvt_history` avant suppression.

Tables MySQL principales detectees:

- `mvt`: table centrale des mouvements valides (`stock`, `vente`, `facture`).
- `mvt_calc`: variante calculee, surtout Andrefana.
- `mvt_history`: corbeille/historique des mouvements supprimes.
- `produit`: articles, references, prix officiels et prix par point de vente.
- `commande`: panier temporaire de vente.
- `vente_calc`: panier temporaire de vente Andrefana.
- `stock_prep`: panier temporaire pour stock, retrait, facture, controle, retours.
- `recap_vente`: recapitulatif d'une vente validee.
- `recap_vente_calc`: recapitulatif Andrefana.
- `stock_recap`: recapitulatif stock/inventaire selon certains scripts.
- `depense`: depenses liees a une activite via `activity_no`.
- `user`: utilisateurs, sessions, permission et contexte courant.
- `history`: journal des changements, conges, prix et mouvements modifies.
- `shop`: points de vente.
- `memo`: memoire de demarrage d'activite.
- `contact`, `chat`, `tsinjo`, `tbl_events`, `checking`, `cisco`: modules auxiliaires.

## Includes, sessions et securite commune

Includes recurrents:

- `connect.php`: connexion PDO MySQL.
- `header.php`: session, verification connexion, menu, notifications, compteurs corbeille.
- `footer.php`: pied de page et scripts.
- `db.php`: utilise par les evenements calendrier.
- `dompdf/autoload.inc.php`: generation PDF.

Sessions:

- La plupart des traitements demarrent avec `session_start()`.
- La session fournit surtout `$_SESSION['User_Name']`, `$_SESSION['fullname']`, `$_SESSION['Id_User']`, `$_SESSION['Password']`.
- Si la session est absente, beaucoup de scripts remplacent l'utilisateur par `default`, ce qui peut creer des donnees non attribuees.

Permissions:

- Connexion: `check_password.php`.
- Permission simple dans `user.Permission`: `Y` autorise, `N` bloque.
- Validation des nouveaux utilisateurs: `give_permission.php`.
- Le controle d'acces est surtout dans `header.php`; plusieurs scripts de traitement restent accessibles directement par URL si l'utilisateur connait le chemin.

Risques transversaux:

- Beaucoup d'actions destructives sont appelees en GET (`delete_*`, `supprimer_*`).
- Pas de jeton CSRF visible.
- Mots de passe stockes et compares en clair dans `user.Password`.
- Nombreuses sorties HTML sans `htmlspecialchars()`.
- Requetes souvent preparees, mais la logique d'autorisation reste faible.
- Beaucoup de `SELECT * ... GROUP BY ...` peuvent retourner des colonnes non deterministes selon MySQL.

## VENTE

Statut: analyse detaillee deja terminee.

Fichiers principaux deja analyses:

- `ancien_code/vente/vente_recap_legere.php`
- `ancien_code/vente/vente_recap_details.php`
- `ancien_code/vente/vente_recap_details_*.php`
- `ancien_code/vente/vente_recap_solde_printed.php`
- variantes `vente_recap_*` dans `ancien_code/codes`

Dependances importantes avec les autres modules:

- `commande.php` prepare une vente dans `commande`.
- `insert_commande.php` ajoute une ligne au panier `commande`.
- `valider_commande.php` transforme `commande` en lignes `mvt` avec `type_de_mvt='vente'`, puis insere `recap_vente`.
- `valider_commande_andrefana.php` utilise `vente_calc`, `mvt_calc`, `recap_vente_calc` et declenche aussi des validations de stock Andrefana.
- `depense` est rattachee a la vente via `activity_no`.
- Les suppressions de lignes vente passent par `delete_mvt_vente.php`, avec copie vers `mvt_history`.

Tables:

- `commande`, `vente_calc`, `mvt`, `mvt_calc`, `recap_vente`, `recap_vente_calc`, `depense`, `produit`, `history`, `mvt_history`, `user`, `memo`.

Operations critiques:

- `INSERT INTO mvt(... type_de_mvt='vente' ...)`
- `INSERT INTO recap_vente(...)`
- `INSERT INTO mvt_calc(...)`
- `INSERT INTO recap_vente_calc(...)`
- `DELETE FROM commande WHERE user=?`
- `DELETE FROM depense WHERE activity_no=?`
- `DELETE FROM mvt WHERE id_mvt=?` apres copie vers `mvt_history`

## STOCK

Fichiers PHP principaux:

- Interfaces stock: `codes/stock.php`, `codes/stock_reduit.php`, `codes/stock_general.php`, `codes/stock_andrefana.php`, `codes/stock_andrefana_retours.php`, `codes/stock_ambato_piece.php`.
- Recapitulatifs: `codes/stock_recap.php`, `stock_recap_details.php`, `stock_recap_andrefana.php`, `stock_recap_ambato_1.php`, `stock_recap_deleted.php`, `stock_recap_critere.php`.
- Etat stock: `stock_balance.php`, `stock_balanced_negatif.php`, `stock_balanced_zero.php`, `stock_epuise*.php`, `stock_ok.php`, `stock_ref.php`.
- Preparations: `ancien-code/new_stock.php`, `autres-codes/new_stock_reduit.php`, `new_stock_andrefana.php`, `new_stock_andrefana_retours.php`.
- Ajouts temporaires: `ancien-code/insert_stock_prep.php`, `insert_stock_prep_reduit.php`, `insert_stock_prep_andrefana.php`, `insert_stock_prep_andrefana_retours.php`, `insert_stock_prep_ajuster.php`, `insert_stock_inventory.php`.
- Validations: `codes/valider_stock.php`, `valider_stock_reduit.php`, `valider_stock_andrefana.php`, `valider_stock_andrefana_retours.php`.
- Modifications: `ancien-code/modifier_stock.php`, `modifier_stock_reduit.php`, `modifier_stock_andrefana.php`, `modifier_mvt_stock.php`, `modifier_date_stock.php`.
- Suppressions: `modernisation/delete_stock_prep*.php`, `delete_mvt_stock.php`, `delete_mvt_stock_andrefana.php`, `delete_mvt_stock_neg.php`, `delete_mvt_stock_bz.php`.
- Annulations: `modernisation-logiciel/anuler_stock*.php`.

Flux metier:

- Demarrage stock: `new_stock.php` lit `memo id_memo=2`, calcule un numero, met a jour `memo` et le contexte `user`.
- Preparation: `insert_stock_prep.php` ajoute des lignes dans `stock_prep` avec `description_date LIKE '%Ajout%'`.
- Validation: `valider_stock.php` lit `stock_prep`, insere chaque ligne dans `mvt` avec `type_de_mvt='stock'`, puis supprime le panier temporaire.
- Retrait/reduit: variantes `stock_reduit`, `insert_stock_prep_reduit`, `valider_stock_reduit`, avec quantites souvent negatives selon le libelle `Reduit` ou `Retirer`.
- Inventaire/controle: utilise `status='General_Inventory'` et parfois `prix_aparafa` comme quantite rectificative.

Parametres GET/POST typiques:

- POST: `description_date`, `nom_du_client`, `id_x`, `qt`, `prix_de_vente`, `prix_client`, `prix_fournisseur`, `note_stock`.
- GET: `id_mvt`, `nom_client_fournisseur`, `description_date`, `no_activite`, `id_stock_prep`.

Tables:

- `stock_prep`, `mvt`, `mvt_calc`, `produit`, `memo`, `user`, `history`, `mvt_history`, `stock_recap`.

Operations critiques:

- `INSERT INTO stock_prep(...)`
- `INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, ...) VALUES ('stock', ...)`
- `UPDATE produit SET prix_de_vente/pu_aparafa/pu_ambato_tantely/pu_soalazaina...`
- `UPDATE mvt SET id_x=?, qt=?, prix_unitaire=?, prix_client=?, note=?, user_mvt=?`
- `INSERT INTO history(... type='Mvt_Stock' ou 'Prix')`
- `INSERT INTO mvt_history(...)` puis `DELETE FROM mvt WHERE id_mvt=?`
- `DELETE FROM stock_prep WHERE description_date LIKE ... AND user_stock_prep=?`

Variantes par point de vente:

- `Ambato_Tantely`: prix `pu_ambato_tantely`, note/difference prix Tantely.
- `Amparafa`: prix `pu_aparafa`.
- `Soalazaina`: prix `pu_soalazaina`.
- `Andrefana`: variantes `*_andrefana`, parfois tables `mvt_calc` / `stock_prep_calc`.
- `Ambato_1`: fichiers suffixes `_ambato_1`.

Doublons/anciennes versions:

- `stockp.php`, `stockp (1).php`, `stockp (2).php`.
- `stockpd1.php`, `stockpd1 (1).php`.
- `stock_recap_details.php`, `stock_recap_details1.php`, `stock_recap_details-s.php`.
- `Copy of stock.php`.

## MVT / mouvements

Fichiers principaux:

- `ancien-code/mvt_produit.php`
- `mvt_produit_s.php`
- `mvt_produit_all.php`
- `mvt_produit_all_2.php`
- `mvt_produit_all-copie.php`
- `mvt_produit_ambato_1.php`
- `mvt_produit_facture.php`
- `mvt_produit_report.php`
- `mvt_prix.php`
- `mvt_comparaison_prix.php`
- `autres-codes/simple_search_mvt*.php`
- `codes/tracking_activity.php`, `tracking_stock.php`, `tracking_vente.php`
- `ancien-code/mouvement.php`

Role:

- Consultation des mouvements par produit, point de vente, type de mouvement et historique.
- Calcul de stock restant par produit avec `SUM(qt)`.
- Recherche de prix utilises dans les mouvements.
- Comparaison des prix officiels/clients/fournisseurs.

Tables:

- `mvt`, `mvt_calc`, `mvt_history`, `produit`, `history`, `recap_vente`, `recap_vente_calc`.

Operations:

- Majoritairement `SELECT`.
- Les modifications reelles passent par `modifier_mvt_stock.php`, `modifier_mvt_vente*.php`, `modifier_mvt_facture.php`.
- Les suppressions passent par `delete_mvt_stock*.php`, `delete_mvt_vente.php`.

Parametres:

- GET/POST: `point_de_vente`, `key_word`, `reference_x`, `id_x`, dates, `type_de_mvt`.

Points sensibles:

- `mvt` est le journal metier central. Toute modernisation doit preserver le signe des quantites:
  - stock/entree: quantite positive sauf retrait/reduction.
  - vente: quantite negative dans le flux historique.
  - facture: quantite positive dans le flux fournisseur.
- Les inventaires utilisent `status='General_Inventory'`.

## GERER / articles / prix / gestion

Fichiers principaux:

- Gestion produits: `codes/stock_general.php`, `autres-codes/nouveau_produit.php`, `produit_x.php`.
- Ajout produit depuis stock: `ancien-code/insert_stock_produit.php`, `insert_stock_produit_reduit.php`.
- Modification produit: `ancien-code/modifier_produit.php`.
- Images: `modifier_image_produit.php`, `modifier_image_produit_stock.php`, `delete_img_produit.php`, `delete_img_produit_stock.php`, `copy_img.php`.
- Prix: `autres-codes/prix.php`, `prix_ambato_1.php`, `reset_note_prix*.php`, `simple_notif_prix*.php`, `codes/valider_different_prix.php`.
- Nouveau nom produit: `new_product_notif.php`, `codes/valider_nouveau_nom.php`.

Tables:

- `produit`, `mvt`, `history`.

Operations critiques:

- `INSERT INTO produit(...)`
- `UPDATE produit SET nom_x=?, prix_de_vente=?, reference_x=?, note_x=?, user_x=?`
- `UPDATE produit SET prix_fournisseur=?`
- `UPDATE produit SET pu_aparafa/pu_ambato_tantely/pu_soalazaina/prix_de_vente=?`
- `INSERT INTO history(... type='Prix')`
- `DELETE FROM produit WHERE id_x=?`

Points metier:

- Le prix officiel depend du point de vente.
- `prix_fournisseur` peut etre modifie lors d'une preparation stock ou facture.
- Les changements de prix sont marques par des colonnes `note_prix*` et `difference_prix*`.

Doublons:

- Plusieurs `reset_note_prix_*` par point de vente.
- `reset_note_prix_all - Copy.php`.
- `test_ref.php`, `test_ref - Copy.php`, `test_ref (1).php`.

## CONTROLE

Fichiers principaux:

- Interfaces: `modernisation/controle_x.php`, `controle_x_details.php`, `controle_x_printable.php`, `controle_xpp.php`, `controle_pannel.php`.
- Controle Ambato: `modernisation-logiciel/controle_ambato.php`, `controle_ambato_new.php`, `controle_ambato1.php`, `controle_ambato2.php`, `controle_ambato_details.php`, `controle_ambato - Copy.php`.
- Controle images: `controle_jpg.php`, `controle_jpg2.php`, `modifier_image_controle_x.php`.
- Validation: `codes/valider_stock_controle.php`, `valider_stock_controle_std.php`, `valider_stock_controle_individual.php`, `valider_stock_controle_ambato.php`.
- Annulation: `modernisation-logiciel/anuler_stock_controle.php`.
- Recherche: `autres-codes/simple_search_controle.php`.

Role:

- Controle/inventaire de stock par point de vente.
- Preparation de lignes de rectification dans `stock_prep` avec `description_date LIKE '%Rectifier%'`.
- Validation vers `mvt` en type `stock`, avec statut d'inventaire ou correction selon le script.

Tables:

- `stock_prep`, `mvt`, `produit`, `user`, `history`.

Parametres:

- POST: `point_de_vente`, `id_x`, `qt`, `prix_de_vente`, `note`.
- GET: `description_date`, `nom_client_fournisseur`, `no_activite`, `id_stock_prep`.

Variantes de points de vente affichees dans les controles:

- `Ambatomainty`, `Amparafa`, `Ambato_Tantely`, `Ambato_veve_photo`, `Bejofo`, `Soalazaina`, `Ambato_Pneu`, `Ambaibo_Electronique`, `Ambaibo_loko`.

Doublons:

- `controle_ambato.php`, `controle_ambato1.php`, `controle_ambato2.php`, `controle_ambato_new.php`, `controle_ambato - Copy.php`.

## COMMANDES

Fichiers principaux:

- Interfaces: `modernisation-logiciel/commande.php`, `commande1.php`, `commande01.php`, `commandef.php`, `commande_royal.php`, `commande_andrefana.php`, `commande_ambato_1.php`, `commande_ambato_1_preview.php`, `commande_ambato_1_export.php`, `commande_for_point_de_vente.php`, `commande_Ref.php`.
- Demarrage: `ancien-code/new_client_name_commande.php`, `new_client_name_commande_royal.php`, `new_client_name_commande_andrefana.php`, `new_client_name_commande_ambato_1.php`.
- Ajout ligne: `modernisation/insert_commande.php`, `insert_commande_royal.php`, `insert_commande_andrefana.php`, `insert_commande_ambato_1.php`, `insert_commande_produit.php`.
- Modification: `ancien-code/modifier_commande.php`, `modifier_commande_royal.php`, `modifier_commande_andrefana.php`, `modifier_commande_ambato_1.php`.
- Validation: `codes/valider_commande.php`, `valider_commande_royal.php`, `valider_commande_andrefana.php`, `valider_commande_ambato_1.php`, `valider_commande_v1.php`, `valider_waiting_commande_*`.
- Suppression/annulation: `delete_commande*.php`, `anuler_commande*.php`.
- Verrouillage: `lock_commande_*.php`, `unlock_commande_*.php`, `editable_mode_commande_*.php`.
- Recherche: `simple_search_commande*.php`.

Flux metier:

- `new_client_name_commande*.php` determine le numero de commande, la date et le point de vente, puis met a jour `memo` ou `user`.
- `insert_commande*.php` ajoute les lignes temporaires dans `commande` ou `vente_calc`.
- `valider_commande*.php` transforme les lignes en `mvt` ou `mvt_calc`, cree un recap, puis appelle `anuler_commande*`.

Tables:

- `commande`, `vente_calc`, `mvt`, `mvt_calc`, `recap_vente`, `recap_vente_calc`, `depense`, `produit`, `user`, `memo`, `history`.

Operations critiques:

- `INSERT INTO commande(...)`
- `INSERT INTO vente_calc(...)`
- `INSERT INTO mvt(... type_de_mvt='vente' ...)`
- `INSERT INTO recap_vente(...)`
- `DELETE FROM commande WHERE user=?`
- `DELETE FROM vente_calc WHERE user=?`
- `DELETE FROM depense WHERE activity_no=?`
- `UPDATE user SET point_de_vente=?, numero_commande=?, description_date=?`

Variantes:

- Standard/Royal: `commande.php`, `commande_royal.php`.
- Andrefana: `commande_andrefana.php`, tables `vente_calc`, `mvt_calc`, `recap_vente_calc`.
- Ambato 1: `commande_ambato_1.php`.

## RETOURS

Fichiers principaux:

- Interface: `autres-codes/retours_vente.php`.
- Demarrage: `ancien-code/new_retours_vente.php`.
- Ajout temporaire: `ancien-code/insert_vente_retours_prep.php`.
- Validation: `codes/valider_retours_vente.php`.
- Modification: `ancien-code/modifier_retours_vente.php`.
- Suppression temporaire: `modernisation/delete_retours_vente_prep.php`.
- Annulation: `modernisation-logiciel/anuler_retours_vente.php`.
- Andrefana retours: `stock_andrefana_retours.php`, `new_stock_andrefana_retours.php`, `insert_stock_prep_andrefana_retours.php`, `valider_stock_andrefana_retours.php`.

Flux:

- Utilise `stock_prep` avec `description_date LIKE '%Abandon%'`.
- Validation insere des mouvements `type_de_mvt='stock'` dans `mvt`.
- Les quantites semblent reutiliser le modele stock pour recrediter ou abandonner des ventes.

Tables:

- `stock_prep`, `mvt`, `produit`, `user`, `memo`.

Operations critiques:

- `INSERT INTO stock_prep(...)`
- `INSERT INTO mvt(type_de_mvt='stock', ...)`
- `DELETE FROM stock_prep WHERE description_date LIKE '%Abandon%' AND user_stock_prep=?`

## DEPENSES

Fichiers principaux:

- Interfaces: `modernisation/depense.php`, `depense1.php`.
- Insertion generale: `modernisation/insert_depense.php`.
- Variantes: `ancien-code/insert_depense_royal.php`, `insert_depense_andrefana.php`, `insert_depense_ambato_1.php`.
- Modification: `ancien-code/modifier_depense.php`, `modifier_depensec.php`, `modifier_dep.php`.
- Suppression: `modernisation/delete_depense.php`, `delete_depense_royal.php`, `delete_depense_individual.php`, `delete_depense_ambato_1.php`.

Role:

- Enregistrer les depenses liees a une activite de vente/commande.
- Colonnes principales utilisees: `montant`, `depense_aparafa`, `depense_tsinjo`, `motif`, `activity_no`, `user_depense`.

Tables:

- `depense`, `user`.

Operations critiques:

- `INSERT INTO depense(montant, motif, activity_no, user_depense)`
- `INSERT INTO depense(depense_aparafa, motif, activity_no, user_depense)`
- `INSERT INTO depense(depense_tsinjo, motif, activity_no, user_depense)`
- `UPDATE depense ...`
- `DELETE FROM depense WHERE id_depense=?`
- `DELETE FROM depense WHERE activity_no=?` lors d'annulation commande.

Dependances:

- `valider_commande.php` lit les depenses par `activity_no` pour calculer `history`, `difference_aparafa`, versement net.
- `vente_recap_solde_printed.php` et pages recap lisent `depense` pour les comptes.

## RAPPORTS ET ANALYSE

Fichiers principaux:

- Rapports: `autres-codes/report_1.php`, `report_2.php`, `report_critere.php`, `mvt_produit_report.php`.
- Analyse: `modernisation-logiciel/analyse_vente.php`, `analyse_stock.php`, `analyse_prix.php`, `analyse_ecart_prix.php`, `analyse_ecart_prix_1.php`, `analyse_ecart_notif.php`, copies `- Copy.php`.
- Graphiques: `char_line_vente_7_jours.php`, `char_line_versement_7_jours.php`, `char_line_saisi_7_jours.php`, `char_bar_horizontal*.php`, `char_pie.php`, `char_doughnut.php`, `char_polar.php`, `char_radar.php`, `char_scatter.php`, `Char_1.php` a `Char_4.php`, `Char_r2.php`, `Char_r3.php`.
- Tableaux de bord: `modernisation/home_char.php`, `home_gs.php`, `eval_saisi.php`.
- Top ventes: `top20a.php`, `top20b.php`, `top20c.php`, `top20_printable.php`, `top20*_activate.php`.
- Tracking: `tracking_activity.php`, `tracking_stock.php`, `tracking_vente.php`.

Tables:

- `mvt`, `mvt_calc`, `recap_vente`, `recap_vente_calc`, `produit`, `depense`, `history`, `user`.

Operations:

- Principalement `SELECT`.
- Certains scripts `*_activate.php` et `additional_analyse_cookie.php` posent des cookies de filtre ou d'activation.

Parametres:

- Dates, point de vente, type analyse, cookies `debut_date`, `fin_date`, `point_de_vente`, etc.

Doublons:

- `analyse_ecart_prix.php` et `analyse_ecart_prix - Copy.php`.
- `analyse_ecart_prix_1.php` et `analyse_ecart_prix_1 - Copy.php`.
- Multiples fichiers `char_*` proches.

## FACTURES / recus / impression / PDF

Fichiers principaux:

- Interface facture: `modernisation/facture.php`.
- Demarrage: `ancien-code/new_facture.php`.
- Ajout temporaire: `ancien-code/insert_facture_prep.php`.
- Validation: `codes/valider_facture.php`.
- Recaps: `modernisation/facture_recap.php`, `facture_recap_details.php`, `Facture_recap_deleted.php`, `facture_recap_details_deleted.php`.
- Modification: `ancien-code/modifier_facture.php`, `modifier_mvt_facture.php`, `modifier_date_facture.php`.
- Suppression: `modernisation/delete_facture.php`, `delete_facture_prep.php`.
- Annulation: `modernisation-logiciel/anuler_facture.php`.
- Impression/PDF: `ancien-code/invoice.php`, `modernisation/html_to_pdf.php`, `html_to_pdf2.php`, `html_to_pdf3.php`, `html_to_pdf4.php`.

Flux:

- `new_facture.php` initialise fournisseur/date via `memo` et `user`.
- `insert_facture_prep.php` ajoute les lignes dans `stock_prep` avec `description_date LIKE '%Facture%'`.
- `valider_facture.php` insere dans `mvt` avec `type_de_mvt='facture'`.
- Si le prix fournisseur change, `produit.prix_fournisseur` est mis a jour et `history` recoit une entree `type='Prix'`.
- Le panier `stock_prep` facture est ensuite supprime.

Tables:

- `stock_prep`, `mvt`, `mvt_history`, `produit`, `history`, `memo`, `user`.

Operations critiques:

- `INSERT INTO mvt(... type_de_mvt='facture' ...)`
- `UPDATE produit SET prix_fournisseur=?`
- `INSERT INTO history(... type='Prix')`
- `DELETE FROM stock_prep WHERE description_date LIKE '%Facture%' AND user_stock_prep=?`
- `DELETE FROM mvt WHERE type_de_mvt='facture'` selon scripts de suppression.

## UTILISATEURS, sessions, roles et permissions

Fichiers principaux:

- Connexion: `modernisation-logiciel/check_password.php`.
- Deconnexion: `modernisation/deconnection.php`.
- Header/session obligatoire: `modernisation/header.php`, `header2.php`.
- Inscription: `autres-codes/registration.php`.
- Creation/modification utilisateur: `modernisation/edit_user.php`, `edit_user_form.php`, `gerer_user.php`.
- Permissions: `modernisation/give_permission.php`.
- Suppression: `modernisation/delete_user_checkbox.php`.
- Profil: `autres-codes/profile.php`.
- Mot de passe: `modernisation-logiciel/change_password.php`.

Tables:

- `user`, `history`, `chat`.

Operations critiques:

- `SELECT * FROM user WHERE User_Name=?`
- Comparaison directe du mot de passe saisi avec `user.Password`.
- `INSERT INTO user(...)`
- `UPDATE user SET Permission='Y' WHERE Id_User=?`
- `UPDATE user SET Password=? WHERE User_Name=?`
- `DELETE FROM user WHERE Id_User=?`

Colonnes utilisateur importantes:

- `Id_User`, `Full_Name`, `User_Name`, `Password`, `Permission`, `Departement`, `img_path`, `Note`.
- Colonnes de contexte metier: `numero_commande`, `point_de_vente`, `description_date`, `stock_client_name`, `stock_numero_commande`, `stock_description_date`, `numero_vente_calc`, `point_de_vente_vente_calc`, `description_date_vente_calc`.

Risques:

- Mots de passe en clair.
- Permission globale `Y/N`, pas de roles granulaires visibles.
- Certains scripts continuent avec utilisateur `default`.

## POINTS DE VENTE

Fichiers principaux:

- Gestion: `modernisation-logiciel/add_shop.php`.
- Insertion: `ancien-code/insert_shop.php`.
- Modification: `ancien-code/modifier_shop.php`.
- Utilisation dans commandes/stock/vente/controle: nombreux selects `point_de_vente`, `nom_client_fournisseur`, `nom_du_client`.

Table:

- `shop(long_name, short_name, shop_user)`.

Operations:

- `INSERT INTO shop(long_name, short_name, shop_user)`
- `UPDATE shop SET ...`
- Les mouvements utilisent surtout `mvt.nom_client_fournisseur`.

Points de vente detectes dans les fichiers:

- Points fortement lies aux calculs de vente/tombony: `Amparafa`, `Bejofo`, `Soalazaina`.
- Autres points ou variantes detectes: `Ambato_Tantely`, `Ambato_veve_photo`, `Ambato_Pneu`, `Ambatomainty`, `Ambaibo_Tole`, `Ambaibo_Electronique`, `Ambaibo_loko`, `MoraranoCh`, `Andrefana`, `Royal`, `Ambato_1`.

Attention:

- Les "trois points de vente" les plus structurants pour les calculs de solde/commission sont `Amparafa`, `Bejofo`, `Soalazaina`.
- `Ambato_Tantely` est tres present pour les prix et variantes Ambato, mais n'est pas dans le trio de compte mensuel `vente_recap_solde_printed.php`.

## OUTILS et autres fonctions

Fichiers/fonctions:

- Contact: `contact.php`, `insert_contact.php`, `modifier_contact.php`, `delete_contact.php`.
- Chat/messages: `chat.php`, `msg_send.php`, `vu_msg.php`, `delete_msg.php`.
- Conges: `conges.php`, `conges_dispo.php`, `modifier_conges.php`, `delete_conges.php`, stocke dans `history type='conges'`.
- Calendrier: `add-event.php`, `fetch-event.php`, `edit-event.php`, `delete-event.php`, table `tbl_events`.
- Citations: `citation.php`, `citation_add.php`, `add_citation.php`, `enable_citation.php`, `modifier_citation.php`, `delete_citation.php`.
- Urgent/tsinjo: `urgent.php`, `add_urgent.php`, `modifier_urgent.php`, `delete_urgent.php`, `tsinjo.php`, `add_tsinjo.php`, `modifier_tsinjo.php`, `delete_tsinjo.php`.
- Outils: `calculator_pourcent.php`, `pourcentage.php`, `reference_generator*.php`, `ping*.php`, `saveimage.php`, `img_scan_directory.php`.
- Backup: `backup_db.php`, logique de backup commentee dans `check_password.php`.
- Cisco/energie solaire: `cisco.php`, `energie_solaire.php`, commandes `add_cisco_command.php`, `modifier_cisco.php`, `delete_cisco.php`.

Tables:

- `contact`, `chat`, `history`, `tbl_events`, `citation`, `urgent`, `tsinjo`, `cisco`, selon scripts.

## Operations critiques a conserver exactement

Pour le futur logiciel, il faut reproduire en priorite:

1. Validation stock:
   - Lire `stock_prep` de l'utilisateur.
   - Inserer dans `mvt` avec `type_de_mvt='stock'`.
   - Mettre a jour les prix du produit selon point de vente.
   - Ecrire l'historique prix si necessaire.
   - Supprimer le panier `stock_prep`.

2. Validation vente:
   - Lire `commande` ou `vente_calc`.
   - Inserer dans `mvt` ou `mvt_calc` avec `type_de_mvt='vente'`.
   - Quantite negative dans le flux historique.
   - Calculer total Royal, total client, benefice/difference, depenses, versement, resolution, mihoatra.
   - Inserer `recap_vente` ou `recap_vente_calc`.
   - Supprimer commande et depenses temporaires associees.

3. Validation facture:
   - Lire `stock_prep` facture.
   - Inserer dans `mvt` avec `type_de_mvt='facture'`.
   - Mettre a jour `produit.prix_fournisseur` si changement.
   - Ecrire `history type='Prix'`.
   - Supprimer le panier facture.

4. Modification d'un mouvement:
   - Comparer avant/apres.
   - Ecrire dans `history` avec type `Mvt_Stock`, `Mvt_Vente` ou equivalent.
   - Mettre a jour `mvt`.
   - Pour inventaire general, recalculer la quantite rectificative stockee dans `prix_aparafa`.

5. Suppression d'un mouvement:
   - Copier la ligne vers `mvt_history`.
   - Ajouter le nom de l'utilisateur qui supprime dans `user_mvt`.
   - Supprimer ensuite de `mvt`.

6. Annulation d'une activite temporaire:
   - Supprimer les lignes temporaires `commande`, `vente_calc` ou `stock_prep` pour l'utilisateur courant.
   - Supprimer aussi les `depense` liees au `activity_no` courant dans certains flux commande.

## Doublons et anciennes versions probables

Copies explicites:

- `about - Copy.php`
- `commande - Copy.php`
- `commande_ambato_1 - Copy.php`
- `controle_ambato - Copy.php`
- `home_char - Copy.php`
- `reset_note_prix_all - Copy.php`
- `reset_note_prix_tantely_1 - Copy.php`
- `analyse_ecart_prix - Copy.php`
- `analyse_ecart_prix_1 - Copy.php`

Versions multiples:

- `commande.php`, `commande1.php`, `commande01.php`, `commandef.php`.
- `controle_ambato.php`, `controle_ambato1.php`, `controle_ambato2.php`, `controle_ambato_new.php`.
- `stockp.php`, `stockp (1).php`, `stockp (2).php`.
- `stockpd1.php`, `stockpd1 (1).php`.
- `mvt_produit_all.php`, `mvt_produit_all_2.php`, `mvt_produit_all-copie.php`.
- `html_to_pdf.php`, `html_to_pdf2.php`, `html_to_pdf3.php`, `html_to_pdf4.php`.

Fichiers de test/prototype:

- `test.php`, `test_ref*.php`, `Test_1000_produit.php`, `new_material.php`, `controle.php` prototype statique.

## Elements encore incertains

- Le schema MySQL exact n'est pas fourni; les colonnes sont deduites des requetes.
- Certaines tables detectees par regex peuvent etre anciennes ou inutilisees.
- Les chemins d'includes supposent souvent que tous les fichiers sont dans le meme dossier web, malgre les 6 dossiers de l'archive.
- Le flux `ancien-code/insert_vente.php` semble plus recent ou experimental: il insere directement une vente dans `mvt` et utilise `date_mvt`, different du flux historique `commande -> valider_commande`. A confirmer avant migration.
- Les roles reels par departement ne sont pas explicites; seul `Permission='Y/N'` est clairement utilise.
- Les points de vente actifs doivent etre confirmes depuis la table `shop` et les donnees de production, pas seulement depuis les fichiers.
- Le traitement exact Andrefana melange vente calculee, retours et validation stock; il faudra le tester avec des donnees reelles.

## Carte rapide par module

| Module | Pages principales | Traitements | Tables principales |
| --- | --- | --- | --- |
| VENTE | `vente_recap*`, `commande*` | `insert_commande*`, `valider_commande*`, `modifier_mvt_vente*`, `delete_mvt_vente` | `commande`, `mvt`, `recap_vente`, `depense` |
| STOCK | `stock*`, `stock_recap*` | `insert_stock_prep*`, `valider_stock*`, `modifier_mvt_stock*`, `delete_mvt_stock*` | `stock_prep`, `mvt`, `produit`, `history` |
| MVT | `mvt_produit*`, `tracking_*` | `modifier_mvt_*`, `delete_mvt_*` | `mvt`, `mvt_calc`, `mvt_history`, `history` |
| GERER | `stock_general`, `prix*`, `nouveau_produit` | `modifier_produit`, `insert_stock_produit`, `valider_different_prix` | `produit`, `history` |
| CONTROLE | `controle_x*`, `controle_ambato*` | `valider_stock_controle*`, `anuler_stock_controle` | `stock_prep`, `mvt`, `produit` |
| COMMANDES | `commande*` | `new_client_name_commande*`, `insert_commande*`, `valider_commande*`, `anuler_commande*` | `commande`, `vente_calc`, `mvt`, `recap_vente`, `depense` |
| RETOURS | `retours_vente`, `stock_andrefana_retours` | `insert_vente_retours_prep`, `valider_retours_vente`, `anuler_retours_vente` | `stock_prep`, `mvt` |
| DEPENSES | `depense*` | `insert_depense*`, `modifier_depense*`, `delete_depense*` | `depense`, `user` |
| RAPPORTS | `report_*`, `analyse_*`, `char_*`, `top20*` | filtres/cookies, surtout SELECT | `mvt`, `recap_vente`, `produit`, `depense` |
| FACTURES | `facture*`, `invoice`, `html_to_pdf*` | `insert_facture_prep`, `valider_facture`, `modifier_facture`, `delete_facture` | `stock_prep`, `mvt`, `produit`, `history` |
| UTILISATEURS | `home`, `gerer_user`, `registration`, `profile` | `check_password`, `edit_user`, `give_permission`, `change_password` | `user`, `history`, `chat` |
| POINTS DE VENTE | `add_shop`, selects PV | `insert_shop`, `modifier_shop` | `shop`, `mvt` |

