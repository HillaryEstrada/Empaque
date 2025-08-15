<?php

class ModeloVenta
{
    // REGISTRO VENTA COMPLETA (transacción para ambas tablas)
    static public function registroVentaCompletaModelo($datosSalida, $datosVenta)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en salida_mango
            $stmtSalida = $pdo->prepare("INSERT INTO salida_mango (fk_lote, tipo_salida, cliente, destino, transporte, observaciones, estado, fecha, hora) 
                                        VALUES (:fk_lote, :tipo_salida, :cliente, :destino, :transporte, :observaciones, 1, CURDATE(), CURTIME())");
            
            $stmtSalida->bindParam(":fk_lote", $datosSalida["fk_lote"], PDO::PARAM_INT);
            $stmtSalida->bindParam(":tipo_salida", $datosSalida["tipo_salida"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":cliente", $datosSalida["cliente"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":destino", $datosSalida["destino"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":transporte", $datosSalida["transporte"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":observaciones", $datosSalida["observaciones"], PDO::PARAM_STR);
            
            if (!$stmtSalida->execute()) {
                throw new Exception("Error al insertar salida de mango");
            }
            
            // 2. Obtener el ID insertado
            $fk_salida = $pdo->lastInsertId();
            
            // 3. Insertar en venta
            $stmtVenta = $pdo->prepare("INSERT INTO venta (fk_salida, ingreso_total, observaciones, estado, fecha, hora) 
                                       VALUES (:fk_salida, :ingreso_total, :observaciones, 1, CURDATE(), CURTIME())");
            
            $stmtVenta->bindParam(":fk_salida", $fk_salida, PDO::PARAM_INT);
            $stmtVenta->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
            $stmtVenta->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
            
            if (!$stmtVenta->execute()) {
                throw new Exception("Error al crear venta");
            }
            
            $pdo->commit();
            return "ok";
            
        } catch (Exception $e) {
            if ($pdo) {
                $pdo->rollBack();
            }
            return "Error: " . $e->getMessage();
        } finally {
            if ($pdo) {
                $pdo = null;
            }
        }
    }

    // CARGAR LOTES ACTIVOS
    static public function cargarLotesModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_lote, numero_lote, variedad FROM lote WHERE estado = 1 ORDER BY numero_lote");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR VENTAS COMPLETAS (con JOIN)
    static public function mostrarVentasCompletasModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                v.pk_venta,
                                sm.pk_salida,
                                sm.fk_lote,
                                sm.tipo_salida,
                                sm.cliente,
                                sm.destino,
                                sm.transporte,
                                sm.observaciones as observaciones_salida,
                                v.ingreso_total,
                                v.observaciones as observaciones_venta,
                                v.fecha as fecha_venta,
                                l.numero_lote,
                                l.variedad
                              FROM venta v
                              INNER JOIN salida_mango sm ON v.fk_salida = sm.pk_salida
                              INNER JOIN lote l ON sm.fk_lote = l.pk_lote
                              WHERE v.estado = :estado AND sm.estado = :estado
                              ORDER BY v.fecha DESC, sm.cliente");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR VENTA COMPLETA (obtener datos)
    static public function editarVentaCompletaModelo($pk_salida, $pk_venta)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                v.pk_venta,
                                sm.pk_salida,
                                sm.fk_lote,
                                sm.tipo_salida,
                                sm.cliente,
                                sm.destino,
                                sm.transporte,
                                sm.observaciones as observaciones_salida,
                                v.ingreso_total,
                                v.observaciones as observaciones_venta
                              FROM venta v
                              INNER JOIN salida_mango sm ON v.fk_salida = sm.pk_salida
                              WHERE v.pk_venta = :pk_venta AND sm.pk_salida = :pk_salida");
        
        $stmt->bindParam(":pk_venta", $pk_venta, PDO::PARAM_INT);
        $stmt->bindParam(":pk_salida", $pk_salida, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR VENTA BÁSICA (solo tabla venta)
    static public function editarVentaBasicaModelo($pk_venta)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_venta, ingreso_total, observaciones 
                              FROM venta 
                              WHERE pk_venta = :pk_venta");
        
        $stmt->bindParam(":pk_venta", $pk_venta, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR VENTA COMPLETA (transacción)
    static public function actualizarVentaCompletaModelo($datosSalida, $datosVenta)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar salida_mango
            $stmtSalida = $pdo->prepare("UPDATE salida_mango SET 
                                        fk_lote = :fk_lote,
                                        tipo_salida = :tipo_salida,
                                        cliente = :cliente,
                                        destino = :destino,
                                        transporte = :transporte,
                                        observaciones = :observaciones
                                        WHERE pk_salida = :pk_salida");
            
            $stmtSalida->bindParam(":fk_lote", $datosSalida["fk_lote"], PDO::PARAM_INT);
            $stmtSalida->bindParam(":tipo_salida", $datosSalida["tipo_salida"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":cliente", $datosSalida["cliente"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":destino", $datosSalida["destino"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":transporte", $datosSalida["transporte"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":observaciones", $datosSalida["observaciones"], PDO::PARAM_STR);
            $stmtSalida->bindParam(":pk_salida", $datosSalida["pk_salida"], PDO::PARAM_INT);
            
            if (!$stmtSalida->execute()) {
                throw new Exception("Error al actualizar salida de mango");
            }
            
            // 2. Actualizar venta
            $stmtVenta = $pdo->prepare("UPDATE venta SET 
                                       ingreso_total = :ingreso_total,
                                       observaciones = :observaciones
                                       WHERE pk_venta = :pk_venta");
            
            $stmtVenta->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
            $stmtVenta->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
            $stmtVenta->bindParam(":pk_venta", $datosVenta["pk_venta"], PDO::PARAM_INT);
            
            if (!$stmtVenta->execute()) {
                throw new Exception("Error al actualizar venta");
            }
            
            $pdo->commit();
            return "ok";
            
        } catch (Exception $e) {
            if ($pdo) {
                $pdo->rollBack();
            }
            return "Error: " . $e->getMessage();
        } finally {
            if ($pdo) {
                $pdo = null;
            }
        }
    }

    // ACTUALIZAR VENTA BÁSICA (solo tabla venta)
    static public function actualizarVentaBasicaModelo($datosVenta)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE venta SET 
                              ingreso_total = :ingreso_total,
                              observaciones = :observaciones
                              WHERE pk_venta = :pk_venta");
        
        $stmt->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
        $stmt->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
        $stmt->bindParam(":pk_venta", $datosVenta["pk_venta"], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // CAMBIAR ESTADO VENTA COMPLETA (activar/desactivar ambas tablas)
    static public function cambiarEstadoVentaCompletaModelo($pk_salida, $pk_venta, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en salida_mango
            $stmtSalida = $pdo->prepare("UPDATE salida_mango SET estado = :estado WHERE pk_salida = :pk_salida");
            $stmtSalida->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtSalida->bindParam(":pk_salida", $pk_salida, PDO::PARAM_INT);
            
            if (!$stmtSalida->execute()) {
                throw new Exception("Error al cambiar estado de la salida");
            }
            
            // Actualizar estado en venta
            $stmtVenta = $pdo->prepare("UPDATE venta SET estado = :estado WHERE pk_venta = :pk_venta");
            $stmtVenta->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtVenta->bindParam(":pk_venta", $pk_venta, PDO::PARAM_INT);
            
            if (!$stmtVenta->execute()) {
                throw new Exception("Error al cambiar estado de la venta");
            }
            
            $pdo->commit();
            return "ok";
            
        } catch (Exception $e) {
            if ($pdo) {
                $pdo->rollBack();
            }
            return "Error: " . $e->getMessage();
        } finally {
            if ($pdo) {
                $pdo = null;
            }
        }
    }
}

?>