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
    group by morada) as counts);

-- c) Quais utilizadores cujos alugáveis foram fiscalizados sempre pelo mesmo
-- fiscal?

select distinct A.nif
from aluga A, fiscaliza F
where A.morada = F.morada and A.codigo = F.codigo
group by A.nif
having count(distinct F.id) = 1;

-- d) Qual o montante total realizado (pago) por cada espaço durante o ano de
-- 2016? Assuma que a tarifa indicada na oferta é diária. Deve considerar os
-- casos em que o espaço foi alugado totalmente ou por postos.

select morada, codigo, sum(montante)
from (
   -- Qual o montante realizado por cada espaco alugado no seu todo em 2016?
   select E.morada, E.codigo,
          -- Temos que ter em conta as datas de fim que acabam para alem de 2016
          -- e as datas de inicio antes de 2016.
          sum(O.tarifa * datediff(least(O.data_fim, '2016/12/12'),
                                  greatest(O.data_inicio, '2016/01/01'))) as montante
   from espaco E, oferta O, aluga A
   where E.morada = O.morada and O.morada = A.morada
     and E.codigo = O.codigo and O.codigo = A.codigo
   group by E.morada, E.codigo

   union

   -- Qual o montante realizado por cada espaco em alugueres de postos
   -- individuais em 2016?
   select E.morada, E.codigo,
          -- Temos que ter em conta as datas de fim que acabam para alem de 2016
          -- e as datas de inicio antes de 2016.
          sum(O.tarifa * datediff(least(O.data_fim, '2016/12/12'),
          greatest(O.data_inicio, '2016/01/01'))) as montante
   from espaco E, posto P, oferta O, aluga A
   where E.morada = P.morada and P.morada = O.morada and O.morada = A.morada
     and E.codigo = P.codigo_espaco and P.codigo = O.codigo and O.codigo = A.codigo
   group by E.morada, E.codigo) as montantes
group by morada, codigo;

-- e) Quais os espaços de trabalho cujos postos nele contidos foram todos
-- alugados? (Por alugado entende-se um posto de trabalho que tenha pelo menos
-- uma oferta aceite, independentemente das suas datas.)

select morada, codigo
from espaco
where (morada, codigo) not in 
   (select distinct morada, codigo_espaco as codigo
    from posto as p
	where (p.morada, p.codigo) not in 
	   (select morada, codigo
        from posto natural join aluga natural join estado
        where estado.estado = 'Aceite'
	   )
   ) ;