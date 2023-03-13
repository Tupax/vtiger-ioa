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
    $blockDetalle->addField($fTipodeAtencion);

    $fTipodeAtencion->setPicklistValues(array('Atención Directa', 'A través de Agencia'));


    echo "Campo agregado! \n";
}

$fieldProvedor = Vtiger_Field::getInstance('es_provedor', $module);
if ($fieldProvedor) {
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
    echo "Campo agregado! \n";
}

$fieldModoContacto = Vtiger_Field::getInstance('modo_de_contacto', $module);
if ($fieldModoContacto) {
    echo "El campo Es Modo de contacto ya existe <br>";
} else {

    $fModoContacto = new Vtiger_Field();
    $fModoContacto->name = 'modo_de_contacto';
    $fModoContacto->label = $fModoContacto->name;
    $fModoContacto->uitype = 16;
    $fModoContacto->typeofdata = 'V~M';
    $fModoContacto->column = $fModoContacto->name;
    $fEsProvedor->columntype = 'VARCHAR(255)';
    $blockDetalle->addField($fModoContacto);

    $values = array(
        'mdc_gproactiva' => array('en_us' => 'Proactive Management', 'es_es' => 'Gestión Proactiva'),
        'mdc_referido' => array('en_us' => 'Referal', 'es_es' => 'Referido'),
        'mdc_intermed' => array('en_us' => 'Medium', 'es_es' => 'Intermediario'),
        'mdc_espontaneo' => array('en_us' => 'Spontaneous customer', 'es_es' => 'Cliente Espontáneo'),
        'mdc_otro' => array('en_us' => 'Other', 'es_es' => 'Otro'),
      );

    $fModoContacto->setPicklistValues(array(
        'Gestión Proactiva',
        'Referido',
        'Intermediario',
        'Cliente Espontáneo',
        'Otro',
    ));

    echo "Campo agregado! \n";
}

$fieldModoContactoOtro = Vtiger_Field::getInstance('modo_de_contacto_otro', $module);
if ($fieldModoContactoOtro) {
    echo "El campo Modo de Contacto otro ya existe <br>";
} else {
    $fMContactoOtro = new Vtiger_Field();
    $fMContactoOtro->name = 'modo_de_contacto_otro';
    $fMContactoOtro->label = $fMContactoOtro->name;
    $fMContactoOtro->uitype = 19;
    $fMContactoOtro->column = $fMContactoOtro->name;
    $fMContactoOtro->columntype = 'TEXT';
    $fMContactoOtro->typeofdata = 'V~O';
    $blockDetalle->addField($fMContactoOtro);
    echo "Campo agregado! <br>";
}

$bloque_social = Vtiger_Block::getInstance('ejemplodos_bloque_auditoria', $module);

if (!$bloque_social) {
    $bloque_social = new Vtiger_Block();
    $bloque_social->label = 'redes_sociales';
    $module->addBlock($bloque_social);
}

$twitter = Vtiger_Field::getInstance('twitter', $module);

if (!$twitter) {
    $twitter = new Vtiger_Field();
    $twitter->name = 'twitter';
    $twitter->label = 'twitter';
    $twitter->uitype = 1;
    $twitter->column = $twitter->name;
    $twitter->columntype = 'TEXT';
    $twitter->typeofdata = 'V~O';
    $bloque_social->addField($twitter);
}
$facebook = Vtiger_Field::getInstance('facebook', $module);

if (!$facebook) {
    $facebook = new Vtiger_Field();
    $facebook->name = 'facebook';
    $facebook->label = 'facebook';
    $facebook->uitype = 1;
    $facebook->column = $facebook->name;
    $facebook->columntype = 'TEXT';
    $facebook->typeofdata = 'V~O';
    $bloque_social->addField($facebook);
}
$instagram = Vtiger_Field::getInstance('instagram', $module);

if (!$instagram) {
    $instagram = new Vtiger_Field();
    $instagram->name = 'instagram';
    $instagram->label = 'instagram';
    $instagram->uitype = 1;
    $instagram->column = $instagram->name;
    $instagram->columntype = 'TEXT';
    $instagram->typeofdata = 'V~O';
    $bloque_social->addField($instagram);
}

$linkedin = Vtiger_Field::getInstance('linkedin', $module);

if (!$linkedin) {
    $linkedin = new Vtiger_Field();
    $linkedin->name = 'linkedin';
    $linkedin->label = 'linkedin';
    $linkedin->uitype = 1;
    $linkedin->column = $linkedin->name;
    $linkedin->columntype = 'TEXT';
    $linkedin->typeofdata = 'V~O';
    $bloque_social->addField($linkedin);
}