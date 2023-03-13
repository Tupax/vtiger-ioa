<?php

require_once('include/utils/utils.php');
require_once('modules/Vtiger/models/Picklist.php');

$moduleName = 'Accounts';
$fieldname = 'tipo_de_compra';
$newValue = array('Compra Anual test');

// Get the picklist model for the field
$picklistModel = Vtiger_Picklist_Model::getInstance($moduleName, $fieldname);

// Add the new picklist value
$picklistModel->addPicklistValue($newValue);

// Save the changes
$picklistModel->save();