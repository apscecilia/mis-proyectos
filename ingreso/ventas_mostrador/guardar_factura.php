<?
include ("../conectar.php"); 
include ("../funciones/fechas.php"); 

$accion=$_POST["accion"];
if (!isset($accion)) { $accion=$_GET["accion"]; }

$codfacturatmp=$_POST["codfacturatmp"];
$codcliente=$_POST["codcliente"];
$fecha=explota($_POST["fecha"]);
$iva=$_POST["iva"];
$minimo=0;

if ($accion=="alta") {
	
	$query_operacion="INSERT INTO facturas (codfactura, fecha, iva, codcliente, estado, borrado) VALUES ('', '$fecha', '$iva', '$codcliente', '1', '0')";					
	$rs_operacion=mysql_query($query_operacion);
	$codfactura=mysql_insert_id();
	
	// Se guarda la nueva numeracion de factura
	if ($setnumfac==1)
	{
		$setnumfac=0;
		$sel_articulos="UPDATE facturas SET codfactura='$numeracionfactura' WHERE codfactura='$codfactura'";
		$rs_articulos=mysql_query($sel_articulos);

		$sel_articulos="UPDATE parametros SET setnumfac=0 WHERE indice=1";
		$rs_articulos=mysql_query($sel_articulos);
		$codfactura=$numeracionfactura;
	}
	
	if ($rs_operacion) { $mensaje="La factura ha sido dada de alta correctamente"; }
	$query_tmp="SELECT * FROM factulineatmp WHERE codfactura='$codfacturatmp' ORDER BY numlinea ASC";
	$rs_tmp=mysql_query($query_tmp);
	$contador=0;
	$baseimponible=0;
	while ($contador < mysql_num_rows($rs_tmp)) {
		//$codfamilia=mysql_result($rs_tmp,$contador,"codfamilia");
		$numlinea=mysql_result($rs_tmp,$contador,"numlinea");
		$codigo=mysql_result($rs_tmp,$contador,"codigo");
		$cantidad=mysql_result($rs_tmp,$contador,"cantidad");
		$precio=mysql_result($rs_tmp,$contador,"precio");
		$importe=mysql_result($rs_tmp,$contador,"importe");
		$baseimponible=$baseimponible+$importe;
		$dcto=mysql_result($rs_tmp,$contador,"dcto");
		$sel_insertar="INSERT INTO factulinea (codfactura,numlinea,codigo,cantidad,precio,importe,dcto) VALUES 
		('$codfactura','$numlinea','$codigo','$cantidad','$precio','$importe','$dcto')";//codfamilia,  '$codfamilia',
		$rs_insertar=mysql_query($sel_insertar);		
		$sel_articulos="UPDATE articulos SET stock=(stock-'$cantidad') WHERE codarticulo='$codigo'";// AND codfamilia='$codfamilia
		$rs_articulos=mysql_query($sel_articulos);
		$sel_minimos = "SELECT stock,stock_minimo,descripcion FROM articulos where codarticulo='$codigo'";// AND codfamilia='$codfamilia'
		$rs_minimos= mysql_query($sel_minimos);
		if ((mysql_result($rs_minimos,0,"stock") < mysql_result($rs_minimos,0,"stock_minimo")) or (mysql_result($rs_minimos,0,"stock") <= 0))
	   		{ 
		  		$mensaje_minimo=$mensaje_minimo . " " . mysql_result($rs_minimos,0,"descripcion")."<br>";
				$minimo=1;
   			};
		$contador++;
	}
	$baseimpuestos=$baseimponible*($iva/100);
	$preciototal=$baseimponible+$baseimpuestos;
	//$preciototal=number_format($preciototal,2);	//????????
	$sel_act="UPDATE facturas SET totalfactura='$preciototal' WHERE codfactura='$codfactura'";
	$rs_act=mysql_query($sel_act);
	$baseimpuestos=0;
	$baseimponible=0;
	$preciototal=0;
	$cabecera1="Inicio >> Ventas &gt;&gt; Venta Mostrador ";
	$cabecera2="NUEVA VENTA ";
}

?>

<html>
	<head>
		<title>Principal</title>
		<link href="../estilos/estilos.css" type="text/css" rel="stylesheet">
		<script language="javascript">
		var cursor;
		if (document.all) {
		// Está utilizando EXPLORER
		cursor='hand';
		} else {
		// Está utilizando MOZILLA/NETSCAPE
		cursor='pointer';
		}
		
		function aceptar() {
			location.href="index.php";
		}
		
		function imprimir(codfactura) {
			window.open("../fpdf/imprimir_factura.php?codfactura="+codfactura);
		}
		
		function efectuarpago(codfactura,codcliente,importe) {
			miPopup = window.open("efectuarpago.php?codfactura="+codfactura+"&codcliente="+codcliente+"&importe="+importe,"miwin","width=500,height=360,scrollbars=yes");			
		}
		
		</script></head>
	<body>
		<div id="pagina">
			<div id="zonaContenido">
				<div align="center">
				<div id="tituloForm" class="header"><?php echo $cabecera2?></div>
				<div id="frmBusqueda">
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensaje"><?php echo $mensaje;?></td>
					    </tr>
						<? if ($minimo==1) { ?>
						<tr>
							<td width="15%"></td>
							<td width="85%" colspan="2" class="mensajeminimo">Los siguientes art&iacute;culos est&aacute;n bajo m&iacute;nimo:<br><?php echo $mensaje_minimo;?></td>
					    </tr>
						<? } 
						 $sel_cliente="SELECT * FROM clientes WHERE codcliente='$codcliente'"; 
						  $rs_cliente=mysql_query($sel_cliente); ?>
						<tr>
							<td width="15%">Cliente</td>
							<td width="85%" colspan="2"><?php echo mysql_result($rs_cliente,0,"nombre");?></td>
					    </tr>
						<tr>
							<td width="15%">CUIL</td>
						    <td width="85%" colspan="2"><?php echo mysql_result($rs_cliente,0,"cuil");?></td>
					    </tr>
						<tr>
						  <td>Direcci&oacute;n</td>
						  <td colspan="2"><?php echo mysql_result($rs_cliente,0,"direccion"); ?></td>
					  </tr>
						<tr>
						  <td>C&oacute;digo de factura</td>
						  <td colspan="2"><?php echo $codfactura?></td>
					  </tr>
					  <tr>
						  <td>Fecha</td>
						  <td colspan="2"><?php echo implota($fecha)?></td>
					  </tr>
					  <tr>
						  <td>IVA</td>
						  <td colspan="2"><?php echo $iva?> %</td>
					  </tr>
					  <tr>
						  <td></td>
						  <td colspan="2"></td>
					  </tr>
				  </table>
					 <table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
						<tr class="cabeceraTabla">
							<td width="5%">ITEM</td>
							<!--<td width="20%">FAMILIA</td>-->
							<td width="15%">REFERENCIA</td>
							<td width="30%">DESCRIPCION</td>
							<td width="7%">CANTIDAD</td>
							<td width="8%">PRECIO</td>
							<td width="7%">DCTO %</td>
							<td width="8%">IMPORTE</td>
						</tr>
					</table>
					<table class="fuente8" width="98%" cellspacing=0 cellpadding=3 border=0 ID="Table1">
					  <? $sel_lineas="SELECT factulinea.*,articulos.* FROM factulinea,articulos WHERE factulinea.codfactura='$codfactura' AND factulinea.codigo=articulos.codarticulo ORDER BY factulinea.numlinea ASC";
					  /*<? $sel_lineas="SELECT factulinea.*,articulos.*,familias.nombre as nombrefamilia FROM factulinea,articulos,familias WHERE factulinea.codfactura='$codfactura' AND factulinea.codigo=articulos.codarticulo AND factulinea.codfamilia=articulos.codfamilia AND articulos.codfamilia=familias.codfamilia ORDER BY factulinea.numlinea ASC";*/
$rs_lineas=mysql_query($sel_lineas);
						for ($i = 0; $i < mysql_num_rows($rs_lineas); $i++) {
							$numlinea=mysql_result($rs_lineas,$i,"numlinea");
							//$codfamilia=mysql_result($rs_lineas,$i,"codfamilia");
							//$nombrefamilia=mysql_result($rs_lineas,$i,"nombrefamilia");
							$codarticulo=mysql_result($rs_lineas,$i,"codarticulo");
							$referencia=mysql_result($rs_lineas,$i,"referencia");
							$descripcion=mysql_result($rs_lineas,$i,"descripcion");
							$cantidad=mysql_result($rs_lineas,$i,"cantidad");
							$precio=mysql_result($rs_lineas,$i,"precio");
							$importe=mysql_result($rs_lineas,$i,"importe");
							$baseimponible=$baseimponible+$importe;
							$descuento=mysql_result($rs_lineas,$i,"dcto");
							if ($i % 2) { $fondolinea="itemParTabla"; } else { $fondolinea="itemImparTabla"; } ?>
									<tr class="<? echo $fondolinea?>">
										<td width="5%" class="aCentro"><? echo $i+1?></td>
										<!--<td width="20%"><? echo $nombrefamilia?></td>-->
										<td width="15%"><? echo $referencia?></td>
										<td width="30%"><? echo $descripcion?></td>
										<td width="7%" class="aCentro"><? echo $cantidad?></td>
										<td width="8%" class="aCentro"><? echo $precio?></td>
										<td width="7%" class="aCentro"><? echo $descuento?></td>
										<td width="8%" class="aCentro"><? echo $importe?></td>
									</tr>
					<? } ?>
					</table>
			  </div>
				  <?
				  $baseimpuestos=$baseimponible*($iva/100);
			      $preciototal=$baseimponible+$baseimpuestos;
			      $preciototal=$preciototal;
			  	  ?>
					<div id="frmBusqueda">
					<table width="25%" border=0 align="right" cellpadding=3 cellspacing=0 class="fuente8">
						<tr>
							<td width="35%"><strong>Neto</strong></td>
						  <td width="25%" align="right"><? echo $simbolomoneda ?> <?php echo number_format($baseimponible,2,".",",");?>&nbsp&nbsp&nbsp</td>
						</tr>    
						<tr>
							<td width="35%"><b>IVA</b></td>
							<td width="25%" align="right"><? echo $simbolomoneda ?> <?php echo number_format($baseimpuestos,2,".",",");?>&nbsp&nbsp&nbsp</td>
						</tr>
						<tr>
							<td width="35%"><b>Total</b></td>
							<td width="25%" align="right"><? echo $simbolomoneda ?> <?php echo number_format($preciototal,2,".",",");?>&nbsp&nbsp&nbsp</td>
						</tr>
					</table>
			  </div>
				<div id="botonBusqueda">
					<div align="center">
					  <img src="../img/botonaceptar.jpg" width="85" height="22" onClick="aceptar()" border="1" onMouseOver="style.cursor=cursor">
					   <img src="../img/botonimprimir.jpg" width="79" height="22" border="1" onClick="imprimir(<? echo $codfactura?>)" onMouseOver="style.cursor=cursor">
				        </div>
						<br>
						<div align="center" id="cajareg">
					  <img src="../img/caja.jpg" width="80" height="77" border="1" onClick="efectuarpago(<? echo $codfactura?>,<? echo $codcliente?>,<? echo $preciototal?>)" onMouseOver="style.cursor=cursor" title="Efectuar pago">
				        </div>
					</div>
			  </div>
		  </div>
		</div>
	</body>
</html>
