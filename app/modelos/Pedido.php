<?php
// ====================================================================
// Modelo: Pedido
// Cumple con RF-07, RF-08, RF-09, RF-10, RF-11, RF-12
// Reglas de negocio: RN-01, RN-02, RN-03, RN-04
// ====================================================================
require_once __DIR__ . '/../../config/conexion.php';

class Pedido {

    // Listar pedidos filtrando por estado opcionalmente
    public static function listar($estado = null) {
        $conexion = obtenerConexion();
        $sql = "
            SELECT p.*, c.nombre AS cliente_nombre, u.nombre AS usuario_nombre,
                   pg.metodo_pago, pg.monto AS monto_pago, pg.fecha_pago
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            LEFT JOIN pago pg ON p.id_pedido = pg.id_pedido
            WHERE 1=1
        ";
        $parametros = [];

        if (!empty($estado) && $estado !== 'todos') {
            $sql .= " AND p.estado = :estado";
            $parametros[':estado'] = $estado;
        }

        $sql .= " ORDER BY p.id_pedido DESC";

        $consulta = $conexion->prepare($sql);
        $consulta->execute($parametros);
        return $consulta->fetchAll();
    }

    // Buscar pedido por ID con datos del cliente y pago
    public static function buscarPorId($id) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            SELECT p.*, c.nombre AS cliente_nombre, c.telefono AS cliente_telefono, c.correo AS cliente_correo,
                   u.nombre AS usuario_nombre,
                   pg.metodo_pago, pg.monto AS monto_pago, pg.fecha_pago
            FROM pedido p
            INNER JOIN cliente c ON p.id_cliente = c.id_cliente
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            LEFT JOIN pago pg ON p.id_pedido = pg.id_pedido
            WHERE p.id_pedido = :id
            LIMIT 1
        ");
        $consulta->execute([':id' => $id]);
        return $consulta->fetch();
    }

    // Obtener los detalles de productos de un pedido
    public static function obtenerDetalles($idPedido) {
        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            SELECT dp.*, pr.nombre AS producto_nombre, pr.plataforma
            FROM detalle_pedido dp
            INNER JOIN producto pr ON dp.id_producto = pr.id_producto
            WHERE dp.id_pedido = :idPedido
        ");
        $consulta->execute([':idPedido' => $idPedido]);
        return $consulta->fetchAll();
    }

    // RF-07, RF-08, RF-09: Crear pedido calculando total en el servidor y descontando stock
    public static function crear($idCliente, $idUsuario, $productosSeleccionados) {
        if (empty($productosSeleccionados)) {
            throw new Exception("Debe seleccionar al menos un producto.");
        }

        $conexion = obtenerConexion();
        $conexion->beginTransaction();

        try {
            $totalServidor = 0;
            $detalles = [];

            // 1. Validar existencias y calcular precios en el servidor (RN-01, RN-03)
            foreach ($productosSeleccionados as $item) {
                $idProducto = (int)$item['id_producto'];
                $cantidad   = (int)$item['cantidad'];

                if ($cantidad <= 0) {
                    throw new Exception("La cantidad debe ser mayor a 0.");
                }

                $consultaProd = $conexion->prepare("SELECT id_producto, nombre, precio, stock FROM producto WHERE id_producto = :id FOR UPDATE");
                $consultaProd->execute([':id' => $idProducto]);
                $producto = $consultaProd->fetch();

                if (!$producto) {
                    throw new Exception("El producto seleccionado no existe.");
                }

                if ($producto['stock'] < $cantidad) {
                    throw new Exception("No hay stock suficiente para '{$producto['nombre']}'. Disponible: {$producto['stock']}.");
                }

                $precioUnitario = (float)$producto['precio'];
                $subtotal = $precioUnitario * $cantidad;
                $totalServidor += $subtotal;

                $detalles[] = [
                    'id_producto'     => $idProducto,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal'        => $subtotal
                ];
            }

            // 2. Insertar pedido con estado 'pendiente'
            $consultaPedido = $conexion->prepare("
                INSERT INTO pedido (id_cliente, id_usuario, estado, total)
                VALUES (:id_cliente, :id_usuario, 'pendiente', :total)
            ");
            $consultaPedido->execute([
                ':id_cliente' => $idCliente,
                ':id_usuario' => $idUsuario,
                ':total'      => $totalServidor
            ]);
            $idPedido = (int)$conexion->lastInsertId();

            // 3. Insertar detalle y descontar stock automáticamente (RF-09)
            $insertarDetalle = $conexion->prepare("
                INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal)
                VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario, :subtotal)
            ");

            $descontarStock = $conexion->prepare("
                UPDATE producto
                SET stock = stock - :cantidad
                WHERE id_producto = :id_producto
            ");

            foreach ($detalles as $det) {
                $insertarDetalle->execute([
                    ':id_pedido'       => $idPedido,
                    ':id_producto'     => $det['id_producto'],
                    ':cantidad'        => $det['cantidad'],
                    ':precio_unitario' => $det['precio_unitario'],
                    ':subtotal'        => $det['subtotal']
                ]);

                $descontarStock->execute([
                    ':cantidad'    => $det['cantidad'],
                    ':id_producto' => $det['id_producto']
                ]);
            }

            $conexion->commit();
            return $idPedido;

        } catch (Exception $error) {
            $conexion->rollBack();
            throw $error;
        }
    }

    // RF-10: Avanzar estado del pedido (pendiente -> alistado -> entregado)
    public static function avanzar($idPedido) {
        $pedido = self::buscarPorId($idPedido);
        if (!$pedido) {
            throw new Exception("El pedido no existe.");
        }

        $estadoActual = $pedido['estado'];

        // RN-02: Pedido entregado o cancelado no se puede modificar
        if ($estadoActual === 'entregado' || $estadoActual === 'cancelado') {
            throw new Exception("Un pedido en estado '{$estadoActual}' no se puede modificar.");
        }

        $siguienteEstado = null;
        if ($estadoActual === 'pendiente') {
            $siguienteEstado = 'alistado';
        } elseif ($estadoActual === 'alistado') {
            // RN-04: No se puede entregar sin pago registrado
            if (empty($pedido['metodo_pago'])) {
                throw new Exception("No se puede marcar como entregado sin haber registrado el pago.");
            }
            $siguienteEstado = 'entregado';
        }

        if ($siguienteEstado) {
            $conexion = obtenerConexion();
            $consulta = $conexion->prepare("UPDATE pedido SET estado = :estado WHERE id_pedido = :id");
            return $consulta->execute([':estado' => $siguienteEstado, ':id' => $idPedido]);
        }

        return false;
    }

    // RF-12: Cancelar pedido (solo pedidos sin pago) y reponer stock (RF-09)
    public static function cancelar($idPedido) {
        $pedido = self::buscarPorId($idPedido);
        if (!$pedido) {
            throw new Exception("El pedido no existe.");
        }

        if ($pedido['estado'] === 'cancelado') {
            return true;
        }

        if ($pedido['estado'] === 'entregado') {
            throw new Exception("No se puede cancelar un pedido entregado.");
        }

        if (!empty($pedido['metodo_pago'])) {
            throw new Exception("No se puede cancelar un pedido que ya tiene un pago registrado.");
        }

        $conexion = obtenerConexion();
        $conexion->beginTransaction();

        try {
            // Reponer stock
            $detalles = self::obtenerDetalles($idPedido);
            $reponerStock = $conexion->prepare("UPDATE producto SET stock = stock + :cant WHERE id_producto = :id");

            foreach ($detalles as $det) {
                $reponerStock->execute([
                    ':cant' => $det['cantidad'],
                    ':id'   => $det['id_producto']
                ]);
            }

            // Cambiar estado a 'cancelado'
            $actualizar = $conexion->prepare("UPDATE pedido SET estado = 'cancelado' WHERE id_pedido = :id");
            $actualizar->execute([':id' => $idPedido]);

            $conexion->commit();
            return true;

        } catch (Exception $error) {
            $conexion->rollBack();
            throw $error;
        }
    }

    // RF-11: Registrar pago
    public static function registrarPago($idPedido, $metodoPago, $monto = null) {
        $pedido = self::buscarPorId($idPedido);
        if (!$pedido) {
            throw new Exception("El pedido no existe.");
        }

        if ($pedido['estado'] === 'cancelado') {
            throw new Exception("No se puede cobrar un pedido cancelado.");
        }

        $metodosValidos = ['efectivo', 'tarjeta', 'transferencia'];
        if (!in_array($metodoPago, $metodosValidos)) {
            throw new Exception("Método de pago no válido.");
        }

        $montoFinal = ($monto !== null) ? (float)$monto : (float)$pedido['total'];

        $conexion = obtenerConexion();
        $consulta = $conexion->prepare("
            INSERT INTO pago (id_pedido, metodo_pago, monto, fecha_pago)
            VALUES (:id_pedido, :metodo_pago, :monto, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE metodo_pago = VALUES(metodo_pago), monto = VALUES(monto)
        ");
        return $consulta->execute([
            ':id_pedido'   => $idPedido,
            ':metodo_pago' => $metodoPago,
            ':monto'       => $montoFinal
        ]);
    }
}
