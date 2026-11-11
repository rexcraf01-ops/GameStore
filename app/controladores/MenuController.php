<?php
// ====================================================================
// Controlador: Menú / Catálogo Público (RF-15)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Producto.php';
require_once __DIR__ . '/../modelos/Categoria.php';

class MenuController {

    // Mostrar el catálogo digital accesible para clientes sin iniciar sesión
    public function index() {
        $idCategoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
        $busqueda    = isset($_GET['buscar']) ? trim($_GET['buscar']) : null;

        $categorias = Categoria::listar();
        $productos  = Producto::listar($busqueda, $idCategoria, true);

        $titulo = "Catálogo de Videojuegos | Level Up Games";
        require_once __DIR__ . '/../vistas/menu/index.php';
    }
}
