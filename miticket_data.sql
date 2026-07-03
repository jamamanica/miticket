USE DBMITICKET;

INSERT INTO usuario(dni,nombre,apellido,correo,contrasena,telefono,fecha_registro) 
VALUES
('63215672','Franklin','Anahua','franklin3@mail.com','123456','934099868','2025-11-26'),
('79193021','Luis','Condori','luis321@mail.com','1235813','902050218','2026-01-05'),
('60124326','Johan','Mamani','johan999@mail.com','199012','910855032','2026-01-22'),
('72357812','Aron','Cachicatari','aronquito88@mail.com','abc123','933765612','2026-01-29'),
('62674561','Frank','Mamani','frank67@mail.com','frank321','986337293','2026-02-15'),
('65320541','Ronald','Huanca','ronald777@mail.com','rh533265','900507693','2026-03-09'),
('61345257','Alison','Flores','chalison123@mail.com','tart999','921692998','2026-04-08'),
('67341241','Alexandra','Choque','choque5@mail.com','choqueonofre2145','96573755','2026-05-19'),
('64315562','Alejandra','Perez','perez10@mail.com','ramosperez3243','986486094','2026-06-22');

INSERT INTO cliente(dni_cliente,preferencias)
VALUES
('79193021','Musica, Comedia'),
('60124326','Musica, Deportes'),
('72357812','Conferencias, Talleres'),
('76770924','Festivales, Streaming'),
('61345257','Musica, Danza');
INSERT INTO organizador(dni_organizador,nombre_empresa,ruc)
VALUES
('63215672','Dia D Eventos','20103455236'),
('62674561','ExperienZia','20123441407'),
('65320541','The Big Night','20063245238'),
('67341241','Golden Events','20153413489'),
('64315562','Luxe Moments','20123456030');

INSERT INTO lugar (nombre_lugar, direccion, ciudad, capacidad)
VALUES 
-- Lugares de Lima
('Estadio Nacional', 'Calle José Díaz s/n, Cercado de Lima', 'Lima', 45000),
('Arena 1', 'Circuito de Playas, San Miguel', 'Lima', 18000),
('Gran Teatro Nacional', 'Av. Javier Prado Este 2225, San Borja', 'Lima', 1500),
('Teatro Peruano Japonés', 'Av. Gregorio Escobedo 803, Jesús María', 'Lima', 1000),
('Jockey Club - Pelousse', 'Av. Manuel Olguín s/n, Santiago de Surco', 'Lima', 25000),
('Coliseo Eduardo Dibós', 'Av. Aviación 2701, San Borja', 'Lima', 5000),
('Anfiteatro del Parque de la Exposición', 'Av. 28 de Julio s/n, Cercado de Lima', 'Lima', 4000),
('Centro de Convenciones de Lima', 'Av. Arqueología 206, San Borja', 'Lima', 3500),
('Estadio Monumental U', 'Av. Javier Prado Este 7700, Ate', 'Lima', 80000),
('Estadio San Marcos', 'Av. Venezuela cuadra 34, Cercado de Lima', 'Lima', 32000),
-- Provincias del Perú
('Estadio UNSA', 'Av. Venezuela s/n, Cercado', 'Arequipa', 40000),
('Palacio de Bellas Artes Mario Vargas Llosa', 'Av. Parra 204', 'Arequipa', 2500),
('Estadio Garcilaso de la Vega', 'Av. de la Infancia s/n', 'Cusco', 42000),
('Teatro Municipal de Trujillo', 'Jr. Bolívar 753', 'Trujillo', 1000),
('Estadio Mansiche', 'Av. Mansiche s/n', 'Trujillo', 25000),
('Estadio Elias Aguirre', 'Av. De la Deporte s/n', 'Chiclayo', 23000),
('Estadio Huancayo', 'Av. Coronel Santiváñez s/n', 'Huancayo', 20000),
-- Locales principales en la Heroica Ciudad de Tacna
('Centro de Convenciones Jorge Basadre Grohmann', 'Calle Blondell 114, Cercado de Tacna.', 'Tacna', 750),
('Estadio Jorge Basadre Grohmann', 'Av. Coronel Justo Arias Araguez s/n', 'Tacna', 19850),
('Estadio Joel Gutiérrez', 'Av. Raúl Porras Barrenechea s/n, Gregorio Albarracín', 'Tacna', 21000),
('Teatro Municipal de Tacna', 'Pasaje 2 de Mayo 625, Cercado', 'Tacna', 600),
('Teatro Orfeón', 'Pje. Calderón de la Barca 200, Cercado', 'Tacna', 350),
('Sala de Teatro Cuadra 21', 'Calle Alto de Lima 2144', 'Tacna', 120),
('Las Palmeras Salón de Eventos', 'Av. Jorge Basadre Grohmann s/n (Arriba de la UPT)', 'Tacna', 1500),
('Quinta Flores Salón de Eventos', 'Av. Jorge Basadre Grohmann 1439, Pocollay', 'Tacna', 400),
('Estadio Enrique Paillardelli', 'Av. Augusto B. Leguía s/n', 'Tacna', 2500);

INSERT INTO categoria (nombre_categoria, descripcion)
VALUES 
('Música', 'Conciertos, recitales y festivales musicales en vivo'),
('Deportes', 'Eventos deportivos, partidos de fútbol y torneos'),
('Teatro', 'Obras dramáticas, comedias teatrales y musicales'),
('Familia', 'Espectáculos infantiles, circos y shows familiares'),
('Festivales', 'Grandes eventos culturales, gastronómicos y artísticos'),
('Conferencias', 'Charlas magistrales, ponencias y congresos profesionales'),
('Talleres', 'Sesiones educativas, cursos prácticos y masterclasses'),
('Exposiciones', 'Muestras de arte, galerías y ferias de exhibición'),
('Comedia', 'Shows de stand-up comedy, monólogos y espectáculos de humor'),
('Infantil', 'Eventos dedicados exclusivamente a la animación y entretenimiento de niños'),
('Danza', 'Espectáculos de baile clásico, folclórico y danza contemporánea'),
('Streaming', 'Eventos virtuales, transmisiones en vivo y contenido on-demand');

INSERT INTO metodo_pago(nombre_metodo) 
VALUES
('Visa'),
('MasterCard'),
('American Express'),
('Yape'),
('Plin'),
('BBVA'),
('BCP'),
('Interbank'),
('Scotiabank'),
('PagoEfectivo');

INSERT INTO evento(nombre,descripcion,fecha,hora,estado,id_organizador,id_lugar,id_categoria) 
VALUES
('Grupo 5: Concierto de Oro 2026','La agrupación de cumbia más galardonada del país en su gran noche de gala en el primer escenario deportivo.','2026-10-16','20:00:00','PUBLICADO',6,1,1),
('El Príncipe: Tributo a José José por Carlos Burga','El ganador de Yo Soy llega a Tacna para una noche romántica inolvidable interpretando los mejores éxitos del "Príncipe de la Canción".','2026-07-10','20:00:00','PUBLICADO',7,11,1),
('Feria Gastronómica Perú, Mucho Gusto - Tacna 2026','La feria gastronómica más importante del país regresa a Tacna con expositores de todas las regiones, shows en vivo y artesanía.','2026-07-25','10:00:00','PUBLICADO',8,12,5),
('Voces del Amor - Aldair Sánchez','Concierto criollo íntimo para celebrar las fiestas patrias en el sur del país. Contratos artísticos pendientes.','2026-07-28','20:30:00','BORRADOR',10,11,1),
('XVIII Expo Ilabaya 2026 - Edición Central','Feria cancelada para el Parque Perú debido a la priorización de su realización en el distrito de origen (Ilabaya).','2026-06-22','10:00:00','CANCELADO',8,12,8),
('Amanda Miguel: El Me Mintió World Tour 2026','La icónica baladista argentina se presenta en la Costa Verde en un concierto lleno de nostalgia y sentimiento.','2026-07-20','21:00:00','PUBLICADO',9,2,1),
('Amén en Tacna - Noche de Rock Peruano','Marcello Motta y toda la banda regresan a la Ciudad Heroica para tocar en vivo éxitos como "Pan con Mantequilla", "Decir Adiós" y "Te Quiero".','2026-07-04','21:00:00','PUBLICADO',6,11,1),
('Mar de Copas: Entre los Árboles - 30 Años','La banda más romántica del rock peruano celebra las tres décadas de su álbum más emblemático.','2026-08-14','22:00:00','PUBLICADO',10,5,1),
('Festival del Rock Subterráneo Limeño','Edición especial de invierno. A la espera de confirmación de permisos municipales del centro de Lima.','2026-07-26','13:00:00','BORRADOR',7,7,1),
('Eva Ayllón: Un Regalo para Tacna','CANCELADO por problemas imprevistos de salud de la artista. Se habilitará pasarela de devoluciones automáticas.','2026-07-24','20:00:00','CANCELADO',8,11,1),
('Hablando Huevadas: Gira Sur 2026 (Pre-salida)','Borrador para la configuración interna de la ticketera antes de abrir la masiva cola virtual.','2026-08-05','20:30:00','BORRADOR',6,11,9);


