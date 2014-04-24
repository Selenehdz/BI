
-- Autor: Selene
-- Fecha: 22/04/2014
-- Base: produccion
-- Consulta de free shipping por hora ó dia
-- condiciones: Fecha de compra de creacion y que el pago no sea nulo ni el status este cancelado, solo para ordenes de outlet

Select hour(o.date_created) hora, count(distinct(o.id)) ordenes,count(distinct(person_id)) compradores,
sum(od.quantity) cantidad,
sum(od.cost) facturacion,
sum(od.cost-(od.quantity*s.cost)) Margen
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
group by date(o.date_created);