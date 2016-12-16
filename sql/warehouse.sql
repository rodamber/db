drop table if exists f_reserva;
drop table if exists d_user;
drop table if exists d_local;
drop table if exists d_tempo;
drop table if exists d_data;

/*Data warehouse*/

/*Criacao das tabela f_reserva, d_user, d_local, d_tempo, d_data*/
CREATE TABLE IF NOT EXISTS d_user(
	userid INT NOT NULL AUTO_INCREMENT,
	nif VARCHAR(9) NOT NULL,
	nome VARCHAR(80) NOT NULL,
	telefone VARCHAR(26) NOT NULL,
	PRIMARY KEY (userid));

CREATE TABLE IF NOT EXISTS d_local(
	localid INT NOT NULL AUTO_INCREMENT,
	codigo_posto VARCHAR(255) NOT NULL,
	codigo_espaco VARCHAR(255) NOT NULL,
	morada VARCHAR(255) NOT NULL,
	PRIMARY KEY (localid));

CREATE TABLE IF NOT EXISTS d_tempo(
	tempoid INT NOT NULL,
	minutos INT NOT NULL,
	horas INT NOT NULL,
	PRIMARY KEY (tempoid));

CREATE TABLE IF NOT EXISTS d_data(
	dataid INT NOT NULL,
	dia INT NOT NULL,
	semana INT NOT NULL,
	mes_numero INT NOT NULL,
	semestre INT NOT NULL,
	ano INT NOT NULL,
	PRIMARY KEY (dataid));
	
CREATE TABLE IF NOT EXISTS f_reserva (
	userid INT NOT NULL,
	localid INT NOT NULL,
	tempoid INT NOT NULL,
	dataid INT NOT NULL,
	PRIMARY KEY (userid,localid,tempoid,dataid),
	FOREIGN KEY (userid) REFERENCES d_user (userid) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (localid) REFERENCES d_local (localid) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (tempoid) REFERENCES d_tempo (tempoid) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (dataid) REFERENCES d_data (dataid) ON DELETE CASCADE ON UPDATE CASCADE);


/*Populate das tabelas estaticas d_tempo e d_data*/

delimiter //

CREATE PROCEDURE load_tempo_dim()
BEGIN
    DECLARE v_full_time DATETIME;
    SET v_full_time = '2016-01-01 00:00:00';
    WHILE v_full_time < '2016-01-02 00:00:00' DO
    INSERT INTO d_tempo(
        tempoid,
        horas,
        minutos
        ) VALUES (
            HOUR(v_full_time) * 100 + MINUTE(v_full_time),
            HOUR(v_full_time),
            MINUTE(v_full_time)
        );
        SET v_full_time = DATE_ADD(v_full_time, INTERVAL 1 MINUTE);
    END WHILE;
END;
//

CREATE PROCEDURE load_data_dim()
BEGIN
    DECLARE v_full_date DATETIME;
    SET v_full_date = '2016-01-01 00:00:00';
    WHILE v_full_date < '2018-01-01 00:00:00' DO
    INSERT INTO d_data(
        dataid,
        ano,
        semestre,
        mes_numero,
        semana,
        dia
        ) VALUES (
            YEAR(v_full_date) * 10000 + MONTH(v_full_date)*100 + DAY(v_full_date),
            YEAR(v_full_date),
            IF(MONTH(v_full_date) < 7, 1, 2),
            MONTH(v_full_date),
            WEEK(v_full_date),
            DAY(v_full_date)
        );
        SET v_full_date = DATE_ADD(v_full_date, INTERVAL 1 DAY);
    END WHILE;
END;
//

delimiter ;

CALL load_tempo_dim;
CALL load_data_dim;