<?php
include ("../conectar.php");

$cadena_busqueda=$_GET["cadena_busqueda"];

if (!isset($cadena_busqueda)) { $cadena_busqueda=""; } else { $cadena_busqueda=str_replace("",",",$cadena_busqueda); }

if ($cadena_busqueda<>"") {
	$array_cadena_busqueda=split("~",$cadena_busqueda);
	$codcliente=$array_cadena_busqueda[1];
	$nombre=$array_cadena_busqueda[2];
	$numalbaran=$array_cadena_busqueda[3];
	$cboEstados=$array_cadena_busqueda[4];
	$fechainicio=$array_cadena_busqueda[5];
	$fechafin=$array_cadena_busqueda[6];
} else {
	$codcliente="";
	$nombre="";
	$numalbaran="";
	$cboEstados="";
	$fechainicio="";
	$fechafin="";
}

?>
<html>
	<head>
		<title>Remito </title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<link href="../calendario/calendar-blue.css" rel="stylesheet" type="text/css">
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/lang/calendar-sp.js"></script>
		<script type="text/JavaScript" language="javascript" src="../calendario/calendar-setup.js"></script>
		<script language="javascript">
		
		function inicio() {
			document.getElementById("form_busqueda").submit();
		}
		
		function nuevo_remito() {
			location.href="nuevo_remito.php";//albaran nuevo_albaran.php
		}
		
		function buscar() {
			var cadena;
			cadena=hacer_cadena_busqueda();
			document.getElementById("cadena_busqueda").value=cadena;
			if (document.getElementById("iniciopagina").value=="") {
				document.getElementById("iniciopagina").value=1;
			} else {
				document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			}
			document.getElementById("form_busqueda").submit();
		}
		
		function paginar() {
			document.getElementById("iniciopagina").value=document.getElementById("paginas").value;
			document.getElementById("form_busqueda").submit();
		}
		
		function hacer_cadena_busqueda() {
			var codcliente=document.getElementById("codcliente").value;
			var nombre=document.getElementById("nombre").value;
			var numalbaran=document.getElementById("numalbaran").value;			
			var cboEstados=document.getElementById("cboEstados").value;
			var fechainicio=document.getElementById("fechainicio").value;
			var fechafin=document.getElementById("fechafin").value;
			var cadena="";
			cadena="~"+codcliente+"~"+nombre+"~"+numalbaran+"~"+cboEstados+"~"+fechainicio+"~"+fechafin+"~";
			return cadena;
			}
		
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function limpiar() {
			document.getElementById("form_busqueda").reset();
		}
		
		function abreVentana(){
			miPopup = window.open("ventana_clientes.php","miwin","width=700,height=380,scrollbars=yes");
			miPopup.focus();
		}
		
		function validarcliente(){
			var codigo=document.getElementById("codcliente").value;
			miPopup = window.open("comprobarcliente_ini.php?codcliente="+codigo,"frame_datos","width=700,height=80,scrollbars=yes");
		}	

		</script>
	    <style type="text/css">
<!--
.Estilo1 {
	font-size: 14pt;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.Estilo2 {
	font-size: 14pt;
	font-family: "Times New Roman", Times, serif;
}
.Estilo3 {font-family: "Times New Roman", Times, serif}
-->
        </style>
</head>
	<body onLoad="inicio()">
		<div class="header" id="pagina">
	  <div id="zonaContenido">
				<div align="center">
				<div class="header Estilo1" id="tituloForm">REMITO</div>
				<div id="frmBusqueda">
				<form id="form_busqueda" name="form_busqueda" method="post" action="buscar.php" target="frame_buscar">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>					
						<tr>
							<td width="16%">Codigo de cliente </td>
							<td width="68%"><input id="codcliente" type="text" class="cajaPequena" NAME="codcliente" maxlength="10" value="<? echo $codcliente?>"> <img src="../img/ver.png" width="16" height="16" onClick="abreVentana()" title="Buscar cliente" onMouseOver="style.cursor=cursor"> <img src="../img/cliente.png" width="16" height="16" onClick="validarcliente()" title="Validar cliente" onMouseOver="style.cursor=cursor"></td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="6%" align="right"></td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td><input id="nombre" name="nombre" type="text" class="cajaGrande" maxlength="45" value="<? echo $nombre?>"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
						  <td>Num. Remito</td>
						  <td><input id="numalbaran" type="text" class="cajaPequena" NAME="numalbaran" maxlength="15" value="<? echo $numalbaran?>"></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
						<tr>
							<td>Estado</td>
							<td><select id="cboEstados" name="cboEstados" class="comboMedio">
								<option value="0" selected>Todos los estados</option>
								<option value="1">Sin Facturar</option>
								<option value="2">Facturados</option>			
								</select></td>
					    </tr>
					  <tr>
						  <td>Fecha de emision</td>
						  <td><input id="fechainicio" type="text" class="cajaPequena" NAME="fechainicio" maxlength="10" value="<? echo $fechainicio?>" readonly><img src="../img/calendario.png" name="Image1" id="Image1" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechainicio",
					ifFormat   : "%d/%m/%Y",
					button     : "Image1"
					  }
					);
		</script>	</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
						<tr>
						  <td>Fecha de vencimiento</td>
						  <td><input id="fechafin" type="text" class="cajaPequena" NAME="fechafin" maxlength="10" value="<? echo $fechafin?>" readonly><img src="../img/calendario.png" name="Image2" id="Image2" width="16" height="16" border="0" onMouseOver="this.style.cursor='pointer'" title="Calendario2">
        <script type="text/javascript">
					Calendar.setup(
					  {
					inputField : "fechafin",
					ifFormat   : "%d/%m/%Y",
					button     : "Image2"
					  }
					);
		</script></td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
						  <td>&nbsp;</td>
					  </tr>
					</table>
			  </div>
			 	<div id="botonBusqueda"><img src="../img/botonbuscar.jpg" width="69" height="22" border="1" onClick="buscar()" onMouseOver="style.cursor=cursor">
			 	  <img src="../img/botonlimpiar.jpg" width="69" height="22" border="1" onClick="limpiar()" onMouseOver="style.cursor=cursor">
					 <img src="../img/botonnuevoremito.jpg" width="106" height="22" border="1" onClick="nuevo_remito()" onMouseOver="style.cursor=cursor">
				</div>
			  <div id="lineaResultado">
			  <table class="fuente8" width="80%" cellspacing=0 cellpadding=3 border=0>
			  	<tr>
				<td width="50%" align="left">Nro de remitos encontrados  <input id="filas" type="text" class="cajaPequena" NAME="filas" maxlength="5" readonly></td>
				<td width="50%" align="right">Mostrados <select name="paginas" id="paginas" onChange="paginar()">
		          </select></td>
			  </table>
				</div>
				<div class="header Estilo2" id="cabeceraResultado">
					VISUALIZADOR</div>
				<div id="frmResultado">
				<table class="fuente8" width="100%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="8%">ITEM</td>
							<td width="8%">NUM. REMITO</td>
							<td width="29%">CLIENTE </td>
							<td width="10%">IMPORTE </td>							
							<td width="10%">FECHA</td>
							<td width="10%">ESTADO </td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
							<td width="5%">&nbsp;</td>
						</tr>
				</table>
				</div>
				<input type="hidden" id="iniciopagina" name="iniciopagina">
				<input type="hidden" id="cadena_busqueda" name="cadena_busqueda">
			</form>
				<div id="lineaResultado_pagos">
					<iframe name="frame_buscar" width="100%" height="250" frameborder="0" class="Estilo3" id="frame_buscar">
						<ilayer width="100%" height="250" id="frame_buscar" name="frame_buscar"></ilayer>					</iframe>
<iframe id="frame_datos" name="frame_datos" width="0" height="0" frameborder="0">
					<ilayer width="0" height="0" id="frame_datos" name="frame_datos"></ilayer>
					</iframe>
				</div>
			</div>
		  </div>			
		</div>
</body>
</html>
