<?php

 define('SHOW_VARIABLES', 1);
 define('DEBUG_LEVEL', 1);

 error_reporting(E_ALL ^ E_NOTICE);
 ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('America/New_York');

function GetGlobalConnectionOptions()
{
    return array(
  'server' => 'localhost',
  'port' => '5432',
  'username' => 'postgres',
  'database' => 'surtirepuestos',
  'client_encoding' => 'utf8'
);
}

function HasAdminPage()
{
    return true;
}

function HasHomePage()
{
    return false;
}

function GetHomeURL()
{
    return 'index.php';
}

function GetPageGroups()
{
    $result = array();
    $result[] = array('caption' => 'Venta', 'description' => '');
    $result[] = array('caption' => 'Bodega', 'description' => '');
    $result[] = array('caption' => 'Registrar', 'description' => '');
    $result[] = array('caption' => 'Informes', 'description' => '');
    $result[] = array('caption' => 'Default', 'description' => '');
    return $result;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Abono', 'short_caption' => 'Abono Cliente', 'filename' => 'deudas_memo.php', 'name' => 'public.deudas_memo', 'group_name' => 'Venta', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Ventas ', 'short_caption' => 'Ventas Registrada', 'filename' => 'facturar_ventas.php', 'name' => 'public.facturar_ventas', 'group_name' => 'Venta', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Consultar Precio Producto', 'short_caption' => 'Consultar Precio Producto', 'filename' => 'Consultar_Precio_producto.php', 'name' => 'Consultar_Precio_producto', 'group_name' => 'Venta', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Productos', 'short_caption' => 'Productos', 'filename' => 'productos.php', 'name' => 'public.productos', 'group_name' => 'Bodega', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Agotados ', 'short_caption' => 'Agotados', 'filename' => 'agotados_views.php', 'name' => 'public.agotados_views', 'group_name' => 'Bodega', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Proveedores', 'short_caption' => 'Registro de Proveedores', 'filename' => 'proveedor.php', 'name' => 'public.proveedor', 'group_name' => 'Bodega', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Iva', 'short_caption' => 'Iva', 'filename' => 'iva.php', 'name' => 'public.iva', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Compra', 'short_caption' => 'Registrar Compra', 'filename' => 'registrar_compra.php', 'name' => 'public.registrar_compra', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Zona', 'short_caption' => 'Zona y Saldo', 'filename' => 'zona.php', 'name' => 'public.zona', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Clientes', 'short_caption' => 'Clientes', 'filename' => 'clientes.php', 'name' => 'public.clientes', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Unidades', 'short_caption' => 'Unidades', 'filename' => 'unidades.php', 'name' => 'public.unidades', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Descuentos', 'short_caption' => 'Descuentos', 'filename' => 'descuentos.php', 'name' => 'public.descuentos', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Marcas', 'short_caption' => 'Marcas de  los Productos', 'filename' => 'caracteristicas.php', 'name' => 'public.caracteristicas', 'group_name' => 'Registrar', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Venta Hoy', 'short_caption' => 'Venta Hoy', 'filename' => 'venta_hoy.php', 'name' => 'venta_hoy', 'group_name' => 'Informes', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Inv. Utilidad Bodega', 'short_caption' => 'Inversión  y Utilidad Total', 'filename' => 'inversion_utilidad.php', 'name' => 'inversion_utilidad', 'group_name' => 'Informes', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Vendedor ', 'short_caption' => 'Vendedor ', 'filename' => 'vendedor_vi.php', 'name' => 'public.vendedor_vi', 'group_name' => 'Informes', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Facturas de Ventas', 'short_caption' => 'All Facturas Ventas', 'filename' => 'all_facturas_ventas.php', 'name' => 'all_facturas_ventas', 'group_name' => 'Informes', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Importación Excel', 'short_caption' => 'Importación Productos de Excel', 'filename' => 'importacion_excel.php', 'name' => 'public.importacion_excel', 'group_name' => 'Default', 'add_separator' => false, 'description' => '');
    return $result;
}

function GetPagesHeader()
{
    return
        '<a class="navbar-brand" href="index.php">
    <img alt="" src="img/logo.png">
    <strong class="hidden-xs hidden-sm">Surtirepuestos La Costa</strong>
</a>';
}

function GetPagesFooter()
{
    return
        'Copyright © 2016- 
<script type="text/javascript">document.write(new Date().getFullYear().toString())</script>
 Created by Javier Mendoza'; 
}

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(true);
    $page->setShowNavigation(true);
    $page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');
    $page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
    $page->OnGetCustomExportOptions->AddListener('Global_OnGetCustomExportOptions');
    $page->getDataset()->OnGetFieldValue->AddListener('Global_OnGetFieldValue');
    $page->getDataset()->OnGetFieldValue->AddListener('OnGetFieldValue', $page);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
    $grid->AfterUpdateRecord->AddListener('Global_AfterUpdateHandler');
    $grid->AfterDeleteRecord->AddListener('Global_AfterDeleteHandler');
    $grid->AfterInsertRecord->AddListener('Global_AfterInsertHandler');
}

/*
  Default code page: 1252
*/
function GetAnsiEncoding() { return 'windows-1252'; }

function Global_OnGetCustomPagePermissionsHandler(Page $page, PermissionSet &$permissions, &$handled)
{

}

function Global_CustomHTMLHeaderHandler($page, &$customHtmlHeaderText)
{
$customHtmlHeaderText = '<meta name="author" content="Javier Mendoza">';

$customHtmlHeaderText .= "\n";

$customHtmlHeaderText .= '<meta name="copyright" content="Javier Mendoza" />';

$customHtmlHeaderText .= "\n";

$customHtmlHeaderText .= '<meta name="keywords" content="reserva,t,usta">';

$customHtmlHeaderText .= "\n";

$customHtmlHeaderText .= '<link rel="icon" href="img/ok.png" type="image/png" />';
}

function Global_GetCustomTemplateHandler($type, $part, $mode, &$result, &$params, CommonPage $page = null)
{
if ($part == PagePart::LoginPage) {
    $result = '..\..\..\data_extermal\custom_login_page.tpl';
}
}

function Global_OnGetCustomExportOptions($page, $exportType, $rowData, &$options)
{

}

function Global_OnGetFieldValue($fieldName, &$value, $tableName)
{

}

function Global_GetCustomPageList(CommonPage $page, PageList $pageList)
{
//$pageList->addGroup('Vaciar Contabilidad');

$pageList->addPage(new PageLink('Facturar Venta', "../Surtirepuestov16.9.0.7/facturar_vent.php", 

    'Click', false, false, 'Venta'));
}

function Global_BeforeUpdateHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeDeleteHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeInsertHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_AfterUpdateHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterDeleteHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{
if(!$success){
$message='ACCI&Oacute;N NO PERMITIDA';
$messageDisplayTime=2;
}
}

function Global_AfterInsertHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{
if($success)
{
$message=' REGISTRO EXITOSO! ';
$messageDisplayTime=2;
}else{
$message='HA OCURRIDO UN ERROR AL REALIZAR EL REGISTRO, POR FAVOR VERIFIQUE LA INFORMACI&Oacute;N E INTENTE NUEVAMENTE!';
$messageDisplayTime=2;
}
}

function GetDefaultDateFormat()
{
    return 'Y-m-d';
}

function GetFirstDayOfWeek()
{
    return 0;
}

function GetPageListType()
{
    return PageList::TYPE_SIDEBAR;
}

function GetNullLabel()
{
    return ' - ';
}

function UseMinifiedJS()
{
    return true;
}

function GetOfflineMode()
{
    return false;
}

function GetInactivityTimeout()
{
    return 0;
}



?>