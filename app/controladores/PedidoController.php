<?php
// ====================================================================
// Controlador: Pedidos y Cobro en Caja
// Cumple con RF-07, RF-08, RF-09, RF-10, RF-11, RF-12
// ====================================================================
require_once __DIR__ . '/../../config/ayudas.php';
require_once __DIR__ . '/../modelos/Pedido.php';
require_once __DIR__ . '/../modelos/Cliente.php';
require_once __DIR__ . '/../modelos/Producto.php';

class PedidoController {

    // Listar todos los pedidos
    public function index() {
        requerirAutenticacion();

        $estadoFiltro = $_GET['estado'] ?? 'todos';
        $pedidos = Pedido::listar($estadoFiltro);

        $titulo = "Gestión de Pedidos | Level Up Games";
        require_once __DIR__ . '/../vistas/pedidos/index.php';
    }

    // Formulario de nuevo pedido
    public function nuevo() {
        requerirAutenticacion();

        $clientes  = Cliente::listar();
        $productos = Producto::listar(null, null, true); // solo activos

        $titulo = "Registrar Nuevo Pedido | Level Up Games";
        require_once __DIR__ . '/../vistas/pedidos/nuevo.php';
    }

    // Procesar creación de pedido
    public function crear() {
        requerirAutenticacion();

        $idCliente = (int)($_POST['id_cliente'] ?? 0);
        $idUsuario = (int)$_SESSION['id_usuario'];
        $productosRecibidos = $_POST['productos'] ?? [];

        if (empty($idCliente)) {
            establecerMensaje('peligro', 'Debe seleccionar un cliente.');
            redireccionar('pedidos/nuevo');
        }

        // Estructurar items seleccionados con cantidad mayor a 0
        $items = [];
        foreach ($productosRecibidos as $idProd => $cantidad) {
            $cant = (int)$cantidad;
            if ($cant > 0) {
                $items[] = [
                    'id_producto' => (int)$idProd,
                    'cantidad'    => $cant
                ];
            }
        }

        if (empty($items)) {
            establecerMensaje('peligro', 'Debe indicar al menos un producto con cantidad mayor a 0.');
            redireccionar('pedidos/nuevo');
        }

        try {
            $idPedido = Pedido::crear($idCliente, $idUsuario, $items);
            establecerMensaje('exito', "Pedido PED-$idPedido registrado exitosamente.");
            redireccionar('pedidos/detalle&id=' . $idPedido);
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al registrar pedido: ' . $error->getMessage());
            redireccionar('pedidos/nuevo');
        }
    }

    // Ver detalle de un pedido
    public function detalle() {
        requerirAutenticacion();

        $id = (int)($_GET['id'] ?? 0);
        $pedido = Pedido::buscarPorId($id);

        if (!$pedido) {
            establecerMensaje('peligro', 'El pedido no existe.');
            redireccionar('pedidos');
        }

        $detalles = Pedido::obtenerDetalles($id);

        $titulo = "Detalle de Pedido PED-{$pedido['id_pedido']} | Level Up Games";
        require_once __DIR__ . '/../vistas/pedidos/detalle.php';
    }

    // Avanzar el estado de un pedido (pendiente -> alistado -> entregado)
    public function avanzar() {
        requerirAutenticacion();
        $id = (int)($_POST['id_pedido'] ?? 0);

        try {
            Pedido::avanzar($id);
            establecerMensaje('exito', 'Estado del pedido actualizado.');
        } catch (Exception $error) {
            establecerMensaje('peligro', $error->getMessage());
        }
        redireccionar('pedidos/detalle&id=' . $id);
    }

    // Cancelar un pedido y reponer el inventario
    public function cancelar() {
        requerirAutenticacion();
        $id = (int)($_POST['id_pedido'] ?? 0);

        try {
            Pedido::cancelar($id);
            establecerMensaje('exito', 'El pedido ha sido cancelado y los productos han vuelto al stock.');
        } catch (Exception $error) {
            establecerMensaje('peligro', $error->getMessage());
        }
        redireccionar('pedidos/detalle&id=' . $id);
    }

    // Registrar cobro / pago de un pedido
    public function registrarPago() {
        requerirAutenticacion();

        $idPedido   = (int)($_POST['id_pedido'] ?? 0);
        $metodoPago = trim($_POST['metodo_pago'] ?? '');
        $monto      = !empty($_POST['monto']) ? (float)$_POST['monto'] : null;

        try {
            Pedido::registrarPago($idPedido, $metodoPago, $monto);
            establecerMensaje('exito', 'Pago registrado correctamente en el sistema.');
        } catch (Exception $error) {
            establecerMensaje('peligro', 'Error al registrar pago: ' . $error->getMessage());
        }
        redireccionar('pedidos/detalle&id=' . $idPedido);
    }
}
