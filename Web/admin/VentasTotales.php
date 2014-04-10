<?php
setlocale(LC_MONETARY, 'en_US.UTF-8');

$user="selene";
$password='+gV6)MV?{"T!';
$database="clickonero-live";
$server="184.106.25.121";
$date= $_GET["date"];
preg_match('/(\d{4})-(\d{2})-(\d{2})/',$date,$partes);
$date_month1 = $partes[1]."-".$partes[2]."-01";
if ($partes[2]==4 || $partes[2]==6 || $partes[2]==9 || $partes[2]==11)
$date_month2 = $partes[1]."-".$partes[2]."-30";
elseif ($partes[2]==2)
$date_month2 = $partes[1]."-".$partes[2]."-28";
else
$date_month2 = $partes[1]."-".$partes[2]."-31";
//echo $date_month1." ".$date_month2;
$today = date("F j, Y, g:i a");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Ventas</title>
<style>
.subtitle
{
	color:#FFFFFF;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	font-weight:bold;
	background:#0f5366;
}

.etiquetas
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	
}

.etiquetas2
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#efeff2;
}
.etiquetas3
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#29bddb; 
}
.etiquetas4
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#FF9900; 
}
.etiquetas5
{
	color:#FFFFFF;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#599653;
}
.etiquetas6
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#eb6841;
.status
{
	color:#333333;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#E86850;
}
</style>
</head>
<body>
<br /><br />

<?php echo "Ventas de: ".$date;
	  echo "<br> Consulta: ".$today;		 ?>

<br /><br />

<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1200px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Fecha de Venta</td>
                    <td bgcolor="#000066" class="subtitle">Campaña ID</td>
					<td bgcolor="#000066" class="subtitle">Visibilidad</td>
					<td bgcolor="#000066" class="subtitle">Categoria</td>
					<td bgcolor="#000066" class="subtitle">Campaña</td>
                    <td bgcolor="#000066" class="subtitle">Vendidos</td>
					<td bgcolor="#000066" class="subtitle">Venta</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Margen %</td>
					<td bgcolor="#000066" class="subtitle">Comp</td>
					<td bgcolor="#000066" class="subtitle">Fecha Inicio Campaña</td>
					<td bgcolor="#000066" class="subtitle">Fecha Fin Campaña</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select   date(o.date_created) fechadeCompra, i.id item,c.id campana,
				ic.name category,c.visibility, c.name campaign, sum(od.quantity) cantidad, s.price, sum(od.cost) 		
				venta,sum(od.cost-(od.quantity*s.cost)) margen,count(distinct(o.person_id)) compradores,
				 date(c.start_date) as fechaInicioCampana, date(c.end_date) as fechaFinCampana, ic.parent_id parent,
				case
					When ic.id in (27,45,55,65,107,117,210,213,214,217) -- Fashion 
					Then 'Textil'
					When ic.id in (218,219,220,221,222,223,224,225,228,229,230,231,232,233,234,235,260,261,269,270, 
					/*Nuevos*/222,	260,271,273,275,277,279,281,283,285,288,290,292,294,296,223,261,272,274,276,278,280,282,284,286,289,291,293,295,297,269,300,302,304,305,307,309,311,313,315	,317,319,321,323,270,301,303,305,306,308,310,312,314,316,318,320,322,324,325) -- Deportes
					Then 'Deportes'
					when ic.id in (51,111,120,212,216,227,337)
					then 'Calzado y Bolsos'
					when ic.id in (118,127,128,144,145,155,206,207,211,215,253,254,255,256,257,264,265,332,333)
					Then 'Accesorios'
					When ic.parent_id = 157
					Then 'Hogar y Gadgets'
					Else 'CATEGORIA NO VALIDA'
					END as 'Vertical'
				from campaign c
				inner join item i on i.campaign_id=c.id
				inner join item_category ic on ic.id=i.category_id
				inner join sku s on s.item_id= i.id
				inner join order_detail od on od.sku_id=s.id
				inner join orders o on o.id = od.order_id
				where order_status= 'PAID'
				and od.status = 'AVAILABLE'
				and ic.parent_id in (1,2,3,4,5,6,157)
				and o.date_created >'".$date."'
				and o.date_created < date(DATE_ADD('".$date."',INTERVAL 1 DAY))
				group by date(o.date_created), c.id
				order by sum(od.cost) desc";
				
				
				//echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_total=0;	
				$cant_total=0;
				
				// OUTLET
				$cant_out=0;
				$comp_out=0;
				$vta_outlet=0;
				$margen_per_ind=0;
				

				while($rs=mysql_fetch_array($result_qry)){
					$i++;
					if(($i%2)==0)
					{
						$class="etiquetas2";
					}
					else
					{
						$class="etiquetas";
					}	
					if($rs["visibility"]== 'HIDDEN')
					{
						$class="etiquetas4";
					}
					$vta_outlet= $vta_outlet + $rs["venta"];
					$cant_out = $cant_out + $rs["cantidad"];
					$comp_out = $comp_out + $rs["compradores"];
					$margenOutlet = $margenOutlet + $rs["margen"];
					$margen_per_ind = ($rs["margen"]/$rs["venta"])*100;
					
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fechadeCompra"]."</td>";
					echo "<td class=\"".$class."\">".$rs["campana"]."-".$rs["parent"]."</td>";
					echo "<td class=\"".$class."\">".$rs["visibility"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Vertical"]."-".$rs["category"]."</td>";
					echo "<td class=\"".$class."\">".$rs["campaign"]."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["venta"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["margen"])."</td>";
					echo "<td class=\"".$class."\">".round($margen_per_ind)."%</td>";
					echo "<td class=\"".$class."\">".$rs["compradores"]."</td>";
					echo "<td class=\"".$class."\">".$rs["fechaInicioCampana"]."</td>";
					echo "<td class=\"".$class."\">".$rs["fechaFinCampana"]."</td>";
					//echo "<td class=\"".$class."\">".$rs["parent"]."</td>";
				}
				// Fila de Total
				$margen_perOutlet = $margenOutlet/$vta_outlet*100;
				echo "<tr>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'>".$parent."</td>";
						echo "<td class='etiquetas3'><b>VENTAS</b></td>";
						echo "<td class='etiquetas3'><b>OUTLET</b></td>";
						echo "<td class='etiquetas3'><b> - </b></td>";
						echo "<td class='etiquetas3'><b>".$cant_out."</b> </td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $vta_outlet)."</b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $margenOutlet)."</b></td>";
						echo "<td class='etiquetas3'><b>".round($margen_perOutlet)."%</td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
					echo "<tr>";
				$vta_total= $vta_total +  $vta_outlet ;
				$cant_total = $cant_total +$cant_out;
				$margen_total = $margen_total + $margenOutlet;
				$margen_per_outlet = ($margen_total/$vta_total)*100;
				
				
			?>
			</table>
		</td>
	</tr>
</table>
<br>

<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1200px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Fecha de Venta</td>
                    <td bgcolor="#000066" class="subtitle">Item ID</td>
					<td bgcolor="#000066" class="subtitle">Categoría/Plaza</td>
					<td bgcolor="#000066" class="subtitle">Visibilidad</td>
					<td bgcolor="#000066" class="subtitle">Campaña</td>
                    <td bgcolor="#000066" class="subtitle">Vendidos</td>
					<td bgcolor="#000066" class="subtitle">Venta</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Margen %</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
					<td bgcolor="#000066" class="subtitle">Fecha Inicio Campaña</td>
					<td bgcolor="#000066" class="subtitle">Fecha Fin Campaña</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select   date(o.date_created) fechadeCompra, i.id item,c.id campana,
				ic.name category,c.visibility, c.name campaign, sum(od.quantity) cantidad, s.price, sum(od.cost) 		
				venta,
				Case 
					when kind = 'TRADITIONAL' 
					then sum(od.cost-(od.quantity*s.cost))
					when kind = 'PREPAID' 
					then sum(od.quantity*price_by_kind)
					END as 'Margen',
				count(distinct(o.person_id)) compradores,
				 date(c.start_date) as fechaInicioCampana, date(c.end_date) as fechaFinCampana, ic.parent_id parent
				from campaign c
				inner join item i on i.campaign_id=c.id
				inner join store_service ss on i.id=ss.id
				inner join item_category ic on ic.id=i.category_id
				inner join sku s on s.item_id= i.id
				inner join order_detail od on od.sku_id=s.id
				inner join orders o on o.id = od.order_id
				where order_status= 'PAID'
				and od.status = 'AVAILABLE'
				and ic.parent_id not in (1,2,3,4,5,6,157)
				and date(o.date_created) = '".$date."'
				and o.date_created < date(DATE_ADD('".$date."',INTERVAL 1 DAY))
				group by date(o.date_created), c.id
				order by ic.parent_id,sum(od.cost) desc";
				
				
				//echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				
				$cant_viajes=0;
				$vta_viajes=0;
				
				$cant_serv=0;
				$vta_servicios=0;
				
				
				$cant_bell=0;
				
				
				
				$vta_bell=0;
				$parent = 7;
				
				

				while($rs=mysql_fetch_array($result_qry)){
					$i++;
					if(($i%2)==0)
					{
						$class="etiquetas2";
					}
					else
					{
						$class="etiquetas";
					}	
				
				if ($rs[parent] == 7  ){
						$vta_viajes = $vta_viajes + $rs["venta"];
						$cant_viajes = $cant_viajes + $rs["cantidad"];
						$margen_viajes = $margen_viajes + $rs["Margen"];
						$margen_per_viajes = ($margen_viajes/$vta_viajes)*100;
					}
				if ($rs[parent] == 9  ){
						$vta_servicios =$vta_servicios + $rs["venta"];
						$cant_serv = $cant_serv +$rs["cantidad"];
						$margen_serv = $margen_serv + $rs["Margen"];
						$margen_per_serv = ($margen_serv/$vta_servicios)*100;
					}
				if ($rs[parent] == 152  ){
						$cant_bell = $cant_bell +$rs["cantidad"];
						$vta_bell = $vta_bell + $rs["venta"];
						$margen_bell = $margen_bell + $rs["Margen"];
						$margen_per_bell = ($margen_bell/$vta_bell)*100;
					}
					
				if ($parent!= $rs[parent] && $rs[parent] > 7 && $rs[parent] < 10){
						
						echo "<tr>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'>".$parent."</td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'><b>VENTAS</b></td>";
						echo "<td class='etiquetas3'><b>VIAJES</b></td>";
						echo "<td class='etiquetas3'><b> ".$cant_viajes." </b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $vta_viajes)."</b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $margen_viajes)."</b></td>";
						echo "<td class='etiquetas3'><b>".round($margen_per_viajes)."%</b></td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						$parent = $rs[parent];
						$vta_total= $vta_total +  $vta_viajes ;
						$cant_total = $cant_total +$cant_viajes;
					}
					
					if ($parent!= $rs[parent] && $parent == 9){
						
						echo "<tr>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'>".$parent."</td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'><b>VENTAS</b></td>";
						echo "<td class='etiquetas3'><b>SERVICIOS</b></td>";
						echo "<td class='etiquetas3'><b> ".$cant_serv." </b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $vta_servicios)."</b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $margen_serv)."</b></td>";
						echo "<td class='etiquetas3'><b>".round($margen_per_serv)."%</b></td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						$parent = $rs[parent];
						$vta_total= $vta_total +  $vta_servicios ;
						$cant_total = $cant_total +$cant_serv;
					}
					
					if($rs["visibility"]== 'HIDDEN')
					{
						$class="etiquetas4";
					}
					if($rs["visibility"]== 'HIDDEN' && $rs["category"]== 'OFFLINE')
					{
						$class="etiquetas6";
					}
					
					$margen_per_ind = ($rs["Margen"]/$rs["venta"])*100;
					
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fechadeCompra"]."</td>";
					echo "<td class=\"".$class."\">".$rs["item"]."-".$rs["parent"]."</td>";
					echo "<td class=\"".$class."\">".$rs["category"]."</td>";
					echo "<td class=\"".$class."\">".$rs["visibility"]."</td>";
					echo "<td class=\"".$class."\">".$rs["campaign"]."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["venta"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])."</td>";
					echo "<td class=\"".$class."\">".round($margen_per_ind)."%</td>";
					echo "<td class=\"".$class."\">".$rs["compradores"]."</td>";
					echo "<td class=\"".$class."\">".$rs["fechaInicioCampana"]."</td>";
					echo "<td class=\"".$class."\">".$rs["fechaFinCampana"]."</td>";
					//echo "<td class=\"".$class."\">".$rs["parent"]."</td>";
				}
				// Fila de Total
				
				if ($vta_bell > 0) {
				echo "<tr>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'>".$parent."</td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'><b>VENTAS</b></td>";
						echo "<td class='etiquetas3'><b>BELLEZA</b></td>";
						echo "<td class='etiquetas3'><b>".$cant_bell."</b> </td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $vta_bell)."</b></td>";
						echo "<td class='etiquetas3'><b>".money_format('%(#10n', $margen_bell)."</b></td>";
						echo "<td class='etiquetas3'><b>".round($margen_per_bell)."%</b></td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
						echo "<td class='etiquetas3'> - </td>";
					echo "<tr>";
					$vta_total= $vta_total +  $vta_bell;
					$cant_total = $cant_total +$cant_bell;
				}
				else 
				{
				$vta_total= $vta_total +  $vta_servicios ;
				$cant_total = $cant_total +$cant_serv;
				 }
				
				
			?>
			</table>
		</td>
	</tr>
</table>
<br><br/><br/>

<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<h3 align="center" > Resumen de Ventas    <?php 
				echo $date; echo "  (Consulta: ".$today.")";
				$vta_total= $vta_outlet+$vta_viajes+ $vta_servicios+$vta_bell;
				$margen_total = $margenOutlet+$margen_viajes+$margen_serv+$margen_bell;
				$share_vta_out = ($vta_outlet/$vta_total)*100;
				$share_vta_viajes = ($vta_viajes/$vta_total)*100;
				$share_vta_serv = ($vta_servicios/$vta_total)*100;
				$share_vta_bell = ($vta_bell/$vta_total)*100;
				$share_margen_out = ($margenOutlet/$margen_total)*100;
				$share_margen_viajes = ($margen_viajes/$margen_total)*100;
				$share_margen_serv = ($margen_serv/$margen_total)*100;
				$share_margen_bell = ($margen_bell/$margen_total)*100;
				?> </h3>
				<tr>
					<td bgcolor="#000066" class="subtitle">Area</td>
                    <td bgcolor="#000066" class="subtitle">Venta </td>
                    <td bgcolor="#000066" class="subtitle">Share Venta </td>
                    <td bgcolor="#000066" class="subtitle">Margen </td>
                    <td bgcolor="#000066" class="subtitle">Share Margen </td>
                    <td bgcolor="#000066" class="subtitle">Productos Vendidos</td>
				</tr>
                <tr>
                	<td class="etiquetas2"><a href="http://clickonero.red10.com/admin/VentasDetalleOutlet.php?date=<?php echo $date; ?>" target='_blank'>Outlet</a></td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_outlet); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_out)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $margenOutlet)." - ".round($margen_per_outlet)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_out)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_out; ?> </td>
                </tr>
                <tr>
                	<td class="etiquetas2">Viajes </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_viajes); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_viajes)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $margen_viajes)." - ".round($margen_per_viajes)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_viajes)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_viajes; ?> </td>
                </tr>
                <tr>
                	<td class="etiquetas2">Servicios </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_servicios); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_serv)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $margen_serv)." - ".round($margen_per_serv)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_serv)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_serv; ?> </td>
                </tr>
                <tr>
                	<td class="etiquetas2">Belleza </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_bell); ?> </td>
                     <td class="etiquetas2"><?php echo round($share_vta_bell)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $margen_bell)." - ".round($margen_per_bell)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_bell)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_bell; ?> </td>
                </tr>
               <!-- <tr>
                	<td class="etiquetas5">Venta Total </td>
                	<?php  $vta_total= $vta_outlet+$vta_viajes+ $vta_servicios+$vta_bell;?>
                    <td class="etiquetas5"><b><?php echo money_format('%(#10n', $vta_total); ?> </b></td>
                    <td class="etiquetas5"><b><?php echo $cant_total ?> </b></td>
                    <td class="etiquetas5">n </td>
                </tr> --!>
                
            </table>
                
<br /><br />
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Fecha de Venta</td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
                    <td bgcolor="#000066" class="subtitle">2013</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Cantidad</td>
					<td bgcolor="#000066" class="subtitle">Ordenes</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
 fecha,sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen,
	date_sub(fecha, INTERVAL 1 year) anterior,

(Select sum(od.cost) from order_detail od 
inner join orders o on o.id = od.order_id
where date(o.date_created) = anterior
and o.order_status = 'PAID'
and od.status = 'AVAILABLE'
 ) LastYear
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and o.date_created < date(DATE_ADD('".$date."',INTERVAL 3 DAY))
		and o.date_created > date(DATE_SUB('".$date."',INTERVAL 2 DAY))
		group by o.id,fecha
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and o.date_created < date(DATE_ADD('".$date."',INTERVAL 3 DAY))
		and o.date_created > date(DATE_SUB('".$date."',INTERVAL 2 DAY))
		group by o.id, fecha) monto
group by date(fecha)";
				
				
				// echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["LastYear"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				// Fila de Total
				echo "<tr>";
				
			?>
			</table>
		</td>
	</tr>
</table><br>

<br /><br />


<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Fecha de Venta</td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
					<td bgcolor="#000066" class="subtitle">Venta del Mail</td>
					<td bgcolor="#000066" class="subtitle">Venta Off</td>
					<td bgcolor="#000066" class="subtitle">Venta On Asistido</td>
					<td bgcolor="#000066" class="subtitle">Venta Orgánica</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "Select date(o.date_created) fecha, sum(od.cost) Venta_Total, 
 						(Select cast(sum(od.cost) AS CHAR CHARSET utf8)
						from orders o 
						inner join tracking t on t.code = o.order_number
						inner join order_detail od on od.order_id = o.id
						where o.order_status = 'PAID'
						and date(o.date_created) = fecha
						and (campaign like '%BEST%' or campaign like '%NEW%')
						and od.status = 'AVAILABLE') 
						 Venta_Mail,
					 	(Select  cast(sum(od.cost)AS CHAR CHARSET utf8)
					from orders o
					inner join order_detail od on od.order_id = o.id
					inner join sku s on s.id = od.sku_id
					inner join item i on i.id = s.item_id
					inner join item_category ic on ic.id = i.category_id
					where order_status = 'PAID'
					and od.status = 'AVAILABLE'
					and date(o.date_created) = fecha
					and ic.id = 263)
					 Venta_OFF,
					 (Select  cast(sum(od.cost)AS CHAR CHARSET utf8)
					from orders o
					inner join order_detail od on od.order_id = o.id
					inner join sku s on s.id = od.sku_id
					inner join item i on i.id = s.item_id
					inner join campaign c on c.id = i.campaign_id
					inner join item_category ic on ic.id = i.category_id
					where order_status = 'PAID'
					and od.status = 'AVAILABLE'
					and date(o.date_created) = fecha
					and ic.parent_id = 7
					and c.visibility = 'HIDDEN'
					and ic.id <> 263)
					 Venta_OnAsis
					from orders o
					inner join order_detail od on od.order_id = o.id
					and o.date_created < date(DATE_ADD('".$date."',INTERVAL 3 DAY))
					and o.date_created > date(DATE_SUB('".$date."',INTERVAL 2 DAY))
					and order_status = 'PAID'
					and od.status = 'AVAILABLE'
					group by date(o.date_created)";
				
				
				//echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}

					$vta_org = $rs["Venta_Total"] - $rs["Venta_Mail"] -$rs["Venta_OFF"]-$rs["Venta_OnAsis"];
					$per_mail = ($rs["Venta_Mail"]/ $rs["Venta_Total"])*100;
					$per_off = ($rs["Venta_OFF"]/ $rs["Venta_Total"])*100;
					$per_org = ($vta_org/ $rs["Venta_Total"])*100;
					$per_onas =  ($rs["Venta_OnAsis"]/ $rs["Venta_Total"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Venta_Total"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Venta_Mail"])." - ".round($per_mail)."%"."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Venta_OFF"])." - ".round($per_off)."%"."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Venta_OnAsis"])." - ".round($per_onas)."%"."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $vta_org)." - ".round($per_org)."%"."</td>";
				}
				// Fila de Total
				echo "<tr>";
				
			?>
			</table>
		</td>
	</tr>
</table><br>
<center>Nota: Las ventas ON Asistido se empezaron a registrar correctamente a partir del Sabado 20 de Julio</center>
<br><br>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Mes de Venta</td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Cantidad</td>
					<td bgcolor="#000066" class="subtitle">Ordenes</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select Cast(concat(year(fecha),'-',month(fecha))AS CHAR CHARSET utf8) fechas,
 sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and o.date_created < Date_ADD('".$date_month2."', interval 3 month)
		and o.date_created > Date_SUB('".$date_month1."', interval 2 month)
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and o.date_created < Date_ADD('".$date_month2."', interval 3 month)
		and o.date_created > Date_SUB('".$date_month1."', interval 2 month)
		group by o.id, month(fecha)) monto
group by month(fecha)
order by year(fecha) asc ,month(fecha) asc
limit 5
	;
";
				
				
				 //echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fechas"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				// Fila de Total
				echo "<tr>";
				
			?>
			</table>
		</td>
	</tr>
</table><br>

<br><br>
<center>Cuadro Comparativo 2013 al 2014 con los rangos de fechas actuales </center>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Periodo </td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Cantidad</td>
					<td bgcolor="#000066" class="subtitle">Ordenes</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat(Date_SUB('".$date_month1."', interval 1 year),' al ',Date_SUB('".$date."', interval 1 year))AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  Date_SUB('".$date_month1."', interval 1 year) and Date_SUB('".$date."', interval 1 year)
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between Date_SUB('".$date_month1."', interval 1 year) and Date_SUB('".$date."', interval 1 year)
		group by o.id, month(fecha)) monto
group by month(fecha)
";
				
				
				 //echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;

		
				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$anterior = $rs["Facturacion"];
					$mar_anterior = $rs["Margen"];
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					
					$cant_anterior = $rs["cantidad"];
					$ord_anterior = $rs["Ordenes"];
					$comp_anterior = $rs["Compradores"];
					
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "<tr>";
				
//Segundo Query

mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat('".$date_month1."' ,' al ','".$date."')AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  '".$date_month1."' and '".$date."'
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between '".$date_month1."' and '".$date."'
		group by o.id, month(fecha)) monto
group by month(fecha)
";
				
				
				// echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$actual = $rs["Facturacion"];
					$mar_actual = $rs["Margen"];
					
					$cant_actual = $rs["cantidad"];
					$ord_actual = $rs["Ordenes"];
					$comp_actual = $rs["Compradores"];
					
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "</tr>";
				$diferencia = $actual - $anterior ;
				$dif_mar = $mar_actual - $mar_anterior;
				
				$dif_cant = $cant_actual - $cant_anterior;
				$dif_ord = $ord_actual - $ord_anterior;
				$dif_comp = $comp_actual - $comp_anterior;
				
				$dif_per = (($actual / $anterior)-1)*100;
				$dif_mar_per = (($mar_actual / $mar_anterior)-1)*100;
				$dif_cant_per = (($cant_actual / $cant_anterior)-1)*100;
				$dif_ord_per = (($ord_actual / $ord_anterior)-1)*100;
				$dif_comp_per = (($comp_actual / $comp_anterior)-1)*100;
				
				
				echo "<tr>";
				echo "<td class=\"".$class."\">Diferencia Mensual </td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $diferencia).' / '.round($dif_per)."%</td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $dif_mar).' / '.round($dif_mar_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_cant.' / '.round($dif_cant_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_ord.' / '.round($dif_ord_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_comp.' / '.round($dif_comp_per
				)."%</td>";
				echo "</tr>";
				
			?>
			</table>
		</td>
	</tr>
</table>



<br><br>
<center>Cuadro Comparativo  mensual del 2014 de inicio de mes al dia </center>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Periodo Venta </td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Cantidad</td>
					<td bgcolor="#000066" class="subtitle">Ordenes</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat(Date_SUB('".$date_month1."', interval 1 month),' al ',Date_SUB('".$date."', interval 1 month))AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  Date_SUB('".$date_month1."', interval 1 month) and Date_SUB('".$date."', interval 1 month)
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between Date_SUB('".$date_month1."', interval 1 month) and Date_SUB('".$date."', interval 1 month)
		group by o.id, month(fecha)) monto
";
				
				
				 //echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;

		
				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$anterior = $rs["Facturacion"];
					$mar_anterior = $rs["Margen"];
					$cant_anterior = $rs["cantidad"];
					$ord_anterior = $rs["Ordenes"];
					$comp_anterior = $rs["Compradores"];
					
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "<tr>";
				
//Segundo Query

mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat('".$date_month1."' ,' al ','".$date."')AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  '".$date_month1."' and '".$date."'
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between '".$date_month1."' and '".$date."'
		group by o.id, month(fecha)) monto
";
				
				
				// echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$actual = $rs["Facturacion"];
					$mar_actual = $rs["Margen"];
					
					$cant_actual = $rs["cantidad"];
					$ord_actual = $rs["Ordenes"];
					$comp_actual = $rs["Compradores"];
					
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "</tr>";
				$diferencia = $actual - $anterior ;
				$dif_mar = $mar_actual - $mar_anterior;
				
				$dif_cant = $cant_actual - $cant_anterior;
				$dif_ord = $ord_actual - $ord_anterior;
				$dif_comp = $comp_actual - $comp_anterior;
				
				$dif_per = (($actual / $anterior)-1)*100;
				$dif_mar_per = (($mar_actual / $mar_anterior)-1)*100;
				
				$dif_cant_per = (($cant_actual / $cant_anterior)-1)*100;
				$dif_ord_per = (($ord_actual / $ord_anterior)-1)*100;
				$dif_comp_per = (($comp_actual / $comp_anterior)-1)*100;
				
				
				echo "<tr>";
				echo "<td class=\"".$class."\">Diferencia Mes actual y anterior </td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $diferencia).' / '.round($dif_per)."%</td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $dif_mar).' / '.round($dif_mar_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_cant.' / '.round($dif_cant_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_ord.' / '.round($dif_ord_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_comp.' / '.round($dif_comp_per)."%</td>";
				echo "</tr>";
				
			?>
			</table>
		</td>
	</tr>
</table>




<br><br>
<center>Cuadro Comparativo 2013 al 2014 con los rangos de Enero a la fecha </center>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Periodo Venta </td>
                    <td bgcolor="#000066" class="subtitle">Venta Total</td>
					<td bgcolor="#000066" class="subtitle">Margen</td>
					<td bgcolor="#000066" class="subtitle">Cantidad</td>
					<td bgcolor="#000066" class="subtitle">Ordenes</td>
					<td bgcolor="#000066" class="subtitle">Compradores</td>
				</tr>
<?php
				
				mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat(Date_SUB('2014-01-01', interval 1 year),' al ',Date_SUB('".$date."', interval 1 year))AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  Date_SUB('2014-01-01', interval 1 year) and Date_SUB('".$date."', interval 1 year)
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between Date_SUB('2014-01-01', interval 1 year) and Date_SUB('".$date."', interval 1 year)
		group by o.id, month(fecha)) monto
";
				
				
				 //echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;

		
				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$anterior = $rs["Facturacion"];
					$mar_anterior = $rs["Margen"];
					$cant_anterior = $rs["cantidad"];
					$ord_anterior = $rs["Ordenes"];
					$comp_anterior = $rs["Compradores"];
					
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "<tr>";
				
//Segundo Query

mysql_connect($server,$user,$password);
				@mysql_select_db($database) or die( "Unable to select database");
				$query = "select 
CAST( concat('2014-01-01' ,' al ','".$date."')AS CHAR CHARSET utf8) as fecha,
sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen
from (
select date(o.date_created) fecha, 
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join store_service ss on i.id=ss.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and date(o.date_created) between  '2014-01-01' and '".$date."'
		group by o.id,month(fecha)
union 
select date(o.date_created) fecha,  
		sum(od.cost) Facturacion,
		o.id Ordenes,
		o.person_id compradores,
		sum(od.quantity) cantidad,
		sum(od.cost-(od.quantity*s.cost))'Margen'
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'
		and parent_id not in (7,9,152)		 
		and date(o.date_created) between '2014-01-01' and '".$date."'
		group by o.id, month(fecha)) monto
";
				
				
				// echo $query;
				$result_qry=mysql_query($query);
				mysql_close();
				$vta_org = 0;


				while($rs=mysql_fetch_array($result_qry)){
					$i++;

					$class="etiquetas2";
					if($rs["fecha"]== $date)
					{
						$class="etiquetas";
					}
					$actual = $rs["Facturacion"];
					$mar_actual = $rs["Margen"];
					
					$cant_actual = $rs["cantidad"];
					$ord_actual = $rs["Ordenes"];
					$comp_actual = $rs["Compradores"];
					
					$margen_per = ($rs["Margen"]/$rs["Facturacion"])*100;
					echo "<tr>";
					echo "<td class=\"".$class."\">".$rs["fecha"]."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Facturacion"])."</td>";
					echo "<td class=\"".$class."\">".money_format('%(#10n', $rs["Margen"])." - ".round($margen_per)."%"."</td>";
					echo "<td class=\"".$class."\">".$rs["cantidad"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Ordenes"]."</td>";
					echo "<td class=\"".$class."\">".$rs["Compradores"]."</td>";
				}
				
				// Fila de Total
				echo "</tr>";
				$diferencia = $actual - $anterior ;
				$dif_mar = $mar_actual - $mar_anterior;
				
				$dif_cant = $cant_actual - $cant_anterior;
				$dif_ord = $ord_actual - $ord_anterior;
				$dif_comp = $comp_actual - $comp_anterior;
				
				$dif_per = (($actual / $anterior)-1)*100;
				$dif_mar_per = (($mar_actual / $mar_anterior)-1)*100;
				
				$dif_cant_per = (($cant_actual / $cant_anterior)-1)*100;
				$dif_ord_per = (($ord_actual / $ord_anterior)-1)*100;
				$dif_comp_per = (($comp_actual / $comp_anterior)-1)*100;
				
				
				echo "<tr>";
				echo "<td class=\"".$class."\">Diferencia Anual </td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $diferencia).' / '.round($dif_per)."%</td>";
				echo "<td class=\"".$class."\">".money_format('%(#10n', $dif_mar).' / '.round($dif_mar_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_cant.' / '.round($dif_cant_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_ord.' / '.round($dif_ord_per)."%</td>";
				echo "<td class=\"".$class."\">".$dif_comp.' / '.round($dif_comp_per)."%</td>";
				echo "</tr>";
				
			?>
			</table>
		</td>
	</tr>
</table>



<br>
<br><br>
<a href="http://clickonero.red10.com/admin/TopVentas.php?date=<?php echo $date; ?>" target='_blank'>Top Ventas</a>


</body>
</html>
