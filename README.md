# Level Up Games - Sistema de Gestión de Videojuegos

Proyecto desarrollado con arquitectura **MVC (Modelo - Vista - Controlador)** en **PHP y MySQL** para la gestión de pedidos, inventario, clientes y reportes de la tienda de videojuegos *Level Up Games*.

---

## 📁 Estructura del Proyecto (MVC en Español)

```
clase 8/
├── config/
│   ├── conexion.php           # Conexión a la base de datos MySQL con PDO
│   └── ayudas.php             # Funciones de sesión, alertas flash y seguridad
├── controladores/
│   ├── AuthController.php      # Inicio y cierre de sesión (RF-01, RF-02)
│   ├── DashboardController.php # Panel con métricas y alertas del día (RF-13)
│   ├── MenuController.php      # Catálogo digital público sin login (RF-15)
│   ├── CategoriaController.php # CRUD de categorías (RF-03)
│   ├── ProductoController.php  # CRUD y búsqueda de inventario (RF-04, RF-05, RF-09)
│   ├── ClienteController.php   # Directorio y registro de clientes (RF-06)
│   ├── PedidoController.php    # Pedidos, caja y ciclo de estados (RF-07 a RF-12)
│   └── ReporteController.php   # Filtros de fechas y exportación CSV (RF-14)
├── modelos/
│   ├── Usuario.php             # Consultas de usuario y validación de claves
│   ├── Categoria.php           # Categorías de videojuegos y verificación de productos
│   ├── Producto.php            # Inventario, precios, stock y alertas
│   ├── Cliente.php             # Datos del cliente y compras realizadas
│   ├── Pedido.php              # Transacción PDO, cálculo en servidor y stock
│   └── Pago.php                # Registro de cobros (efectivo, tarjeta, transferencia)
├── vistas/
│   ├── plantilla/              # Cabecera, menú lateral y pie de página
│   ├── auth/login.php          # Pantalla de inicio de sesión
│   ├── menu/index.php          # Catálogo público para clientes
│   ├── dashboard/index.php     # Panel principal con tarjetas de cifras
│   ├── categorias/             # Listado y formulario de categorías
│   ├── productos/              # Listado, inventario y formulario de productos
│   ├── clientes/               # Directorio y formulario de clientes
│   ├── pedidos/                # Listado, nuevo pedido y detalle de cobro
│   └── reportes/index.php      # Gráficos, métricas por fecha y descarga CSV
├── database/
│   ├── Level_Up_Games_Database_MySQL.sql # Base de datos lista para XAMPP
│   └── Level_Up_Games_Database.sql       # Script original
├── public/
│   └── css/levelup.css         # Estilos basados en el sistema de diseño Figma
├── index.php                   # Enrutador frontal principal
└── README.md                   # Documentación general
```

---

## 🚀 Cómo ejecutar en XAMPP

1. Iniciar los servicios **Apache** y **MySQL** desde el Panel de Control de XAMPP.
2. Abrir el navegador e ingresar a:
   ```
   http://localhost/clase%208/
   ```
3. El sistema cargará por defecto el **Catálogo Público Digital** (accesible sin iniciar sesión).

---

## 🔑 Cuentas de Acceso (Pruebas)

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@levelup.com` | `admin123` |
| **Vendedor / Cajero** | `vendedor@levelup.com` | `vendedor123` |

---

## 🎮 Reglas de Negocio Implementadas

* **RN-01:** No se puede vender un producto sin stock disponible suficiente.
* **RN-02:** Los pedidos en estado `entregado` o `cancelado` no se pueden modificar.
* **RN-03:** El cálculo del total se hace siempre en el servidor desde la base de datos.
* **RN-04:** No se permite marcar un pedido como `entregado` sin antes registrar el pago.
* Al cancelar un pedido sin pagar, las unidades se devuelven automáticamente al stock.
