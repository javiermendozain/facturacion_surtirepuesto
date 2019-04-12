

<?php


function star($nombreArchivo){

require 'Classes/PHPExcel/IOFactory.php'; //Agregamos la librería 
//require 'conexion.php'; //Agregamos la conexión
//Variable con el nombre del archivo

include("conexion.php");
//$nombreArchivo  ="../data_extermal/importacion_excel/2691.xlsx";





// Cargo la hoja de cálculo
$objPHPExcel = PHPExcel_IOFactory::load($nombreArchivo);
//Asigno la hoja de calculo activa
$objPHPExcel->setActiveSheetIndex(0);
//Obtengo el numero de filas del archivo
$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

//echo '<table border=0>';
for ($i = 1; $i <= $numRows; $i++) {
$cod = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
$nombre = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
$precio_vent = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
$precio_cost = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
$cantidad= $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
/*echo '<tr>';
echo '<td>'. $nombre.'</td>';
echo '<td>'. $precio_vent.'</td>';
echo '<td>'. $precio_cost.'</td>';
echo '</tr>';*/

$sql="SELECT count (idproducto) as n
    FROM productos

Where idproducto='".$cod."'";
$rs=pg_query($conn,$sql);

   while($row=pg_fetch_row($rs)) {
               if($row[0]==0){
		               	$sql = "INSERT INTO productos(idproducto, nombre, estado, precio_venta, precio_costo, stock)
		  			  VALUES ('".$cod."', '".$nombre."', 1 ,".$precio_vent ." , ".$precio_cost." , ".$cantidad." );";

						$rs=pg_query($conn,$sql);

                         }else{
                         	 	$sql = "UPDATE productos
									   SET  nombre='".$nombre."', precio_venta=".$precio_vent ." , precio_costo=".$precio_cost.", stock=stock+".$cantidad."
									 WHERE idproducto='".$cod."'";
						$rs=pg_query($conn,$sql);
                         }
          }




}
//echo '<table>';

}
?>

