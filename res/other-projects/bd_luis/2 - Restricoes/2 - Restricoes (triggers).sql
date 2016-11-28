/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela sequencia verifica-se a existencia de uma e uma so ocorrencia de idseq equivalente a
contador_sequencia. Isto e, se idseq em qualquer uma das outras tabelas, para alem da tabela sequencia, e apenas uma vez igual a contador_sequencia.
Caso seja diferente, levanta um erro */

Delimiter //
CREATE TRIGGER verifica_sequencia BEFORE INSERT ON sequencia
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM tipo_registo T, registo R, pagina P, campo C, valor V
    WHERE NEW.contador_sequencia = T.idseq
        AND NEW.contador_sequencia = R.idseq
        AND NEW.contador_sequencia = P.idseq
        AND NEW.contador_sequencia = C.idseq
        AND NEW.contador_sequencia = V.idseq) != 1 THEN CALL error; END IF;
end //

/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela tipo_registo, caso exista uma ocorrencia de idseq, nao pode voltar a existir uma com valor equivalente
entre as restantes tabelas. Isto e, verificamos se idseq em qualquer uma das outras quatro tabelas, para alem da tabela tipo_registo,
tem um valor igual ao idseq da tabela tipo_registo que pretendemos acrescentar. Caso se verifique, levanta um erro. */

Delimiter //
CREATE TRIGGER verifica_tipo BEFORE INSERT ON tipo_registo
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM registo R, pagina P, campo C, valor V
    WHERE NEW.idseq = R.idseq
        AND NEW.idseq = P.idseq
        AND NEW.idseq = C.idseq
        AND NEW.idseq = V.idseq) != 0 THEN CALL error; END IF;
end //

/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela registo, caso exista uma ocorrencia de idseq, nao pode voltar a existir uma com valor equivalente
entre as restantes tabelas. Isto e, verificamos se idseq em qualquer uma das outras quatro tabelas, para alem da tabela registo,
tem um valor igual ao idseq da tabela registo que pretendemos acrescentar. Caso se verifique, levanta um erro. */

Delimiter //
CREATE TRIGGER verifica_registo BEFORE INSERT ON registo
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM tipo_registo T, pagina P, campo C, valor V
    WHERE NEW.idseq = T.idseq
        AND NEW.idseq = P.idseq
        AND NEW.idseq = C.idseq
        AND NEW.idseq = V.idseq) != 0 THEN CALL error; END IF;
end //

/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela pagina, caso exista uma ocorrencia de idseq, nao pode voltar a existir uma com valor equivalente
entre as restantes tabelas. Isto e, verificamos se idseq em qualquer uma das outras quatro tabelas, para alem da tabela pagina,
tem um valor igual ao idseq da tabela pagina que pretendemos acrescentar. Caso se verifique, levanta um erro. */

Delimiter //
CREATE TRIGGER verifica_pagina BEFORE INSERT ON pagina
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM tipo_registo T, registo R, campo C, valor V
    WHERE NEW.idseq = T.idseq
        AND NEW.idseq = R.idseq
        AND NEW.idseq = C.idseq
        AND NEW.idseq = V.idseq) != 0 THEN CALL error; END IF;
end //

/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela campo, caso exista uma ocorrencia de idseq, nao pode voltar a existir uma com valor equivalente
entre as restantes tabelas. Isto e, verificamos se idseq em qualquer uma das outras quatro tabelas, para alem da tabela campo,
tem um valor igual ao idseq da tabela campo que pretendemos acrescentar. Caso se verifique, levanta um erro. */

Delimiter //
CREATE TRIGGER verifica_campo BEFORE INSERT ON campo
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM tipo_registo T, registo R, pagina P, valor V
    WHERE NEW.idseq = T.idseq
        AND NEW.idseq = R.idseq
        AND NEW.idseq = P.idseq
        AND NEW.idseq = V.idseq) != 0 THEN CALL error; END IF;
end //

/* Instituicao: Instituto Superior Tecnico */
/* Curso: LEIC-A            Data: 9/12/2015*/
/* Autores: João Catarino 78877,
      Ricardo Mota 78131,
      Luis Morais 78416 */

/* Descrição: Para a tabela valor, caso exista uma ocorrencia de idseq, nao pode voltar a existir uma com valor equivalente
entre as restantes tabelas. Isto e, verificamos se idseq em qualquer uma das outras quatro tabelas, para alem da tabela valor,
tem um valor igual ao idseq da tabela valor que pretendemos acrescentar. Caso se verifique, levanta um erro. */

Delimiter //
CREATE TRIGGER verifica_valor BEFORE INSERT ON valor
FOR EACH ROW
BEGIN
  IF (SELECT COUNT(*) FROM tipo_registo T, registo R, pagina P, campo C
    WHERE NEW.idseq = T.idseq
        AND NEW.idseq = R.idseq
        AND NEW.idseq = P.idseq
        AND NEW.idseq = c.idseq) != 0 THEN CALL error; END IF;
end //
