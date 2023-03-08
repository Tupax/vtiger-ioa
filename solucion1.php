<?php


$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

$module = new Vtiger_Module();
$module->name = 'Accounts';
$module = $module->getInstance('Accounts');
var_dump($module);
// $moduleInstance = Vtiger_Module::getInstance('Accounts');

// Block instance
$blockDetalle = new Vtiger_Block();
$blockDetalle->label = 'LBL_ACCOUNT_INFORMATION';
$blockDetalle = $blockDetalle->getInstance($blockDetalle->label,$module);
var_dump($blockDetalle);



$fieldRut = Vtiger_Field::getInstance('rut', $module);

if($fieldRut) {
    var_dump($fieldRut);
    echo "El campo RUT ya existe \n <br>";
} else {

    $fieldRut = new Vtiger_Field();
    $fieldRut->name = 'rut';
    $fieldRut->table = $module->basetable;
    $fieldRut->label = 'RUT';
    $fieldRut->column = 'rut';
    $fieldRut->columntype = 'VARCHAR(100)';
    $fieldRut->uitype = 2;
    $fieldRut->typeofdata = 'V~O';
    $blockDetalle->addField($fieldRut);

    echo "Campo agregado! \n"; 
}

// $fieldTipoCompra = Vtiger_Field::getInstance('tipo_de_compra', $module);

// if($fieldTipoCompra) {
//     // var_dump($fieldTipoCompra);
//     echo "El campo Tipo de Compra ya existe \n";
// } else {

//     $fTipodeCompra = new Vtiger_Field();
//     $fTipodeCompra->name = 'tipo_de_compra';
//     $fTipodeCompra->label = 'Tipo de Compra';
//     $fTipodeCompra->uitype = 33;
//     $fTipodeCompra->typeofdata = 'V~O';
//     $fTipodeCompra->displaytype = 2;
//     // $fTipodeCompra->masseditable = 1;
//     // $fTipodeCompra->presence = 0;
//     $fTipodeCompra->column = 'tipo_de_compra';
//     $fTipodeCompra->columntype = 'VARCHAR(255)';
//     $fTipodeCompra->typeofdata = 'V~O';
//     $fieldInstance->setPicklistValues( Array ('Compra Anual', 'Compra Semestral', 'Compra de CP') );
    
//     $blockDetalle->addField($fTipodeCompra);
//     // $moduleInstance = Vtiger_Module::getInstance('Accounts');
//     // $moduleInstance->addField($fTipodeCompra);


//     echo "Campo agregado! \n"; 
// }
