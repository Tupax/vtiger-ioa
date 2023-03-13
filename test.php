<?php

$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Module.php');
$moduleInstance = new Vtiger_Module();
$moduleInstance->name = 'Test3';
$moduleInstance->parent = 'Accounts';
$moduleInstance->save();
$moduleInstance->initTables();
$menuInstance = Vtiger_Menu::getInstance('Tools');
$menuInstance->addModule($moduleInstance);
