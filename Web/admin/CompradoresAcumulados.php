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
	color:#FFFFFF;
	font-family:Georgia, "Times New Roman", Times, serif;
	font-size:11px;
	background-color:#599653;
}


</style>


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
$today = date("Y-m-d");


$link = mysql_connect($server,$user,$password);
mysql_select_db($database, $link) or die( "Unable to select database");





$semanas = array(
array('367'   ,'500'   ,'2014-04-01'),
array('733'   ,'1000' ,'2014-04-02'),
array('1100' ,'1500' ,'2014-04-03'),
array('1467' ,'2000' ,'2014-04-04'),
array('1833' ,'3500' ,'2014-04-05'),
array('2200' ,'3000' ,'2014-04-06'),
array('2567' ,'3500' ,'2014-04-07'),
array('2933' ,'4000' ,'2014-04-08'),
array('3300' ,'4500' ,'2014-04-09'),
array('3667' ,'5000' ,'2014-04-10'),
array('4033' ,'5500' ,'2014-04-11'),
array('4400' ,'6000' ,'2014-04-12'),
array('4767' ,'6500' ,'2014-04-13'),
array('5133' ,'7000' ,'2014-04-14'),
array('5500' ,'7500' ,'2014-04-15'),
array('5867' ,'8000' ,'2014-04-16'),
array('6233' ,'8500' ,'2014-04-17'),
array('6600' ,'9000' ,'2014-04-18'),
array('6967' ,'9500' ,'2014-04-19'),
array('7333' ,'10000' ,'2014-04-20'),
array('7700' ,'10500' ,'2014-04-21'),
array('8067' ,'11000' ,'2014-04-22'),
array('8433' ,'11500' ,'2014-04-23'),
array('8800' ,'12000' ,'2014-04-24'),
array('9167' ,'12500' ,'2014-04-25'),
array('9533' ,'13000' ,'2014-04-26'),
array('9900' ,'13500' ,'2014-04-27'),
array('10267' ,'14000' ,'2014-04-28'),
array('10633' ,'14500' ,'2014-04-29'),
array('11000' ,'15000' ,'2014-04-30')
); 

$categorias = array(7,9,152,157);

$mysql = "
		select 
		count(distinct(o.person_id)) Compradores,
		count(distinct(o.id)) Ordenes
		from orders o
		inner join order_detail od on od.order_id = o.id
		where
		o.paid_date is not null
		and o.order_status <> 'CANCELLED'
		and date(o.paid_date) between '2014-04-01' and '".$today."'
		";
		$result_qry=mysql_query($mysql, $link);
		$rs=mysql_fetch_array($result_qry);



echo 'Compradores Acumulados ';
echo "<br> Consulta: ".date("Y-m-d");
echo "<br>";
echo 'Compradores Acumulados financieros: '.number_format($rs['Compradores']);
echo "<br>";
echo 'Ordenes Acumuladas financieros: '.number_format($rs['Ordenes']);

	echo '<table border=1> ';
	
	echo '<thead>';
	echo '<tr><td colspan="6"><center>Compradores Acumulados Venta Diaria</center></td></tr>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr><td class="etiquetas4" >Fecha</td><td class="etiquetas4">Meta 1</td><td class="etiquetas4">Meta 2</td><td class="etiquetas4">Compradores</td><td class="etiquetas4">Meta 1%</td><td class="etiquetas4">Meta 2%</td></tr>';
	foreach($semanas as $semana){ 

		$mysql = "
		select 
		count(distinct(o.person_id)) Compradores
		from orders o
		inner join order_detail od on od.order_id = o.id
		where
		o.order_status = 'PAID'
		and od.status ='AVAILABLE'		 
		and date(o.date_created) between '2014-04-01' and '".$semana[2]."'

		";
		
		$result_qry=mysql_query($mysql, $link);
		$rs=mysql_fetch_array($result_qry);
		
		
		$PerMeta1 = (($rs['Compradores']/$semana[0])-1)*100;
		$PerMeta2 = (($rs['Compradores']/$semana[1])-1)*100;
		
		$class="etiquetas2";
		if($semana[2]== $today)
					{
						$class="etiquetas3";
					}
		
		echo '<tr>';
		echo '<td class='.$class.'><center>'.$semana[2].'</center></td>';
		echo '<td class='.$class.'><center>'.number_format($semana[0]).'</center></td>';
		echo '<td class='.$class.'><center>'.number_format($semana[1]).'</center></td>';
		echo '<td class='.$class.'><center>'.number_format($rs['Compradores']).'</center></td><td class='.$class.'>'.round($PerMeta1).'%'.'</td><td class='.$class.'>'.round($PerMeta2).'%'.'</td>'."</td>";
		//number_format(number,decimals,decimalpoint,separator) 
		echo '</tr>';
	


}
	echo '</tbody>';
	echo '</table>';
	echo '<br><br>---------------------------------<br><br>';

mysql_close();