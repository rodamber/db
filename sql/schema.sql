-- Duvidas:
-- 1. not null
-- 2. tipo do telefone
-- 3. muita atencao auto_increment
-- 4. tipo da foto
-- 5. Ver o que fazer on delete e on update

CREATE TABLE IF NOT EXISTS User (
    nif INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    telefone INT NOT NULL,
    PRIMARY KEY (nif)
);

CREATE TABLE IF NOT EXISTS Fiscal (
       id INT NOT NULL,
       empresa VARCHAR(255) NOT NULL,
       PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Edificio (
       morada VARCHAR(255) NOT NULL,
       PRIMARY KEY (morada)
);

CREATE TABLE IF NOT EXISTS Alugavel (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL AUTO_INCREMENT,
       foto VARCHAR(255) NOT NULL,
       PRIMARY KEY (morada, codigo),
       FOREIGN KEY (morada) REFERENCES Edificio (morada) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Arrenda (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL,
       nif INT NOT NULL AUTO_INCREMENT,
       PRIMARY KEY (morada, codigo),
       FOREIGN KEY (morada, codigo) REFERENCES Alugavel (morada, codigo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Fiscaliza (
       id INT NOT NULL,
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL,
       PRIMARY KEY (id, morada, codigo),
       FOREIGN KEY (id) REFERENCES Fiscal (id),
       FOREIGN KEY (morada, codigo) REFERENCES Arrenda (morada, codigo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Espaco (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL, -- auto_increment,
       PRIMARY KEY (morada, codigo),
       FOREIGN KEY (morada, codigo) REFERENCES Alugavel (morada, codigo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Posto (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL, -- auto_increment,
       codigo_espaco INT NOT NULL,
       PRIMARY KEY (morada, codigo),
       FOREIGN KEY (morada, codigo) REFERENCES Alugavel (morada, codigo) ON DELETE CASCADE,
       FOREIGN KEY (morada, codigo_espaco) REFERENCES Espaco (morada, codigo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Oferta (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL, -- auto_increment,
       data_inicio TIMESTAMP NOT NULL,
       data_fim TIMESTAMP NOT NULL,
       tarifa INT NOT NULL,
       PRIMARY KEY (morada, codigo, data_inicio),
       FOREIGN KEY (morada, codigo) REFERENCES Alugavel (morada, codigo) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Aluga (
       morada VARCHAR(255) NOT NULL,
       codigo INT NOT NULL, -- auto_increment,
       data_inicio TIMESTAMP NOT NULL,
       nif INT NOT NULL,
       numero INT NOT NULL AUTO_INCREMENT,
       PRIMARY KEY (morada, codigo, data_inicio, nif, numero),
       FOREIGN KEY (morada, codigo, data_inicio) REFERENCES Oferta (morada, codigo, data_inicio),
       FOREIGN KEY (nif) REFERENCES User (nif),
       FOREIGN KEY (numero) REFERENCES Reserva (numero)
);

CREATE TABLE IF NOT EXISTS Paga (
       numero INT NOT NULL AUTO_INCREMENT,
       data TIMESTAMP NOT NULL,
       metodo VARCHAR(255) NOT NULL,
       PRIMARY KEY (numero),
       FOREIGN KEY (numero) REFERENCES Reserva (numero)
);

CREATE TABLE IF NOT EXISTS Estado (
       numero INT NOT NULL AUTO_INCREMENT,
       timestamp TIMESTAMP NOT NULL,
       estado VARCHAR(255) NOT NULL,
       PRIMARY KEY (numero),
       FOREIGN KEY (numero) REFERENCES Reserva (numero)
);

CREATE TABLE IF NOT EXISTS Reserva (
       numero INT NOT NULL AUTO_INCREMENT,
       PRIMARY KEY (numero)
);
