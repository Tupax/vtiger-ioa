<?php

global $adb;

$appNameMenuModules = array(
    'TOOLS' => array(
        /* #1 */ 'Sucursalestest',
    ),
);

foreach ($appNameMenuModules as $APPNAME => $menuModules) {

    echo "Poblar menu '$APPNAME' con: " . implode(', ', $menuModules) . "... ";

    foreach ($menuModules as $sequence => $MODULENAME) {

        $moduleInstance = Vtiger_Module::getInstance($MODULENAME);

        if ($moduleInstance) {

            $adb->pquery("DELETE FROM vtiger_app2tab WHERE tabid = ?", array($moduleInstance->getId()));

            $adb->pquery(
                "INSERT INTO vtiger_app2tab (tabid, appname, sequence) SELECT * FROM (SELECT ?, ?, ?) AS tmp
                WHERE NOT EXISTS (SELECT 1 FROM vtiger_app2tab WHERE tabid = ? AND appname = ?) LIMIT 1",
                array($moduleInstance->getId(), $APPNAME, $sequence, $moduleInstance->getId(), $APPNAME)
            );
        }
    }
}

echo "<br><br>FIN";
