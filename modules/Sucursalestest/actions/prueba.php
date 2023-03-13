<?php

require_once("integracion/ejemplos/LpEjemplos.php");

class Sucursalestest_prueba_Action extends Vtiger_Action_Controller {

	function checkPermission(Vtiger_Request $request) {
		return;
	}

	public function process(Vtiger_Request $request) {

		global $adb, $log;

		$log->debug("__METHOD__" . __METHOD__ . "__LINE__" . __LINE__);

		$result = array(
			"info" => "probando...",
			"dato" => $request->get('dato'),
		);

		LpEjemplos::exec_flujo_uno();

		$log->debug("__METHOD__" . __METHOD__ . "__LINE__" . __LINE__);

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();

	}

}