SELECT
    SUM(FR.montante_pago) / COUNT(FR.montante_pago) AS Media,
    DL.localid                                      AS Localizacao,
    DT.dataid                                       AS Data
FROM
    f_reserva AS FR,
    d_local   AS DL,
    d_data    AS DT
WHERE
    FR.localid = DL.localid AND
    FR.dataid  = DT.dataid
GROUP BY
    DL.localid, DT.dataid WITH ROLLUP;
