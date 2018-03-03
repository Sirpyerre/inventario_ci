<h1>Crear rack</h1>
<hr>
<form method="post" action="<?php echo base_url()?>index.php/welcome/generarRack">
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			Niveles
			<input type="text" name="niveles" id="niveles" class="form-control" value="4">
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			Ubicaciones
			<input type="number" name="ubicaciones" id="ubicaciones" class="form-control" value="5">
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
		$("#niveles").val(1);
		$("#ubicaciones").val(1);
	});
</script>