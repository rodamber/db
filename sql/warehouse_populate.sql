-- Senhorios
insert into d_user (nif, nome, telefone) values ('123456719', 'Jorge Poeta',     '992323123');
insert into d_user (nif, nome, telefone) values ('113056729', 'António Martins', '992333123');
insert into d_user (nif, nome, telefone) values ('133956139', 'David Manuel',    '992323124');
insert into d_user (nif, nome, telefone) values ('143856248', 'Nuno Sousa',      '992323125');
-- Arrendatarios
insert into d_user (nif, nome, telefone) values ('153756357', 'Armando Sousa',   '992323126');
insert into d_user (nif, nome, telefone) values ('163656466', 'Gonçalo Santos',  '992323127');
insert into d_user (nif, nome, telefone) values ('173516575', 'Alberto Silva',   '992323128');
insert into d_user (nif, nome, telefone) values ('183426684', 'Rubim Guerreiro', '992323129');
insert into d_user (nif, nome, telefone) values ('193336793', 'Anacleto Vieira', '993323123');
insert into d_user (nif, nome, telefone) values ('103246782', 'Luis Raposo',     '995323123');
insert into d_user (nif, nome, telefone) values ('120456781', 'Rui Vitória',     '997323123');

insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'Central', 'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEI',     'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEG',     'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEQ',     'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEI',  'Lab1',    'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEI',  'Lab2',    'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEI',  'Lab3',    'IST');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'Central', 'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEI',     'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEG',     'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEQ',     'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEG',  'Lab1',    'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEG',  'Lab2',    'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEG',  'Lab3',    'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DEG',  'Lab4',    'FEUP');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'Central', 'Catolica');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DMKT',    'Catolica');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DG',      'Catolica');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DMKT', 'Sala1',   'Catolica');
insert into d_local (codigo_posto, codigo_espaco, morada) values ('DMKT', 'Sala2',   'Catolica');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'Central', 'ISEL');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEI',     'ISEL');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEG',     'ISEL');
insert into d_local (codigo_posto, codigo_espaco, morada) values (null,   'DEQ',     'ISEL');

insert into f_reserva values (1, 1, 2000, 20160101, 35, 1);
insert into f_reserva values (2, 2, 2100, 20160102, 40, 1);
insert into f_reserva values (3, 3, 2200, 20160103, 45, 1);
insert into f_reserva values (4, 4, 2300, 20160104, 50, 1);
