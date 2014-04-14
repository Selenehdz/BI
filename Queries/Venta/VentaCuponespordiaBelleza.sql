select  date(o.date_created),
		sum(od.cost) Facturacion,
	 	count(distinct(o.id)) Ordenes,
		count(distinct(o.person_id)) compradores,
		sum(od.quantity) cupones
		from campaign c
		inner join item i on i.campaign_id=c.id
		inner join item_category ic on ic.id=i.category_id
		inner join sku s on s.item_id= i.id
		inner join order_detail od on od.sku_id=s.id
		inner join orders o on o.id = od.order_id
		Where
		o.order_status <> 'CANCELLED' and
		ic.parent_id = 152 and
		paid_date is not null and
		date(o.date_created) between '2014-01-01' and '2014-04-14'
		and s.price <> 0
		group by date(o.date_created)