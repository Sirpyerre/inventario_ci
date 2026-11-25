<h1><?php echo html_escape($title); ?></h1>
<p>
    <a class="btn btn-default" href="<?php echo site_url('index.php/welcome/listado'); ?>">Listado de racks</a>
    <?php if (!$rack): ?>
        <a class="btn btn-default" href="<?php echo base_url(); ?>">Volver al formulario</a>
        <button id="guardar-rack" type="button" class="btn btn-primary">Guardar rack</button>
    <?php endif; ?>
</p>
<p id="estado-rack" role="alert" class="alert alert-danger" hidden></p>

<?php if ($filas): ?>
    <?php
    $columnas = [];
    foreach ($filas as $fila) {
        $columnas += $fila;
    }
    uksort($columnas, function ($a, $b) {
        return (strlen($a) <=> strlen($b)) ?: strcmp($a, $b);
    });
    ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nivel</th>
                    <?php foreach (array_keys($columnas) as $columna): ?>
                        <th scope="col" class="text-center"><?php echo html_escape($columna); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filas as $nivel => $fila): ?>
                    <tr>
                        <th scope="row">N<?php echo (int) $nivel; ?></th>
                        <?php foreach (array_keys($columnas) as $columna): ?>
                            <?php $celda = $fila[$columna] ?? null; ?>
                            <td class="text-center">
                                <?php if ($celda): ?>
                                    <img src="<?php echo base_url('public/img/caja2.jpg'); ?>" width="85" alt="">
                                    <div><strong class="pieza-numero"><?php echo html_escape($celda->numero_parte ?? 'Sin pieza asignada'); ?></strong></div>
                                    <div class="pieza-descripcion"><?php echo html_escape($celda->descripcion ?? ''); ?></div>
                                    <button type="button" class="btn btn-link editar-ubicacion"
                                        data-coordenada="<?php echo html_escape('N'.$nivel.'-'.$columna); ?>"
                                        data-id="<?php echo (int) $celda->id_ubicacion; ?>"
                                        data-numero="<?php echo html_escape($celda->numero_parte ?? ''); ?>"
                                        data-descripcion="<?php echo html_escape($celda->descripcion ?? ''); ?>"
                                        data-cantidad="<?php echo (int) $celda->cantidad; ?>"
                                        aria-label="Editar ubicación <?php echo html_escape('N'.$nivel.'-'.$columna); ?>">
                                        Unidades: <span class="pieza-cantidad"><?php echo (int) $celda->cantidad; ?></span>
                                    </button>
                                <?php else: ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Este rack no tiene ubicaciones registradas.</p>
<?php endif; ?>

<div class="modal fade" id="editor-ubicacion" tabindex="-1" role="dialog" aria-labelledby="titulo-editor">
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="form-ubicacion">
            <div class="modal-header">
                <h2 class="modal-title h4" id="titulo-editor">Editar ubicación</h2>
            </div>
            <div class="modal-body">
                <p id="estado-ubicacion" role="alert" class="alert alert-danger" hidden></p>
                <div class="form-group">
                    <label for="numero-parte">Número de parte</label>
                    <input id="numero-parte" class="form-control" maxlength="80">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <input id="descripcion" class="form-control" maxlength="255">
                </div>
                <div class="form-group">
                    <label for="cantidad">Unidades</label>
                    <input id="cantidad" class="form-control" type="number" min="0" max="2147483647" step="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar ubicación</button>
            </div>
        </form>
    </div>
</div>

<script>
(function ($) {
    var rack = <?php echo $rack ? (int) $rack->id_rack : 'null'; ?>;
    var niveles = <?php echo (int) ($niveles ?? 0); ?>;
    var ubicaciones = <?php echo (int) ($ubicaciones ?? 0); ?>;
    var crearUrl = <?php echo json_encode(site_url('index.php/welcome/crearRack'), JSON_HEX_TAG); ?>;
    var editarUrl = <?php echo json_encode(site_url('index.php/welcome/actualizarUbicacion/'), JSON_HEX_TAG); ?>;
    var editor = $('#editor-ubicacion');
    var formulario = $('#form-ubicacion');
    var activo;
    var guardando = false;

    function leer(boton) {
        return {
            numero_parte: boton.attr('data-numero'),
            descripcion: boton.attr('data-descripcion'),
            cantidad: boton.attr('data-cantidad')
        };
    }

    function pintar(boton, datos) {
        boton.attr({
            'data-numero': datos.numero_parte || '',
            'data-descripcion': datos.descripcion || '',
            'data-cantidad': datos.cantidad
        });
        boton.find('.pieza-cantidad').text(datos.cantidad);
        boton.closest('td').find('.pieza-numero').text(datos.numero_parte || 'Sin pieza asignada');
        boton.closest('td').find('.pieza-descripcion').text(datos.descripcion || '');
    }

    function enviar(url, datos) {
        return $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(datos)
        });
    }

    function errorEn(elemento, respuesta) {
        var datos = respuesta.responseJSON || {};
        $(elemento).text(datos.error || 'No se pudo guardar. Inténtalo de nuevo.').prop('hidden', false);
    }

    $('.editar-ubicacion').on('click', function () {
        activo = $(this);
        var datos = leer(activo);
        $('#titulo-editor').text('Editar ubicación ' + activo.attr('data-coordenada'));
        $('#numero-parte').val(datos.numero_parte);
        $('#descripcion').val(datos.descripcion);
        $('#cantidad').val(datos.cantidad);
        $('#estado-ubicacion').prop('hidden', true);
        editor.modal('show');
    });

    editor.on('shown.bs.modal', function () {
        $('#numero-parte').focus();
    }).on('hide.bs.modal', function (evento) {
        if (guardando) {
            evento.preventDefault();
        }
    });

    formulario.on('submit', function (evento) {
        evento.preventDefault();
        if (guardando) {
            return;
        }
        var datos = {
            numero_parte: $('#numero-parte').val().trim().toUpperCase(),
            descripcion: $('#descripcion').val().trim(),
            cantidad: $('#cantidad').val()
        };
        if ((datos.numero_parte === '') !== (datos.descripcion === '') ||
            (!datos.numero_parte && Number(datos.cantidad) !== 0)) {
            errorEn('#estado-ubicacion', {responseJSON: {error: 'Indica el número de parte y la descripción. Una posición vacía debe tener cero unidades.'}});
            return;
        }

        if (!rack) {
            pintar(activo, datos);
            editor.modal('hide');
            return;
        }

        var boton = activo;
        guardando = true;
        formulario.find(':input').prop('disabled', true);
        $('#estado-ubicacion').prop('hidden', true);
        enviar(editarUrl + rack + '/' + boton.attr('data-id'), datos)
            .always(function () {
                guardando = false;
                formulario.find(':input').prop('disabled', false);
            })
            .done(function (guardado) {
                pintar(boton, guardado);
                editor.modal('hide');
                boton.focus();
            })
            .fail(function (respuesta) {
                errorEn('#estado-ubicacion', respuesta);
            });
    });

    $('#guardar-rack').on('click', function () {
        var boton = $(this);
        var celdas = {};
        $('.editar-ubicacion').each(function () {
            var celda = $(this);
            celdas[celda.attr('data-coordenada')] = leer(celda);
        });

        boton.prop('disabled', true);
        $('#estado-rack').prop('hidden', true);
        enviar(crearUrl, {niveles: niveles, ubicaciones: ubicaciones, celdas: celdas})
            .done(function (respuesta) {
                window.location.assign(respuesta.destino);
            })
            .fail(function (respuesta) {
                errorEn('#estado-rack', respuesta);
                boton.prop('disabled', false);
            });
    });
})(jQuery);
</script>
