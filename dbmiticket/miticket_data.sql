USE DBMITICKET;

INSERT INTO usuario (dni, nombre, apellido, correo, contrasena, telefono, fecha_nacimiento) VALUES
('11111111', 'Juan', 'Pérez', 'juanperez@gmail.com', 'pass123', '911111111', '2006-05-12'),
('22222222', 'María', 'López', 'marialopezg@mail.com', 'pass123', '922222222', '1995-08-22'),
('33333333', 'Carlos', 'Gómez', 'carlosgomez@gmail.com', 'pass123', '933333333', '1995-11-02'),
('44444444', 'Ana', 'Rodríguez', 'anarod@gmail.com', 'pass123', '944444444', '1993-03-14'),
('55555555', 'Luis', 'Sánchez', 'luissanchez@gmail.com', 'pass123', '955555555', '1997-07-19'),
('66666666', 'Laura', 'Torres', 'lauratorres@gmail.com', 'pass123', '966666666', '2004-01-25'),
('77777777', 'Pedro', 'Ramírez', 'pedroram@gmail.com', 'pass123', '977777777', '2005-09-30'),
('88888888', 'Sofía', 'Castro', 'sofiacastro@gmail.com', 'pass123', '988888888', '1994-04-10'),
('99999999', 'Diego', 'Flores', 'diegoflores@gmail.com', 'pass123', '999999999', '1992-12-05'),
('12345678', 'Elena', 'Vargas', 'elenavargas@gmail.com', 'pass123', '912345678', '1996-06-15'),
('87654321', 'Jorge', 'Díaz', 'jorgediaz@gmail.com', 'pass123', '987654321', '2007-02-28'),
('56781234', 'Lucía', 'Benítez', 'luciaben@gmail.com', 'pass123', '956781234', '1998-10-20'),
('43218765', 'Miguel', 'Mendoza', 'miguelmen@gmail.com', 'pass123', '943218765', '1994-07-07'),
('98761234', 'Carmen', 'Salazar', 'carmensal@gmail.com', 'pass123', '987612345', '1993-09-11'),
('11223344', 'Andrés', 'Ríos', 'andresrios@gmail.com', 'pass123', '911223344', '2002-01-01'),
('55667788', 'Rosa', 'Chávez', 'rosachavez@gmail.com', 'pass123', '955667788', '1995-12-12'),
('99001122', 'Javier', 'Guerra', 'javierguerra@gmail.com', 'pass123', '999001122', '1997-04-18'),
('33445566', 'Patricia', 'Luna', 'patricialuna@gmail.com', 'pass123', '933445566', '2000-08-08'),
('77889900', 'Raúl', 'Sotto', 'raulsoto@gmail.com', 'pass123', '977889900', '2003-03-23'),
('12435687', 'Gabriela', 'Peña', 'gabipena@gmail.com', 'pass123', '912435687', '1999-11-11');

INSERT INTO cliente (dni_cliente) VALUES
('11111111'), ('22222222'), ('33333333'), ('44444444'), ('55555555'),
('66666666'), ('77777777'), ('88888888'), ('99999999'), ('12345678');

INSERT INTO organizador (dni_organizador, nombre_empresa, ruc) VALUES
('87654321', 'Live Nation Perú', '20123456781'),
('56781234', 'Artes Perú', '20876543212'),
('43218765', 'Kandavu Producciones', '20567812343'),
('98761234', 'Masterlive', '20432187654'),
('11223344', 'Teleticket Events', '20987612345'),
('55667788', 'Inmortal Productions', '20112233446'),
('99001122', 'Move Concerts', '20556677887'),
('33445566', 'Fans & Music Entertainment', '20990011228'),
('77889900', 'One Entertainment', '20334455669'),
('12435687', 'Vívelo Rock', '20778899001');

INSERT INTO lugar (nombre_lugar, direccion, ciudad, capacidad) VALUES
('Estadio Nacional', 'Calle José Díaz s/n', 'Lima', 45000),
('Arena 1', 'Circuito de Playas', 'Lima', 15000),
('Gran Teatro Nacional', 'Av. Javier Prado Este 2225', 'Lima', 1500),
('Teatro Municipal de Tacna', 'Pasaje 2 de Mayo 625', 'Tacna', 600),
('Estadio Jorge Basadre', 'Av. Coronel Arias Araguez', 'Tacna', 20000),
('Estadio UNSA', 'Av. Venezuela s/n', 'Arequipa', 40000),
('Jockey Club Pelousse', 'Av. Manuel Olguín s/n', 'Lima', 20000),
('Parque de la Exposición', 'Av. 28 de Julio s/n', 'Lima', 4000),
('Teatro Municipal de Trujillo', 'Jr. Bolívar 753', 'Trujillo', 1000),
('Estadio Mansiche', 'Av. Mansiche s/n', 'Trujillo', 24000);

INSERT INTO categoria (nombre_categoria, descripcion) VALUES
('Concierto Rock', 'Conciertos de rock nacional e internacional'),
('Concierto Pop', 'Música pop y juvenil'),
('Teatro', 'Obras de teatro y drama'),
('Stand-up Comedy', 'Monólogos y comedia en vivo'),
('Fútbol', 'Partidos del torneo local e internacional'),
('Festival', 'Festivales de música de larga duración'),
('Folclor', 'Eventos de música tradicional y danzas'),
('Ópera', 'Presentaciones líricas y música clásica'),
('Infantil', 'Shows y espectáculos para niños'),
('Conferencia', 'Charlas y eventos corporativos');

INSERT INTO cliente_categoria (dni_cliente, id_categoria) VALUES
('11111111', 1), ('11111111', 5),
('22222222', 2), ('33333333', 1),
('44444444', 3), ('55555555', 4),
('66666666', 6), ('77777777', 5),
('88888888', 2), ('99999999', 10),
('12345678', 3), ('12345678', 4);

INSERT INTO evento (nombre, descripcion, fecha, hora, estado, dni_organizador, id_lugar, id_categoria) VALUES
('Rock en el Parque 2026', 'El festival de rock más grande.', '2026-08-15', '16:00:00', 'PUBLICADO', '87654321', 8, 1),
('Shakira en Lima', 'Las mujeres ya no lloran world tour.', '2026-09-20', '21:00:00', 'PUBLICADO', '56781234', 1, 2),
('Gisela Ponce de León: Solo yo', 'Obra unipersonal dramática.', '2026-07-12', '20:00:00', 'PUBLICADO', '43218765', 3, 3),
('Carlos Alcántara Stand Up', 'Asu Mare el monólogo regresa.', '2026-07-30', '21:30:00', 'PUBLICADO', '98761234', 4, 4),
('Perú vs Chile', 'Clásico del Pacífico por eliminatorias.', '2026-10-10', '20:30:00', 'BORRADOR', '11223344', 1, 5),
('Vivo x el Rock 2026', 'Edición especial de reencuentro.', '2026-11-05', '12:00:00', 'PUBLICADO', '55667788', 5, 6),
('Eva Ayllón y Amigos', 'Noche criolla espectacular.', '2026-07-28', '20:00:00', 'PUBLICADO', '99001122', 7, 7),
('El Lago de los Cisnes', 'Ballet clásico ruso.', '2026-12-15', '19:30:00', 'BORRADOR', '33445566', 3, 8),
('La Granja de Zenón', 'Show oficial en vivo para niños.', '2026-08-02', '15:00:00', 'PUBLICADO', '77889900', 9, 9),
('Tech Conference Perú', 'Innovación y desarrollo tecnológico.', '2026-08-25', '09:00:00', 'CANCELADO', '12435687', 10, 10),
('Hablando Huevadas', 'Show de comedia en vivo improvisado.', '2026-09-05', '21:00:00', 'PUBLICADO', '98761234', 2, 4);

INSERT INTO zona (nombre_zona, precio, stock, capacidad, id_evento) VALUES
('Platinum', 380, 500, 500, 1),
('VIP', 250, 800, 800, 1),
('Occidente', 150, 1200, 1200, 1),
('Apdayc (General)', 80, 2000, 2000, 1),
('VIP Golden', 100, 80, 80, 2),
('General Preferencial', 50, 150, 150, 2),
('Entrada General Adulto', 15, 5000, 5000, 3),
('Entrada Niño (Menor de 12)', 0, 2000, 2000, 3),
('Mesa VIP', 120, 40, 40, 4),
('General Stand Up', 60, 120, 120, 4),
('Entrada Única', 10, 0, 3000, 5),
('Experiencia Amanda', 450, 150, 150, 6),
('Platinium', 320, 300, 300, 6),
('VIP', 190, 500, 500, 6),
('Zona Te Quiero ', 199, 150, 150, 7),
('Zona Decir Adios', 149, 250, 250, 7),
('Zona Fin del Tiempo', 99, 300, 300, 7),
('VIP Siniestro', 160, 350, 350, 8),
('General Balcón', 85, 600, 600, 8),
('Moshpit Box', 90, 150, 150, 9),
('General Subte', 45, 600, 600, 9),
('Golden Criollo', 150, 0, 100, 10),
('General Preferencial', 70, 0, 250, 10),
('Fila 0 (Frente al escenario)', 280, 30, 30, 11),
('Zona Yapa (Media)', 180, 200, 200, 11),
('Zona Pobre (Alta/General)', 90, 400, 400, 11);

INSERT INTO asiento (numero_asiento, estado, id_zona) VALUES
('PL-1', 'DISPONIBLE', 1), ('PL-2', 'RESERVADO', 1), ('PL-3', 'DISPONIBLE', 1),
('VIP-1', 'DISPONIBLE', 2), ('VIP-2', 'DISPONIBLE', 2), ('VIP-3', 'RESERVADO', 2),
('OCC-1', 'DISPONIBLE', 3), ('OCC-2', 'DISPONIBLE', 3), ('OCC-3', 'DISPONIBLE', 3),
('APD-1', 'DISPONIBLE', 4), ('APD-2', 'RESERVADO', 4), ('APD-3', 'DISPONIBLE', 4),
('VG-1', 'DISPONIBLE', 5), ('VG-2', 'DISPONIBLE', 5), ('VG-3', 'RESERVADO', 5),
('GP-1', 'DISPONIBLE', 6), ('GP-2', 'DISPONIBLE', 6), ('GP-3', 'DISPONIBLE', 6),
('EGA-1', 'DISPONIBLE', 7), ('EGA-2', 'DISPONIBLE', 7), ('EGA-3', 'RESERVADO', 7),
('EN-1', 'DISPONIBLE', 8), ('EN-2', 'DISPONIBLE', 8), ('EN-3', 'DISPONIBLE', 8),
('M-1', 'RESERVADO', 9), ('M-2', 'DISPONIBLE', 9), ('M-3', 'DISPONIBLE', 9),
('SU-1', 'DISPONIBLE', 10), ('SU-2', 'DISPONIBLE', 10), ('SU-3', 'DISPONIBLE', 10),
('EU-1', 'RESERVADO', 11), ('EU-2', 'RESERVADO', 11), ('EU-3', 'RESERVADO', 11),
('EXA-1', 'DISPONIBLE', 12), ('EXA-2', 'RESERVADO', 12), ('EXA-3', 'DISPONIBLE', 12),
('PLA-1', 'DISPONIBLE', 13), ('PLA-2', 'DISPONIBLE', 13), ('PLA-3', 'DISPONIBLE', 13),
('VAM-1', 'DISPONIBLE', 14), ('VAM-2', 'RESERVADO', 14), ('VAM-3', 'DISPONIBLE', 14),
('ZTQ-1', 'DISPONIBLE', 15), ('ZTQ-2', 'DISPONIBLE', 15), ('ZTQ-3', 'RESERVADO', 15),
('ZDA-1', 'DISPONIBLE', 16), ('ZDA-2', 'DISPONIBLE', 16), ('ZDA-3', 'DISPONIBLE', 16),
('ZFT-1', 'DISPONIBLE', 17), ('ZFT-2', 'RESERVADO', 17), ('ZFT-3', 'DISPONIBLE', 17),
('VS-1', 'DISPONIBLE', 18), ('VS-2', 'DISPONIBLE', 18), ('VS-3', 'RESERVADO', 18),
('GB-1', 'DISPONIBLE', 19), ('GB-2', 'DISPONIBLE', 19), ('GB-3', 'DISPONIBLE', 19),
('MB-1', 'DISPONIBLE', 20), ('MB-2', 'DISPONIBLE', 20), ('MB-3', 'RESERVADO', 20),
('GS-1', 'DISPONIBLE', 21), ('GS-2', 'DISPONIBLE', 21), ('GS-3', 'DISPONIBLE', 21),
('GC-1', 'RESERVADO', 22), ('GC-2', 'RESERVADO', 22), ('GC-3', 'RESERVADO', 22),
('GPE-1', 'RESERVADO', 23), ('GPE-2', 'RESERVADO', 23), ('GPE-3', 'RESERVADO', 23),
('F0-1', 'RESERVADO', 24), ('F0-2', 'DISPONIBLE', 24), ('F0-3', 'DISPONIBLE', 24),
('ZY-1', 'DISPONIBLE', 25), ('ZY-2', 'DISPONIBLE', 25), ('ZY-3', 'DISPONIBLE', 25),
('ZP-1', 'DISPONIBLE', 26), ('ZP-2', 'RESERVADO', 26), ('ZP-3', 'DISPONIBLE', 26);

INSERT INTO compra (fecha_compra, total, estado, dni_cliente) VALUES
('2026-06-15 10:30:00', 380.00, 'PAGADA', '11111111'),
('2026-06-16 14:22:00', 100.00, 'PAGADA', '22222222'),
('2026-06-18 09:15:00', 15.00, 'PAGADA', '33333333'),
('2026-06-20 18:45:00', 120.00, 'PAGADA', '44444444'),
('2026-06-21 11:00:00', 10.00, 'PENDIENTE', '55555555'),
('2026-06-22 13:12:00', 450.00, 'CANCELADA', '66666666'),
('2026-06-23 16:40:00', 199.00, 'PAGADA', '77777777'),
('2026-06-24 20:05:00', 160.00, 'PAGADA', '88888888'),
('2026-06-25 22:30:00', 90.00, 'PENDIENTE', '99999999'),
('2026-06-26 08:10:00', 280.00, 'PAGADA', '12345678');

INSERT INTO metodo_pago (nombre_metodo) VALUES
('Visa Crédito'), ('Visa Débito'), ('MasterCard'), ('American Express'),
('Yape'), ('Plin'), ('PagoEfectivo'), ('BCP Banca Móvil'),
('BBVA Net'), ('Interbank App');

INSERT INTO pago (monto, fecha_pago, estado_pago, id_compra, id_metodo) VALUES
(380.00, '2026-06-15 10:31:00', 'APROBADO', 1, 1),
(100.00, '2026-06-16 14:23:00', 'APROBADO', 2, 5),
(15.00, '2026-06-18 09:16:00', 'APROBADO', 3, 6),
(120.00, '2026-06-20 18:46:00', 'APROBADO', 4, 2),
(10.00, '2026-06-21 11:00:00', 'PENDIENTE', 5, 7),
(450.00, '2026-06-22 13:15:00', 'RECHAZADO', 6, 3),
(199.00, '2026-06-23 16:42:00', 'APROBADO', 7, 4),
(160.00, '2026-06-24 20:06:00', 'APROBADO', 8, 5),
(90.00, '2026-06-25 22:30:00', 'PENDIENTE', 9, 8),
(280.00, '2026-06-26 08:12:00', 'APROBADO', 10, 1);

INSERT INTO detalle_compra (cantidad, precio_unitario, subtotal, id_compra, id_zona) VALUES
(1, 380.00, 380.00, 1, 1),
(1, 100.00, 100.00, 2, 5),
(1, 15.00, 15.00, 3, 7),
(1, 120.00, 120.00, 4, 9),
(1, 10.00, 10.00, 5, 11),
(1, 450.00, 450.00, 6, 12),
(1, 199.00, 199.00, 7, 15),
(1, 160.00, 160.00, 8, 18),
(1, 90.00, 90.00, 9, 20),
(1, 280.00, 280.00, 10, 24);

INSERT INTO ticket (fecha_emision, estado, id_compra, id_zona, id_asiento) VALUES
('2026-06-15 10:31:00', 'ACTIVO', 1, 1, 1),
('2026-06-16 14:23:00', 'ACTIVO', 2, 5, 13),
('2026-06-18 09:16:00', 'ACTIVO', 3, 7, 19),
('2026-06-20 18:46:00', 'USADO', 4, 9, 25),
('2026-06-21 11:00:00', 'ACTIVO', 5, 11, 31),
('2026-06-23 16:42:00', 'ACTIVO', 7, 15, 43),
('2026-06-24 20:06:00', 'ACTIVO', 8, 18, 52),
('2026-06-26 08:12:00', 'ACTIVO', 10, 24, 70),
('2026-06-23 17:00:00', 'ANULADO', 7, 15, 44),
('2026-06-24 21:00:00', 'ACTIVO', 8, 18, 53);