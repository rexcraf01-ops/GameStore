
CREATE TYPE estado_pedido AS ENUM ('pendiente', 'alistado', 'entregado', 'cancelado');
CREATE TYPE metodo_pago   AS ENUM ('efectivo', 'tarjeta', 'transferencia');


CREATE TABLE rol (
  id_rol  SMALLINT    GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nombre  VARCHAR(30) NOT NULL,
  CONSTRAINT uq_rol_nombre UNIQUE (nombre)
);


CREATE TABLE usuario (
  id_usuario     INT          GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  id_rol         SMALLINT     NOT NULL,
  nombre         VARCHAR(80)  NOT NULL,
  correo         VARCHAR(100) NOT NULL,
  contrasena     VARCHAR(255) NOT NULL,
  activo         BOOLEAN      NOT NULL DEFAULT TRUE,
  fecha_creacion TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT uq_usuario_correo UNIQUE (correo),
  CONSTRAINT fk_usuario_rol FOREIGN KEY (id_rol)
    REFERENCES rol (id_rol)
    ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE categoria (
  id_categoria INT          GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nombre       VARCHAR(50)  NOT NULL,
  descripcion  VARCHAR(150),
  CONSTRAINT uq_categoria_nombre UNIQUE (nombre)
);

CREATE TABLE producto (
  id_producto  INT           GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  id_categoria INT           NOT NULL,
  nombre       VARCHAR(100)  NOT NULL,
  plataforma   VARCHAR(40),
  precio       NUMERIC(10,2) NOT NULL,
  stock        INT           NOT NULL DEFAULT 0,
  stock_minimo INT           NOT NULL DEFAULT 0,
  activo       BOOLEAN       NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria)
    REFERENCES categoria (id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_producto_precio CHECK (precio >= 0),
  CONSTRAINT ck_producto_stock  CHECK (stock >= 0),
  CONSTRAINT ck_producto_minimo CHECK (stock_minimo >= 0)
);
CREATE INDEX idx_producto_nombre    ON producto (nombre);
CREATE INDEX idx_producto_categoria ON producto (id_categoria);


CREATE TABLE cliente (
  id_cliente     INT          GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  nombre         VARCHAR(80)  NOT NULL,
  telefono       VARCHAR(20),
  correo         VARCHAR(100),
  fecha_registro TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_cliente_nombre ON cliente (nombre);


CREATE TABLE pedido (
  id_pedido    INT           GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  id_cliente   INT           NOT NULL,
  id_usuario   INT           NOT NULL,
  fecha_pedido TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado       estado_pedido NOT NULL DEFAULT 'pendiente',
  total        NUMERIC(10,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_cliente)
    REFERENCES cliente (id_cliente)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_pedido_usuario FOREIGN KEY (id_usuario)
    REFERENCES usuario (id_usuario)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_pedido_total CHECK (total >= 0)
);
CREATE INDEX idx_pedido_fecha   ON pedido (fecha_pedido);  -- reportes por rango de fechas
CREATE INDEX idx_pedido_cliente ON pedido (id_cliente);
CREATE INDEX idx_pedido_usuario ON pedido (id_usuario);
CREATE INDEX idx_pedido_estado  ON pedido (estado);


CREATE TABLE detalle_pedido (
  id_pedido       INT           NOT NULL,
  id_producto     INT           NOT NULL,
  cantidad        INT           NOT NULL,
  precio_unitario NUMERIC(10,2) NOT NULL,
  subtotal        NUMERIC(10,2) NOT NULL,
  PRIMARY KEY (id_pedido, id_producto),
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (id_pedido)
    REFERENCES pedido (id_pedido)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_detalle_producto FOREIGN KEY (id_producto)
    REFERENCES producto (id_producto)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ck_detalle_cantidad CHECK (cantidad > 0),
  CONSTRAINT ck_detalle_precio   CHECK (precio_unitario >= 0)
);
CREATE INDEX idx_detalle_producto ON detalle_pedido (id_producto);


CREATE TABLE pago (
  id_pedido   INT           PRIMARY KEY,
  metodo_pago metodo_pago   NOT NULL,
  monto       NUMERIC(10,2) NOT NULL,
  fecha_pago  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pago_pedido FOREIGN KEY (id_pedido)
    REFERENCES pedido (id_pedido)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT ck_pago_monto CHECK (monto >= 0)
);
CREATE INDEX idx_pago_fecha ON pago (fecha_pago);  -- reportes por rango de fechas



INSERT INTO rol (nombre) VALUES
  ('Administrador'),
  ('Vendedor/Cajero');

-- Los usuarios se crean desde el sistema para que la contraseña
-- quede cifrada con password_hash de PHP.

INSERT INTO categoria (nombre, descripcion) VALUES
  ('Consolas',       'Consolas de videojuegos'),
  ('Juegos',         'Videojuegos físicos y digitales'),
  ('Accesorios',     'Controles, audífonos y más'),
  ('Coleccionables', 'Figuras y artículos de colección');

INSERT INTO producto (id_categoria, nombre, plataforma, precio, stock, stock_minimo) VALUES
  (1, 'PlayStation 5 Slim',          'PS5',             2299900.00, 4, 2),
  (1, 'Nintendo Switch OLED',        'Switch',          1649900.00, 2, 2),
  (2, 'EA Sports FC 26',             'PS5',              249900.00, 12, 4),
  (2, 'Zelda: Tears of the Kingdom', 'Switch',           259900.00, 0, 3),
  (3, 'Control DualSense',           'PS5',              329900.00, 7, 3),
  (3, 'Audífonos gamer',             'Multiplataforma',  189900.00, 9, 3),
  (4, 'Figura Mario 25 cm',          'Coleccionable',    129900.00, 3, 3);

INSERT INTO cliente (nombre, telefono, correo) VALUES
  ('Laura Pérez',  '3001234567', 'laura@correo.com'),
  ('Andrés Rojas', '3109876543', 'andres@correo.com'),
  ('Camila Díaz',  '3205558899', 'camila@correo.com');

