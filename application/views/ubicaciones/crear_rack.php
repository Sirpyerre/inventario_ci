<h1>Crear rack</h1>
<div class="row">
	
<?php if($niveles && $ubicaciones):
	$columna = 'A';
	echo "Niveles: $niveles, ubicaciones: $ubicaciones<br>";
?>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<a class="btn btn-xs btn-success" href="#" id="guardar">Guardar cambios</a>
	</div>
	<div class="col-sm-6 col-md-6 col-lg-6">
		<a class="btn btn-xs btn-info" href="/ubicaciones">Regresar</a>
	</div>
	<div class="col-md-12 col-lg-12">
		<form method="post" id="form">
			<!-- <input type="hidden" name="rack" id="id_rack" value="<?php //echo $consecutivo?>"> -->
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>&nbsp;</th>
						<?php for($i=0; $i< $ubicaciones; $i++) {
							echo '<th class="text-center">'.$columna.'</th>';
							$columna++;
						}?>
						</tr>
					</thead>
					<tbody>
					<?php for($i = $niveles; $i > 0; $i--):
						$columna = 'A';
						?>
						<tr>
							<td><b>N<?php echo $i?></b></td>
							<?php for($j = 0; $j < $ubicaciones; $j++):?>
								<td class="text-center">
									<img src="<?php echo base_url()?>public/img/caja2.jpg" 
										class="center-block" style="width: 100px">
									<input type="hidden" value="0"
										id="ubicacion_<?php echo $i.'_'.$columna?>"
										name="ubicacion[<?php echo $i?>][<?php echo $columna?>]"
									/>
									<a href="#" class="ubicacion" 
										data-id="ubicacion_<?php echo $i.'_'.$columna?>"
										> 
										0.0
									</a>
								</td>
							<?php 
							$columna++;
							endfor;
							?>
						</tr>
					<?php endfor;?>
					</tbody>

				</table>
			</div>
		</form>
	</div>
<?php endif;?>
</div>

<script type="text/javascript">
	$(document).on("click", ".ubicacion", function(e){
		e.preventDefault();
		var $this = $(this);
		var id =$this.data("id");
		console.log("id:"+id);
		bootbox.prompt("Editar objeto del rack", function(result){ 
			if (result) {
				console.log(id+":"+result); 
				$("#"+id).val(result);
				$this.text(result);
				console.log($("#"+id));
			}
		});
	});

	//Enviar por ajax el rack creado
	$(document).on("click", "#guardar", function(e){
		e.preventDefault();

		$.ajax({
			url: BASEURL+"/index.php/welcome/crearRack",
			data: $("#form").serialize(),
			type:"post",
			dataType: "json",
			success: function(data) {
				if (data.status !== undefined && data.status === 'ok') {
					window.location.href = BASEURL+"/index.php/welcome/listado";				
				}
			}
		});
	});
</script>