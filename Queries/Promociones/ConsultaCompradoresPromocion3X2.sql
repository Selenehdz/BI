-- Autor: Selene
-- Fecha: 07/04/2014
-- Base: produccion
-- Promocion: 3X2 Compra 3 items de cualquier vertical y se devuelve el más barato (no aplica viajes)
-- Consulta: Todas las compras que tengan a partir de 3 items pagados 

Select p.id PersonID, p.email, o.id orden, sum(od.cost)gastoOrden, shipping_total,sum(od.quantity)items_prod, o.date_created, o.paid_date,
(
		Select min(s.price) 
		from orders o 
		inner join order_detail od on od.order_id = o.id
		inner join sku s on s.id = od.sku_id
		inner join item i on i.id = s.item_id
		inner join item_category ic on ic.id = i.category_id
		where o.id = orden
		and o.order_status = 'PAID'
		and od.status = 'AVAILABLE'
		) as 'Item_menor_prec',		
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
and date(o.date_created) = '2014-04-18' 
and o.order_status = 'PAID'
and od.status = 'AVAILABLE'
group by o.id
having items_prod > 2
order by items_prod
;
