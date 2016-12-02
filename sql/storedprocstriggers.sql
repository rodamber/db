-- a) RI-1: "Não podem existir ofertas com datas sobrepostas"

DROP TRIGGER IF EXISTS verifica_oferta;

Delimiter //

CREATE TRIGGER verifica_oferta BEFORE INSERT ON oferta
FOR EACH ROW
BEGIN
    IF (SELECT COUNT(*)
        FROM oferta O
        WHERE NEW.morada = O.morada
          AND NEW.codigo = O.codigo
          AND ((NEW.data_inicio BETWEEN O.data_inicio AND O.data_fim)
                OR (NEW.data_fim BETWEEN O.data_inicio AND O.data_fim)
                OR (NEW.data_inicio < O.data_inicio AND NEW.data_fim > O.data_fim)
              )
        ) != 0
    THEN CALL error;
    END IF;

END //

Delimiter ;
	

-- b) RI-2: "A data de pagamento de uma reserva paga tem de ser superior ao timestamp do último estado dessa reserva"