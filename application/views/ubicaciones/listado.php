<h1>Listado de racks</h1>

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<a class="btn btn-xs btn-info" href="/ubicaciones">Crear rack</a>
	</div>
	<div class="col-md-12 col-lg-12">
		<?php if(!empty($racks)):?>
			<table class="table table-border">
				<thead>
					<tr>
						<th>Rack</th>
						<th>Fecha de creación</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($racks as $item):?>
					<tr>
						<td><?php echo $item->nombre?></td>
						<td><?php echo $item->fecha_creacion?></td>
					</tr>
				<?php endforeach;?>
				</tbody>

			</table>
		<?php endif?>
	</div>
</div>
<script type="text/javascript">

	$("#deshacer").on("click", function(e){
		$("#niveles").val(1);
		$("#ubicaciones").val(1);
	});
</script>