<?php
// ====================================================================
// Controlador: Clientes (RF-06)
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Cliente.php';

class ClienteController {

    // Listar clientes
    public function index() {
        requerirAutenticacion();
        $busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : null;
        $clientes = Cliente::listar($busqueda);
        $titulo   = "Gestión de Clientes | Level Up Games";
        require_once __DIR__ . '/../vistas/clientes/index.php';
    }

    // Formulario de nuevo cliente
    public function crear() {
        requerirAutenticacion();
        $cliente = null;
        $titulo  = "Nuevo Cliente | Level Up Games";
        require_once __DIR__ . '/../vistas/clientes/formulario.php';
    }

    // Guardar nuevo cliente
    public function guardar() {
        requerirAutenticacion();

        $datos = [
            'nombre'   => $_POST['nombre'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'correo'   => $_POST['correo'] ?? ''
        ];

        if (empty($datos['nombre'])) {
            establecerMensaje('peligro', 'El nombre del cliente es obligatorio.');
            redireccionar('clientes/crear');
        }

        try {
            Cliente::crear($datos);
            establecerMensaje('exito', 'Cliente registrado correctamente.');
            redireccionar('clientes');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al guardar cliente: ' . $error->getMessage());
            redireccionar('clientes/crear');
        }
    }

    // Formulario para editar cliente
    public function editar() {
        requerirAutenticacion();
        $id = (int)($_GET['id'] ?? 0);
        $cliente = Cliente::buscarPorId($id);

        if (!$cliente) {
            establecerMensaje('peligro', 'El cliente no existe.');
            redireccionar('clientes');
        }

        $titulo = "Editar Cliente | Level Up Games";
        require_once __DIR__ . '/../vistas/clientes/formulario.php';
    }

    // Actualizar datos del cliente
    public function actualizar() {
        requerirAutenticacion();
        $id = (int)($_POST['id_cliente'] ?? 0);

        $datos = [
            'nombre'   => $_POST['nombre'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'correo'   => $_POST['correo'] ?? ''
        ];

        if (empty($datos['nombre'])) {
            establecerMensaje('peligro', 'El nombre del cliente es obligatorio.');
            redireccionar('clientes/editar&id=' . $id);
        }

        try {
            Cliente::actualizar($id, $datos);
            establecerMensaje('exito', 'Cliente actualizado con éxito.');
            redireccionar('clientes');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al actualizar cliente: ' . $error->getMessage());
            redireccionar('clientes/editar&id=' . $id);
        }
    }

    // Eliminar cliente
    public function eliminar() {
        requerirAdministrador();
        $id = (int)($_POST['id_cliente'] ?? 0);

        try {
            $resultado = Cliente::eliminar($id);
            if ($resultado) {
                establecerMensaje('exito', 'Cliente eliminado correctamente.');
            } else {
                establecerMensaje('peligro', 'No se puede eliminar el cliente porque tiene pedidos asociados.');
            }
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al eliminar cliente: ' . $error->getMessage());
        }
        redireccionar('clientes');
    }
}
