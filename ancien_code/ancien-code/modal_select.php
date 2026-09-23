<!-------------------------------------->
<!-- model form SELECT-->
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="<?php echo $var_mod; ?>" class="modal fade">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"><?php echo $var_mod; ?></h4><button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times</button>
		</div> <div class="modal-body">
<!-- actual form -->
<form role="form" action="insert_vente.php" method="post">
	<div class="form-group">
		<label>Quantite</label>
		<input class="form-control" name="qt" placeholder="Atsasany: 0.5 ; Fefany: 0.25 (Tsy miasa ny virgule)" type="number">
	</div>
	<div class="form-group">
		<label>Prix Unitaire (Ariary)</label>
		<input class="form-control" name="prix_de_vente" placeholder="Ex: 15000" type="number">
	</div>
<button type="submit" class="btn btn-default">Valider</button>
</form>
<!-- actual form ends -->
</div>
</div>
</div>
</div>
<!-------------------------------------->