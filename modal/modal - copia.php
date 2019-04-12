<html lang="es">
<head>
  <title>Bienvenido</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="cs.css">
  <script src="ajax.js"></script>
  <script src="my.js"></script>
</head>
<body>

<div class="container">
  <!--<h2>Activate Modal with JavaScript</h2>
  <!-- Trigger the modal with a button -
  <button type="button" class="btn btn-info btn-lg" id="myBtn">Open Modal</button>
    -->
	
	
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         <center> <h4 class="modal-title" >Bienvenido</h4></center>
        </div>
        <div class="modal-body">
          <p>Gracias Por Usar SIAC		  </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<script>
 $("#myModal").modal();
 
/*$(document).ready(function(){
   $("#myBtn").click(function(){
        $("#myModal").modal();
    }
	);
});*/
</script>

</body>
</html>