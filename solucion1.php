<?php


$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = new Vtiger_Module();
$module->name = 'Accounts';
$module = $module->getInstance('Accounts');

// Block instance
$blockDetalle = new Vtiger_Block();
$blockDetalle->label = 'LBL_ACCOUNT_INFORMATION';
$blockDetalle = $blockDetalle->getInstance($blockDetalle->label, $module);

$fieldRut = Vtiger_Field::getInstance('rut', $module);
if ($fieldRut) {
    // var_dump($fieldRut);
    echo "El campo RUT ya existe \n <br>";
} else {

    $fieldRut = new Vtiger_Field();
    $fieldRut->name = 'rut';
    $fieldRut->table = $module->basetable;
    // $fieldRut->label = 'RUT'; // tendria que ser el mismo que name;
    $fieldRut->label = $fieldRut->name;
    $fieldRut->column = $fieldRut->name;
    $fieldRut->columntype = 'VARCHAR(100)';
    $fieldRut->uitype = 2;
    $fieldRut->typeofdata = 'V~O';
    $blockDetalle->addField($fieldRut);

    echo "Campo agregado! \n";
}

$fieldTipoCompra = Vtiger_Field::getInstance('tipo_de_compra', $module);
if ($fieldTipoCompra) {
    echo "El campo Tipo de Compra ya existe \n";
    // $fieldTipoCompra->delete();
    // echo "El campo Tipo de Compra fue eliminados \n";
} else {

    $fTipodeCompra = new Vtiger_Field();
    $fTipodeCompra->name = 'tipo_de_compra';
    $fTipodeCompra->label = 'Tipo de Compra';
    $fTipodeCompra->uitype = 33;
    $fTipodeCompra->typeofdata = 'V~O';
    $fTipodeCompra->displaytype = 1;
    // $fTipodeCompra->masseditable = 1;
    // $fTipodeCompra->presence = 0;
    $fTipodeCompra->column = 'tipo_de_compra';
    $fTipodeCompra->columntype = 'VARCHAR(255)';
    $fTipodeCompra->typeofdata = 'V~O';
    $blockDetalle->addField($fTipodeCompra);

    $fTipodeCompra->setPicklistValues(array('Compra Anual', 'Compra Semestral', 'Compra de CP'));


    echo "Campo agregado! \n";
}

// Tipo de Atencion
$fieldTipoAtencion = Vtiger_Field::getInstance('tipo_de_atencion', $module);
if ($fieldTipoAtencion) {
    echo "El campo Tipo de Compra ya existe <br>";
} else {

    $fTipodeAtencion = new Vtiger_Field();
    $fTipodeAtencion->name = 'tipo_de_atencion';
    $fTipodeAtencion->label = $fTipodeAtencion->name;
    $fTipodeAtencion->uitype = 33;
    $fTipodeAtencion->typeofdata = 'V~O';
    $fTipodeAtencion->column = $fTipodeAtencion->name;
    $fTipodeAtencion->columntype = 'VARCHAR(255)';
    $fTipodeAtencion->typeofdata = 'V~O';
    $blockDetalle->addField($fTipodeAtencion);

    $fTipodeAtencion->setPicklistValues(array('Atención Directa', 'A través de Agencia'));


    echo "Campo agregado! \n";
}

$fieldProvedor = Vtiger_Field::getInstance('es_provedor',$module);
if ($fieldProvedor){
    echo "El campo Es Provedor ya existe <br>";
} else {

    $fEsProvedor = new Vtiger_Field();
    $fEsProvedor->name = 'es_provedor';
    $fEsProvedor->label = $fEsProvedor->name;
    $fEsProvedor->uitype = 56;
    $fEsProvedor->typeofdata = 'V~O';
    $fEsProvedor->column = $fEsProvedor->name;
    // $fEsProvedor->columntype = 'INT';
    $blockDetalle->addField($fEsProvedor);
}
