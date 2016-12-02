-- a) Quais os espaços com postos que nunca foram alugados?

SELECT DISTINCT E.morada, E.codigo
FROM espaco E, posto P
WHERE E.morada = P.morada AND E.codigo = P.codigo_espaco
  AND NOT EXISTS (
    SELECT *
      FROM posto P2, aluga A
      WHERE P2.morada = A.morada AND P2.codigo = A.codigo
        AND P.morada = P2.morada AND P.codigo = P2.codigo);

-- b) Quais edifícios com um número de reservas superior à média?

SELECT DISTINCT morada
FROM aluga
GROUP BY morada
HAVING COUNT(numero) > (
  SELECT AVG(C)
  FROM (
    SELECT COUNT(numero) AS C
    FROM aluga
    GROUP BY morada) AS counts);

-- c) Quais utilizadores cujos alugáveis foram fiscalizados sempre pelo mesmo
-- fiscal?

SELECT DISTINCT A.nif
FROM aluga A, fiscaliza F
WHERE A.morada = F.morada AND A.codigo = F.codigo
GROUP BY A.nif
HAVING COUNT(DISTINCT F.id) = 1;

-- d) Qual o montante total realizado (pago) por cada espaço durante o ano de
-- 2016? Assuma que a tarifa indicada na oferta é diária. Deve considerar os
-- casos em que o espaço foi alugado totalmente ou por postos.

SELECT morada, codigo, SUM(montante)
FROM (
   -- Qual o montante realizado por cada espaco alugado no seu todo em 2016?
   SELECT E.morada, E.codigo,
          -- Temos que ter em conta as datas de fim que acabam para alem de 2016
          -- e as datas de inicio antes de 2016.
          SUM(O.tarifa * DATEDIFF(LEAST(O.data_fim, '2016/12/12'),
                                  GREATEST(O.data_inicio, '2016/01/01'))) AS montante
   FROM espaco E, oferta O, aluga A
   WHERE E.morada = O.morada AND O.morada = A.morada
     AND E.codigo = O.codigo AND O.codigo = A.codigo
   GROUP BY E.morada, E.codigo

   UNION

   -- Qual o montante realizado por cada espaco em alugueres de postos
   -- individuais em 2016?
   SELECT E.morada, E.codigo,
          -- Temos que ter em conta as datas de fim que acabam para alem de 2016
          -- e as datas de inicio antes de 2016.
          SUM(O.tarifa * DATEDIFF(LEAST(O.data_fim, '2016/12/12'),
          GREATEST(O.data_inicio, '2016/01/01'))) AS montante
   FROM espaco E, posto P, oferta O, aluga A
   WHERE E.morada = P.morada AND P.morada = O.morada AND O.morada = A.morada
     AND E.codigo = P.codigo_espaco AND P.codigo = O.codigo AND O.codigo = A.codigo
   GROUP BY E.morada, E.codigo) AS montantes
GROUP BY morada, codigo;

-- e) Quais os espaços de trabalho cujos postos nele contidos foram todos
-- alugados? (Por alugado entende-se um posto de trabalho que tenha pelo menos
-- uma oferta aceite, independentemente das suas datas.)

SELECT E.morada, E.codigo
FROM espaco E, posto P
WHERE E.morada = P.morada AND E.codigo = P.codigo_espaco
GROUP BY E.morada, E.codigo
HAVING COUNT(P.codigo) = (
  SELECT COUNT(P2.codigo)
    FROM posto P2, aluga A
    WHERE P2.morada = A.morada AND P2.codigo = A.codigo
      AND E.morada = P2.morada AND E.codigo = P2.codigo_espaco);
