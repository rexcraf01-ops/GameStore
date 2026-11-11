-- ===================================================
-- Base de Datos: Level Up Games (MySQL / MariaDB)
-- Compatible con XAMPP y MySQL 8+
-- ===================================================

CREATE DATABASE IF NOT EXISTS levelup_games
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE levelup_games;

-- 1. Tabla: rol
CREATE TABLE IF NOT EXISTS rol (
  id_rol  TINYINT AUTO_INCREMENT PRIMARY KEY,
  nombre  VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 2. Tabla: usuario
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario     INT AUTO_INCREMENT PRIMARY KEY,
  id_rol         TINYINT NOT NULL,
  nombre         VARCHAR(80)  NOT NULL,
  correo         VARCHAR(100) NOT NULL UNIQUE,
  contrasena     VARCHAR(255) NOT NULL,
  activo         BOOLEAN NOT NULL DEFAULT TRUE,
  fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol)
    REFERENCES rol (id_rol)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 3. Tabla: categoria
CREATE TABLE IF NOT EXISTS categoria (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(50)  NOT NULL UNIQUE,
  descripcion  VARCHAR(150) NULL
) ENGINE=InnoDB;

-- 4. Tabla: producto
CREATE TABLE IF NOT EXISTS producto (
  id_producto  INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria INT NOT NULL,
  nombre       VARCHAR(100)  NOT NULL,
  plataforma   VARCHAR(40) NULL,
  precio       DECIMAL(10,2) NOT NULL,
  stock        INT NOT NULL DEFAULT 0,
  stock_minimo INT NOT NULL DEFAULT 0,
  activo       BOOLEAN NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria)
    REFERENCES categoria (id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_producto_precio CHECK (precio >= 0),
  CONSTRAINT ck_producto_stock  CHECK (stock >= 0),
  CONSTRAINT ck_producto_minimo CHECK (stock_minimo >= 0),
  INDEX idx_producto_nombre (nombre),
  INDEX idx_producto_categoria (id_categoria)
) ENGINE=InnoDB;

-- 5. Tabla: cliente
CREATE TABLE IF NOT EXISTS cliente (
  id_cliente     INT AUTO_INCREMENT PRIMARY KEY,
  nombre         VARCHAR(80)  NOT NULL,
  telefono       VARCHAR(20) NULL,
  correo         VARCHAR(100) NULL,
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_cliente_nombre (nombre)
) ENGINE=InnoDB;

-- 6. Tabla: pedido
CREATE TABLE IF NOT EXISTS pedido (
  id_pedido    INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente   INT NOT NULL,
  id_usuario   INT NOT NULL,
  fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado       ENUM('pendiente', 'alistado', 'entregado', 'cancelado') NOT NULL DEFAULT 'pendiente',
  total        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_cliente)
    REFERENCES cliente (id_cliente)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_pedido_usuario FOREIGN KEY (id_usuario)
    REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_pedido_total CHECK (total >= 0),
  INDEX idx_pedido_fecha (fecha_pedido),
  INDEX idx_pedido_cliente (id_cliente),
  INDEX idx_pedido_usuario (id_usuario),
  INDEX idx_pedido_estado (estado)
) ENGINE=InnoDB;

-- 7. Tabla: detalle_pedido
CREATE TABLE IF NOT EXISTS detalle_pedido (
  id_pedido       INT NOT NULL,
  id_producto     INT NOT NULL,
  cantidad        INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal        DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id_pedido, id_producto),
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (id_pedido)
    REFERENCES pedido (id_pedido)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto)
    REFERENCES producto (id_producto)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_detalle_cantidad CHECK (cantidad > 0),
  CONSTRAINT ck_detalle_precio   CHECK (precio_unitario >= 0),
  INDEX idx_detalle_producto (id_producto)
) ENGINE=InnoDB;

-- 8. Tabla: pago
CREATE TABLE IF NOT EXISTS pago (
  id_pedido   INT PRIMARY KEY,
  metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
  monto       DECIMAL(10,2) NOT NULL,
  fecha_pago  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pago_pedido FOREIGN KEY (id_pedido)
    REFERENCES pedido (id_pedido)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT ck_pago_monto CHECK (monto >= 0),
  INDEX idx_pago_fecha (fecha_pago)
) ENGINE=InnoDB;

-- ===================================================
-- Datos Iniciales
-- ===================================================

INSERT IGNORE INTO rol (id_rol, nombre) VALUES
  (1, 'Administrador'),
  (2, 'Vendedor/Cajero');

-- Usuarios iniciales de prueba (password: admin123 y vendedor123)
-- Creados con password_hash(..., PASSWORD_DEFAULT)
INSERT IGNORE INTO usuario (id_usuario, id_rol, nombre, correo, contrasena, activo) VALUES
  (1, 1, 'Administrador Principal', 'admin@levelup.com', '$2y$12$Frovo79TI7EM0X8C2mU4RO4knxLfIzI0PhwbNKnscv8jUU1oKPzJO', 1),
  (2, 2, 'Cajero Turno Principal', 'vendedor@levelup.com', '$2y$12$TuOjp0iJhOCYI6z43gu3bePzVJVL1J2fbUGIUAFNWByMVGgXFYDEG', 1);

INSERT IGNORE INTO categoria (id_categoria, nombre, descripcion) VALUES
  (1, 'Consolas',       'Consolas de videojuegos'),
  (2, 'Juegos',         'Videojuegos físicos y digitales'),
  (3, 'Accesorios',     'Controles, audífonos y más'),
  (4, 'Coleccionables', 'Figuras y artículos de colección');

INSERT IGNORE INTO producto (id_producto, id_categoria, nombre, plataforma, precio, stock, stock_minimo, activo) VALUES
  (1, 1, 'PlayStation 5 Slim',          'PS5',             2299900.00, 4, 2, 1),
  (2, 1, 'Nintendo Switch OLED',        'Switch',          1649900.00, 2, 2, 1),
  (3, 2, 'EA Sports FC 26',             'PS5',              249900.00, 12, 4, 1),
  (4, 2, 'Zelda: Tears of the Kingdom', 'Switch',           259900.00, 0, 3, 1),
  (5, 3, 'Control DualSense',           'PS5',              329900.00, 7, 3, 1),
  (6, 3, 'Audífonos gamer',             'Multiplataforma',  189900.00, 9, 3, 1),
  (7, 4, 'Figura Mario 25 cm',          'Coleccionable',    129900.00, 3, 3, 1);

INSERT IGNORE INTO cliente (id_cliente, nombre, telefono, correo) VALUES
  (1, 'Laura Pérez',  '3001234567', 'laura@correo.com'),
  (2, 'Andrés Rojas', '3109876543', 'andres@correo.com'),
  (3, 'Camila Díaz',  '3205558899', 'camila@correo.com');
