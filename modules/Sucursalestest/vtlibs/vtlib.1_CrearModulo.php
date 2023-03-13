<?php

require_once('vtlib/Vtiger/Module.php');

$Vtiger_Utils_Log = true;

$MODULENAME = 'Sucursalestest';

/*******************************************************************************
Se crea el MODULO
*******************************************************************************/

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);

// LIMPIAR TODO ANTES DE CREAR EL MODULO (RE-INSTALACION)

// (NO HACER ESTO PORQUE REPITE PICKLISTS)

// if ($moduleInstance) {
//     // SOLO MODULOS CUSTOM
//     $moduleInstance->delete(); 
//     $moduleInstance = null;
//     // SOLO MODULOS CUSTOM
// }

// Nota : Los campos, tablas y datos seguiran en la  BD

// //////////////////////////////////////////////////////

$nuevoModulo = false;

if (!$moduleInstance) {

    $nuevoModulo = true;

    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent = 'Accounts';
    $moduleInstance->save();
    $moduleInstance->initTables();
    echo "tablas creadas";

}

/*******************************************************************************
Se crean los 4 BLOQUES 

$s_bloque_principal 
$s_bloque_dentrega
$s_bloque_dcobranza
$s_bloque_custom

*******************************************************************************/

$s_bloque_principal = Vtiger_Block::getInstance('LBL_SUCURSALESTEST_INFORMATION', $moduleInstance);
if (!$s_bloque_principal) {
    $s_bloque_principal = new Vtiger_Block();
    $s_bloque_principal->label = 'LBL_SUCURSALESTEST_INFORMATION';
    $moduleInstance->addBlock($s_bloque_principal);
}

$s_bloque_dentrega = Vtiger_Block::getInstance('LBL_SUCURSALESTEST_DIRENTREGA_INFORMATION', $moduleInstance);
if (!$s_bloque_dentrega) {
    $s_bloque_dentrega = new Vtiger_Block();
    $s_bloque_dentrega->label = 'LBL_SUCURSALESTEST_DIRENTREGA_INFORMATION';
    $moduleInstance->addBlock($s_bloque_dentrega);
}

$s_bloque_dcobranza = Vtiger_Block::getInstance('LBL_SUCURSALESTEST_DIRCOBRANZA_INFORMATION', $moduleInstance);
if (!$s_bloque_dcobranza) {
    $s_bloque_dcobranza = new Vtiger_Block();
    $s_bloque_dcobranza->label = 'LBL_SUCURSALESTEST_DIRCOBRANZA_INFORMATION';
    $moduleInstance->addBlock($s_bloque_dcobranza);
}

$s_bloque_custom = Vtiger_Block::getInstance('LBL_CUSTOM_INFORMATION', $moduleInstance);
if (!$s_bloque_custom) {
    $s_bloque_custom = new Vtiger_Block();
    $s_bloque_custom->label = 'LBL_CUSTOM_INFORMATION';
    $moduleInstance->addBlock($s_bloque_custom);
}

/*******************************************************************************
Se crean los CAMPOS para los BLOQUES

1. $s_bloque_principal con los campos:


$fNombre ........ nombre
$fIdSucursal .... idsucursal


*******************************************************************************/


// 1 . $s_bloque_principal
$fNombre = Vtiger_Field::getInstance('nombre', $moduleInstance);

if (!$fNombre) {
    $fNombre = new Vtiger_Field();
    $fNombre->name = 'nombre';
    $fNombre->label = 'nombre';
    $fNombre->table = $moduleInstance->basetable;
    $fNombre->uitype = 4;
    $fNombre->column = $fNombre->name;
    $fNombre->columntype = 'VARCHAR(255)';
    $fNombre->typeofdata = 'V~O';

    $s_bloque_principal->addField($fNombre);
    
    // como chequeo el atributo ReadOnly ?
    // $fNombre->setReadOnly(true);
}

/******************************************************************************/
$moduleInstance->setEntityIdentifier($fNombre); // Para vtiger_entityname
/******************************************************************************/


$fIdSucursal = Vtiger_Field::getInstance('idsucursal', $moduleInstance);

if ($fIdSucursal) {
    $fIdSucursal->delete();
    $fIdSucursal = null;
}

if (!$fIdSucursal) {
    $fIdSucursal = new Vtiger_Field();
    $fIdSucursal->name = 'idsucursal';
    $fIdSucursal->label = 'idsucursal';
    $fIdSucursal->table = $moduleInstance->basetable;
    $fIdSucursal->uitype = 1;
    $fIdSucursal->column = $fIdSucursal->name;
    $fIdSucursal->columntype = 'INT';
    $fIdSucursal->typeofdata = 'I~O';
    $s_bloque_principal->addField($fIdSucursal);
    // $fIdSucursal->setRelatedModules(Array('EjemploDos'));
}

// $ejemploidexterno = Vtiger_Field::getInstance('ejemploidexterno', $moduleInstance);

// if (!$ejemploidexterno) {
//     $ejemploidexterno = new Vtiger_Field();
//     $ejemploidexterno->name = 'ejemploidexterno';
//     $ejemploidexterno->label = 'ejemploidexterno';
//     $ejemploidexterno->table = $moduleInstance->basetable;
//     $ejemploidexterno->uitype = 1;
//     $ejemploidexterno->column = $ejemploidexterno->name;
//     $ejemploidexterno->columntype = 'INT(16)';
//     $ejemploidexterno->typeofdata = 'I~M';
//     $ejemploidexterno->quickcreate = 1;
//     $ejemplouno_bloque_principal->addField($ejemploidexterno);
// }

// $ejemplodato = Vtiger_Field::getInstance('ejemplodato', $moduleInstance);

// if (!$ejemplodato) {
//     $ejemplodato = new Vtiger_Field();
//     $ejemplodato->name = 'ejemplodato';
//     $ejemplodato->label = 'ejemplodato';
//     $ejemplodato->table = $moduleInstance->basetable;
//     $ejemplodato->uitype = 1;
//     $ejemplodato->column = $ejemplodato->name;
//     $ejemplodato->columntype = 'TEXT';
//     $ejemplodato->typeofdata = 'V~O';
//     $ejemplodato->quickcreate = 1;
//     $ejemplouno_bloque_principal->addField($ejemplodato);
// }



// $mfield1 = Vtiger_Field::getInstance('assigned_user_id', $moduleInstance);

// if (!$mfield1) {
//     $mfield1 = new Vtiger_Field();
//     $mfield1->name = 'assigned_user_id';
//     $mfield1->label = 'Assigned To';
//     $mfield1->table = 'vtiger_crmentity';
//     $mfield1->column = 'smownerid';
//     $mfield1->uitype = 53;
//     $mfield1->typeofdata = 'V~M';
//     $s_bloque_custom->addField($mfield1);
// }

// $mfield2 = Vtiger_Field::getInstance('createdtime', $moduleInstance);

// if (!$mfield2) {
//     $mfield2 = new Vtiger_Field();
//     $mfield2->name = 'createdtime';
//     $mfield2->label= 'Created Time';
//     $mfield2->table = 'vtiger_crmentity';
//     $mfield2->column = 'createdtime';
//     $mfield2->uitype = 70;
//     $mfield2->typeofdata = 'T~M';
//     $mfield2->displaytype = 2;
//     $s_bloque_custom->addField($mfield2);
// }

// $mfield3 = Vtiger_Field::getInstance('modifiedtime', $moduleInstance);

// if (!$mfield3) {
//     $mfield3 = new Vtiger_Field();
//     $mfield3->name = 'modifiedtime';
//     $mfield3->label= 'Modified Time';
//     $mfield3->table = 'vtiger_crmentity';
//     $mfield3->column = 'modifiedtime';
//     $mfield3->uitype = 70;
//     $mfield3->typeofdata = 'T~M';
//     $mfield3->displaytype = 2;
//     $s_bloque_custom->addField($mfield3);
// }

/******************************************************************************/

if ($nuevoModulo) {

    // Eliminar todos los filtros del modulo:
    Vtiger_Filter::deleteForModule($moduleInstance);
    // //////////////////////////////////////

    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;

    $moduleInstance->addFilter($filter1);

    $filter1->addField($fNombre, 0)
    ->addField($fIdSucursal, 1)
    // ->addField($ejemplodato, 2)
    // ->addField($mfield3, 3)
    // ->addField($mfield2, 4)
    // ->addField($mfield1, 5)
    ;

}

/******************************************************************************/

//$moduleInstance->setDefaultSharing(); $moduleInstance->initWebservice();

// /////////////////////////////////////////////////////////////////////////////

// Los registros de este tipo apuntan al modulo padre...

// $moduloPadre = Vtiger_Module::getInstance('EjemploDos'); // Este es el 'padre'
// $etiqueta = 'Lista de Ejemplos'; // Tener en cuenta a la hora de unsetear (*)
// $permisos = Array('SELECT', 'ADD'); // Posibles operaciones permitidas
// $funcion = 'get_dependents_list'; // Tambien puede ser una custom (*)
// $campoHijo = $fIdSucursal->id; // Para que se autocomplete
// $moduloPadre->unsetRelatedList($moduleInstance, $etiqueta, $funcion);
// $moduloPadre->setRelatedList($moduleInstance, $etiqueta, $permisos, $funcion, $campoHijo);

// /////////////////////////////////////////////////////////////////////////////

// Habilitar el registro de cambios (AUDITORIA) para este modulo:

// ModTracker::disableTrackingForModule($moduleInstance->id); // DESACTIVAR 
// ModTracker::enableTrackingForModule($moduleInstance->id); // ACTIVAR 