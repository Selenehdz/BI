-- Venta de Credito Clickonero QlikView
Select sum(amount) from (
Select amount,order_id from order_payment op
inner join payment_method pm on pm.id = op.payment_method_id
and pm.id = 1
 and status in ( 'PAID', 'FRAUD')
-- and status <> 'CANCELLED'
group by order_id
)a
inner join orders o on o.id = a.order_id
where date(paid_date) between '2014-02-01' AND '2014-02-28'
;


-- credito redimido
Select   redimido.*,o.id,o.order_status,o.paid_date
from (
select  -- sum(cd.amount/16.66),  ca.description,ca.type
 ca.id,ca.person_id, p.email, sum(cd.amount/16.66) amount, ca.description,ca.type,cd.date_created, cd.document,op.status statusPayment, op.order_id
from person p, order_payment op,credit_account ca
left join credit_detail cd on ca.id = cd.credit_account_id
where
p.id = ca.person_id  
and op.id = cd.document
and cd.amount < 0
 and op.status in ('PAID', 'FRAUD')
and date(cd.date_created) between '2013-12-01' AND '2014-03-05' -- Actualizar con fecha actual
group by ca.id,document)redimido
left join orders o on o.id = redimido.order_id
 where date(o.paid_date) between '2014-02-01' AND '2014-02-28'
;


-- Credito abonado

Select -- sum(amount)/16.66, description, type
 ca.id,ca.person_id, p.email, sum(cd.amount/16.66),description,type, ca.date_created, ca.expiration_date
from credit_account ca
inner join credit_detail cd on cd.credit_account_id = ca.id
inner join person p on p.id = ca.person_id
where document is null 
and date(cd.date_created) between '2014-02-01' and '2014-02-28'
 group by ca.id
;


-- Credito expirado
select ca.id,p.id person_id, p.email,sum(balance)/16.66 monto, description, type, ca.date_created, ca.expiration_date
from credit_account ca
inner join person p on p.id = ca.person_id
 where 
 expiration_date between '2014-03-01 00:00:00' AND '2014-03-31 23:59:59'  and
-- person_id = 19136 and 
 balance > 0
group by ca.id
-- limit 10
;
