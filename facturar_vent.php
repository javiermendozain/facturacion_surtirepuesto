
<?php 
include("conexion.php");
/*
define('SHOW_VARIABLES', 1);
 define('DEBUG_LEVEL', 1);

 error_reporting(E_ALL ^ E_NOTICE);
 ini_set('display_errors', 'On');
*/

include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/components/page/login_page.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/database_engine/pgsql_engine.php';
include_once dirname(__FILE__) . '/components/security/user_identity_storage/user_identity_session_storage.php';
include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';
include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/components/application.php';
include_once dirname(__FILE__) . '/components/page/home_page.php';
include_once dirname(__FILE__) . '/components/error_utils.php';

$contador=0;

function sumar(){
 global $contador;
  $contador++;
//$_SESSION['control'] =1 ;
  return $contador;
}


//

if(isset($_POST["iduser"])&&isset($_POST["n_factura"])&&isset($_POST["fecha"])   && isset($_SESSION['userName'])){
/*
echo 'Paso Bien, '.$_POST["n_factura"].' , '.$_POST["iduser"].' , '.$_POST["radio"].' , '.$_POST["fecha"].' , '.$_POST["radio1"].' , '.$_POST["id_cliente"].' ,  Conta: '.$_POST["conta"];

$strin='---';
*/
$n_factura=$_POST["n_factura"];
$iduser=$_POST["iduser"];
$tipo_de_venta=$_POST["radio"];
$fecha=$_POST["fecha"];
$forma_pago=$_POST["radio1"];
$id_cliente=$_POST["id_cliente"];
$contador=$_POST["conta"];


$sql=" INSERT INTO facturar_ventas(clientes_nit_cc,  fecha_hora, tipo_de_venta,  n_factura,  estado,  user_id, forma_pago)    VALUES ('".$id_cliente."', '".$fecha."', ".$tipo_de_venta.", '".$n_factura."', 2,".$iduser.",".$forma_pago."); 

SELECT idventa FROM facturar_ventas  WHERE n_factura='".$n_factura."';
";

//Registra La Factura
$rs=pg_query($conn,$sql);

while($row=pg_fetch_row($rs)) { 
$idventa=$row[0];
}

//echo $idventa;
 
for ($i=0; $i <=$contador ; $i++) {  
      if (isset($_POST["ref".$i.""])) {
            $cantidad=$_POST["c".$i.""];
            $ref=$_POST["ref".$i.""];

            $sql="INSERT INTO detalle_factura_venta(cantidad, precio_venta_unitario, valor_parcial, idproducto, 
                        idventa, utilidad_para_vendedor,iva)
                VALUES (".$cantidad.", 
                (SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."'),
                ((SELECT   precio_venta  FROM productos  WHERE idproducto='$ref')*(".$cantidad.")),
                  '$ref', 
                  ".$idventa.",
                   (SELECT   utilidad_para_vendedor  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."),
                   (SELECT   id_iva  FROM productos  WHERE idproducto='".$ref."')); 

            UPDATE public.productos SET  stock=stock-".$cantidad." WHERE idproducto='".$ref."'; ";
          //  $rs=pg_query($conn,$sql);
              

               // $rs=pg_query($conn,$sql);
//------------Inicio-----------

        //    $precio=$rowData['precio_venta_unitario'];
            $descuento=0; // Descuento de 0 por que aún, no se ha programador para facturar descuentos
            
          
            if($tipo_de_venta==1){ //Igual a Debito
            $sql.="UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta+  ((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='".$ref."')*".$cantidad."),iva_total=iva_total+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='".$ref."')*".($cantidad)."*(SELECT   id_iva  FROM productos  WHERE idproducto='".$ref."')/100, 
               descuento_total=descuento_total+".($cantidad.("*(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100))).", 
                     saldo=0,abono=abono+
                     ((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-".($cantidad.("    *(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100))).", sub_total=sub_total+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERe  productos.idproducto='".$ref."')*".($cantidad)."*(SELECT   id_iva  FROM productos  WHERE idproducto='".$ref."')/100, valor_total_pagar=valor_total_pagar+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-".($cantidad.("*(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100)))."
             WHERE facturar_ventas.idventa=".$idventa.";";
            
            }else{
            
            $sql.="UPDATE public.facturar_ventas
               SET    utilidad_en_venta=utilidad_en_venta+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-((SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='".$ref."')*".$cantidad."),iva_total=iva_total+(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERe  productos.idproducto='".$ref."')*".($cantidad)."*(SELECT   id_iva  FROM productos  WHERE idproducto='".$ref."')/100, 
               descuento_total=descuento_total+".($cantidad.("*(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100))).", 
                     saldo=valor_total_pagar+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-".($cantidad.("*(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100)))."-(SELECT 
              facturar_ventas.abono
            FROM 
              public.facturar_ventas
            WHERE 
              facturar_ventas.idventa = ".$idventa."), sub_total=sub_total+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-(SELECT 
                productos.precio_costo
            FROM 
              public.productos
              WHERE  productos.idproducto='".$ref."')*".($cantidad)."*(SELECT   id_iva  FROM productos  WHERE idproducto='".$ref."')/100, valor_total_pagar=valor_total_pagar+((SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*(".$cantidad."))-".($cantidad.("*(SELECT   precio_venta  FROM productos  WHERE idproducto='".$ref."')*".($descuento/100)))."
             WHERE facturar_ventas.idventa=".$idventa.";";
            
            }
            $rs=pg_query($conn,$sql);
      
          //  echo ' . ';
//-------------Final ----------
   

      }

}

header('location: paso.php');
}

if (isset($_SESSION['userName'])) {
//echo 'Bien'.$_SESSION['userName'];

$sql=" SELECT user_id
  FROM phpgen_users
  WHERE user_name='".$_SESSION['userName']."'";
 

$rs=pg_query($conn,$sql);


$iduser;
$nombre_usuario=$_SESSION['userName'];
while($row=pg_fetch_row($rs)) { 
 $iduser=$row[0];
 }

//echo $user.' Nombre: '.$_SESSION['userName'];
if (!empty($rs)) {
  

?>
<!--Aqui el codigo A ejecutar-->


<html>
<head>
  <meta charset="utf-8" />
  <title>Facturación Venta</title>
  <script src="facturar/js/jquery-2.1.1.min.js"></script>
  <script src="facturar/js/bootstrap.js"></script>

<style>



  .selected{
    cursor: pointer;
  }
  .selected:hover{
    background-color: #324b80;
    color: white;
  }
  .seleccionada{
    background-color:    #324b80;
    color: white;
  }



.button {
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
      border-radius: 5px;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}




select {
    width: 20%;
    padding: 12px 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f1f1f1;
      border-radius: 5px;
      color: black;
}

input[type=text] {
    width: 20%;
    padding: 6px 20px;
    margin: 5px 0;
    box-sizing: border-box;
   border: 1px solid #ccc;
    border-radius: 5px;
}

input[type=date] {
    width: 180px;
    padding: 5px 20px;
    margin: 5px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
   

     border-radius: 5px;
}

input[type=number] {
    width: 180px;
    padding: 7px 20px;
    margin: 5px 0;
    box-sizing: border-box;

 border: 1px solid #ccc;
    
     border-radius: 5px;
}



.content{

height: 35%;
width: 100%;
overflow:scroll;
overflow:auto;
overflow-x:hidden;
visibility:visible;

}





table * {height: auto; min-height: none;
table-layout:auto;
  border-collapse: collapse;
  } /* fixed ie9 & <*/

thead {
  background: #FF7361;
  text-align: center;
  z-index: 2;
 


}
thead tr {


  padding-right: 17px;
  box-shadow: 0 4px 6px rgba(0,0,0,.6);
  z-index: 2;
}

thead {
    display: table-header-group;
    vertical-align: middle;
    border-color: inherit;
       




}


body
{
  background:#fffff;
  font-family:"Lucida Grande", Tahoma, Arial, Verdana, sans-serif;
  font-size:small;
  margin:8px 0 16px;
  text-align:center;
}


 .btn > .caret,
  .dropup > .btn > .caret {
    border-top-color: #000 !important;
  }




.btn {
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: normal;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
      touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 4px;
}
.btn:focus,
.btn:active:focus,
.btn.active:focus,
.btn.focus,
.btn:active.focus,
.btn.active.focus {
  outline: thin dotted;
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.btn:hover,
.btn:focus,
.btn.focus {
  color: #333;
  text-decoration: none;
}
.btn:active,
.btn.active {
  background-image: none;
  outline: 0;
  -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
          box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
}
.btn.disabled,
.btn[disabled],
fieldset[disabled] .btn {
  pointer-events: none;
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
          box-shadow: none;
  opacity: .65;
}
.btn-default {
  color: #333;
  background-color: #fff;
  border-color: #ccc;
}
.btn-default:hover,
.btn-default:focus,
.btn-default.focus,
.btn-default:active,
.btn-default.active,
.open > .dropdown-toggle.btn-default {
  color: #333;
  background-color: #e6e6e6;
  border-color: #adadad;
}
.btn-default:active,
.btn-default.active,
.open > .dropdown-toggle.btn-default {
  background-image: none;
}
.btn-default.disabled,
.btn-default[disabled],
fieldset[disabled] .btn-default,
.btn-default.disabled:hover,
.btn-default[disabled]:hover,
fieldset[disabled] .btn-default:hover,
.btn-default.disabled:focus,
.btn-default[disabled]:focus,
fieldset[disabled] .btn-default:focus,
.btn-default.disabled.focus,
.btn-default[disabled].focus,
fieldset[disabled] .btn-default.focus,
.btn-default.disabled:active,
.btn-default[disabled]:active,
fieldset[disabled] .btn-default:active,
.btn-default.disabled.active,
.btn-default[disabled].active,
fieldset[disabled] .btn-default.active {
  background-color: #fff;
  border-color: #ccc;
}
.btn-default .badge {
  color: #fff;
  background-color: #333;
}
.btn-primary {
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
}
.btn-primary:hover,
.btn-primary:focus,
.btn-primary.focus,
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.btn-primary:active,
.btn-primary.active,
.open > .dropdown-toggle.btn-primary {
  background-image: none;
}
.btn-primary.disabled,
.btn-primary[disabled],
fieldset[disabled] .btn-primary,
.btn-primary.disabled:hover,
.btn-primary[disabled]:hover,
fieldset[disabled] .btn-primary:hover,
.btn-primary.disabled:focus,
.btn-primary[disabled]:focus,
fieldset[disabled] .btn-primary:focus,
.btn-primary.disabled.focus,
.btn-primary[disabled].focus,
fieldset[disabled] .btn-primary.focus,
.btn-primary.disabled:active,
.btn-primary[disabled]:active,
fieldset[disabled] .btn-primary:active,
.btn-primary.disabled.active,
.btn-primary[disabled].active,
fieldset[disabled] .btn-primary.active {
  background-color: #337ab7;
  border-color: #2e6da4;
}
.btn-primary .badge {
  color: #337ab7;
  background-color: #fff;
}
.btn-success {
  color: #fff;
  background-color: #5cb85c;
  border-color: #4cae4c;
}
.btn-success:hover,
.btn-success:focus,
.btn-success.focus,
.btn-success:active,
.btn-success.active,
.open > .dropdown-toggle.btn-success {
  color: #fff;
  background-color: #449d44;
  border-color: #398439;
}
.btn-success:active,
.btn-success.active,
.open > .dropdown-toggle.btn-success {
  background-image: none;
}
.btn-success.disabled,
.btn-success[disabled],
fieldset[disabled] .btn-success,
.btn-success.disabled:hover,
.btn-success[disabled]:hover,
fieldset[disabled] .btn-success:hover,
.btn-success.disabled:focus,
.btn-success[disabled]:focus,
fieldset[disabled] .btn-success:focus,
.btn-success.disabled.focus,
.btn-success[disabled].focus,
fieldset[disabled] .btn-success.focus,
.btn-success.disabled:active,
.btn-success[disabled]:active,
fieldset[disabled] .btn-success:active,
.btn-success.disabled.active,
.btn-success[disabled].active,
fieldset[disabled] .btn-success.active {
  background-color: #5cb85c;
  border-color: #4cae4c;
}
.btn-success .badge {
  color: #5cb85c;
  background-color: #fff;
}
.btn-info {
  color: #fff;
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info:hover,
.btn-info:focus,
.btn-info.focus,
.btn-info:active,
.btn-info.active,
.open > .dropdown-toggle.btn-info {
  color: #fff;
  background-color: #31b0d5;
  border-color: #269abc;
}
.btn-info:active,
.btn-info.active,
.open > .dropdown-toggle.btn-info {
  background-image: none;
}
.btn-info.disabled,
.btn-info[disabled],
fieldset[disabled] .btn-info,
.btn-info.disabled:hover,
.btn-info[disabled]:hover,
fieldset[disabled] .btn-info:hover,
.btn-info.disabled:focus,
.btn-info[disabled]:focus,
fieldset[disabled] .btn-info:focus,
.btn-info.disabled.focus,
.btn-info[disabled].focus,
fieldset[disabled] .btn-info.focus,
.btn-info.disabled:active,
.btn-info[disabled]:active,
fieldset[disabled] .btn-info:active,
.btn-info.disabled.active,
.btn-info[disabled].active,
fieldset[disabled] .btn-info.active {
  background-color: #5bc0de;
  border-color: #46b8da;
}
.btn-info .badge {
  color: #5bc0de;
  background-color: #fff;
}
.btn-warning {
  color: #fff;
  background-color: #f0ad4e;
  border-color: #eea236;
}
.btn-warning:hover,
.btn-warning:focus,
.btn-warning.focus,
.btn-warning:active,
.btn-warning.active,
.open > .dropdown-toggle.btn-warning {
  color: #fff;
  background-color: #ec971f;
  border-color: #d58512;
}
.btn-warning:active,
.btn-warning.active,
.open > .dropdown-toggle.btn-warning {
  background-image: none;
}
.btn-warning.disabled,
.btn-warning[disabled],
fieldset[disabled] .btn-warning,
.btn-warning.disabled:hover,
.btn-warning[disabled]:hover,
fieldset[disabled] .btn-warning:hover,
.btn-warning.disabled:focus,
.btn-warning[disabled]:focus,
fieldset[disabled] .btn-warning:focus,
.btn-warning.disabled.focus,
.btn-warning[disabled].focus,
fieldset[disabled] .btn-warning.focus,
.btn-warning.disabled:active,
.btn-warning[disabled]:active,
fieldset[disabled] .btn-warning:active,
.btn-warning.disabled.active,
.btn-warning[disabled].active,
fieldset[disabled] .btn-warning.active {
  background-color: #f0ad4e;
  border-color: #eea236;
}
.btn-warning .badge {
  color: #f0ad4e;
  background-color: #fff;
}
.btn-danger {
  color: #fff;
  background-color: #d9534f;
  border-color: #d43f3a;
}
.btn-danger:hover,
.btn-danger:focus,
.btn-danger.focus,
.btn-danger:active,
.btn-danger.active,
.open > .dropdown-toggle.btn-danger {
  color: #fff;
  background-color: #c9302c;
  border-color: #ac2925;
}
.btn-danger:active,
.btn-danger.active,
.open > .dropdown-toggle.btn-danger {
  background-image: none;
}
.btn-danger.disabled,
.btn-danger[disabled],
fieldset[disabled] .btn-danger,
.btn-danger.disabled:hover,
.btn-danger[disabled]:hover,
fieldset[disabled] .btn-danger:hover,
.btn-danger.disabled:focus,
.btn-danger[disabled]:focus,
fieldset[disabled] .btn-danger:focus,
.btn-danger.disabled.focus,
.btn-danger[disabled].focus,
fieldset[disabled] .btn-danger.focus,
.btn-danger.disabled:active,
.btn-danger[disabled]:active,
fieldset[disabled] .btn-danger:active,
.btn-danger.disabled.active,
.btn-danger[disabled].active,
fieldset[disabled] .btn-danger.active {
  background-color: #d9534f;
  border-color: #d43f3a;
}
.btn-danger .badge {
  color: #d9534f;
  background-color: #fff;
}
.btn-link {
  font-weight: normal;
  color: #337ab7;
  border-radius: 0;
}
.btn-link,
.btn-link:active,
.btn-link.active,
.btn-link[disabled],
fieldset[disabled] .btn-link {
  background-color: transparent;
  -webkit-box-shadow: none;
          box-shadow: none;
}
.btn-link,
.btn-link:hover,
.btn-link:focus,
.btn-link:active {
  border-color: transparent;
}
.btn-link:hover,
.btn-link:focus {
  color: #23527c;
  text-decoration: underline;
  background-color: transparent;
}
.btn-link[disabled]:hover,
fieldset[disabled] .btn-link:hover,
.btn-link[disabled]:focus,
fieldset[disabled] .btn-link:focus {
  color: #777;
  text-decoration: none;
}
.btn-lg,
.btn-group-lg > .btn {
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.33;
  border-radius: 6px;
}
.btn-sm,
.btn-group-sm > .btn {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.btn-xs,
.btn-group-xs > .btn {
  padding: 1px 5px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.btn-block {
  display: block;
  width: 100%;
}
.btn-block + .btn-block {
  margin-top: 5px;
}
input[type="submit"].btn-block,
input[type="reset"].btn-block,
input[type="button"].btn-block {
  width: 100%;
}




</style>

  
  <script src="data_extermal/chosen/jquery.js"></script>
    <script src="data_extermal/chosen/chosen.jquery.min.js"></script>

 <link rel="stylesheet" href="data_extermal/chosen/chosen.min.css">
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
 <script src="Facturar/notify.js" > </script>



 <!-- <link rel="stylesheet" href="facturar/css/bootstrap.css">-->
 <!-- Notificaciones 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
-->
</head>

<body  style="background-color:#EBEBEB">
<a style="padding: 4px 5px; float: left; ">  <button id="salir"  onclick=" location.href='index.php' " class="btn btn-default">Volver</button></a>


<form id="form" name="pasar" method="post" action="facturar_vent.php">



 <input class="large" type="text"   name="n_factura" required="required" placeholder="Numero de Factura" value=""/>
<input  class="large" data-format="yyyy-mm-dd" type="date" name="fecha" required="required" placeholder="Fecha de Factura"/>

<label class="title"><font size=4>Tipo de Venta </font></label> 
<input type="radio" name="radio" id="debi" value="1" onchange="activar_btn_addproduct()" required="required"  checked="checked"/>Debito
    <input type="radio" name="radio" id="credi"  onchange="activar_btn_addproduct()" value="2" required="required"/>Crédito


<div class="element-radio"><label class="title"> <font size=4>Forma de Pago</font></label>  
  <input type="radio" name="radio1" value="0" checked="checked" required="required"/>Efectivo
  <input type="radio" name="radio1" value="1" required="required"/>Transación
  <input type="radio" name="radio1" value="2" required="required"/>Otros Medios
  </div>
</div>
  
</div>
  <input class="medium"   type="text" name="id_cliente"  id="id_cliente" onchange="traer_cliente()" onkeypress=" if (event.keyCode==13){ traer_cliente();}  "  required="required" placeholder="ID Cliente"/>
  

<select id="cliente" class="sl" data-placeholder="Nombre del Cliente"   onchange="selec_cliente()">

<option ></option>
    <?php   
$sql=" SELECT 
  clientes.nit_cc, 
  clientes.nombre_completo, 
  clientes.cupo_tope, 
  clientes.cupo_tope

FROM 
  public.clientes

GROUP BY clientes.nit_cc
ORDER BY clientes.nombre_completo asc;";

 $rs=pg_query($conn,$sql);
  
  //echo '<option value="0">Seleccionar</option>';
 $basedecliente = array();
 $p=0;
while(($row=pg_fetch_row($rs))) { 
 $basedecliente[$p]['nit_cc'] = $row[0];
 $basedecliente[$p]['nombre_completo'] = $row[1];
 $basedecliente[$p]['cupo_tope'] = $row[2];
 $basedecliente[$p]['cupo'] = $row[3];
echo ' <option value="'.$row[0].'">'.$row[1].'</option>';

$p++;

}






 ?>   
</select>

<?php

for ($i=$p; $i >=0 ; $i--) { 

$sql=" SELECT 
  sum(facturar_ventas.saldo) as saldo

FROM 
  public.facturar_ventas
WHERE 
  facturar_ventas.clientes_nit_cc = '".$basedecliente[$i]['nit_cc']."'
GROUP BY facturar_ventas.clientes_nit_cc";

$rs=pg_query($conn,$sql);

while ( $row=pg_fetch_row($rs)) {
 $basedecliente[$i]['cupo'] =$basedecliente[$i]['cupo']- $row[0];
 } 
}

?>




  <input class="medium" type="text" id="idproducto"    onkeypress=" if (event.keyCode==13){ agregar();}  "   placeholder="ID del Producto"/>
  
  <select id="producto" class="sl" data-placeholder="Seleccione el Producto"   onchange="selecc_producto()" >

     <option ></option>

     <?php   
$sql=" SELECT 
  productos.idproducto, 
  productos.nombre,productos.stock,productos.precio_venta,productos.id_iva,productos.iddescuentos
FROM 
  public.productos

  ORDER BY productos.nombre asc";

 $rs=pg_query($conn,$sql);
  
  //echo '<option value="0">Seleccionar</option>';

$basedeprodutos = array();
$i = 0;
while(($row=pg_fetch_row($rs))) { 


 $basedeprodutos[$i]['ref'] = $row[0];
 $basedeprodutos[$i]['nombre'] = $row[1];
 $basedeprodutos[$i]['stock'] = $row[2];
 $basedeprodutos[$i]['precio'] = $row[3];
 $basedeprodutos[$i]['iva'] = $row[4];
 $basedeprodutos[$i]['descuento'] = $row[5];


$i++;
 echo ' <option value="'.$row[0].'">'.$row[1].'</option>';
}  ?>
   
     </select>
<br>

<a id="tope"  >Tope: 0     </a>
<a id="cupo"  >Cupo: 0     </a>

<a id="stokc_bodega"  >Cantidad en Bodega: 0 </a>
<a id="precio">Precio: 0 </a>


    </div></div></div> 
<br>
  
  Cantidad: <input class="large" type="text"  id="cantidad" name="number1" onkeypress=" if (event.keyCode==13){ agregar();}  " placeholder="Cantidad" value="1"/></div></div>

  <label>Acciones: </label>
    <button id="bt_add" type="button" class="btn btn-default">Agregar</button>
    <button id="bt_del" type="button" class="btn btn-default">Eliminar</button>
    <button id="bt_delall" type="button" class="btn btn-default">Eliminar todo</button>
  
<!--
Notificaciones

<div class="alert alert-success">
  <strong>Success!</strong> Indicates a successful or positive action.
</div>

<div class="alert alert-info">
  <strong>Info!</strong> Indicates a neutral informative change or action.
</div>

<div class="alert alert-warning">
  <strong>Warning!</strong> Indicates a warning that might need attention.
</div>

<div class="alert alert-danger">
  <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
</div>-->

  <div id="content" >
  
    <div class="content">


    <table id="tabla"  name="tabla" class="table table-bordered">
   
    <thead >
      <tr>
          <th>Item (s)</th>          
          <th>Ref.</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Descuento</th>
          <th>Iva</th>
          <th>Precio Unitario</th>
          <th>Valor Parcial</th>
      </tr>
    </thead>
  



  </table>
  </div>
  </div>

<hr />

<div style="  float: right; padding: 0px 15px;   ">
<table style=" width: 300px; " >
 <!-- <tr>
    <th>Sub Total:</th>
    <td id="sub_total" >0</td>
  
  </tr>
  <tr>
    <th>Total Descuento:</th>
    <td id="desc_total">0</td>
  
  </tr>
  <tr>
    <th>Total Iva:</th>
      <td id="iva_total">0</td>
  </tr>
-->

  <tr>
    <th>Valor total a Pagar:</th>
     <td style=" text-align:right ; background: #324b80; color: white;"  >$</td> <td id="valor_total" style=" text-align:right ; background: #324b80; color: white; font: bold; "  > 0</td>
  </tr>
  </table>
</div>
<!--<div class="submit"><input type="submit" value="Submit"/></div>

 Stop Formoid form-->



  <label>Guardar la Factura: </label>
  

  <input type="hidden" id="conta" name="conta"  value="1" />

   <input type="hidden" id="iduser" name="iduser"  value="<?php echo $iduser; ?>" />

       <input id="save" type="submit" class="btn btn-default" value="Grabar Facturación" />

</form> 


</body>
</html>




<script type="text/javascript">


// Traer valor del array (Base de datos )_PHP 
var basedeprodutos_js=<?php echo json_encode($basedeprodutos); ?>;
 
var basedeclientes_js=<?php echo json_encode($basedecliente); ?>;
 


// Evitar enviar el formulario con un enter
function stopRKey(evt) {
   var evt = (evt) ? evt : ((event) ? event : null);
   var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
   if ((evt.keyCode == 13) && (node.type=="text") || (node.type=="number")   || (node.type=="date")   ) {return false;}
}
document.onkeypress = stopRKey;



//Inicio Acciones Tabla

  $(document).ready(function(){
    $('#bt_add').click(function(){
      agregar();

    });
    $('#bt_del').click(function(){
      eliminar(id_fila_selected);
      activar_btn_addproduct();

    });
    $('#bt_delall').click(function(){
      eliminarTodasFilas();
    });
    

  });
  var cont=0;
  var id_fila_selected=[];

//Valida que el mismo producto no se ingrese doble
function verficaradd(ref_producto) {
  var rs=true;
     for (var i=0;i < document.getElementById('tabla').rows.length ; i++){
          if (ref_producto== document.getElementById('tabla').rows[i].cells[1].innerHTML) {
           
           //    $('#fila'+i).notify('Ya Exite !',{ className: 'info', position:"bottom left" });
       return false;
              
                 }
     } 
return rs;
}


function stock_precio(ref){
 
 // Mostramos los valores del array
    for(var i=0;i<basedeprodutos_js.length;i++)
    {

      if(basedeprodutos_js[i]['ref']==ref){
     var stock=basedeprodutos_js[i]['stock'];
     var precio=basedeprodutos_js[i]['precio'];
      if (stock==0) {


$('#stokc_bodega').notify('Agotado! ',{ className: 'error', position:"bottom right" });
      }

document.getElementById("stokc_bodega").innerHTML = 'Cantidad en Bodega: '+ '<font size=4>'+stock+'</font>';
document.getElementById("precio").innerHTML = 'Precio: '+ '<font size=4>'+precio+'</font>';

      } 

       }
}


//Adicionar un producto a la tabla
  function agregar(){

  /* 
$.notify('Hoal Word');
$.notify('Hoal wor por lo mismo yo siempre digo que eto no funciona',"warn");
$.notify('Hoal Word',"success");
*/

 //  document.submit.disabled=true;

var ref= document.getElementById("idproducto").value;
var _cant = document.getElementById("cantidad").value;
  //Mostrar Stock y Precio
 stock_precio(ref);
 cont++;
 //alert(cont);
for(var i=0;i<basedeprodutos_js.length;i++)
    {

      if(basedeprodutos_js[i]['ref']==ref){
     var nombre=basedeprodutos_js[i]['nombre'];
     var iva=basedeprodutos_js[i]['iva'];
     var descuento=basedeprodutos_js[i]['descuento'];
     var precio_unitario=basedeprodutos_js[i]['precio'];
     var stock=basedeprodutos_js[i]['stock'];
  

      } 
 }

  document.getElementById("conta").value=cont;
// var contan;

// contan='<?php //echo sumar(); ?>';
//alert(contan);


var valor_parcial=_cant*precio_unitario;

if ( !(_cant<=stock) && verficaradd(ref)) {

  $('#stokc_bodega').notify('Agotado! ',{ className: 'error', position:"bottom right" });

}


if (ref.length!=0 && _cant>0 && _cant<=stock  && verficaradd(ref)) {
// alert(cont);


    var fila='<tr class="selected" id="fila'+cont+'"  onclick="seleccionar(this.id);"><td>'+cont+'</td><td >'+ref+'</td><td>'+nombre+'</td><td style=" text-align:center" > <input type="hidden"  name="c'+cont+'" value="'+_cant+'" /> '+_cant+'</td><td>'+descuento+' %</td><td><input type="hidden"  name="ref'+cont+'" value="'+ref+'" />'+iva+' %</td><td style=" text-align:right" > '+precio_unitario+'</td><td style=" text-align:right" > '+valor_parcial+'</td> </tr>    ';

    $('#tabla').append(fila);
    reordenar();
   
$('#tabla').notify('Producto Adicionado Correctamente! ',{ className: 'success', position:"top left" });
 

//$('#cupo').notify('Supera el cupo disponible! ',{ className: 'error', position:"bottom right" });

var suma= valor_parcial+parseInt(document.getElementById('valor_total').innerHTML); 

//document.getElementById('sub_total').innerHTML = ''+suma;
//document.getElementById('iva_total').innerHTML = ''+valor_parcial;
//document.getElementById('desc_total').innerHTML = ''+valor_parcial;
document.getElementById('valor_total').innerHTML = ''+suma;
activar_btn_addproduct();
}

//Activar o desactivar el boton de Guardado
//activar_btn();

 }

var id_ultima_seleccion=[];

  function seleccionar(id_fila){
    if($('#'+id_fila).hasClass('seleccionada')){
      $('#'+id_fila).removeClass('seleccionada');
    
   // alert(id_fila_selected.indexOf(id_fila));

     for (var i = 0; i < id_fila_selected.length; i++) {
            if (id_fila_selected[i] == id_fila) {
                  id_fila_selected.splice(i, 1);

                   id_ultima_seleccion.splice(i, 1);

             return;
            }
           }


  
    }
    else{
      $('#'+id_fila).addClass('seleccionada');
 //  alert('No entro');
      id_ultima_seleccion.push(id_fila);
   
//$('#'+id_fila).notify('Producto Selecionado! ',"info");
 
    }
      id_fila_selected.push(id_fila);
    //2702id_fila_selected=id_fila;


  }


  function eliminar(id_fila){
    /*$('#'+id_fila).remove();
    reordenar();*/ 
  //  alert(id_ultima_seleccion);

//alert(id_ultima_seleccion.length);

   
   
   //   alert(valor_fila);

var valor_fila;
    for(var i=0; i<id_fila.length; i++){
 
 //alert(id_fila);
     valor_fila=document.getElementById(id_fila).cells[7].innerHTML;
    document.getElementById('valor_total').innerHTML =  parseInt(document.getElementById('valor_total').innerHTML)-valor_fila;
  

    $('#'+id_fila[i]).remove();
     id_fila.splice(i, 1);


    }
  
   valor_fila=0;

//alert(valor_fila);
  
    reordenar();

 
  }


  function reordenar(){
    var num=1;
    $('#tabla tbody tr').each(function(){
      $(this).find('td').eq(0).text(num);
      num++;
    });
  }
  function eliminarTodasFilas(){
    document.getElementById('valor_total').innerHTML = '0';

$('#tabla tbody tr').each(function(){
      $(this).remove();


  });

  }

//--Fin Acciones tabla


//Permite buscar en los select  escribir y busca
$(document).ready(function() {
  $(".sl").select2();
document.getElementById('save').disabled=true;

  $.notify.defaults( { clickToHide: true,
  // whether to auto-hide the notification
  autoHide: true,
  // if autoHide, hide after milliseconds
  autoHideDelay: 2250,
  // show the arrow pointing at the element
  arrowShow: true,
  // arrow size in pixels
  arrowSize: 5,
  // position defines the notification position though uses the defaults below
  position: '...',
  // default positions
  elementPosition: 'bottom left',
  globalPosition: 'top right',
  // default style
  style: 'bootstrap',
  // default class (string or [string])
  className: 'error',
  // show animation
  showAnimation: 'slideDown',
  // show animation duration
  showDuration: 300,
  // hide animation
  hideAnimation: 'slideUp',
  // hide animation duration
  hideDuration: 200,
  // padding between element and notification
  gap: 2
} );


});

//Obtiene el id_producto de la selecion y lo asginar al campo idproducto
function selecc_producto(){
 var seleccion = document.getElementById('producto');
 var valor = seleccion.options[seleccion.selectedIndex].value;//coges el valor
 var texto = seleccion.options[seleccion.selectedIndex].text;//esto es lo que ve el usuario
document.getElementById("idproducto").value=valor;

if (valor!=null) {

stock_precio(valor);
}
}

//Obtiene el id_cliente de la selecion y lo asginar al campo id_cliente


function traer_cliente(){
cliente=document.getElementById("id_cliente").value;

tope_cupo(cliente);

}
  var tope;
    var cupo;
function tope_cupo(cliente){
   // Mostramos los valores del array
  
    for(var i=0;i<basedeclientes_js.length;i++)
    {
      if(basedeclientes_js[i]['nit_cc']==cliente){
     tope=basedeclientes_js[i]['cupo_tope'];
     cupo=basedeclientes_js[i]['cupo'];
      document.getElementById("tope").innerHTML = 'Tope: '+ '<font size=4>'+tope+'</font>';
      document.getElementById("cupo").innerHTML = 'Cupo: '+ '<font size=4>'+cupo+'</font>';

      }  
    }

activar_btn(cupo);


//document.getElementById("stokc_bodega").value=valor;
//alert(valor +' ' + texto);
 }


function getRadioButtonSelectedValue(ctrl)
{
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}

function  activar_btn(cupo){
//alert(cupo);
if ((cupo<= parseInt(document.getElementById('valor_total').innerHTML) && getRadioButtonSelectedValue(document.getElementsByName("radio"))!='1' )) {
$('#cupo').notify('Supera el cupo disponible! ',{ className: 'error', position:"bottom right" });
}

    if((cupo>= parseInt(document.getElementById('valor_total').innerHTML) || getRadioButtonSelectedValue(document.getElementsByName("radio"))=='1' ) && document.getElementById('tabla').rows.length!=1 ){
  //Activa el boton Guardar


 // alert(document.getElementById('tabla').rows.length);
document.getElementById('save').disabled=false;
//alert('Activado');
//lert(getRadioButtonSelectedValue(document.getElementsByName("radio")));
  }else{
document.getElementById('save').disabled=true;
//alert('desactivado');
  }
//var crear_control='<?php //echo sumar(); ?>';
}

function  activar_btn_addproduct(){


    if((cupo>= parseInt(document.getElementById('valor_total').innerHTML ) || getRadioButtonSelectedValue(document.getElementsByName("radio"))=='1') && document.getElementById('tabla').rows.length!=1  ){
  //Activa el boton Guardar
document.getElementById('save').disabled=false;
//alert('Activado');
  }else{
document.getElementById('save').disabled=true;

if ((cupo<= parseInt(document.getElementById('valor_total').innerHTML) && getRadioButtonSelectedValue(document.getElementsByName("radio"))!='1' ))  {
$('#cupo').notify('Supera el cupo disponible! ',{ className: 'error', position:"bottom right" });
}
//alert('desactivado');
  }

//var crear_control='<?php //echo sumar(); ?>';
}



function selec_cliente(){
 var seleccion = document.getElementById('cliente');
 var valor = seleccion.options[seleccion.selectedIndex].value;
  document.getElementById("id_cliente").value=valor;
//Mustra el Tope y Cupo el Cliente


$('#id_cliente').notify('Selecionado Correctamente! ',  { className: 'success', position:"top left" });
 

tope_cupo(valor);
 }




function addd(s) {
    var x = document.getElementById("select");
    var option = document.createElement("option");
    option.text = s;
    option.value=1;
    x.add(option);
}



</script>



<!--Fin de Codigo a Ejecutar->

<?php
}else{
   header('location: login.php');
}

}else{
 //echo 'Mal'.$_SESSION['userName'];
 header('location: login.php');

}



?>
