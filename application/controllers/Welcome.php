<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Rack_model');
        $this->load->model('Ubicacion_model');
    }

    public function index()
    {
        $this->pagina('Crear rack', 'ubicaciones/index');
    }

    public function generarRack()
    {
        $niveles = $this->entero($this->input->post('niveles'), 1, 127);
        $ubicaciones = $this->entero($this->input->post('ubicaciones'), 1, 702);

        if ($niveles === false || $ubicaciones === false) {
            $this->output->set_status_header(422);
            $this->pagina('Crear rack', 'ubicaciones/index', [
                'error' => 'Indica entre 1 y 127 niveles y entre 1 y 702 ubicaciones por nivel.'
            ]);
            return;
        }

        $filas = [];
        for ($nivel = $niveles; $nivel > 0; $nivel--) {
            $columna = 'A';
            for ($i = 0; $i < $ubicaciones; $i++, $columna++) {
                $filas[$nivel][$columna] = (object) [
                    'id_ubicacion' => null, 'numero_parte' => null, 'descripcion' => null, 'cantidad' => 0
                ];
            }
        }

        $this->pagina('Crear rack', 'ubicaciones/crear_rack', [
            'rack' => null, 'filas' => $filas, 'niveles' => $niveles, 'ubicaciones' => $ubicaciones
        ]);
    }

    public function crearRack()
    {
        $datos = $this->datosJson();
        $niveles = $this->entero($datos['niveles'] ?? null, 1, 127);
        $ubicaciones = $this->entero($datos['ubicaciones'] ?? null, 1, 702);
        $celdas = $datos['celdas'] ?? null;

        if (!$niveles || !$ubicaciones || !is_array($celdas) || count($celdas) !== $niveles * $ubicaciones) {
            return $this->responder(422, ['error' => 'La cuadrícula del rack está incompleta.']);
        }

        $filas = [];
        for ($nivel = $niveles; $nivel > 0; $nivel--) {
            $columna = 'A';
            for ($i = 0; $i < $ubicaciones; $i++, $columna++) {
                $pieza = $this->pieza($celdas['N'.$nivel.'-'.$columna] ?? null);
                if ($pieza === false) {
                    return $this->responder(422, ['error' => 'Revisa los datos de N'.$nivel.'-'.$columna.'.']);
                }
                $filas[] = $pieza + ['nivel' => $nivel, 'ubicacion' => $columna];
            }
        }

        $this->db->db_debug = false;
        if (!$this->db->trans_begin()) {
            return $this->responder(500, ['error' => 'No se pudo iniciar el guardado del rack.']);
        }

        $id = $this->Rack_model->crear();
        $guardado = (bool) $id;
        if ($id) {
            foreach (array_chunk($filas, 250) as $grupo) {
                if (!$this->Ubicacion_model->crearVarias($id, $grupo)) {
                    $guardado = false;
                    break;
                }
            }
        }

        if (!$guardado || !$this->db->trans_status() || !$this->db->trans_commit()) {
            $this->db->trans_rollback();
            return $this->responder(500, ['error' => 'No se pudo guardar el rack. Inténtalo de nuevo.']);
        }

        return $this->responder(201, [
            'destino' => site_url('index.php/welcome/detalle/'.$id)
        ]);
    }

    public function detalle($id)
    {
        $rack = $this->Rack_model->obtener($id);
        if (!$rack) {
            show_404();
            return;
        }

        $filas = [];
        foreach ($this->Ubicacion_model->porRack($id) as $celda) {
            $filas[$celda->nivel][$celda->ubicacion] = $celda;
        }

        $this->pagina('Rack '.$rack->nombre, 'ubicaciones/crear_rack', [
            'rack' => $rack, 'filas' => $filas
        ]);
    }

    public function actualizarUbicacion($rack, $id)
    {
        $actual = $this->Ubicacion_model->obtener($rack, $id);
        if (!$actual) {
            return $this->responder(404, ['error' => 'La ubicación no pertenece a este rack.']);
        }

        $pieza = $this->pieza($this->datosJson());
        if ($pieza === false) {
            return $this->responder(422, [
                'error' => 'Indica número de parte, descripción y unidades enteras no negativas. Una posición vacía debe tener cero unidades.'
            ]);
        }

        $this->db->db_debug = false;
        if (!$this->Ubicacion_model->actualizar($rack, $id, $pieza)) {
            return $this->responder(500, ['error' => 'No se pudo guardar la ubicación. Inténtalo de nuevo.']);
        }

        return $this->responder(200, $pieza);
    }

    public function listado()
    {
        $entrada = $this->input->get('numero_parte');
        $numero = is_string($entrada) ? trim($entrada) : '';
        $this->pagina('Listado de racks', 'ubicaciones/listado', [
            'racks' => $this->Rack_model->obtenerRacks(),
            'numero' => $numero,
            'resultados' => $numero === '' ? [] : $this->Ubicacion_model->buscar(mb_strtoupper($numero, 'UTF-8'))
        ]);
    }

    private function datosJson()
    {
        if ($this->input->method() !== 'post') {
            show_error('Método no permitido.', 405);
        }
        return json_decode($this->input->raw_input_stream, true);
    }

    private function entero($valor, $min, $max)
    {
        if ((!is_int($valor) && !is_string($valor)) || !preg_match('/^[0-9]+$/D', (string) $valor)) {
            return false;
        }
        $entero = (int) $valor;
        return $entero >= $min && $entero <= $max ? $entero : false;
    }

    private function pieza($datos)
    {
        if (!is_array($datos) || !is_string($datos['numero_parte'] ?? null) || !is_string($datos['descripcion'] ?? null)) {
            return false;
        }

        $numero = mb_strtoupper(trim($datos['numero_parte']), 'UTF-8');
        $descripcion = trim($datos['descripcion']);
        $cantidad = $this->entero($datos['cantidad'] ?? null, 0, 2147483647);

        if ($cantidad === false || mb_strlen($numero, 'UTF-8') > 80 || mb_strlen($descripcion, 'UTF-8') > 255) {
            return false;
        }
        if ($numero === '' || $descripcion === '') {
            if ($numero !== '' || $descripcion !== '' || $cantidad !== 0) {
                return false;
            }
        }

        return ['numero_parte' => $numero === '' ? null : $numero,
            'descripcion' => $descripcion === '' ? null : $descripcion, 'cantidad' => $cantidad];
    }

    private function pagina($titulo, $vista, array $datos = [])
    {
        $this->load->view('layouts/app', $datos + ['title' => $titulo, 'page' => $vista]);
    }

    private function responder($estado, array $datos)
    {
        return $this->output->set_status_header($estado)->set_content_type('application/json')
            ->set_output(json_encode($datos));
    }
}
