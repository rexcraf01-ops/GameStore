<?php
// ====================================================================
// Punto de entrada principal (Enrutador Frontal - MVC Sencillo)
// Sistema: Level Up Games
// Estudiante: 4to Semestre - Análisis y Desarrollo de Software
// ====================================================================

require_once __DIR__ . '/config/ayudas.php';

// Cargar todos los controladores del sistema
require_once __DIR__ . '/app/controladores/AuthController.php';
require_once __DIR__ . '/app/controladores/DashboardController.php';
require_once __DIR__ . '/app/controladores/MenuController.php';
require_once __DIR__ . '/app/controladores/CategoriaController.php';
require_once __DIR__ . '/app/controladores/ProductoController.php';
require_once __DIR__ . '/app/controladores/ClienteController.php';
require_once __DIR__ . '/app/controladores/PedidoController.php';
require_once __DIR__ . '/app/controladores/ReporteController.php';

// Obtener la acción por GET, por defecto muestra el catálogo público
$accion = $_GET['accion'] ?? 'menu';

switch ($accion) {

    // ----------------------------------------------------
    // Catálogo Público (RF-15)
    // ----------------------------------------------------
    case 'menu':
        (new MenuController())->index();
        break;

    // ----------------------------------------------------
    // Autenticación (RF-01, RF-02)
    // ----------------------------------------------------
    case 'login':
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->iniciarSesion();
        } else {
            $auth->mostrarLogin();
        }
        break;

    case 'logout':
        (new AuthController())->cerrarSesion();
        break;

    // ----------------------------------------------------
    // Panel de Control / Dashboard (RF-13)
    // ----------------------------------------------------
    case 'dashboard':
        (new DashboardController())->index();
        break;

    // ----------------------------------------------------
    // Módulo de Categorías (RF-03)
    // ----------------------------------------------------
    case 'categorias':
        (new CategoriaController())->index();
        break;
    case 'categorias/crear':
        (new CategoriaController())->crear();
        break;
    case 'categorias/guardar':
        (new CategoriaController())->guardar();
        break;
    case 'categorias/editar':
        (new CategoriaController())->editar();
        break;
    case 'categorias/actualizar':
        (new CategoriaController())->actualizar();
        break;
    case 'categorias/eliminar':
        (new CategoriaController())->eliminar();
        break;

    // ----------------------------------------------------
    // Módulo de Productos e Inventario (RF-04, RF-05, RF-09)
    // ----------------------------------------------------
    case 'productos':
        (new ProductoController())->index();
        break;
    case 'productos/crear':
        (new ProductoController())->crear();
        break;
    case 'productos/guardar':
        (new ProductoController())->guardar();
        break;
    case 'productos/editar':
        (new ProductoController())->editar();
        break;
    case 'productos/actualizar':
        (new ProductoController())->actualizar();
        break;
    case 'productos/eliminar':
        (new ProductoController())->eliminar();
        break;

    // ----------------------------------------------------
    // Módulo de Clientes (RF-06)
    // ----------------------------------------------------
    case 'clientes':
        (new ClienteController())->index();
        break;
    case 'clientes/crear':
        (new ClienteController())->crear();
        break;
    case 'clientes/guardar':
        (new ClienteController())->guardar();
        break;
    case 'clientes/editar':
        (new ClienteController())->editar();
        break;
    case 'clientes/actualizar':
        (new ClienteController())->actualizar();
        break;
    case 'clientes/eliminar':
        (new ClienteController())->eliminar();
        break;

    // ----------------------------------------------------
    // Módulo de Pedidos y Cobro en Caja (RF-07 a RF-12)
    // ----------------------------------------------------
    case 'pedidos':
        (new PedidoController())->index();
        break;
    case 'pedidos/nuevo':
        (new PedidoController())->nuevo();
        break;
    case 'pedidos/crear':
        (new PedidoController())->crear();
        break;
    case 'pedidos/detalle':
        (new PedidoController())->detalle();
        break;
    case 'pedidos/avanzar':
        (new PedidoController())->avanzar();
        break;
    case 'pedidos/cancelar':
        (new PedidoController())->cancelar();
        break;
    case 'pedidos/pagar':
        (new PedidoController())->registrarPago();
        break;

    // ----------------------------------------------------
    // Módulo de Reportes (RF-14)
    // ----------------------------------------------------
    case 'reportes':
        (new ReporteController())->index();
        break;
    case 'reportes/csv':
        (new ReporteController())->exportarCSV();
        break;

    // ----------------------------------------------------
    // Ruta por Defecto
    // ----------------------------------------------------
    default:
        (new MenuController())->index();
        break;
}
