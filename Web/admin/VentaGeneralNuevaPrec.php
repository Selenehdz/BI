<?php

$user="selene";
$password='+gV6)MV?{"T!';
$database="clickonero-live";
$server="184.106.25.121";
setlocale(LC_MONETARY, 'en_US');
$link = mysql_connect($server,$user,$password);
mysql_select_db($database, $link) or die( "Unable to select database");


$semanas = array(
/*array('2012-12-31','2013-01-06','Semana 1'),
array('2013-01-07','2013-01-13','Semana 2'),
array('2013-01-14','2013-01-20','Semana 3'),
array('2013-01-21','2013-01-27','Semana 4'),
array('2013-01-28','2013-02-03','Semana 5'),
array('2013-02-04','2013-02-10','Semana 6'),
array('2013-02-11','2013-02-17','Semana 7'),
array('2013-02-18','2013-02-24','Semana 8'),
array('2013-02-25','2013-03-03','Semana 9'),
array('2013-03-04','2013-03-10','Semana 10'),
array('2013-03-11','2013-03-17','Semana 11'),
array('2013-03-18','2013-03-24','Semana 12'),
array('2013-03-25','2013-03-31','Semana 13'),
array('2013-04-01','2013-04-07','Semana 14'),
array('2013-04-08','2013-04-14','Semana 15'),
array('2013-04-15','2013-04-21','Semana 16'),
array('2013-04-22','2013-04-28','Semana 17'),
array('2013-04-29','2013-05-05','Semana 18'),
array('2013-05-06','2013-05-12','Semana 19'),
array('2013-05-13','2013-05-19','Semana 20'),
array('2013-05-20','2013-05-26','Semana 21'),
array('2013-05-27','2013-06-02','Semana 22'),
array('2013-06-03','2013-06-09','Semana 23'),
array('2013-06-10','2013-06-16','Semana 24'),
array('2013-06-17','2013-06-23','Semana 25'),
array('2013-06-24','2013-06-30','Semana 26'),
array('2013-07-01','2013-07-07','Semana 27'),
array('2013-07-08','2013-07-14','Semana 28'),
array('2013-07-15','2013-07-21','Semana 29'),
array('2013-07-22','2013-07-28','Semana 30'),
array('2013-07-29','2013-08-04','Semana 31'),
array('2013-08-05','2013-08-11','Semana 32'),
array('2013-08-12','2013-08-18','Semana 33'),
array('2013-08-19','2013-08-25','Semana 34'),
array('2013-08-26','2013-09-01','Semana 35'),
array('2013-09-02','2013-09-08','Semana 36'),
array('2013-09-09','2013-09-15','Semana 37'),
array('2013-09-16','2013-09-22','Semana 38'),
array('2013-09-23','2013-09-29','Semana 39'),
array('2013-09-30','2013-10-06','Semana 40'),
array('2013-10-07','2013-10-13','Semana 41'),
array('2013-10-14','2013-10-20','Semana 42'),
array('2013-10-21','2013-10-27','Semana 43'),
array('2013-10-28','2013-11-03','Semana 44'),
array('2013-11-04','2013-11-10','Semana 45'),
array('2013-11-11','2013-11-17','Semana 46'),
array('2013-11-18','2013-11-24','Semana 47'),*/
array('2013-12-30','2014-01-05','Semana 1'),
array('2014-01-06','2014-01-12','Semana 2'),
array('2014-01-13','2014-01-19','Semana 3'),
array('2014-01-20','2014-01-26','Semana 4'),
array('2014-01-27','2014-02-02','Semana 5'),
array('2014-02-03','2014-02-09','Semana 6'),
array('2014-02-10','2014-02-16','Semana 7'),
array('2014-02-17','2014-02-23','Semana 8'),
array('2014-02-24','2014-03-02','Semana 9'),
array('2014-03-03','2014-03-09','Semana 10'),
array('2014-03-10','2014-03-16','Semana 11'),
array('2014-03-17','2014-03-23','Semana 12'),
array('2014-03-24','2014-03-30','Semana 13'),
array('2014-03-31','2014-04-06','Semana 14'),/*
array('2012-12-31','2013-02-03','Enero'),
array('2013-02-04','2013-03-03','Febrero'),
array('2013-03-04','2013-03-31','Marzo'),
array('2013-04-01','2013-05-05','Abril'),
array('2013-05-06','2013-06-02','Mayo'),
array('2013-06-03','2013-06-30','junio'),
array('2013-07-01','2013-08-04','Julio'),
array('2013-08-05','2013-09-01','Agosto'),
array('2013-09-02','2013-09-29','Septiembre'),
array('2013-09-30','2013-11-03','Octubre'),
array('2013-11-04','2013-12-01','Noviembre'),
array('2013-12-02','2013-12-29','Diciembre'),
array('2013-12-30','2014-02-02','Enero14'),
array('2014-02-03','2014-03-02','Febrero14'),
array('2014-03-03','2014-03-30','Marzo14'),
array('2014-03-01','2014-03-31','Marzo14Natural'),
array('2014-03-31','2014-05-04','Abril14'),*/
/*
array('2012-12-31','2013-03-31','Trimestre 1'),
array('2013-04-01','2013-06-30','Trimestre 2'),
array('2013-07-01','2013-09-29','Trimestre 3'),
array('2013-09-02','2013-12-01','Trimestre sep-oct-nov'),
array('2013-09-30','2013-12-29','Trimestre 4'),
array('2012-12-31','2013-12-29','Total'),
array('2013-10-01','2013-10-30','oct'),
array('2013-11-01','2013-11-31','nov'),
array('2013-12-01','2013-12-16','dic 16'),
array('2013-10-01','2013-11-31','oct-nov'),
array('2013-12-30','2014-03-30','Trimestre 1-14'),
array('2012-12-31','2013-06-30','Semestre 1'),
array('2013-07-01','2013-12-29','Semestre 2'),*/
array('2013-12-30','2014-06-29','Semestre 1-14')
); 

$categorias = array(7,9,152,157);

echo 'Compradores Totales Generales ';

	echo '<table border=1> ';
	
	echo '<thead>';
	echo '<tr><td colspan="3">Total Generales</td></tr>';
	echo '</thead>';
	echo '<tbody>';
	echo '<tr><td>Fecha</td><td>Facturacion</td><td>Compradores</td><td>Ordenes</td></tr>';
	foreach($semanas as $semana){ 

		$mysql = "
		select  sum(od.cost) Facturacion,
		count(distinct(o.id)) Ordenes,
		count(distinct(o.person_id)) compradores
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		Where
		o.order_status <> 'CANCELLED' and
		date (o.paid_date) between '".$semana[0]."' and '".$semana[1]."'
		and s.price <> 0
		";
		
		$result_qry=mysql_query($mysql, $link);
		$rs=mysql_fetch_array($result_qry);
		
		
		echo '<tr>';
		echo '<td>'.$semana[2].'</td>';
		echo '<td>'.$rs['Facturacion'].'</td><td>'.$rs['compradores'].'</td><td>'.$rs['Ordenes']."</td>";
		echo '</tr>';
	


}
	echo '</tbody>';
	echo '</table>';
	echo '<br><br>---------------------------------<br><br>';

mysql_close();