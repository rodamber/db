SELECT A.nif
FROM arrenda A
INNER JOIN fiscaliza F
ON A.morada = F.morada
AND A.codigo = F.codigo
GROUP BY A.nif
HAVING COUNT(DISTINCT F.id) = 1;

CREATE UNIQUE INDEX arrenda_index ON arrenda (morada, codigo, nif);

SELECT DISTINCT P.morada, P.codigo_espaco
FROM posto P
WHERE (P.morada, P.codigo_espaco) NOT IN (
SELECT P.morada, P.codigo_espaco
FROM posto P
NATURAL JOIN aluga A
NATURAL JOIN estado E
WHERE E.estado = 'aceite');

CREATE INDEX estado_index ON estado (numero,estado);

