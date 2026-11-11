<?php
// ====================================================================
// Controlador: Productos e Inventario (RF-04, RF-05, RF-09)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Producto.php';
require_once __DIR__ . '/../modelos/Categoria.php';

class ProductoController {

    // Listar productos e inventario
    public function index() {
        requerirAutenticacion();

        $busqueda    = isset($_GET['buscar']) ? trim($_GET['buscar']) : null;
        $idCategoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;

        $productos  = Producto::listar($busqueda, $idCategoria, null);
        $categorias = Categoria::listar();
        $metricas   = Producto::obtenerMetricas();

        $titulo = "Productos e Inventario | Level Up Games";
        require_once __DIR__ . '/../vistas/productos/index.php';
    }

    // Formulario de creación
    public function crear() {
        requerirAdministrador();
        $producto   = null;
        $categorias = Categoria::listar();
        $titulo     = "Nuevo Producto | Level Up Games";
        require_once __DIR__ . '/../vistas/productos/formulario.php';
    }

    // Guardar nuevo producto
    public function guardar() {
        requerirAdministrador();

        $datos = [
            'id_categoria' => $_POST['id_categoria'] ?? '',
            'nombre'       => $_POST['nombre'] ?? '',
            'plataforma'   => $_POST['plataforma'] ?? '',
            'precio'       => $_POST['precio'] ?? '',
            'stock'        => $_POST['stock'] ?? 0,
            'stock_minimo' => $_POST['stock_minimo'] ?? 0,
            'activo'       => isset($_POST['activo']) ? 1 : 0
        ];

        if (empty($datos['nombre']) || empty($datos['id_categoria']) || $datos['precio'] === '') {
            establecerMensaje('peligro', 'Los campos Nombre, Categoría y Precio son obligatorios.');
            redireccionar('productos/crear');
        }

        try {
            Producto::crear($datos);
            establecerMensaje('exito', 'Producto guardado exitosamente.');
            redireccionar('productos');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al registrar producto: ' . $error->getMessage());
            redireccionar('productos/crear');
        }
    }

    // Formulario de edición
    public function editar() {
        requerirAdministrador();
        $id = (int)($_GET['id'] ?? 0);
        $producto = Producto::buscarPorId($id);

        if (!$producto) {
            establecerMensaje('peligro', 'El producto no existe.');
            redireccionar('productos');
        }

        $categorias = Categoria::listar();
        $titulo = "Editar Producto | Level Up Games";
        require_once __DIR__ . '/../vistas/productos/formulario.php';
    }

    // Actualizar producto
    public function actualizar() {
        requerirAdministrador();
        $id = (int)($_POST['id_producto'] ?? 0);

        $datos = [
            'id_categoria' => $_POST['id_categoria'] ?? '',
            'nombre'       => $_POST['nombre'] ?? '',
            'plataforma'   => $_POST['plataforma'] ?? '',
            'precio'       => $_POST['precio'] ?? '',
            'stock'        => $_POST['stock'] ?? 0,
            'stock_minimo' => $_POST['stock_minimo'] ?? 0,
            'activo'       => isset($_POST['activo']) ? 1 : 0
        ];

        if (empty($datos['nombre']) || empty($datos['id_categoria']) || $datos['precio'] === '') {
            establecerMensaje('peligro', 'Los campos Nombre, Categoría y Precio son obligatorios.');
            redireccionar('productos/editar&id=' . $id);
        }

        try {
            Producto::actualizar($id, $datos);
            establecerMensaje('exito', 'Producto actualizado correctamente.');
            redireccionar('productos');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al actualizar producto: ' . $error->getMessage());
            redireccionar('productos/editar&id=' . $id);
        }
    }

    // Eliminar o desactivar producto
    public function eliminar() {
        requerirAdministrador();
        $id = (int)($_POST['id_producto'] ?? 0);

        try {
            Producto::eliminar($id);
            establecerMensaje('exito', 'El producto fue eliminado o desactivado según su historial.');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al eliminar producto: ' . $error->getMessage());
        }
        redireccionar('productos');
    }
}
