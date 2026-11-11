<?php
// ====================================================================
// Controlador: Categorías (RF-03)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Categoria.php';

class CategoriaController {

    // Listar categorías
    public function index() {
        requerirAutenticacion();
        $categorias = Categoria::listar();
        $titulo = "Gestión de Categorías | Level Up Games";
        require_once __DIR__ . '/../vistas/categorias/index.php';
    }

    // Mostrar formulario de creación
    public function crear() {
        requerirAdministrador();
        $categoria = null;
        $titulo = "Nueva Categoría | Level Up Games";
        require_once __DIR__ . '/../vistas/categorias/formulario.php';
    }

    // Guardar nueva categoría
    public function guardar() {
        requerirAdministrador();

        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($nombre)) {
            establecerMensaje('peligro', 'El nombre de la categoría es obligatorio.');
            redireccionar('categorias/crear');
        }

        try {
            Categoria::crear($nombre, $descripcion);
            establecerMensaje('exito', 'Categoría creada correctamente.');
            redireccionar('categorias');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al guardar la categoría: ' . $error->getMessage());
            redireccionar('categorias/crear');
        }
    }

    // Mostrar formulario de edición
    public function editar() {
        requerirAdministrador();
        $id = (int)($_GET['id'] ?? 0);
        $categoria = Categoria::buscarPorId($id);

        if (!$categoria) {
            establecerMensaje('peligro', 'La categoría solicitada no existe.');
            redireccionar('categorias');
        }

        $titulo = "Editar Categoría | Level Up Games";
        require_once __DIR__ . '/../vistas/categorias/formulario.php';
    }

    // Actualizar categoría
    public function actualizar() {
        requerirAdministrador();
        $id          = (int)($_POST['id_categoria'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($nombre)) {
            establecerMensaje('peligro', 'El nombre de la categoría es obligatorio.');
            redireccionar('categorias/editar&id=' . $id);
        }

        try {
            Categoria::actualizar($id, $nombre, $descripcion);
            establecerMensaje('exito', 'Categoría actualizada exitosamente.');
            redireccionar('categorias');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al actualizar categoría: ' . $error->getMessage());
            redireccionar('categorias/editar&id=' . $id);
        }
    }

    // Eliminar categoría
    public function eliminar() {
        requerirAdministrador();
        $id = (int)($_POST['id_categoria'] ?? 0);

        if (Categoria::tieneProductos($id)) {
            establecerMensaje('peligro', 'No se puede eliminar la categoría porque contiene productos asignados.');
            redireccionar('categorias');
        }

        try {
            Categoria::eliminar($id);
            establecerMensaje('exito', 'Categoría eliminada correctamente.');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al eliminar: ' . $error->getMessage());
        }
        redireccionar('categorias');
    }
}
