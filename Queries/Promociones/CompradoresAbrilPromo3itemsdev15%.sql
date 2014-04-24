-- Autor: Selene
-- Fecha: 07/04/2014
-- Base: QA de preferencia
-- Consulta: Compradores de Abril con items a partir de 3 para devolverles el 15%
-- condicion: No plica para usuarios beneficiados en free shipping, ni usuarios de devolucion escalonada, ni usuarios de 3x2


Select PersonId, email, count(orden) ordenes,sum(gastoOrden), sum(shipping_total), sum(items_prod),
sum(Gasto_Viajes),sum(Gasto_Experiencias),sum(Gasto_Belleza),sum(Gasto_Outlet),
sum(Margen_Viajes),sum(Margen_Experiencias),sum(Margen_Belleza),sum(Margen_Outlet)
 from (
Select p.id PersonID, p.email, o.id orden, sum(od.cost)gastoOrden, shipping_total,sum(od.quantity)items_prod, o.date_created, o.paid_date,		
(
		Select sum(od.cost) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 7
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Gasto_Viajes',
		(
		Select sum(od.cost) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 9
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Gasto_Experiencias',
		(
		Select sum(od.cost) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 152
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Gasto_Belleza',
		(
		Select sum(od.cost) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id not in ( 7,9,152)
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Gasto_Outlet',

	(
		Select Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join store_service ss on i.id=ss.id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 7
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Margen_Viajes',
(
		Select Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join store_service ss on i.id=ss.id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 9
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Margen_Experiencias',
(
		Select Case 
			when kind = 'TRADITIONAL' 
			then sum(od.cost-(od.quantity*s.cost))
			when kind = 'PREPAID' 
			then sum(od.quantity*price_by_kind)
			END as 'Margen'
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join store_service ss on i.id=ss.id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id = 152
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Margen_Belleza',
		(
		Select sum(od.cost-(od.quantity*s.cost)) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and parent_id not in ( 7,9,152)
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Margen_Outlet'
from orders o
inner join order_detail od on od.order_id = o.id
inner join person p on o.person_id = p.id
where o.paid_date is not null
and o.date_created between '2014-04-01 00:00:00' and '2014-04-22 14:00:00'
and o.order_status = 'PAID'
and od.status = 'AVAILABLE'
and o.person_id not in ( 2536461)
and o.id not in (621845,621576,621927,621579,621792,621793,621919,621620,622040	,621825	,621720	,621967	,621755	,621591	,622051	,621682	,621613	,621806	,621794	,621780	,621807	,621899	,621949	,621666	,621572	,621574	,621909	,621747	) -- Devolucion escalonada
and o.id not in (622290,622488,622186	,622385	,622555	,622067	,622492	,622558	,622069	,622314	,622496	,622560	,622403	,622499	,622406	,622564	,622322	,622407	,622323	,622419	,622570	,622510	,622219	,
622423	,622571	,622220	,622572	,622327	,622102	,622428	,622334	,622116	,622517	,622443	,622461	,622124	,622249	,622130	,622467	,622539	,622266	,622472	,622268	,622370	,622474	,622371	,
622280	,622372	,622281	,622477	,622177	,622550	,622283	,622485	,622076	,622565	,622434	,622236	,622159	,622543	,622179	,622380	,622292	,622508	,622328	,622117	,622146	,622169	,622545	,
622549	,622071	) -- ordenes 3 x2
and o.id not in (
Select o.id
from orders o
inner join order_detail od on od.order_id = o.id
inner join sku s on s.id = od.sku_id
inner join item i on i.id = s.item_id
inner join item_category ic on ic.id = i.category_id 
where parent_id not in (7,9,152)
and shipping_total = 0
and order_status <> 'CANCELLED'
and paid_date is not null
and date(o.date_created) between '2014-04-10' and '2014-04-20'
group by o.id
)

group by o.id
) a
group by PersonId
having ordenes >2
;
