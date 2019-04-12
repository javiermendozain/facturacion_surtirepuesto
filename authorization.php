<?php

require_once 'phpgen_settings.php';
require_once 'components/application.php';
require_once 'components/security/permission_set.php';
require_once 'components/security/tablebased_auth.php';
require_once 'components/security/grant_manager/user_grant_manager.php';
require_once 'components/security/grant_manager/composite_grant_manager.php';
require_once 'components/security/grant_manager/hard_coded_user_grant_manager.php';
require_once 'components/security/grant_manager/table_based_user_grant_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/pgsql_engine.php';

$grants = array();

$appGrants = array();

$dataSourceRecordPermissions = array('public.facturar_ventas' => new DataSourceRecordPermission('user_id', false, false, false, true, true, true));

$tableCaptions = array('public.deudas_memo' => 'Abono',
'public.facturar_ventas' => 'Ventas ',
'public.facturar_ventas.public.detalle_factura_venta' => 'Ventas ->Detalle Factura Venta',
'Consultar_Precio_producto' => 'Consultar Precio Producto',
'public.productos' => 'Productos',
'public.agotados_views' => 'Agotados ',
'public.proveedor' => 'Proveedores',
'public.proveedor.public.registrar_compra' => 'Proveedores-> Compras',
'public.iva' => 'Iva',
'public.iva.public.productos' => 'Iva->Productos',
'public.registrar_compra' => 'Compra',
'public.registrar_compra.public.detalle_factura_compra' => 'Compra->Detalle Factura Compra',
'public.zona' => 'Zona',
'public.zona.public.cliente_saldo_vi01' => 'Zona->Cliente Saldo ',
'public.clientes' => 'Clientes',
'public.clientes.public.facturar_ventas' => 'Clientes->Facturas Ventas',
'public.clientes.public.facturar_ventas.public.factura_detalle_vi01' => 'Clientes->Facturas Ventas->Detalle Factura',
'public.unidades' => 'Unidades',
'public.descuentos' => 'Descuentos',
'public.descuentos.public.productos' => 'Descuentos->Productos',
'public.caracteristicas' => 'Marcas',
'venta_hoy' => 'Venta Hoy',
'inversion_utilidad' => 'Inv. Utilidad Bodega',
'public.vendedor_vi' => 'Vendedor ',
'public.vendedor_vi.public.factura_venta_vi01' => 'Vendedor ->Factura Venta ',
'all_facturas_ventas' => 'Facturas de Ventas',
'utilidad_mensual' => 'Utilidad Mensual',
'utilidad_x_ano' => 'Utilidad X Año',
'public.importacion_excel' => 'Importación Excel',
'user' => 'User',
'public.departamento' => 'Departamento',
'public.departamento.public.ciudad' => 'Departamento->Ciudad',
'public.detalle_factura_compra' => 'Productos Factura Compra',
'public.ciudad' => 'Ciudad',
'public.ciudad.public.clientes' => 'Ciudad->Clientes',
'public.ciudad.public.proveedor' => 'Ciudad->Proveedor',
'public.detalle_factura_venta' => 'Detalle Factura Venta',
'public.tipo_productos' => 'Tipo Productos',
'public.tipo_productos.public.productos' => 'Tipo Productos->Productos',
'producto_venta' => 'Producto Venta',
'Departamento_multiselec' => 'Departamento Multiselec',
'ciudad' => 'Ciudad',
'product_compra' => 'Product Compra',
'public.cliente_abono' => 'Cliente Abono',
'public.factura_abono' => 'Factura Abono',
'public.saldo_zona_v' => 'Saldo Zona V');

function CreateTableBasedGrantManager()
{
    global $tableCaptions;
    $usersTable = array('TableName' => 'public.phpgen_users', 'UserName' => 'user_name', 'UserId' => 'user_id', 'Password' => 'user_password');
    $userPermsTable = array('TableName' => 'public.phpgen_user_perms', 'UserId' => 'user_id', 'PageName' => 'page_name', 'Grant' => 'perm_name');

    $passwordHasher = GetHasher('MD5');
    if ($passwordHasher instanceof CustomStringHasher) {
        $passwordHasher->OnEncryptPassword->AddListener('EncryptPassword');
        $passwordHasher->OnVerifyPassword->AddListener('VerifyPassword');
    }

    $connectionOptions = GetGlobalConnectionOptions();
    $tableBasedGrantManager = new TableBasedUserGrantManager(PgConnectionFactory::getInstance(), $connectionOptions,
        $usersTable, $userPermsTable, $tableCaptions, $passwordHasher, false);
    return $tableBasedGrantManager;
}

function EncryptPassword($password, &$result)
{

}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{

}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;
    $hardCodedGrantManager = new HardCodedUserGrantManager($grants, $appGrants);
    $tableBasedGrantManager = CreateTableBasedGrantManager();
    $grantManager = new CompositeGrantManager();
    $grantManager->AddGrantManager($hardCodedGrantManager);
    if (!is_null($tableBasedGrantManager)) {
        $grantManager->AddGrantManager($tableBasedGrantManager);
        GetApplication()->SetUserManager($tableBasedGrantManager);
    }
    $userAuthorizationStrategy = new TableBasedUserAuthorization(new UserIdentitySessionStorage(GetIdentityCheckStrategy()), PgConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 'public.phpgen_users', 'user_name', 'user_id', $grantManager);
    GetApplication()->SetUserAuthorizationStrategy($userAuthorizationStrategy);

    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(
        new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}

function GetIdentityCheckStrategy()
{
    return new TableBasedIdentityCheckStrategy(PgConnectionFactory::getInstance(), GetGlobalConnectionOptions(), 'public.phpgen_users', 'user_name', 'user_password', 'MD5');
}

function CanUserChangeOwnPassword()
{
    return true;
}

?>