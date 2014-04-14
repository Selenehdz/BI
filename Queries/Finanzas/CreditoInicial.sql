
-- CREDITO INICIAL 2014
Select credit, person_id,email,description,date_created,type,abono,Redimido,abono+Redimido as Balance,(abono+Redimido)/16.66 as BalancePesos,expiration_date 
 from (

Select ca.id credit,ca.person_id,email,ca.description,date(ca.date_created)date_created,ca.type,
(Select amount from credit_detail where credit_account_id = credit and amount >0) abono,
Case when 

(Select sum(cd.amount) from credit_detail cd  inner join order_payment op on op.id = document  where cd.credit_account_id = credit and cd.amount < 0 and status = 'PAID'  and date(cd.date_created) < '2014-04-01')
Is null
Then 0
Else 
(Select sum(cd.amount) from credit_detail cd  inner join order_payment op on op.id = document  where cd.credit_account_id = credit and cd.amount < 0 and status = 'PAID'  and date(cd.date_created) < '2014-04-01')
End as 'Redimido',

ca.balance,
(Select amount from credit_detail where credit_account_id = credit and amount >0) /16.66 abono_dinero,
ca.balance/16.66,
date(expiration_date) expiration_date
from credit_account ca 
inner join person p on p.id = ca.person_id
where ca.date_created < '2014-04-01'
and ( date(ca.expiration_date) > '2014-03-31' or ca.expiration_date is null)
)
a
Having Balance > 0
;


