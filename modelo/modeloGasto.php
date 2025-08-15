<?php

class ModeloGasto
{
    // REGISTRO GASTO COMPLETO (transacción para ambas tablas)
    static public function registroGastoCompletoModelo($datosGasto, $datosDetalle, $tipo)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en gasto
            $stmtGasto = $pdo->prepare("INSERT INTO gasto (nombre, descripcion, tipo, estado, fecha, hora) 
                                       VALUES (:nombre, :descripcion, :tipo, 1, CURDATE(), CURTIME())");
            
            $stmtGasto->bindParam(":nombre", $datosGasto["nombre"], PDO::PARAM_STR);
            $stmtGasto->bindParam(":descripcion", $datosGasto["descripcion"], PDO::PARAM_STR);
            $stmtGasto->bindParam(":tipo", $datosGasto["tipo"], PDO::PARAM_STR);
            
            if (!$stmtGasto->execute()) {
                throw new Exception("Error al insertar gasto");
            }
            
            // 2. Obtener el ID insertado
            $fk_gasto = $pdo->lastInsertId();
            
            // 3. Insertar en la tabla de detalle según el tipo
            if ($tipo === 'llegada') {
                $stmtDetalle = $pdo->prepare("INSERT INTO gasto_llegada (fk_lote, fk_gasto, monto, estado, fecha, hora) 
                                             VALUES (:fk_lote, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
                
                $stmtDetalle->bindParam(":fk_lote", $datosDetalle["fk_lote"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":fk_gasto", $fk_gasto, PDO::PARAM_INT);
                $stmtDetalle->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
                
            } elseif ($tipo === 'salida') {
                $stmtDetalle = $pdo->prepare("INSERT INTO gasto_salida (fk_salida, fk_gasto, monto, estado, fecha, hora) 
                                             VALUES (:fk_salida, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
                
                $stmtDetalle->bindParam(":fk_salida", $datosDetalle["fk_salida"], PDO::PARAM_INT);
                $stmtDetalle->bindParam(":fk_gasto", $fk_gasto, PDO::PARAM_INT);
                $stmtDetalle->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
            }
            
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al crear detalle del gasto");
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

    // CARGAR SALIDAS ACTIVAS
    static public function cargarSalidasModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT sm.pk_salida, sm.cliente, sm.destino, sm.tipo_salida, l.numero_lote, l.variedad 
                              FROM salida_mango sm 
                              LEFT JOIN lote l ON sm.fk_lote = l.pk_lote 
                              WHERE sm.estado = 1 
                              ORDER BY sm.cliente, sm.fecha DESC");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR GASTOS COMPLETOS (con JOIN)
    static public function mostrarGastosCompletosModelo($estado)
    {
        $pdo = Conexion::conectar();
        
        // Query unificada con UNION para ambos tipos de gasto
        $stmt = $pdo->prepare("
            SELECT 
                g.pk_gasto,
                g.nombre,
                g.descripcion,
                g.tipo,
                gl.pk_gasto_llegada as pk_detalle,
                gl.monto,
                CONCAT('Lote: ', l.numero_lote, ' - ', l.variedad) as referencia_info,
                gl.fk_lote,
                NULL as fk_salida
            FROM gasto g
            INNER JOIN gasto_llegada gl ON g.pk_gasto = gl.fk_gasto
            LEFT JOIN lote l ON gl.fk_lote = l.pk_lote
            WHERE g.estado = :estado AND gl.estado = :estado AND g.tipo = 'llegada'
            
            UNION ALL
            
            SELECT 
                g.pk_gasto,
                g.nombre,
                g.descripcion,
                g.tipo,
                gs.pk_gasto_salida as pk_detalle,
                gs.monto,
                CONCAT('Cliente: ', sm.cliente, ' - Destino: ', sm.destino) as referencia_info,
                NULL as fk_lote,
                gs.fk_salida
            FROM gasto g
            INNER JOIN gasto_salida gs ON g.pk_gasto = gs.fk_gasto
            LEFT JOIN salida_mango sm ON gs.fk_salida = sm.pk_salida
            WHERE g.estado = :estado AND gs.estado = :estado AND g.tipo = 'salida'
            
            ORDER BY nombre
        ");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR GASTO COMPLETO (obtener datos)
    static public function editarGastoCompletoModelo($pk_gasto, $pk_detalle, $tipo)
    {
        $pdo = Conexion::conectar();
        
        if ($tipo === 'llegada') {
            $stmt = $pdo->prepare("SELECT 
                                    g.pk_gasto,
                                    g.nombre,
                                    g.descripcion,
                                    g.tipo,
                                    gl.pk_gasto_llegada,
                                    gl.monto,
                                    gl.fk_lote,
                                    NULL as fk_salida
                                  FROM gasto g
                                  INNER JOIN gasto_llegada gl ON g.pk_gasto = gl.fk_gasto
                                  WHERE g.pk_gasto = :pk_gasto AND gl.pk_gasto_llegada = :pk_detalle");
        } else {
            $stmt = $pdo->prepare("SELECT 
                                    g.pk_gasto,
                                    g.nombre,
                                    g.descripcion,
                                    g.tipo,
                                    gs.pk_gasto_salida,
                                    gs.monto,
                                    NULL as fk_lote,
                                    gs.fk_salida
                                  FROM gasto g
                                  INNER JOIN gasto_salida gs ON g.pk_gasto = gs.fk_gasto
                                  WHERE g.pk_gasto = :pk_gasto AND gs.pk_gasto_salida = :pk_detalle");
        }
        
        $stmt->bindParam(":pk_gasto", $pk_gasto, PDO::PARAM_INT);
        $stmt->bindParam(":pk_detalle", $pk_detalle, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR GASTO BÁSICO (solo tabla gasto)
    static public function editarGastoBasicoModelo($pk_gasto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_gasto, nombre, descripcion, tipo 
                              FROM gasto 
                              WHERE pk_gasto = :pk_gasto");
        
        $stmt->bindParam(":pk_gasto", $pk_gasto, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR GASTO COMPLETO (transacción)
    static public function actualizarGastoCompletoModelo($datosGasto, $datosDetalle, $tipo_original, $tipo_nuevo)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar gasto
            $stmtGasto = $pdo->prepare("UPDATE gasto SET 
                                       nombre = :nombre,
                                       descripcion = :descripcion,
                                       tipo = :tipo
                                       WHERE pk_gasto = :pk_gasto");
            
            $stmtGasto->bindParam(":nombre", $datosGasto["nombre"], PDO::PARAM_STR);
            $stmtGasto->bindParam(":descripcion", $datosGasto["descripcion"], PDO::PARAM_STR);
            $stmtGasto->bindParam(":tipo", $datosGasto["tipo"], PDO::PARAM_STR);
            $stmtGasto->bindParam(":pk_gasto", $datosGasto["pk_gasto"], PDO::PARAM_INT);
            
            if (!$stmtGasto->execute()) {
                throw new Exception("Error al actualizar gasto");
            }
            
            // 2. Si cambió el tipo, manejar cambio de tabla
            if ($tipo_original !== $tipo_nuevo) {
                // Desactivar registro anterior
                if ($tipo_original === 'llegada' && isset($datosDetalle['pk_gasto_llegada'])) {
                    $stmtDesactivar = $pdo->prepare("UPDATE gasto_llegada SET estado = 0 WHERE pk_gasto_llegada = :pk_detalle");
                    $stmtDesactivar->bindParam(":pk_detalle", $datosDetalle['pk_gasto_llegada'], PDO::PARAM_INT);
                    $stmtDesactivar->execute();
                } elseif ($tipo_original === 'salida' && isset($datosDetalle['pk_gasto_salida'])) {
                    $stmtDesactivar = $pdo->prepare("UPDATE gasto_salida SET estado = 0 WHERE pk_gasto_salida = :pk_detalle");
                    $stmtDesactivar->bindParam(":pk_detalle", $datosDetalle['pk_gasto_salida'], PDO::PARAM_INT);
                    $stmtDesactivar->execute();
                }
                
                // Crear nuevo registro en la tabla correcta
                if ($tipo_nuevo === 'llegada' && isset($datosDetalle['fk_lote'])) {
                    $stmtNuevo = $pdo->prepare("INSERT INTO gasto_llegada (fk_lote, fk_gasto, monto, estado, fecha, hora) 
                                               VALUES (:fk_lote, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
                    $stmtNuevo->bindParam(":fk_lote", $datosDetalle["fk_lote"], PDO::PARAM_INT);
                    $stmtNuevo->bindParam(":fk_gasto", $datosGasto["pk_gasto"], PDO::PARAM_INT);
                    $stmtNuevo->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
                    $stmtNuevo->execute();
                    
                } elseif ($tipo_nuevo === 'salida' && isset($datosDetalle['fk_salida'])) {
                    $stmtNuevo = $pdo->prepare("INSERT INTO gasto_salida (fk_salida, fk_gasto, monto, estado, fecha, hora) 
                                               VALUES (:fk_salida, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
                    $stmtNuevo->bindParam(":fk_salida", $datosDetalle["fk_salida"], PDO::PARAM_INT);
                    $stmtNuevo->bindParam(":fk_gasto", $datosGasto["pk_gasto"], PDO::PARAM_INT);
                    $stmtNuevo->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
                    $stmtNuevo->execute();
                }
            } else {
                // Mismo tipo, solo actualizar
                if ($tipo_original === 'llegada' && isset($datosDetalle['pk_gasto_llegada'])) {
                    $stmtDetalle = $pdo->prepare("UPDATE gasto_llegada SET 
                                                 fk_lote = :fk_lote,
                                                 monto = :monto
                                                 WHERE pk_gasto_llegada = :pk_gasto_llegada");
                    $stmtDetalle->bindParam(":fk_lote", $datosDetalle["fk_lote"], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":pk_gasto_llegada", $datosDetalle["pk_gasto_llegada"], PDO::PARAM_INT);
                    
                } elseif ($tipo_original === 'salida' && isset($datosDetalle['pk_gasto_salida'])) {
                    $stmtDetalle = $pdo->prepare("UPDATE gasto_salida SET 
                                                 fk_salida = :fk_salida,
                                                 monto = :monto
                                                 WHERE pk_gasto_salida = :pk_gasto_salida");
                    $stmtDetalle->bindParam(":fk_salida", $datosDetalle["fk_salida"], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(":monto", $datosDetalle["monto"], PDO::PARAM_STR);
                    $stmtDetalle->bindParam(":pk_gasto_salida", $datosDetalle["pk_gasto_salida"], PDO::PARAM_INT);
                }
                
                if (!$stmtDetalle->execute()) {
                    throw new Exception("Error al actualizar detalle del gasto");
                }
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

    // ACTUALIZAR GASTO BÁSICO (solo tabla gasto)
    static public function actualizarGastoBasicoModelo($datosGasto)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE gasto SET 
                              nombre = :nombre,
                              descripcion = :descripcion,
                              tipo = :tipo
                              WHERE pk_gasto = :pk_gasto");
        
        $stmt->bindParam(":nombre", $datosGasto["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosGasto["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datosGasto["tipo"], PDO::PARAM_STR);
        $stmt->bindParam(":pk_gasto", $datosGasto["pk_gasto"], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // CAMBIAR ESTADO GASTO COMPLETO (activar/desactivar ambas tablas)
    static public function cambiarEstadoGastoCompletoModelo($pk_gasto, $pk_detalle, $tipo, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en gasto
            $stmtGasto = $pdo->prepare("UPDATE gasto SET estado = :estado WHERE pk_gasto = :pk_gasto");
            $stmtGasto->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtGasto->bindParam(":pk_gasto", $pk_gasto, PDO::PARAM_INT);
            
            if (!$stmtGasto->execute()) {
                throw new Exception("Error al cambiar estado del gasto");
            }
            
            // Actualizar estado en tabla de detalle
            if ($tipo === 'llegada') {
                $stmtDetalle = $pdo->prepare("UPDATE gasto_llegada SET estado = :estado WHERE pk_gasto_llegada = :pk_detalle");
            } else {
                $stmtDetalle = $pdo->prepare("UPDATE gasto_salida SET estado = :estado WHERE pk_gasto_salida = :pk_detalle");
            }
            
            $stmtDetalle->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":pk_detalle", $pk_detalle, PDO::PARAM_INT);
            
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al cambiar estado del detalle del gasto");
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