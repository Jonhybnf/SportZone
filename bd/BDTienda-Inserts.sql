-- ============================================================
--  TIENDA DE ARTÍCULOS DE DEPORTES — Esquema MySQL
--  Codificación: UTF-8 / Motor: InnoDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS tienda_deportes
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tienda_deportes;

-- ------------------------------------------------------------
-- USUARIOS
-- rol: 'cliente' | 'admin'
-- ------------------------------------------------------------
CREATE TABLE usuarios (
  id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  nombre         VARCHAR(120)    NOT NULL,
  contrasena     VARCHAR(255)    NOT NULL,   -- hash bcrypt/argon2, NUNCA en texto plano
  rol            ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',

  PRIMARY KEY (id)
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- CATEGORÍAS  (soporta subcategorías con padre_id)
-- ------------------------------------------------------------
CREATE TABLE categorias (
  id        INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  nombre    VARCHAR(100)  NOT NULL,
  padre_id  INT UNSIGNED  DEFAULT NULL,   -- NULL = categoría raíz

  PRIMARY KEY (id),
  CONSTRAINT fk_cat_padre FOREIGN KEY (padre_id)
    REFERENCES categorias (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- PRODUCTOS
-- ------------------------------------------------------------
CREATE TABLE productos (
  id            INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  categoria_id  INT UNSIGNED   DEFAULT NULL,
  nombre        VARCHAR(200)   NOT NULL,
  descripcion   TEXT           DEFAULT NULL,
  precio        DECIMAL(10,2)  NOT NULL,
  stock         INT            NOT NULL DEFAULT 0,
  activo        TINYINT(1)     NOT NULL DEFAULT 1,

  PRIMARY KEY (id),
  CONSTRAINT fk_prod_categoria FOREIGN KEY (categoria_id)
    REFERENCES categorias (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- IMÁGENES DE PRODUCTO
-- ------------------------------------------------------------
CREATE TABLE imagenes_producto (
  id           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  producto_id  INT UNSIGNED   NOT NULL,
  url          VARCHAR(500)   NOT NULL,

  PRIMARY KEY (id),
  CONSTRAINT fk_img_producto FOREIGN KEY (producto_id)
    REFERENCES productos (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- PEDIDOS
-- Los datos de envío se copian aquí en el momento del pedido
-- para que el ticket sea siempre fiel a lo que se confirmó.
-- ------------------------------------------------------------
CREATE TABLE pedidos (
  id               INT UNSIGNED   NOT NULL AUTO_INCREMENT,
  usuario_id       INT UNSIGNED   DEFAULT NULL,
  numero_pedido    VARCHAR(30)    NOT NULL,
  subtotal         DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  impuestos        DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  total            DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  nombre_envio     VARCHAR(120)   NOT NULL,
  direccion_envio  VARCHAR(200)   NOT NULL,
  ciudad_envio     VARCHAR(100)   NOT NULL,
  cp_envio         VARCHAR(10)    NOT NULL,
  notas            TEXT           DEFAULT NULL,

  PRIMARY KEY (id),
  UNIQUE KEY uq_pedidos_numero (numero_pedido),
  CONSTRAINT fk_ped_usuario FOREIGN KEY (usuario_id)
    REFERENCES usuarios (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ------------------------------------------------------------
-- LÍNEAS DE PEDIDO
-- nombre_producto y precio_unitario son un snapshot del momento
-- de la compra: si el producto cambia de nombre o precio después,
-- el ticket sigue siendo correcto.
-- ------------------------------------------------------------
CREATE TABLE lineas_pedido (
  id               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  pedido_id        INT UNSIGNED    NOT NULL,
  producto_id      INT UNSIGNED    DEFAULT NULL,
  nombre_producto  VARCHAR(200)    NOT NULL,
  precio_unitario  DECIMAL(10,2)   NOT NULL,
  cantidad         INT             NOT NULL DEFAULT 1,
  subtotal         DECIMAL(10,2)   NOT NULL,   -- precio_unitario * cantidad

  PRIMARY KEY (id),
  CONSTRAINT fk_lin_pedido   FOREIGN KEY (pedido_id)
    REFERENCES pedidos (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_lin_producto FOREIGN KEY (producto_id)
    REFERENCES productos (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;


-- ============================================================
--  DATOS DE EJEMPLO
-- ============================================================

-- Categorías (raíz)
INSERT INTO categorias (nombre, padre_id) VALUES
  ('Fútbol',   NULL),
  ('Running',  NULL),
  ('Natación', NULL),
  ('Fitness',  NULL),
  ('Ciclismo', NULL);

-- Subcategorías
INSERT INTO categorias (nombre, padre_id) VALUES
  ('Botas de fútbol', 1),
  ('Balones',         1),
  ('Zapatillas',      2),
  ('Gafas',           3),
  ('Mancuernas',      4);

-- Usuarios  (contraseñas ficticias hasheadas)
INSERT INTO usuarios (nombre, contrasena, rol) VALUES
  ('Admin', '1234', 'admin'),
  ('Jonathan', '1234', 'cliente');

-- Productos
INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, activo) VALUES
  (6,  'Nike Mercurial Vapor',    'Bota de fútbol sala, suela lisa de goma.',           89.99,  50,  1),
  (7,  'Balón Adidas Champions',  'Balón oficial talla 5, cosido a mano.',              24.95, 120,  1),
  (8,  'Asics Gel-Kayano 40',     'Máxima amortiguación para largas distancias.',      134.00,  30,  1),
  (9,  'Gafas Speedo Biofuse',    'Lente antivaho, correa ajustable.',                  22.50,  80,  1),
  (10, 'Mancuernas 10 kg par',    'Recubiertas de neopreno, agarre antideslizante.',    38.00,  25,  1),
  (6,  'Bañador Arena Pro',       'Fibra técnica resistente al cloro.',                 49.00,   0,  1);

-- Imágenes de producto
INSERT INTO imagenes_producto (producto_id, url) VALUES
  (1, '/img/productos/nike-mercurial-1.jpg'),
  (1, '/img/productos/nike-mercurial-2.jpg'),
  (2, '/img/productos/adidas-champions-1.jpg'),
  (3, '/img/productos/asics-gel-1.jpg'),
  (4, '/img/productos/speedo-biofuse-1.jpg'),
  (5, '/img/productos/mancuernas-1.jpg');

-- Pedido de ejemplo
INSERT INTO pedidos
  (usuario_id, numero_pedido, subtotal, impuestos, total,
   nombre_envio, direccion_envio, ciudad_envio, cp_envio)
VALUES
  (2, 'PED-2024-00001', 139.89, 29.38, 169.27,
   'María García', 'Calle Mayor 10', 'Madrid', '28001');

-- Líneas del pedido anterior
INSERT INTO lineas_pedido
  (pedido_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal)
VALUES
  (1, 1, 'Nike Mercurial Vapor',   89.99, 1,  89.99),
  (1, 2, 'Balón Adidas Champions', 24.95, 2,  49.90);


-- ============================================================
--  CONSULTA PARA GENERAR EL TICKET PDF
-- ============================================================
/*
SELECT
  p.numero_pedido,
  p.nombre_envio,
  p.direccion_envio,
  p.ciudad_envio,
  p.cp_envio,
  p.notas,
  p.subtotal,
  p.impuestos,
  p.total,
  u.nombre                AS nombre_cliente,
  lp.nombre_producto,
  lp.precio_unitario,
  lp.cantidad,
  lp.subtotal             AS subtotal_linea
FROM pedidos p
LEFT JOIN usuarios u       ON u.id = p.usuario_id
JOIN  lineas_pedido lp     ON lp.pedido_id = p.id
WHERE p.numero_pedido = 'PED-2024-00001'
ORDER BY lp.id;
*/