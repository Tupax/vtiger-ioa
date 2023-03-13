# Agregar un modulo completo

## Objetivo:
El objetivo de este documento es presentar un ejercicio para poder agregar un modulo completo a un proyecto de vtiger.
En el ejemplo utilizaremos el modulo de Sucursales (Sucursales) el cual depende de las cuentas

## Leer antes de hacer:
Manejo de modulos: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module.html
Manejo de Bloques: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module-block.html
Menejo de Campos: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module-field.html
Manejo de Filtros: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module-filter.html

Tener en cuenta que los filtros son los elementos que permite definir columnas y filas que se quieren ver en el modulo. Cada modulo, tiene que haber un filtro que se llame ALL (todos), si no se pone en el momento de crear el modulo, los usuarios no podran luego agregar filtros.
 
Manejo de Related List: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module-related-list.html
Las related list son las listas que se encuentran a mano derecha en la vista detalle o resumen de cada modulo. Gracias a ellas podemos acceder de un clic a diferente información de modulo. Por ejemplo estos elementos como related list.

Manejo de Perfiles de Acceso: http://community.vtiger.com/help/vtigercrm/developers/vtlib/module-sharing-access.html
El manejo de la seguriad es fuerte en Vtiger, ver el manual para entender los mecanismos de seguridad que maneja vtiger


## Pasos para Crear un modulo
    1. Crear el Vtlib con la definicion de campos, bloques, filtros y demas.
    2. Crear un directorio dentro del /modules con el mismo nombre que el modulo es decir con el nombre que se puso en la sentencia

        ```php
        $MODULENAME = “Sucursales”;
        $moduleInstance = Vtiger_Module::getInstance($MODULENAME);

        if (!$moduleInstance) {
            $moduleInstance = new Vtiger_Module();
            $moduleInstance->name = $MODULENAME;
            $moduleInstance->parent = 'Marketing'; // aca va el que corresponda
            $moduleInstance->save();
            $moduleInstance->initTables();
        }
        ```
    3. Dentro del módulo hay que crear un archivo con la información básica de la clase. Normalmente ajustamos el que esta en el directorio con el nombre sucursales.php

## Definición funcional:
Sucursales
Las sucursales son los diferentes lugares físicos que tiene un cliente. Es decir todos comparten el mismo RUT y Lugar de Facturación pero utilizaremos las sucursales como diferentes lugares de entrega.

Las sucursales se crearán en el SIGE y se actualizarán los datos en el CRM. Asimismo habrá un conjunto de datos que solo estarán disponibles en el CRM


## Definición de Campos


- Nombre
    Este dato proviene de SIGE y se cargará por medio de las sincronizaciones de datos.
    No podrá ser modificado en el CRM

   ### Notes 
   - For this is find a no editable field inside modules/Users/models/EditRecordStructure.php:33

- ID Sucursal
    Este dato proviene de SIGE y se cargará por medio de las sincronizaciones de datos.
    No podrá ser modificado en el CRM

- Dia y Horarios de Entrega
    Módulo nuevo en Vtiger que presenta la lista de los posibles días de entrega
    El módulo tendrá los siguientes campos
        • Clientes
        • Sucursal
        • Días (lista desplegable)
            ◦ Lunes,martes,miércoles,jueves,viernes, sábado y domingo.
        • Hora Desde (HH:MM)
        • Horas Hasta (HH:MM)

- Agencias de Carga
    Lista de Múltiple selección
    Los valores se deben sincronizar con la tabla de SIGE OCEMPTSP - tener en cuenta que esta tabla hay que juntarla con empresas y traer los nombres.

- Agencia de Ómnibus
    Lista de de Múltiple selección
    Los valores se deben sincronizar con la tabla de SIGE
    Idem Anterior

- Comentario de Entregas


- Dirección de Entrega
    Geolocalizar con Google

- Dirección
- Departamento
- Ciudad
- Código Postal
- País
- Latitud
- Longitud
- Dirección de Cobranza
    Tiene que tener la funcionalidad de búsquedas de GOOGLE, este bloque de direcciones se visualiza si se seleccionó que el cliente paga por sucursales.
- Dirección en SIGE
    Este campo es una descripción de la dirección que existe en SIGE. Se utiliza para la migración inicial de datos ya que la funcionalidad de Geolocalizar funciona directamente con los datos en Vtiger.
    Tener en cuenta que dependiendo de la configuración a nivel del cliente si esta dirección es independiente por sucursal o es general para el cliente

        Dirección
        Departamento
        Ciudad
        Código Postal
        País
        Latitud
        Longitud

- Teléfono

- Lista de Precio
    Este dato proviene de SIGE y se cargará por medio de las sincronizaciones de datos.
    No podrá ser modificado en el CRM

- RUBRO
- Lista multivaluada
- Asignado A
    Vendedor de la sucursal






