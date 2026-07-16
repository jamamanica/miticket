CREATE DATABASE DBMITICKET;
USE DBMITICKET;

CREATE TABLE usuario (
    dni CHAR(8) PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    fecha_nacimiento DATE NOT NULL
);

CREATE TABLE cliente (
    dni_cliente CHAR(8) PRIMARY KEY,
    CONSTRAINT fk_cliente_usuario
        FOREIGN KEY (dni_cliente)
        REFERENCES usuario(dni)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200)
);

CREATE TABLE cliente_categoria (
    dni_cliente CHAR(8) NOT NULL,
    id_categoria INT NOT NULL,
    PRIMARY KEY (dni_cliente, id_categoria), 
    
    CONSTRAINT fk_pref_cliente 
        FOREIGN KEY (dni_cliente) REFERENCES cliente(dni_cliente) 
        ON DELETE CASCADE ON UPDATE CASCADE,
        
    CONSTRAINT fk_pref_categoria 
        FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE organizador (
    dni_organizador CHAR(8) PRIMARY KEY,
    nombre_empresa VARCHAR(100) NOT NULL,
    ruc CHAR(11) UNIQUE,
    CONSTRAINT fk_organizador_usuario
        FOREIGN KEY (dni_organizador)
        REFERENCES usuario(dni)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE lugar (
    id_lugar INT AUTO_INCREMENT PRIMARY KEY,
    nombre_lugar VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),
    ciudad VARCHAR(100),
    capacidad INT NOT NULL
);

CREATE TABLE evento (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('BORRADOR','PUBLICADO','CANCELADO') DEFAULT 'BORRADOR',
    dni_organizador CHAR(8) NOT NULL,
    id_lugar INT NOT NULL,
    id_categoria INT NOT NULL,
    CONSTRAINT fk_evento_organizador FOREIGN KEY (dni_organizador) REFERENCES organizador(dni_organizador) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_evento_lugar FOREIGN KEY (id_lugar) REFERENCES lugar(id_lugar) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_evento_categoria FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE zona (
    id_zona INT AUTO_INCREMENT PRIMARY KEY,
    nombre_zona VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    capacidad INT NOT NULL,
    id_evento INT NOT NULL,
    CONSTRAINT fk_zona_evento FOREIGN KEY (id_evento) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE asiento (
    id_asiento INT AUTO_INCREMENT PRIMARY KEY,
    numero_asiento VARCHAR(20) NOT NULL,
    fila CHAR(2) NOT NULL,
    columna INT NOT NULL,
    estado ENUM('DISPONIBLE','VENDIDO') DEFAULT 'DISPONIBLE',
    id_zona INT NOT NULL,
    CONSTRAINT uq_asiento UNIQUE(numero_asiento, id_zona),
    CONSTRAINT fk_asiento_zona FOREIGN KEY (id_zona) REFERENCES zona(id_zona) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE compra (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    fecha_compra DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('PENDIENTE','PAGADA','CANCELADA') DEFAULT 'PENDIENTE',
    dni_cliente CHAR(8) NOT NULL,
    CONSTRAINT fk_compra_cliente FOREIGN KEY (dni_cliente) REFERENCES cliente(dni_cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE metodo_pago (
    id_metodo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_metodo VARCHAR(50) NOT NULL
);

CREATE TABLE pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago DATETIME NOT NULL,
    estado_pago ENUM('PENDIENTE','APROBADO','RECHAZADO') DEFAULT 'PENDIENTE',
    id_compra INT NOT NULL UNIQUE,
    id_metodo INT NOT NULL,
    CONSTRAINT fk_pago_compra FOREIGN KEY (id_compra) REFERENCES compra(id_compra) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pago_metodo FOREIGN KEY (id_metodo) REFERENCES metodo_pago(id_metodo) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE detalle_compra (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_compra INT NOT NULL,
    id_zona INT NOT NULL,
    CONSTRAINT fk_detalle_compra FOREIGN KEY (id_compra) REFERENCES compra(id_compra) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_zona FOREIGN KEY (id_zona) REFERENCES zona(id_zona) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE ticket (
    id_ticket INT AUTO_INCREMENT PRIMARY KEY,
    fecha_emision DATETIME NOT NULL,
    estado ENUM('ACTIVO','USADO','ANULADO') DEFAULT 'ACTIVO',
    id_compra INT NOT NULL,
    id_zona INT NOT NULL,
    id_asiento INT NOT NULL,
    CONSTRAINT fk_ticket_compra FOREIGN KEY (id_compra) REFERENCES compra(id_compra) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ticket_zona FOREIGN KEY (id_zona) REFERENCES zona(id_zona) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_ticket_asiento FOREIGN KEY (id_asiento) REFERENCES asiento(id_asiento) ON DELETE RESTRICT ON UPDATE CASCADE
);