# Sesión 2 · Problema, actores y requisitos (ISO/IEC/IEEE 29148:2018)

**Descripción del problema.** Las tiendas pequeñas de videojuegos anotan las ventas a mano, cuentan el inventario a ojo y no saben qué se vende más. Esto causa errores de cobro, juegos agotados sin aviso y decisiones sin datos.&nbsp;

**Pregunta problema.** ¿Cómo puede un sistema web mejorar la gestión de pedidos e inventario en una tienda de videojuegos?&nbsp;

**Justificación.** Un sistema propio reduce errores, ahorra tiempo en caja y da datos para decidir qué comprar.&nbsp;

**Objetivo general.** Desarrollar un sistema web para gestionar pedidos, productos e inventario de la tienda Level Up Games.&nbsp;

**Objetivos específicos**

1. Registrar pedidos con cálculo automático del total.  
2. Controlar el stock con descuento automático y alertas de pocas unidades.  
3. Administrar productos, categorías y clientes.  
4. Generar un panel y reportes de ventas por mes, producto y método de pago.  
5. Publicar un catálogo digital público.

**Alcance.** Inicio de sesión con roles, catálogo público, categorías, productos, clientes, pedidos, pagos, inventario, panel y reportes.&nbsp;

**Limitaciones.** Sin pago en línea real, sin app móvil, el cliente no hace pedidos desde su cuenta (los registra el vendedor), un solo local.&nbsp;

## Actores

| Actor | Responsabilidad | Interacción con el sistema |
| :---- | :---- | :---- |
| Administrador | Catálogos, precios, usuarios y reportes&nbsp; | Todos los módulos |
| Vendedor/Cajero&nbsp; | Registrar pedidos, cobrar, cambiar estados | Pedidos, clientes, productos&nbsp; |
| Cliente | Consultar el menú | Catálogo público&nbsp; |

Casos de uso (dibújalos en draw.io):&nbsp;

* Iniciar sesión&nbsp;  
* Gestionar categorías&nbsp;  
* Gestionar productos&nbsp;  
* Gestionar clientes&nbsp;  
* Registrar pedido&nbsp;  
* Cobrar pedido&nbsp;  
* Cambiar estado&nbsp;  
* Cancelar pedido&nbsp;  
* Ver panel&nbsp;  
* Ver reportes&nbsp;  
* Consultar catálogo.&nbsp;

## Requisitos funcionales

| ID | Nombre | Descripción | Prioridad | Criterio de aceptación |
| :---- | :---- | :---- | :---- | :---- |
| RF-01 | Iniciar sesión | Acceso con correo y contraseña | Alta | Dado que el usuario tiene una cuenta, al hacer clic en “Ingresar” con su correo y contraseña correctos, el sistema lo lleva a su panel.  Dado que el usuario escribe datos incorrectos, al hacer clic en “Ingresar”, el sistema muestra un mensaje de error y no lo deja entrar.&nbsp; |
| RF-02 | Cerrar sesión | Termina la sesión | Alta | Dado que el usuario cierra sesión al volver hacia atrás el sistema le pedirá volver a registrarse impidiendole el acceso a los módulos . |
| RF-03 | Gestionar categorías | Crear, listar, editar y eliminar categorías | Alta | Dado que el administrador está en la sección de categorías, al hacer clic en “Guardar” con los datos completos (consolas, juegos, accesorios), el sistema muestra la categoría en el listado.Dado que una categoría tiene productos, al hacer clic en “Eliminar”, el sistema no la borra y avisa el motivo.&nbsp; |
| RF-04 | Gestionar productos | CRUD con precio, stock y categoría | Alta | Dado que el administrador completa el nombre, precio, plataforma, stock y categoría, al hacer clic en “Guardar”, el sistema muestra el producto en el listado y en el catálogo. Dado que existen varios productos, al hacer una búsqueda por nombre, el sistema muestra solo los productos que coinciden.&nbsp; |
| RF-05 | Buscar productos | Filtrar por nombre | Media | Dado que el vendedor llena el nombre, teléfono y correo del cliente, al hacer clic en “Guardar”, el sistema muestra al cliente en el listado y lo deja disponible para sus pedidos.&nbsp; |
| RF-06 | Gestionar clientes | CRUD con teléfono y correo | Alta | Dado que el vendedor llena el nombre, teléfono y correo del cliente, al hacer clic en “Guardar”, el sistema muestra al cliente en el listado y lo deja disponible para sus pedidos.&nbsp; |
| RF-07 | Registrar pedido | Elegir cliente y productos con cantidades | Alta | Dado que el vendedor eligió un cliente y productos con sus cantidades, al hacer clic en “Registrar pedido”, el sistema guarda el pedido con estado “pendiente” y calcula el total (cantidad × precio), que coincide con el detalle.&nbsp; |
| RF-08 | Calcular total | El servidor suma cantidad × precio | Alta | Dado que un producto tiene stock disponible, al hacer una venta, el sistema descuenta las unidades vendidas. Dado que un pedido se cancela, al hacer clic en “Cancelar”, el sistema devuelve las unidades al stock. Dado que no hay stock suficiente, al hacer clic en “Registrar pedido”, el sistema no permite la venta y muestra un aviso.&nbsp; |
| RF-09 | Controlar stock | Descontar al vender y devolver al cancelar | Alta | Dado que un pedido está en un estado (pendiente → alistado → entregado), al hacer clic en “Siguiente estado”, el sistema lo pasa solo al paso que sigue.&nbsp; |
| RF-10 | Cambiar estado | pendiente → preparando → entregado | Alta | Dado que existe un pedido pendiente de pago, al hacer clic en “Cobrar” y elegir efectivo, tarjeta o transferencia, el sistema guarda el pago y lo muestra en el pedido. Dado que un pedido no tiene pago, al hacer clic en “Entregar”, el sistema no lo permite y avisa que falta el pago.&nbsp; |
| RF-11 | Registrar pago | Efectivo, tarjeta o transferencia | Alta | Dado que un pedido no tiene pago registrado, al hacer clic en “Cancelar”, el sistema lo marca como “cancelado”. Dado que un pedido ya tiene pago, al hacer clic en “Cancelar”, el sistema no lo permite.&nbsp; |
| RF-12 | Cancelar pedido | Cancela pedidos sin pago | Media | Dado que el administrador inició sesión, al hacer clic en “Panel”, el sistema muestra las ventas del día, los pedidos pendientes, los productos más vendidos y el stock bajo, con cifras que coinciden con los datos.&nbsp; |
| RF-13 | Panel | Ventas del día, pendientes, más vendidos, stock bajo | Alta | Dado que existen ventas registradas, al hacer clic en “Reportes”, el sistema muestra los ingresos por mes, los clientes frecuentes y los métodos de pago, con cifras que coinciden con las consultas SQL. Dado que el reporte está en pantalla, al hacer clic en “Descargar CSV”, el sistema descarga el archivo con esos datos.&nbsp; |
| RF-14 | Reportes | Ingresos por mes, clientes frecuentes, métodos de pago, CSV | Media | Dado que existen ingresos por mes, clientes frecuentes y uso de métodos de pagos al hacer clic a “descargar reporte ” el sistema descargara un reporte en un archivo csv con la información solicitada teniendo en cuenta los rangos de tiempo puestos. |
| RF-15 | Menú público | Lista de productos por categoría sin iniciar sesión | Media | Dado que una persona entra al catálogo sin iniciar sesión, al hacer clic en una categoría, el sistema muestra los productos activos con su precio y marca los agotados.&nbsp; |

Requisitos no funcionales

| ID | Descripción | Criterio |
| :---- | :---- | :---- |
| RNF-01 | Rendimiento | Cada página responde en menos de 3 segundos |
| RNF-02 | Seguridad de claves | Contraseñas cifradas con `password_hash` |
| RNF-03 | Seguridad de datos | Consultas preparadas PDO, escape de salida y token CSRF |
| RNF-04 | Responsive | Se usa en pantallas de 390 px y de 1440 px |
| RNF-05 | Compatibilidad | Funciona en Chrome y Firefox actuales |
| RNF-06 | Accesibilidad | Contraste de texto de al menos 4,5:1 y foco visible |
| RNF-07 | Mantenibilidad | Arquitectura MVC y código comentado |

## Reglas de negocio

| ID | Regla |
| :---- | :---- |
| RN-01 | No se vende un producto sin stock suficiente |
| RN-02 | Un pedido entregado o cancelado no se puede modificar |
| RN-03 | El total se calcula siempre en el servidor desde el detalle |
| RN-04 | No se entrega un pedido sin pago registrado |

## Historias de usuario

| ID | Historia | Requisito |
| :---- | :---- | :---- |
| HU-01 | Como barista, quiero iniciar sesión para acceder a mi panel | RF-01 |
| HU-02 | Como administrador, quiero gestionar categorías para organizar el catálogo&nbsp; | RF-03 |
| HU-03 | Como administrador, quiero gestionar productos para mantener precios y stock | RF-04 |
| HU-04 | Como barista, quiero buscar un producto para encontrarlo rápido | RF-05 |
| HU-05 | Como vendedor, quiero registrar clientes para asociarlos pedidos&nbsp; | RF-06 |
| HU-06 | Como vendedor, quiero registrar un pedido para vender sin errores&nbsp; | RF-07, RF-08 |
| HU-07 | Como vendedor, quiero que el stock se descuente solo para no vender lo que no hay&nbsp; | RF-09 |
| HU-08 | Como vendedor, quiero cambiar el estado del pedido para saber cómo va&nbsp; | RF-10 |
| HU-09 | Como cajero, quiero registrar el pago para cerrar la venta&nbsp; | RF-11 |
| HU-10 | Como administrador, quiero ver el panel para saber cómo va el día&nbsp; | RF-13 |
| HU-11 | Como administrador, quiero reportes para decidir qué decisiones tomar&nbsp; | RF-14 |
| HU-12 | Como cliente, quiero ver el menú desde mi celular para elegir antes de llegar | RF-15 |

**Entrega:** diagrama de casos de uso \+ este documento en `docs/requisitos.pdf` · commit `docs: agregar requisitos`