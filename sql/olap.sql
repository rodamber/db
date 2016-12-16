SELECT
    SUM(FR.montante_pago) / COUNT(FR.montante_pago) AS Media,
    DL.localid AS Localizacao,
    DT.tempoid AS Data
FROM
    f_reserva AS FR,
    d_local   AS DL,
    d_tempo   AS DT
WHERE
    FR.localid = DL.localid AND
    FR.tempoid = DT.tempoid
GROUP BY
    DL.localid, DT.tempoid WITH ROLLUP;
