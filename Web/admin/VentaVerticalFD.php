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
<title>Ventas Fashion y Deportes</title>
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
					When ic.id  in (27,45,51,55,65,107,111,117,118,120,127,128,144,145,155,206,204,207,210,211,212,213,214,215,216,217,227,253,254,255,256,257,264,265,332,333)					 
					Then 'Fashion'
					When ic.id in (218,219,220,221,222,223,224,225,228,229,230,231,232,233,234,235,260,261,269,270, 
					222,260,271,273,275,277,279,281,283,285,288,290,292,294,296,223,261,272,274,276,278,280,282,284,286,289,291,293,295,297,269,300,302,304,305,307,309,311,313,315	,317,319,321,323,270,301,303,305,306,308,310,312,314,316,318,320,322,324,325) -- Deportes
					Then 'Deportes'
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
				and ic.parent_id not in (7,9,152,157)
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
					
					if($rs["Vertical"]== 'Fashion')
					{
						$vta_fashion = $vta_fashion + $rs["venta"];
						$marg_fashion = $marg_fashion + $rs["margen"];
					}
					if($rs["Vertical"]== 'Deportes')
					{
						$vta_deportes = $vta_deportes + $rs["venta"];
						$marg_deportes = $marg_deportes + $rs["margen"];
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
				$margen_per_fashion = ($marg_fashion/$vta_fashion)*100;
				$margen_per_deportes = ($marg_deportes/$vta_deportes)*100;
				
				
			?>
			</table>
		</td>
	</tr>
</table>
<br><br>

<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<h3 align="center" > Resumen de Ventas    <?php 
				echo $date; echo "  (Consulta: ".$today.")";
				$vta_total= $vta_outlet;
				$margen_total = $margenOutlet;
				$share_vta_out = ($vta_outlet/$vta_total)*100;
				$share_margen_out = ($margenOutlet/$margen_total)*100;
				
				$share_vta_fash = ($vta_fashion/$vta_total)*100;
				$share_margen_fash = ($marg_fashion/$margen_total)*100;
				$share_vta_dep = ($vta_deportes/$vta_total)*100;
				$share_margen_dep = ($marg_deportes/$margen_total)*100;
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
                	<td class="etiquetas2">Fashion y Deportes</td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_outlet); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_out)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $margenOutlet)." - ".round($margen_per_outlet)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_out)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_out; ?> </td>
                </tr>
                <tr>
                	<td class="etiquetas2">Fashion</td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_fashion); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_fash)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $marg_fashion)." - ".round($margen_per_fashion)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_fash)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_out; ?> </td>
                </tr>
                <tr>
                	<td class="etiquetas2">Deportes</td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $vta_deportes); ?> </td>
                    <td class="etiquetas2"><?php echo round($share_vta_dep)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo money_format('%(#10n', $marg_deportes)." - ".round($margen_per_deportes)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo round($share_margen_dep)."%"; ?> </td>
                    <td class="etiquetas2"><?php echo $cant_out; ?> </td>
                </tr>

                
            </table>
                
<br /><br />




<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table width="1000px" height="100%" cellpadding="5" cellspacing="0" border="1" align="center" bgcolor="#A0E0E0" bordercolor="#FFFFFF">
				<tr>
					<td bgcolor="#000066" class="subtitle">Fecha de Venta</td>
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
 fecha,sum(Facturacion) as Facturacion, sum(cantidad) cantidad, count(distinct(Ordenes)) as Ordenes, 
	count(distinct(compradores)) as Compradores, sum(Margen) as Margen,
	date_sub(fecha, INTERVAL 1 year) anterior
from (
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
		and parent_id not in (7,9,152,157)		 
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




<br>
<br><br>
<a href="http://clickonero.red10.com/admin/TopVentas.php?date=<?php echo $date; ?>" target='_blank'>Top Ventas</a>


</body>
</html>
