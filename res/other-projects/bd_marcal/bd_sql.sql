/* Query A*/
select distinct U.userid
from utilizador U, login L
where(select count(sucesso) 
	  from login L
	  where sucesso = 0 and U.userid = L.userid) > 
	 (select count(sucesso) 
	  from login L
	  where sucesso = 1 and U.userid = L.userid) 
order by userid; 

/*Query B*/
SELECT u_rp_nbp.userid, u_rp_nbp.regid
FROM
  (SELECT u.userid, rp.regid, COUNT(rp.pageid) AS nbp
   FROM utilizador u, reg_pag rp,
        pagina p,
        registo r,
        tipo_registo tr
   WHERE u.userid      = rp.userid
     AND u.userid      = p.userid
     AND u.userid      = r.userid
     AND u.userid      = tr.userid
     AND rp.pageid     = p.pagecounter
     AND r.typecounter = tr.typecnt
     AND tr.typecnt    = rp.typeid
     AND rp.regid      = r.regcounter
     AND rp.typeid     = r.typecounter
     AND r.ativo       = 1
     AND rp.ativa      = 1
     AND p.ativa       = 1
     AND tr.ativo      = 1
   GROUP BY(rp.regid)
   ORDER BY u.userid) AS u_rp_nbp,
  (SELECT u.userid, COUNT(p.pagecounter) AS nbp
   FROM utilizador u, pagina p
   WHERE u.userid = p.userid
     AND p.ativa  = 1
   GROUP BY(u.userid)) AS u_nbp
WHERE u_nbp.userid = u_rp_nbp.userid
 AND u_nbp.nbp    = u_rp_nbp.nbp;

/*Query C*/
SELECT u_rpp.userid
FROM
  (SELECT u_nbpag.userid,
          u_nbreg.nb_reg/u_nbpag.nb_pag AS reg_p_pag 
   FROM
     (SELECT u.userid, COUNT(p.pagecounter) AS nb_pag
      FROM utilizador u,
           pagina p
      WHERE u.userid = p.userid
        AND p.ativa  = 1
      GROUP BY(u.userid)) AS u_nbpag,
     (SELECT u.userid, COUNT(r.regcounter) AS nb_reg
      FROM utilizador u,
           registo r,
           tipo_registo tr
      WHERE r.userid   = u.userid
        AND tr.userid  = u.userid
        AND tr.typecnt = r.typecounter
        AND r.ativo    = 1
        AND tr.ativo   = 1
      GROUP BY u.userid) AS u_nbreg
   WHERE u_nbpag.userid = u_nbreg.userid
   GROUP BY u_nbpag.userid) AS u_rpp
WHERE u_rpp.reg_p_pag =
    (SELECT MAX(u_rpp.reg_p_pag)
     FROM
       (SELECT u_nbpag.userid,
               u_nbreg.nb_reg/u_nbpag.nb_pag AS reg_p_pag 
        FROM
          (SELECT u.userid,
                  COUNT(p.pagecounter) AS nb_pag
           FROM utilizador u,
                pagina p
           WHERE u.userid = p.userid
             AND p.ativa  = 1
           GROUP BY(u.userid)) AS u_nbpag,
          (SELECT u.userid, COUNT(r.regcounter) AS nb_reg
           FROM utilizador u,
                registo r,
                tipo_registo tr
           WHERE r.userid   = u.userid
             AND tr.userid  = u.userid
             AND tr.typecnt = r.typecounter
             AND r.ativo    = 1
             AND tr.ativo   = 1
           GROUP BY u.userid) AS u_nbreg
        WHERE u_nbpag.userid = u_nbreg.userid
        GROUP BY u_nbpag.userid) AS u_rpp);

/*Query D*/
SELECT u_nbpg.userid 
FROM
/*Query com utilizador e numero total de paginas que tenham todos os tipos de reg do utilizador em si*/
  (SELECT u_p_nbtr.userid, COUNT(u_p_nbtr.pagecounter) AS nbpg 
   FROM
	/*Query com utilizador paginas e quantos tipos de registo 
	essa pagina tem*/
     (SELECT u.userid, p.pagecounter, COUNT(DISTINCT rp.typeid) AS nbtr
      FROM reg_pag rp, utilizador u, pagina p, registo r, tipo_registo tr
      WHERE rp.userid = u.userid
        AND p.userid = u.userid
        AND r.userid = u.userid
        AND tr.userid = u.userid
        AND tr.typecnt = r.typecounter
        AND tr.typecnt = rp.typeid
        AND rp.pageid = p.pagecounter
        AND rp.regid = r.regcounter
        AND rp.typeid = r.typecounter
        AND r.ativo = 1
        AND tr.ativo = 1
        AND p.ativa = 1
        AND rp.ativa = 1
      GROUP BY(p.pagecounter)
      ORDER BY u.userid) AS u_p_nbtr, 
	/*Query com utilizador e quantos tipos de registo ele tem*/
     (SELECT u.userid, COUNT(t.typecnt) AS nbtr
      FROM utilizador u, tipo_registo t
      WHERE u.userid = t.userid
        AND t.ativo = 1
      GROUP BY (u.userid)) AS u_nbtr
   WHERE u_p_nbtr.userid = u_nbtr.userid
     AND u_p_nbtr.nbtr = u_nbtr.nbtr
   GROUP BY (u_p_nbtr.userid)) AS u_nbpg_tr,
  /*Query com utilizador e o nr total de paginas de um utilizador*/
  (SELECT u.userid,
          COUNT(p.pagecounter) AS nbpg
   FROM utilizador u,
        pagina p
   WHERE u.userid = p.userid
     AND p.ativa = 1
   GROUP BY(u.userid)) AS u_nbpg
WHERE u_nbpg.userid = u_nbpg_tr.userid
  AND u_nbpg.nbpg = u_nbpg_tr.nbpg;

/*RI*/
/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
tipo_registo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_tr BEFORE INSERT ON tipo_registo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
tipo_registo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_tr BEFORE UPDATE ON tipo_registo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
registo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_r BEFORE INSERT ON registo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
registo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_r BEFORE UPDATE ON registo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
pagina e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_p BEFORE INSERT ON pagina
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
pagina e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_p BEFORE UPDATE ON pagina
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
campo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_c BEFORE INSERT ON campo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
campo e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_c BEFORE UPDATE ON campo
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
valor e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_v BEFORE INSERT ON valor
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
valor e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_v BEFORE UPDATE ON valor
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos inserir na tabela
reg_pag e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_i_rp BEFORE INSERT ON reg_pag
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger verifica em todas as tabelas que contem um idseq se existe algum idseq igual ao do campo idseq do registo que vamos actualizar na tabela
reg_pag e caso haja chama uma funcao que nao existe de forma a anular a transacao
RI: Todo o valor de contador sequência existente na relação sequência existe numa e uma vez no universo das relações tipo registo, pagina, campo, registo e valor.*/
DELIMITER //
CREATE TRIGGER contador_sequencia_duplicado_u_rp BEFORE UPDATE ON reg_pag
FOR EACH ROW BEGIN IF
  (SELECT COUNT(*) FROM registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM tipo_registo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM pagina WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM campo WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM valor WHERE new.idseq = idseq) > 0 OR
  (SELECT COUNT(*) FROM reg_pag WHERE new.idseq = idseq) > 0 THEN 
	CALL ContadorDeSequenciaDuplicado; 
   END IF;
 END// 
DELIMITER ;

/*Indices*/

/*a)*/
/*Media do numero de registos por pagina de um utilizador*/
SELECT u_nbpag.userid, u_nbreg.nb_reg/u_nbpag.nb_pag AS reg_p_pag
       FROM (SELECT u.userid, COUNT(p.pagecounter) AS nb_pag
             FROM utilizador u, pagina p
             WHERE u.userid = p.userid
             AND p.ativa = 1
             GROUP BY(u.userid)) AS u_nbpag,
             (SELECT u.userid, COUNT(r.regcounter) AS nb_reg
              FROM  utilizador u, registo r, tipo_registo tr
              WHERE r.userid  = u.userid
              AND   tr.userid = u.userid
              AND   tr.typecnt = r.typecounter
              AND   r.ativo   = 1
              AND   tr.ativo  = 1
              GROUP BY u.userid) AS u_nbreg
       WHERE u_nbpag.userid = u_nbreg.userid
       GROUP BY u_nbpag.userid;

/*b)*/
/*Ver o nome dos registos associados a pagina de um utilizador*/
SELECT u_rp_nbp.userid, u_rp_nbp.nome
FROM
  (SELECT u.userid, r.nome, COUNT(rp.pageid) AS nbp
   FROM utilizador u,
        reg_pag rp,
        pagina p,
        registo r,
        tipo_registo tr
   WHERE u.userid = rp.userid
     AND u.userid = p.userid
     AND u.userid = r.userid
     AND u.userid = tr.userid
     AND rp.pageid = p.pagecounter
     AND r.typecounter = tr.typecnt
     AND tr.typecnt = rp.typeid
     AND rp.regid = r.regcounter
     AND rp.typeid = r.typecounter
     AND r.ativo = 1
     AND rp.ativa = 1
     AND p.ativa = 1
     AND tr.ativo = 1
   GROUP BY(r.nome)
   ORDER BY u.userid) AS u_rp_nbp,

  (SELECT u.userid, COUNT(p.pagecounter) AS nbp
   FROM utilizador u,
        pagina p
   WHERE u.userid = p.userid
     AND p.ativa = 1
   GROUP BY(u.userid)) AS u_nbp
WHERE u_nbp.userid = u_rp_nbp.userid
  AND u_nbp.nbp = u_rp_nbp.nbp;

/*Data warehouse*/
/*Criacao das tabelas d_utilizador e d_tempo*/
CREATE TABLE IF NOT EXISTS d_utilizador(
duserid INT NOT NULL AUTO_INCREMENT,
userid INT NOT NULL,
email VARCHAR(255) NOT NULL,
nome VARCHAR(255) NOT NULL,
pais VARCHAR(45) NOT NULL,
categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (duserid));

CREATE TABLE IF NOT EXISTS d_tempo(
timeid INT NOT NULL AUTO_INCREMENT,
dia INT NOT NULL,
mes INT NOT NULL,
ano INT NOT NULL,
horas INT NOT NULL,
minutos INT NOT NULL,
segundos INT NOT NULL,
PRIMARY KEY (timeid));

/*Inserts nas tabelas d_utiliadores e d_tempo*/
INSERT INTO d_utilizador (userid, email, nome, pais, categoria)
SELECT userid, email, nome, pais, categoria
FROM utilizador;

INSERT INTO d_tempo (dia, mes, ano, horas, minutos, segundos)
SELECT DISTINCT DATE_FORMAT(moment, '%d') dia, 
DATE_FORMAT(moment, '%m') AS mes, 
DATE_FORMAT(moment, '%Y') AS ano,  
DATE_FORMAT(moment, '%H') AS horas, 
DATE_FORMAT(moment, '%i') AS minutos, 
DATE_FORMAT(moment, '%s') AS segundos  FROM login;

/*Criacao da tabela f_login */
CREATE TABLE IF NOT EXISTS f_login (
duserid INT NOT NULL,
timeid INT NOT NULL,
sucesso TINYINT(1) NOT NULL,
FOREIGN KEY (duserid) REFERENCES d_utilizador (duserid) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (timeid) REFERENCES d_tempo (timeid) ON DELETE CASCADE ON UPDATE CASCADE);

/*Insert na tabela f_login*/
INSERT INTO f_login (duserid, timeid, sucesso)
SELECT du.duserid, dt.timeid, l.sucesso
FROM login l, d_tempo dt, d_utilizador du
WHERE dt.dia      = DATE_FORMAT(l.moment, '%d')
AND   dt.mes      = DATE_FORMAT(l.moment, '%m')
AND   dt.ano      = DATE_FORMAT(l.moment, '%Y')
AND   dt.horas    = DATE_FORMAT(l.moment, '%H')
AND   dt.minutos  = DATE_FORMAT(l.moment, '%i')
AND   dt.segundos = DATE_FORMAT(l.moment, '%s')
AND   du.userid   = l.userid;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger assegura que quando uma entrada da tabela utilizador sofre um update em campos
presentes na tebela d_utilizador é criada uma nova entrada na tabela d_utilizador com os campos 
actualizados.
RI: Consistencia da base de dados OLAP*/
delimiter //
CREATE TRIGGER du_update BEFORE UPDATE ON utilizador
FOR EACH ROW
BEGIN
     IF new.email != (SELECT u.email FROM utilizador u WHERE u.userid = new.userid) 
     OR new.nome  != (SELECT u.nome  FROM utilizador u WHERE u.userid = new.userid)
     OR new.pais != (SELECT u.pais FROM utilizador u WHERE u.userid = new.userid) 
     OR new.categoria != (SELECT u.categoria FROM utilizador u WHERE u.userid = new.userid) THEN
      INSERT INTO d_utilizador(userid, email, nome, pais, categoria) VALUES
      (new.userid, new.email, new.nome, new.pais, new.categoria);
     END IF;
END//
delimiter ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger assegura que quando é criada uma nova entrada na tabela utilizador 
é criada uma nova entrada na tabela d_utilizador.
RI: Consistencia da base de dados OLAP*/
delimiter //
CREATE TRIGGER du_insert AFTER INSERT ON utilizador
FOR EACH ROW
BEGIN
      INSERT INTO d_utilizador(userid, email, nome, pais, categoria) VALUES
      (new.userid, new.email, new.nome, new.pais, new.categoria);
END//
delimiter ;

/*Campos: Instituto Superior Tecnico Alameda
Disciplina: Bases de Dados 15/16
Data: 10/12/2015
Grupo: 26
Elementos do Grupo: Joao Marcal Nº78471, Joao Alves Nº79155, Jose Semedo Nº78294
Trigger desenvolvido por: Joao Marcal Nº78471
Este trigger assegura que quando é criada uma nova entrada na tabela loging, e criada na tabela
d_tempo uma nova entrada com o moment desse login e tambem e criada uma nova entrada na tabela factual.
RI: Consistencia da base de dados OLAP*/
delimiter //
CREATE TRIGGER f_insert AFTER INSERT ON login
FOR EACH ROW
BEGIN
      INSERT INTO d_tempo (dia, mes, ano, horas, minutos, segundos) VALUES
      (DATE_FORMAT(new.moment, '%d'), DATE_FORMAT(new.moment, '%m'), DATE_FORMAT(new.moment, '%Y'), 
       DATE_FORMAT(new.moment, '%H'), DATE_FORMAT(new.moment, '%i'), DATE_FORMAT(new.moment, '%s'));

      INSERT INTO f_login (duserid, timeid, sucesso)
      SELECT MAX(du.duserid), dt.timeid, new.sucesso
      FROM d_tempo dt, d_utilizador du
      WHERE dt.dia      = DATE_FORMAT(new.moment, '%d')
      AND   dt.mes      = DATE_FORMAT(new.moment, '%m')
      AND   dt.ano      = DATE_FORMAT(new.moment, '%Y')
      AND   dt.horas    = DATE_FORMAT(new.moment, '%H')
      AND   dt.minutos  = DATE_FORMAT(new.moment, '%i')
      AND   dt.segundos = DATE_FORMAT(new.moment, '%s')
      AND   du.userid   = new.userid;
END//
delimiter ;


/*b) Media de tentativas de login para todos os utilizadores de
Portugal, em cada categoria, com rollup por ano e mes.*/
SELECT dt.ano, dt.mes, COUNT(fl.sucesso)/COUNT(DISTINCT du.userid) AS media
FROM f_login fl, d_utilizador du, d_tempo dt 
WHERE fl.duserid = du.duserid
AND   fl.timeid = dt.timeid
AND   du.pais = "Portugal"
GROUP BY du.categoria, dt.ano, dt.mes with ROLLUP;