<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
		//Cargar helpers
		$this->load->helper(array('debug_helper','url'));
		$this->load->model('Rack_model');
		$this->load->model('Ubicacion_model');
	}

	public function index()
	{
		//titulo de la pagina actual
		$data['title'] = 'Crear rack';
		// la vista parcial de donde se genera el contendido de la pagina
		$data['page'] = 'ubicaciones/index';

		// Layout predefinido de la aplicacion
		$this->load->view('layouts/app', $data);
	}

	/**
	 * Genera la matriz de ubicaciones
	 */
	public function generarRack()
	{
		if ($_POST && !empty($_POST)) {
			// $rackModelo = $this->Rack_model;

			$niveles = intval($_POST['niveles']);
			$ubicaciones = intval($_POST['ubicaciones']);

			$data['title'] = 'Crear rack';
			$data['page'] = 'ubicaciones/crear_rack';
			// $data['racks'] = array(
			$data['niveles'] = $niveles;
			$data['ubicaciones'] = $ubicaciones;
			// $data['consecutivo'] = $consecutivo;
			
			$this->load->view('layouts/app', $data);
		}
	}

	public function crearRack()
	{
		if (isset($_POST) && !empty($_POST)){
			$rackModelo = $this->Rack_model;
			$ubicacionModelo = $this->Ubicacion_model;

			$consecutivo = $rackModelo->obtenerMaxId()+1;

			$dataRack = array('nombre' => 'R'.$consecutivo);
			
			if ($idRack = $rackModelo->crear($dataRack)) {

				$ubicaciones = $_POST['ubicacion'];

				$datosRack = array();
				foreach ($ubicaciones as $nivel => $item) {
					foreach ($item as $ubicacion => $cantidad) {
						$datosRack = array(
							'id_rack' => $idRack,
							'nivel' => $nivel,
							'ubicacion' => $ubicacion,
							'cantidad' => $cantidad
						);

						$ubicacionModelo->crear($datosRack);
					}
				}

				$respuesta = array('status'=> 'ok', 'Se ha creado el rack');
				// detener script y devolver un json
				exit(json_encode($respuesta));
			}
		}
	}

	public function listado()
	{
		$rackModelo = $this->Rack_model;
		$racks = $rackModelo->obtenerRacks();


		//titulo de la pagina actual
		$data['title'] = 'Listado de racks';
		// la vista parcial de donde se genera el contendido de la pagina
		$data['page'] = 'ubicaciones/listado';
		$data['racks'] = $racks;

		// Layout predefinido de la aplicacion
		$this->load->view('layouts/app', $data);

	}

}
