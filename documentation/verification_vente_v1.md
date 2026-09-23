# Verification du module VENTE V1 apres alignement history

Date: 2026-09-19

Perimetre:

- Nouveau code: `nouveau_site/app/Services/SalesService.php`, `nouveau_site/actions/vente.php`, `nouveau_site/pages/vente.php`.
- Ancien code lu uniquement: `ancien_code/ancien-code/new_client_name_commande.php`, `ancien_code/modernisation/insert_commande.php`, `ancien_code/ancien-code/modifier_commande.php`, `ancien_code/modernisation/delete_commande.php`, `ancien_code/codes/valider_commande.php`, `ancien_code/modernisation-logiciel/anuler_commande_1.php`, `ancien_code/ancien-code/modifier_mvt_vente.php`, `ancien_code/codes/valider_commande_andrefana.php`.

Aucune ecriture MySQL n'a ete faite. Aucune vente reelle n'a ete creee. `ancien_code/` n'a pas ete modifie.

## Resultat de recherche history

Pendant la validation standard d'une vente, l'ancien logiciel n'ecrit pas dans la table `history`.

Le journal de la vente est le champ `history` de la table `recap_vente`, alimente dans `ancien_code/codes/valider_commande.php` au moment de l'insertion `recap_vente`, apres les insertions `mvt` et avant `include('anuler_commande_1.php')`.

Valeurs concatenees pendant la validation standard:

- utilisateur: `$_SESSION['User_Name']`, ecrit dans `mvt.user_mvt`, pas dans `recap_vente.history`;
- numero de vente / `activity_no`: `user.numero_commande`, repris comme `commande.numero_commande`, `mvt.numero_commande_stock`, `recap_vente.no_activite`, et utilise pour lire `depense.activity_no`;
- date: extraite de `description_date` vers `Date_du_Journal_mvt` et `recap_vente.Date_du_Journal`; elle n'est pas concatenee dans `recap_vente.history`;
- point de vente: `user.point_de_vente`, repris comme `commande.nom_du_client`, `mvt.nom_client_fournisseur`, et en majuscules dans les libelles `history`;
- texte par ligne: produit, note, quantite, prix Royal, prix client, montants Royal/client et benefice client;
- texte final: total vente Royal, total client, difference/benefice, depenses Royal/client, total depense, net verse Royal.

La table `history` est utilisee pour les modifications ulterieures de mouvements de vente:

- fichier: `ancien_code/ancien-code/modifier_mvt_vente.php`;
- table: `history`;
- colonnes: `after_change`, `before_change`, `details`, `responsable`, `type`;
- `after_change`: numero de vente `mvt.numero_commande_stock`;
- `before_change`: reference de ligne `mvt.ref_commande_stock`;
- `details`: changements concaténes, par exemple `|QT (...)<br>`, `|PU (...)<br>`, `|PU Client (...)<br>`, `|PRODUCT (...)<br>`;
- `responsable`: utilisateur session;
- `type`: `Mvt_Vente`;
- date/heure: colonne implicite `date_time` de la table, non fournie par l'INSERT.

Ce flux de modification post-validation n'est pas le flux de validation standard actuellement supporte par la V1.

Autres flux historiques identifies:

- `valider_commande_royal.php` et `valider_commande_ambato_1.php`: meme table `recap_vente`, mais format ligne avec `PU`, `MT`, `A/FA` et bloc `SPECIAL AMPARAFA`.
- `valider_commande_andrefana.php`: tables `vente_calc`, `mvt_calc`, `recap_vente_calc`, puis validations stock Andrefana; ce flux reste bloque dans V1.
- `valider_commande_v1.php`, `line.php`, `Script_valider_commande.php`: anciens essais/variantes, non retenus comme flux standard actuellement supporte.

## Format exact de `recap_vente.history` pour le flux standard

Pour chaque ligne panier, dans l'ordre courant du panier:

```text
=> {no} # {nom_x} | {note_commande} # qt: {qt} # PU_ROY: {prix_de_vente} # PU_CLI: {prix_client} # MT_ROY: {prix_de_vente * qt} # {POINT_DE_VENTE_MAJUSCULE} : {prix_client * qt} # BC: {(prix_client - prix_de_vente) * qt} #\r
```

Puis un retour chariot supplementaire et le bloc final:

```text
\r----------------------------------------------------------------\r
----------TL VENTE:{total_royal} Ar----------\r
----------------------------------------------------------------\r
-----------------SPECIAL {POINT_DE_VENTE_MAJUSCULE}------------------\r
 IVAROTANY TL:{total_client} Ar | BENEFICE:{difference_client} Ar | NALAINY:{depense_client}\r
------------------------DEPENSE---------------------------\r
DEPENSE ROYAL:{depense_royal} Ar | VOLA NALAIN NY {POINT_DE_VENTE_MAJUSCULE}:{depense_client} Ar | TL DEPENSE:{depense_royal + depense_client} Ar\r
-----------------------------------------------------------------\r
--------NET POUR ROYAL:{versement} Ar-------\r
-----------------------------------------------------------------
```

Correction appliquee dans `nouveau_site/app/Services/SalesService.php`: la lecture du panier calcule maintenant `sous_total` et `sous_total_client` dans le `SELECT`, comme l'ancien code, et `buildHistory()` reprend ces valeurs pour le texte `MT_ROY`, le montant client et `BC`. V1 reproduit maintenant ce mode de concatenation, y compris le `\r` final de chaque ligne article et la ligne vide avant le bloc total.

## Comparaison statique des operations critiques

| Operation critique | Ancien code | Nouveau code apres correction | Statut | Commentaire |
| --- | --- | --- | --- | --- |
| Calcul numero vente | `MAX(memo.valeur_memo+1, MAX(mvt vente)+1, MAX(commande)+1)` | Meme calcul | EQUIVALENTE | Meme logique metier. |
| Mise a jour `memo` | `UPDATE memo SET note_memo=?, valeur_memo=?, description_date=? WHERE id_memo=1` | Meme requete | IDENTIQUE | Au demarrage vente, avant `user`. |
| Mise a jour `user` | `UPDATE user SET point_de_vente=?, numero_commande=?, description_date=? WHERE User_Name=?` | Meme requete | IDENTIQUE | Meme contexte de vente. |
| Creation ligne panier | `INSERT INTO commande(numero_commande, nom_du_client, description_date, id_x, qt, prix_de_vente, prix_client, state, note_commande, user)` | Meme insertion | IDENTIQUE | V1 ajoute seulement validation applicative. |
| Modification ligne panier | `UPDATE commande SET qt=?, prix_de_vente=?, prix_client=?, note_commande=? WHERE id_commande=?` | Meme champs avec `AND user=?` | EQUIVALENTE | Filtre utilisateur ajoute. |
| Suppression ligne panier | `DELETE FROM commande WHERE id_commande=?` | Meme suppression avec `AND user=?` | EQUIVALENTE | Filtre utilisateur ajoute. |
| Ordre panier validation | Fetch panier historique en ordre naturel d'insertion | `ORDER BY id_commande ASC` | EQUIVALENTE | Reproduit l'ordre historique utilise pour `{numero}-{no}`. |
| Lecture depenses | `SELECT * FROM depense WHERE activity_no=?` | Meme lecture | IDENTIQUE | Sert aux calculs `recap_vente.history`. |
| Prix royal | `commande.prix_de_vente` | `commande.prix_de_vente` | IDENTIQUE | Insere aussi dans `mvt.prix_unitaire`. |
| Prix client | `commande.prix_client` | `commande.prix_client` | IDENTIQUE | Insere aussi dans `mvt.prix_client`. |
| Quantite vente | `qt = -commande.qt` | `qt = -abs(commande.qt)` | EQUIVALENTE | Panier V1 force une quantite positive avant validation. |
| Total royal | Somme `prix_de_vente * qt` | Meme calcul | IDENTIQUE | Sur panier positif. |
| Total client | Somme `prix_client * qt` | Meme calcul | IDENTIQUE | Sur panier positif. |
| `difference_aparafa` | `client - royal` seulement pour `Amparafa`, `Soalazaina`, `Bejofo`; sinon `0` | Meme condition | IDENTIQUE | Correction appliquee. |
| `resolution` | `-abs(POST resolution)` | Meme calcul | IDENTIQUE | Meme signe. |
| `mihoatra` | `abs(POST mihoatra)` | Meme calcul | IDENTIQUE | Meme signe. |
| Insertion `mvt` | `INSERT INTO mvt(type_de_mvt, id_x, qt, prix_unitaire, prix_client, nom_client_fournisseur, description_date, numero_commande_stock, ref_commande_stock, note, user_mvt, Date_du_Journal_mvt)` | Meme insertion | EQUIVALENTE | Valeurs metier alignees. |
| Champ `recap_vente.history` | Concaténation exacte de `valider_commande.php` | Meme format reproduit | IDENTIQUE | Correction appliquee. |
| Insertion `recap_vente` | `INSERT INTO recap_vente(no_activite, nb_ligne, Montant, difference_aparafa, resolution, mihoatra, history, note_general, c_point, directory, status, Date_du_Journal, responsable)` | Meme insertion | IDENTIQUE | Meme colonnes, meme statut `NON_RESOLU`, responsable `No_Change`. |
| Table `history` pendant validation | Aucune ecriture | Aucune ecriture | IDENTIQUE | Les ecritures `history` concernent les modifications post-validation. |
| Nettoyage panier apres validation | `include anuler_commande_1.php`, puis `DELETE FROM commande WHERE user=?` | `DELETE FROM commande WHERE user=?` | IDENTIQUE | Pas de suppression de `depense`. |
| Suppression `depense` apres validation | Aucune | Aucune | IDENTIQUE | Correction appliquee. |
| Flux Andrefana/Morarano | Flux specifique avec `vente_calc`, `mvt_calc`, `recap_vente_calc` | V1 bloque | DIFFERENTE volontaire | Non destructif; ces cas restent sur l'ancienne interface. |
| Recu/PDF | Anciens scripts separes | V1 aperçu/impression navigateur, PDF non valide metier | A VERIFIER APRES VALIDATION METIER | Ne bloque pas le test SQL de validation. |

## Tables affectees par la validation V1 standard

Ecrites:

- `memo`;
- `user`;
- `commande`;
- `mvt`;
- `recap_vente`.

Lues:

- `memo`;
- `user`;
- `commande`;
- `produit`;
- `depense`;
- `mvt`;
- `recap_vente`.

Non ecrites pendant la validation standard:

- `history`;
- `depense`;
- `mvt_calc`;
- `vente_calc`;
- `recap_vente_calc`;
- `mvt_history`.

## Points hors perimetre de ce test

Andrefana/Morarano restent BLOQUES dans la V1. Cette difference volontaire ne peut pas alterer les mouvements du flux standard, car aucune validation n'est autorisee pour ces cas.

Le recu/PDF est marque A VERIFIER APRES VALIDATION METIER. Il ne modifie pas `memo`, `user`, `commande`, `mvt`, `recap_vente` ni `history`, et ne bloque donc pas le test d'enregistrement SQL d'une vente standard.

## Base de test

Aucune base de test n'a ete utilisee et aucune vente n'a ete lancee.

Verification technique effectuee:

- `C:\xampp\php\php.exe -l nouveau_site\app\Services\SalesService.php`: OK, aucune erreur de syntaxe.

La prochaine verification doit etre faite uniquement sur une copie de la base, par exemple `gestion_stock_test`, en comparant avant/apres:

- `memo`;
- `user`;
- `commande`;
- `mvt`;
- `recap_vente`;
- `depense`;
- `history` pour confirmer l'absence d'ecriture pendant la validation.

## Verdict final

PRÊT POUR TEST SUR BASE DE TEST pour le flux VENTE standard supporte.

Ne pas tester sur la vraie base de la societe. Andrefana/Morarano doivent continuer a passer par l'ancienne interface.
