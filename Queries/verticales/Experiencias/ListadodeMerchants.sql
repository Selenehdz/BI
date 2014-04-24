Select year(d.start_date),m.id merchantID, m.name, m.url, dc.characteristic,c.title, a.contact_name, a.phone,a.street, mfd.*
from deal d
inner join merchant m on m.id = d.merchant_id
left join deal_characteristics dc on dc.deal_id = d.id
inner join deal_categories dcat on dcat.deal_id = d.id
inner join category c on dcat.category_id = c.id
left join merchant_fiscal_data mfd on mfd.merchant_id = m.id
left join merchant_address ma on ma.merchant_addresses_id = m.id
left join address a on ma.address_id = a.id
where year(d.start_date) = '2013'
and c.title in ('A&B','E&D','otros')
group by m.name
order by d.start_date
limit 150000;


Select -- i.active_start_date, i.active_end_date, i.id DealID, i.name DealName,
m.id MerchantID, m.name, a.contact_name, a.street, a.internal_number,a.city, a.phone,a.phone
from item i
inner join item_category ic on ic.id = i.category_id
left join merchant m on m.id = i.merchant_id
left join merchant_address ma on ma.merchant_addresses_id = m.id
left join address a on ma.address_id = a.id
where ic.parent_id = 9
and year(i.active_start_date) = 2014
group by m.id
limit 99999999;
