<h1>Listado de racks</h1>
<p><a class="btn btn-primary" href="<?php echo base_url(); ?>">Crear rack</a></p>

<form method="get" action="<?php echo site_url('index.php/welcome/listado'); ?>" class="form-inline">
    <div class="form-group">
        <label for="buscar-pieza">Número de parte</label>
        <input type="search" class="form-control" id="buscar-pieza" name="numero_parte"
            value="<?php echo html_escape($numero); ?>" required>
    </div>
    <button class="btn btn-default" type="submit">Buscar</button>
</form>

<?php if ($numero !== ''): ?>
    <h2>Resultados para <?php echo html_escape($numero); ?></h2>
    <?php if ($resultados): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Número de parte</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Rack</th>
                        <th scope="col">Nivel</th>
                        <th scope="col">Columna</th>
                        <th scope="col">Unidades</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $pieza): ?>
                        <tr>
                            <td><?php echo html_escape($pieza->numero_parte); ?></td>
                            <td><?php echo html_escape($pieza->descripcion); ?></td>
                            <td><a href="<?php echo site_url('index.php/welcome/detalle/'.(int) $pieza->id_rack); ?>"><?php echo html_escape($pieza->nombre); ?></a></td>
                            <td>N<?php echo (int) $pieza->nivel; ?></td>
                            <td><?php echo html_escape($pieza->ubicacion); ?></td>
                            <td><?php echo (int) $pieza->cantidad; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron coincidencias.</p>
    <?php endif; ?>
    <p><a href="<?php echo site_url('index.php/welcome/listado'); ?>">Limpiar búsqueda</a></p>
<?php endif; ?>

<h2>Racks registrados</h2>
<?php if ($racks): ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Rack</th>
                <th scope="col">Fecha de creación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($racks as $rack): ?>
                <tr>
                    <td><a href="<?php echo site_url('index.php/welcome/detalle/'.(int) $rack->id_rack); ?>"><?php echo html_escape($rack->nombre); ?></a></td>
                    <td><?php echo html_escape($rack->fecha_creacion); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aún no hay racks registrados.</p>
<?php endif; ?>
