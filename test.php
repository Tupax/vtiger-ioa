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


$fieldModoContactoOtro = Vtiger_Field::getInstance('modo_de_contacto_otro_test', $module);
if ($fieldModoContactoOtro) {
    echo "El campo Modo de Contacto otro ya existe <br>";
} else {
    $fMContactoOtro = new Vtiger_Field();
    $fMContactoOtro->name = 'modo_de_contacto_otro_test';
    $fMContactoOtro->label = $fMContactoOtro->name;
    $fMContactoOtro->uitype = 19;
    $fMContactoOtro->column = $fMContactoOtro->name;
    $fMContactoOtro->columntype = 'TEXT';
    $fMContactoOtro->typeofdata = 'V~O';
    $fMContactoOtro->mandatory = true;

    $blockDetalle->addField($fMContactoOtro);
    echo "Campo agregado! <br>";
}
    // $fieldTipoCompra->delete();
    // echo "El campo Tipo de Compra fue eliminados \n";