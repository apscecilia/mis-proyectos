<?php
header('Cache-Control: no-cache');
header('Pragma: no-cache'); 
?>
<html>
<head>
<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
</head>
<script language="javascript">

function pon_prefijo(nombre) {
	parent.document.form_busqueda.nombre.value=nombre;
	//parent.document.form_busqueda.nif.value=nif;
}//,nif

function limpiar() {
	parent.document.form_busqueda.nombre.value="";
	//parent.document.form_busqueda.nif.value="";
	parent.document.form_busqueda.codproveedor.value="";
}

</script>
<? include ("../conectar.php"); ?>
<body>
<?
	$codproveedor=$_GET["codproveedor"];
	$consulta="SELECT * FROM proveedores WHERE codproveedor='$codproveedor' AND borrado=0";
	$rs_tabla = mysql_query($consulta);
	if (mysql_num_rows($rs_tabla)>0) {
		?>
		<script languaje="javascript">
		pon_prefijo("<? echo mysql_result($rs_tabla,0,nombre) ?>","<? echo mysql_result($rs_tabla,0) ?>");//sak ,nif
		</script>
		<? 
	} else { ?>
	<script>
	alert ("No existe ningun proveedor con ese codigo");
	limpiar();
	</script>
	<? }
?>
</div>
</body>
</html>