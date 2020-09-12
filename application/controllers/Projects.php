<?php 
	
namespace App\Controllers;

// use App\Model\ProjectModel;

class Projects
{
	public function projects($control)
	{
		$token = $control->getToken();
		
		$res = curl_request('GET', $control->evolta_host.'/api/proyecto', [
			'headers'  => [
				'Authorization'  => 'Bearer '.$token
			]
		]);

		$projects = json_decode($res);
		// $projectModel = new ProjectModel();

		// foreach($projects as $project) {
		// 	$_project = [
		// 		'idProyecto' => $project->idProyecto,
		// 		'proyecto' => 	$project->proyecto,
		// 		'idEmpresa' => 	$project->idEmpresa,
		// 		'direccion' => 	$project->direccion,
		// 		'idDepartamento' => $project->idDepartamento->value,
		// 		'idProvincia' => 	$project->idProvincia->value,
		// 		'idDistrito' => 	$project->idDistrito->value,
		// 		'techoPropio' => 	$project->techoPropio,
		// 		'miVivienda' => 	$project->miVivienda,
		// 		'observaciones' => 	$project->observaciones,
		// 		'numeroInmuebles' => $project->numeroInmuebles,
		// 		'anulado' => 	$project->anulado,
		// 		'idPais' => 	$project->idPais->value,
		// 		'fechaCreacion' => 	$project->fechaCreacion,
		// 		'fechaInicio' => 	$project->fechaInicio,
		// 		'fechaEntrega' => 	$project->fechaEntrega,
		// 		'direccionReferencia' => $project->direccionReferencia,
		// 		'urbanizacion' => 	$project->urbanizacion,
		// 		'idEstado' => 	$project->idEstado,
		// 		'idUsuario' => 	$project->idUsuario->value,
		// 		'idUsuarioModificacion' => $project->idUsuarioModificacion->value,
		// 		'fechaModificacion' => 	$project->fechaModificacion
		// 	];

		// 	// $projectModel->insert($_project);
		// }

		return $projects;
	}

	// public function details()
	// {
	// 	$token = $this->getToken();

	// 	// get projects from database
	// 	$projects = array();

	// 	foreach($projects as $project){
	// 		$result = $this->client->request('get', '/api/proyecto'.$project->idProyecto, [
	// 			'headers'  => [
	// 				'Authorization'  => 'Bearer '.$token
	// 			]
	// 		]);

	// 		$detail = json_decode($result->getBody());

	// 		// update project detail of database
	// 		// code here
	// 	}
		
	// 	return 'fetched project details';
	// }
}
