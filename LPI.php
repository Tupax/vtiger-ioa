<?php
ini_set('display_errors','off'); error_reporting(0);

require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('includes/Loader.php');
require_once('modules/ServiceContracts/ServiceContracts.php');
require_once 'includes/runtime/LanguageHandler.php';

global $adb, $log, $default_timezone;
global $site_URL, $application_unique_key;
global $default_language;
global $current_language;
global $default_theme;
global $current_user;


if (!$current_user) {
	include_once 'includes/main/WebUI.php';
	$webUI = new Vtiger_WebUI();
	Vtiger_Session::init();
	$current_user  = $webUI->getLogin();
}

vimport('includes.http.Request');
vimport('includes.runtime.Globals');
vimport('includes.runtime.BaseModel');
vimport('includes.runtime.Controller');

include_once('modules/com_vtiger_workflow/VTEntityMethodManager.inc');

$vtlib = $_POST['vtlib'];
if ($vtlib && !empty($vtlib)) {
    if (!$current_user || !$current_user->is_admin) {
        echo "NO SOS ADMIN, TOMATELAS‼🤐🚫🚫";
        exit();
    }
    $Vtiger_Utils_Log = true;
    // ini_set('display_errors','on'); error_reporting(E_ALL); 
    abstract class BaseVtliv {
        protected $modulename;
        protected $operations = array();
        protected $moduleInstance;
        protected $parent="Marketing";
        abstract function process();
        function createModule() {
            $moduleInstance = Vtiger_Module::getInstance($this->modulename);
            if(!$moduleInstance){       
                $moduleInstance = new Vtiger_Module();
                $moduleInstance->name = $this->modulename;
                $moduleInstance->parent = $this->parent;
                $moduleInstance->save();
                static::addModuleToApp($moduleInstance);
                static::crearEstructuraArchivosModulo($this->modulename);
                $moduleInstance->initTables();
            } else {
                echo "El modulo ya existe\n";
            }
            $this->moduleInstance = $moduleInstance;
        }
        function defaultModuleConfig() {
            /** Set sharing access of this module */
            $this->moduleInstance->setDefaultSharing('Public'); 
            /** Enable and Disable available tools */
            $this->moduleInstance->enableTools(Array('Import', 'Export'));
            $this->moduleInstance->disableTools('Merge');
            $this->moduleInstance->initWebservice();
        }
        function createBlock($label) {
            $blockInstance = Vtiger_Block::getInstance($label,$this->moduleInstance);
            if(!$blockInstance){
                $blockInstance = new Vtiger_Block();
                $blockInstance->label = $label;
                $this->moduleInstance->addBlock($blockInstance); 
            } else {
                echo "El bloque $label ya existe\n";
            }
            return $blockInstance;
        }
        function createField($data, $blockInstance) {
            $fieldInstance = Vtiger_Field::getInstance($data['name'], $this->moduleInstance);
            if (!$fieldInstance) {
                $fieldInstance             = new Vtiger_Field();
                $fieldInstance->name       = $data["name"];
    
                $opcionales = array("typeofdata","uitype","columntype","helpinfo","summaryfield","masseditable","presence","maximumlength","sequence","quickcreate","quicksequence","info_type","isunique","headerfield", "defaultvalue");
                foreach ($opcionales as $fiendAttr) 
                if (isset($data[$fiendAttr]) && !empty($data[$fiendAttr])) {
                    $fieldInstance->$fiendAttr = $data[$fiendAttr];
                }
    
    
                if (isset($data["displaytype"]) && !empty($data["displaytype"])) {
                    $fieldInstance->displaytype = $data["displaytype"];
                } else {
                    $fieldInstance->displaytype = 1;
                }
                if (isset($data["label"]) && !empty($data["label"])) {
                    $fieldInstance->label = $data["label"];
                } else {
                    $fieldInstance->label = $fieldInstance->name;
                } 
                if (isset($data["column"]) && !empty($data["column"])) {
                    $fieldInstance->column = $data["column"];
                } else {
                    $fieldInstance->column = $fieldInstance->name;            
                }
                if (isset($data["table"]) && !empty($data["table"])) {
                    $fieldInstance->table = $data["table"];
                } else {
                    $fieldInstance->table = $this->moduleInstance->basetable;
                }
    
                $blockInstance->addField($fieldInstance);
                if (isset($data["setRelatedModules"]) && !empty($data["setRelatedModules"]) && is_array($data["setRelatedModules"])) {
                    $fieldInstance->setRelatedModules($data["setRelatedModules"]);
                }
                if (isset($data["setPicklistValues"]) && !empty($data["setPicklistValues"]) && is_array($data["setPicklistValues"])) {
                    $fieldInstance->setPicklistValues($data["setPicklistValues"]);
                }
            }
            return $fieldInstance;
        }
        function agregarRelacion($relatedToModuleName, $label, $fn, $actions=array(), $fieldId = null) {
            if (!$this->moduleInstance) $this->createModule();
            $fn              = $fn;
            $relationLabel   = $label;
            $relatedToModule = Vtiger_Module::getInstance($relatedToModuleName);
            $this->moduleInstance->unsetRelatedList($relatedToModule, $relationLabel, $fn);
            $this->moduleInstance->setRelatedList($relatedToModule, $relationLabel, $actions, $fn, $fieldId);
        }
        function createFilter($fields, $name = "All", $deleteOthers = true) {    
            if ($deleteOthers) Vtiger_Filter::deleteForModule($this->moduleInstance);
            $filter1 = new Vtiger_Filter();
            $filter1->name = $name;
            $filter1->isdefault = true;
            $this->moduleInstance->addFilter($filter1);
            // invertir orden
            foreach(array_reverse($fields) as $field) $filter1->addField($field);
    
        }
        function crearEstructuraArchivosModulo() {
            $targetpath = 'modules/' . $this->modulename;
            $fieldid  = strtolower($this->modulename);    
            if (!is_file($targetpath)) {
                mkdir($targetpath, 7777);
                mkdir($targetpath . '/language', 7777);
                $templatepath = 'vtlib/ModuleDir/6.0.0';    
                $moduleFileContents = file_get_contents($templatepath . '/ModuleName.php');
                $replacevars = array(
                    'ModuleName' => $this->modulename,
                    '<modulename>' => strtolower($this->modulename),
                    '<entityfieldlabel>' => $fieldid,
                    '<entitycolumn>' => $fieldid,
                    '<entityfieldname>' => $fieldid,
                );    
                foreach ($replacevars as $key => $value) $moduleFileContents = str_replace($key, $value, $moduleFileContents);
                file_put_contents($targetpath.'/'.$this->modulename.'.php', $moduleFileContents);
            }
        }
        
        function createWSOperations() {
            global $adb;
            if (!$this->operations || !is_array($this->operations)) return false;
            foreach($this->operations as $op) {
                $NA = $op["name"];
                $HF = $op["handlerFilePath"];
                $HM = $op["handlerMethodName"];
                $RT = $op["requestType"];
                $PL = $op["preLogin"];
                $PA = $op["params"];
                $sql = 'SELECT operationid from vtiger_ws_operation WHERE name=? AND handler_path=? AND handler_method=?';
                $exist = $adb->pquery($sql, array($NA, $HF, $HM));
                if ($adb->num_rows($exist) > 0) {
                    $old_id = $adb->query_result($exist, 0, "operationid");
                    vtws_deleteWebServiceOperation($old_id);
                    echo "Se elimina la operacion con id $old_id \n";
                }
                $operationId = vtws_addWebserviceOperation($NA, $HF, $HM, $RT, $PL);
                echo "Operacion $NA creada como con id $operationId \n";
                if ($PA && is_array($PA)) {
                    foreach($PA as $i => $param) {
                        list($param_name, $param_type) = $param;
                        vtws_addWebserviceOperationParam($operationId, $param_name, $param_type, $i+1);
                        echo "$NA nuevo parametro => $param_name, $param_type \n";
                    }
                }
            }
            return true;
        }
        static function addModuleToApp($module){
            $db   = PearDatabase::getInstance();
            $parent = strtoupper($module->parent);
            $menu = Vtiger_Menu::getInstance($parent);
            $menu->addModule($module);
            $result = $db->pquery('SELECT * FROM vtiger_app2tab WHERE tabid = ? AND appname = ?', array($module->getId(), $parent));
            if ($db->num_rows($result) == 0) {        
                $resultSec = $db->pquery('SELECT MAX(sequence) AS maxsequence FROM vtiger_app2tab WHERE appname=?', array($parent));
                $sequence = 0;
                if ($db->num_rows($resultSec) > 0) $sequence = intval( $db->query_result($resultSec, 0, 'maxsequence') ) + 1;
                $db->pquery('INSERT INTO vtiger_app2tab(tabid,appname,sequence) VALUES(?,?,?)', array($module->getId(), $parent, $sequence));
            }
        }
    }
    echo "<hr>\n";
    if (file_exists("modules/$vtlib")) {
        echo "--- <b>Importando Instalador $vtlib </b> ---\n";
        include_once "modules/$vtlib";
        list($order, $module, $action) = explode(".", str_replace([".php","vtlib."], "", $vtlib));
        $classNAme = "${module}_${action}";
        if(class_exists($classNAme)) {
            $_ = new $classNAme();
            $wea = $_->process();
            if ($wea) echo "<br>😁 Ejecucion <b>${module} ${action}</b> finalizada correctamente ✅✅✅";
            else echo "<br>🤯 Ejecucion <b>${module} ${action}</b> finalizada con error ⚠️⚠️⚠️";
        } else {
            echo "<br>😁 Ejecucion <b>${module} ${action}</b> finalizada correctamente";
        }
        return;
    } else {
        echo "<br>🧐 NO se encuentra el archivo $vtlib ⚠️⚠️⚠️";
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #3A2127;
        padding:30px 40px;

        background-color: #ffffff;
        opacity: 0.8;
        background-image:  linear-gradient(#f1f1f1 2px, transparent 2px), linear-gradient(90deg, #f1f1f1 2px, transparent 2px), linear-gradient(#f1f1f1 1px, transparent 1px), linear-gradient(90deg, #f1f1f1 1px, #ffffff 1px);
        background-size: 50px 50px, 50px 50px, 10px 10px, 10px 10px;
        background-position: -2px -2px, -2px -2px, -1px -1px, -1px -1px;

    }
    /* css list with numeber circle background -------------- */
    .numberlist {
        width:auto;
    }
    .numberlist ol {
        counter-reset: li;
        list-style: none;
        *list-style: decimal;
        font: 15px'trebuchet MS', 'lucida sans';
        padding: 0;
        display: grid;
    }
    .numberlist ol ol {
        margin: 0 0 0 2em;
    }
    .numberlist a {
        position: relative;
        display: block;
        padding: .4em .4em .4em 2em;
        *padding: .4em;
        margin: .4em 0;
        background: #FFF;
        color: #444;
        text-decoration: none;
        -moz-border-radius: .3em;
        -webkit-border-radius: .3em;
        border-radius: .3em;
        transition: 0.5s;
        background-size: 200% auto;
        background-image: linear-gradient(to right, #627aad 0%,#6d91ca 49%, #979797 50%, #888 100%);
        background-position: right center;
    }
    .numberlist a:hover {
        background-position: left center;
        text-decoration:underline;
    }
    .numberlist a:before {
        content: counter(li);
        transition: 0.1s;
        counter-increment: li;
        position: absolute;
        left: -1.3em;
        top: 50%;
        margin-top: -1.3em;
        /* background: -webkit-gradient(linear, center top, center bottom, from(#507EC7), to(#426EB5));
        background: -webkit-linear-gradient(#507EC7, #426EB5); */
        height: 2em;
        width: 2em;
        line-height: 2em;
        border: .0em solid #fff;
        text-align: center;
        font-weight: bold;
        -moz-border-radius: 2em;
        -webkit-border-radius: 2em;
        border-radius: 2em;
        background: #fff;color:#000; border-color:white ;
    }
    .numberlist a:hover:before {background: #4B79C2; color:#FFF; border: .3em solid #fff; }
    /* End css list with numeber circle background -------------- */
    .content{
        display: flex;
        width: 100%;
        height: calc(100vh - 76px);
    }
    .wrap-numberlist{
        overflow-y: auto;
    }
    .results , .wrap-numberlist{
        width: 50%;
        position: relative;
        overflow: hidden;
        display: grid;
    }
    .wrap-numberlist{
        position: relative;
        overflow: hidden;
        display: grid;
        padding: 0 5px 0 25px;
        margin-top: -15px;
        overflow-y: auto;
    }
    .results {
        margin-right: 0px;
        color: #eee;
        padding: 2px 5px;
        color: #222;
        overflow: hidden;
        overflow-y: auto;
        height: fit-content;
        max-height: 100%;
    }
    /* width */
    ::-webkit-scrollbar {
    width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
    background: #f1f1f1; 
    }
    
    /* Handle */
    ::-webkit-scrollbar-thumb {
    background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
    background: #555; 
    }
    .loginerror {
        text-align: center;
        font-size: large;
        color: red;
        text-shadow: 0 0 1px black, 0 0 1px black, 0 0 1px black;
        text-transform: uppercase;
    }
    .title {
        position: absolute;
        top: 5px;
        left: 50%;
        color: #888;
        transform: translateX(-50%);
        text-shadow: 0 0 0px black, 0 0 1px black, 0 0 0px black;
        font-size: larger;
        text-transform: uppercase;
    }
    </style>
</head>
<body> 
    <?php 

        if (!$current_user || !$current_user->is_admin) {
            echo "<div class='loginerror'> <br> ⚠️ <br> <br>  Primero accede con un usuario de administrador <br><br><a href='index.php'>Acceder</a></div>";
            exit();
        }
    ?> 
    <div class="title" > INSTALACION DE CARACTERISTICAS <?php 
        $companyDetails = Vtiger_CompanyDetails_Model::getInstanceById();
		echo $companyDetails->get('organizationname');
    ?></div>
    <div class="content">
        <div class="wrap-numberlist">
            <div class="numberlist">
                
                    <?php 
                    $first_ele = ""; 
                    foreach( scandir("modules") as $modules) { 
                        $mostrarmodule = true;
                        foreach( preg_grep('~^vtlib.*\.php$~', scandir("modules/$modules/vtlibs")) as $i_f) { 
                            $action = str_replace(["vtlib.", ".php"], "", $i_f);
                            if ($mostrarmodule) echo "<ol><li><h2>$modules</h2></li>";
                            $mostrarmodule = false;
                    ?>
                        <li>
                            <a data-type="instalink" href="#" data-vtlib="<?=$modules?>/vtlibs/<?=$i_f?>"> <?=$action?> </a>
                        </li>
                    <?php 
                        }
                    if(!$mostrarmodule) { ?></ol><?php }
                } ?>
                </ol>
            </div> 
        </div> 
        <div class="results">

        </div> 
    </div> 
    <script>
        $("a[data-type='instalink']").click(e=> {
            e.preventDefault();
            let vtlib = $(e.currentTarget).data("vtlib");
            console.log(vtlib)
            $.ajax({
                url: "LPI.php",
                type: 'POST',
                dataType: 'json',
                data: {vtlib},
                // shows the loader element before sending.
                beforeSend: function() {
                },
                // hides the loader after completion of request, whether successfull or failor.             
                complete: function(e) {
                    // console.log(e.responseText);
                    // $.toast(e.responseText, {enableHtml: true})
                    let results = e.responseText.split('\n');
                    for(let i = 0; i < results.length; i++) {
                        $(".results").append("<div>"+results[i]+"<div>")
                    }
                    $(".results").animate({scrollTop: $(".results")[0].scrollHeight}, 500);
                }
            });

        })
    </script>
</body>
</html>
