-- a) Quais os espaços com postos que nunca foram alugados?

select distinct E.morada, E.codigo
from espaco E, posto P left join aluga A
on P.morada = A.morada and P.codigo = A.codigo
where E.codigo = P.codigo_espaco and A.morada is null;

-- b) Quais edifícios com um número de reservas superior à média?

select distinct morada
from aluga
group by morada
having count(numero) > (
	select avg(C)
	from (
		select count(numero) as C
		from aluga
		group by morada
	) as counts
);

-- c) Quais utilizadores cujos alugáveis foram fiscalizados sempre pelo mesmo fiscal?

select distinct A.nif
from aluga A, fiscaliza F
where A.morada = F.morada and A.codigo = F.codigo
group by A.nif
having count(distinct F.id) = 1;

-- d) Qual o montante total realizado (pago) por cada espaço durante o ano de 2016?
-- Assuma que a tarifa indicada na oferta é diária. Deve considerar os casos em que o
-- espaço foi alugado totalmente ou por postos.

-- e) Quais os espaços de trabalho cujos postos nele contidos foram todos alugados? (Por
-- alugado entende-se um posto de trabalho que tenha pelo menos uma oferta aceite,
-- independentemente das suas datas.)
