<h1>Crear rack</h1>
<hr>
<p><a class="btn btn-default" href="<?php echo site_url('index.php/welcome/listado'); ?>">Listado de racks</a></p>
<?php if (isset($error)): ?>
    <p class="alert alert-danger" role="alert"><?php echo html_escape($error); ?></p>
<?php endif; ?>
<form method="post" action="<?php echo site_url('index.php/welcome/generarRack'); ?>">
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<label for="niveles">Niveles</label>
			<input type="number" name="niveles" id="niveles" class="form-control" value="4" min="1" max="127" required>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<label for="ubicaciones">Ubicaciones por nivel</label>
			<input type="number" name="ubicaciones" id="ubicaciones" class="form-control" value="5" min="1" max="702" required>
		</div>
	</div>

	<div class="row" style="margin-top: 10px">
		<div class="col-xs-10">
			<button type="button" id="deshacer" class="btn btn-md btn-default pull-right">Deshacer cambios</button>
		</div>
		<div class="col-xs-2">
			<button id="generar" class="btn btn-md btn-primary pull-right">Generar rack</button>
		</div>
	</div>
</form>

<script type="text/javascript">

	$("#deshacer").on("click", function(e){
		this.form.reset();
	});
</script>
