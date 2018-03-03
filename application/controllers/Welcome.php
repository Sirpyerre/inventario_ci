<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
		//Cargar helpers
		$this->load->helper(array('debug_helper','url'));
		$this->load->model('Rack_model');
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

	public function generarRack()
	{
		if ($_POST && !empty($_POST)) {
			$rackModelo = $this->Rack_model;
			$racks = array();

			$niveles = intval($_POST['niveles']);
			$ubicaciones = intval($_POST['ubicaciones']);



			$data['title'] = 'Crear rack';
			$data['page'] = 'ubicaciones/crear_rack';
			// $data['racks'] = array(
			$data['niveles'] = $niveles;
			$data['ubicaciones'] = $ubicaciones;
			
			$this->load->view('layouts/app', $data);
		}
	}

}
