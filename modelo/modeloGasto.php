<?php

class ModeloGasto
{
    // REGISTRO GASTO DE LLEGADA (transacción para gasto + gasto_llegada)
    static public function registroGastoLlegadaModelo($datosGasto, $datosEspecificos)
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
                throw new Exception("Error al insertar datos del gasto");
            }
            
            // 2. Obtener el ID insertado
            $fk_gasto = $pdo->lastInsertId();
            
            // 3. Insertar en gasto_llegada
            $stmtGastoLlegada = $pdo->prepare("INSERT INTO gasto_llegada (fk_lote, fk_gasto, monto, estado, fecha, hora) 
                                              VALUES (:fk_lote, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
            
            $stmtGastoLlegada->bindParam(":fk_lote", $datosEspecificos["fk_lote"], PDO::PARAM_INT);
            $stmtGastoLlegada->bindParam(":fk_gasto", $fk_gasto, PDO::PARAM_INT);
            $stmtGastoLlegada->bindParam(":monto", $datosEspecificos["monto"], PDO::PARAM_STR);
            
            if (!$stmtGastoLlegada->execute()) {
                throw new Exception("Error al insertar gasto de llegada");
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

    // REGISTRO GASTO DE SALIDA (transacción para gasto + gasto_salida + venta opcional)
    static public function registroGastoSalidaModelo($datosGasto, $datosEspecificos, $datosVenta = null)
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
                throw new Exception("Error al insertar datos del gasto");
            }
            
            // 2. Obtener el ID insertado
            $fk_gasto = $pdo->lastInsertId();
            
            // 3. Insertar en gasto_salida
            $stmtGastoSalida = $pdo->prepare("INSERT INTO gasto_salida (fk_salida, fk_gasto, monto, estado, fecha, hora) 
                                             VALUES (:fk_salida, :fk_gasto, :monto, 1, CURDATE(), CURTIME())");
            
            $stmtGastoSalida->bindParam(":fk_salida", $datosEspecificos["fk_salida"], PDO::PARAM_INT);
            $stmtGastoSalida->bindParam(":fk_gasto", $fk_gasto, PDO::PARAM_INT);
            $stmtGastoSalida->bindParam(":monto", $datosEspecificos["monto"], PDO::PARAM_STR);
            
            if (!$stmtGastoSalida->execute()) {
                throw new Exception("Error al insertar gasto de salida");
            }
            
            // 4. Insertar en venta (opcional)
            if ($datosVenta !== null) {
                $stmtVenta = $pdo->prepare("INSERT INTO venta (fk_salida, ingreso_total, observaciones, estado, fecha, hora) 
                                           VALUES (:fk_salida, :ingreso_total, :observaciones, 1, CURDATE(), CURTIME())");
                
                $stmtVenta->bindParam(":fk_salida", $datosVenta["fk_salida"], PDO::PARAM_INT);
                $stmtVenta->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
                $stmtVenta->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
                
                if (!$stmtVenta->execute()) {
                    throw new Exception("Error al insertar datos de venta");
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

    // CARGAR LOTES ACTIVOS
    static public function cargarLotesModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_lote, variedad FROM lote WHERE estado = 1 ORDER BY pk_lote DESC");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // CARGAR SALIDAS ACTIVAS
    static public function cargarSalidasModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_salida, cliente, tipo_salida FROM salida_mango WHERE estado = 1 ORDER BY pk_salida DESC");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR GASTOS COMPLETOS (con JOIN)
    static public function mostrarGastosCompletosModelo($estado)
    {
        $pdo = Conexion::conectar();
        
        // Query para gastos de llegada
        $stmtLlegada = $pdo->prepare("SELECT 
                                        g.pk_gasto,
                                        g.nombre,
                                        g.descripcion,
                                        g.tipo,
                                        gl.pk_gasto_llegada,
                                        gl.fk_lote as referencia,
                                        gl.monto,
                                        null as pk_gasto_salida,
                                        null as fk_salida,
                                        null as cliente,
                                        null as pk_venta,
                                        null as ingreso_total
                                      FROM gasto g
                                      INNER JOIN gasto_llegada gl ON g.pk_gasto = gl.fk_gasto
                                      WHERE g.estado = :estado AND gl.estado = :estado AND g.tipo = 'llegada'");
        
        $stmtLlegada->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmtLlegada->execute();
        $gastosLlegada = $stmtLlegada->fetchAll();
        
        // Query para gastos de salida
        $stmtSalida = $pdo->prepare("SELECT 
                                       g.pk_gasto,
                                       g.nombre,
                                       g.descripcion,
                                       g.tipo,
                                       null as pk_gasto_llegada,
                                       sm.pk_salida as referencia,
                                       gs.monto,
                                       gs.pk_gasto_salida,
                                       gs.fk_salida,
                                       sm.cliente,
                                       v.pk_venta,
                                       v.ingreso_total
                                     FROM gasto g
                                     INNER JOIN gasto_salida gs ON g.pk_gasto = gs.fk_gasto
                                     INNER JOIN salida_mango sm ON gs.fk_salida = sm.pk_salida
                                     LEFT JOIN venta v ON gs.fk_salida = v.fk_salida AND v.estado = :estado
                                     WHERE g.estado = :estado AND gs.estado = :estado AND g.tipo = 'salida'");
        
        $stmtSalida->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmtSalida->execute();
        $gastosSalida = $stmtSalida->fetchAll();
        
        // Combinar resultados
        $respuesta = array_merge($gastosLlegada, $gastosSalida);
        
        // Ordenar por nombre
        usort($respuesta, function($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });
        
        $pdo = null;
        return $respuesta;
    }

    // EDITAR GASTO COMPLETO (obtener datos)
    static public function editarGastoCompletoModelo($pk_gasto, $pk_gasto_llegada = null, $pk_gasto_salida = null, $pk_venta = null)
    {
        $pdo = Conexion::conectar();
        
        if ($pk_gasto_llegada) {
            // Gasto de llegada
            $stmt = $pdo->prepare("SELECT 
                                    g.pk_gasto,
                                    g.nombre,
                                    g.descripcion,
                                    g.tipo,
                                    gl.pk_gasto_llegada,
                                    gl.fk_lote,
                                    gl.monto
                                  FROM gasto g
                                  INNER JOIN gasto_llegada gl ON g.pk_gasto = gl.fk_gasto
                                  WHERE g.pk_gasto = :pk_gasto AND gl.pk_gasto_llegada = :pk_gasto_llegada");
            
            $stmt->bindParam(":pk_gasto", $pk_gasto, PDO::PARAM_INT);
            $stmt->bindParam(":pk_gasto_llegada", $pk_gasto_llegada, PDO::PARAM_INT);
            
        } else {
            // Gasto de salida
            $stmt = $pdo->prepare("SELECT 
                                    g.pk_gasto,
                                    g.nombre,
                                    g.descripcion,
                                    g.tipo,
                                    gs.pk_gasto_salida,
                                    gs.fk_salida,
                                    gs.monto,
                                    v.pk_venta,
                                    v.ingreso_total,
                                    v.observaciones as observaciones_venta
                                  FROM gasto g
                                  INNER JOIN gasto_salida gs ON g.pk_gasto = gs.fk_gasto
                                  LEFT JOIN venta v ON gs.fk_salida = v.fk_salida
                                  WHERE g.pk_gasto = :pk_gasto AND gs.pk_gasto_salida = :pk_gasto_salida");
            
            $stmt->bindParam(":pk_gasto", $pk_gasto, PDO::PARAM_INT);
            $stmt->bindParam(":pk_gasto_salida", $pk_gasto_salida, PDO::PARAM_INT);
        }
        
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

    // ACTUALIZAR GASTO DE LLEGADA (transacción)
    static public function actualizarGastoLlegadaModelo($datosGasto, $datosGastoLlegada)
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
                throw new Exception("Error al actualizar datos del gasto");
            }
            
            // 2. Actualizar gasto_llegada
            $stmtGastoLlegada = $pdo->prepare("UPDATE gasto_llegada SET 
                                              fk_lote = :fk_lote,
                                              monto = :monto
                                              WHERE pk_gasto_llegada = :pk_gasto_llegada");
            
            $stmtGastoLlegada->bindParam(":fk_lote", $datosGastoLlegada["fk_lote"], PDO::PARAM_INT);
            $stmtGastoLlegada->bindParam(":monto", $datosGastoLlegada["monto"], PDO::PARAM_STR);
            $stmtGastoLlegada->bindParam(":pk_gasto_llegada", $datosGastoLlegada["pk_gasto_llegada"], PDO::PARAM_INT);
            
            if (!$stmtGastoLlegada->execute()) {
                throw new Exception("Error al actualizar gasto de llegada");
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

    // ACTUALIZAR GASTO DE SALIDA (transacción)
    static public function actualizarGastoSalidaModelo($datosGasto, $datosGastoSalida, $datosVenta = null)
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
                throw new Exception("Error al actualizar datos del gasto");
            }
            
            // 2. Actualizar gasto_salida
            $stmtGastoSalida = $pdo->prepare("UPDATE gasto_salida SET 
                                             fk_salida = :fk_salida,
                                             monto = :monto
                                             WHERE pk_gasto_salida = :pk_gasto_salida");
            
            $stmtGastoSalida->bindParam(":fk_salida", $datosGastoSalida["fk_salida"], PDO::PARAM_INT);
            $stmtGastoSalida->bindParam(":monto", $datosGastoSalida["monto"], PDO::PARAM_STR);
            $stmtGastoSalida->bindParam(":pk_gasto_salida", $datosGastoSalida["pk_gasto_salida"], PDO::PARAM_INT);
            
            if (!$stmtGastoSalida->execute()) {
                throw new Exception("Error al actualizar gasto de salida");
            }
            
            // 3. Actualizar o insertar venta (opcional)
            if ($datosVenta !== null) {
                if (isset($datosVenta['pk_venta']) && !empty($datosVenta['pk_venta'])) {
                    // Actualizar venta existente
                    $stmtVenta = $pdo->prepare("UPDATE venta SET 
                                               fk_salida = :fk_salida,
                                               ingreso_total = :ingreso_total,
                                               observaciones = :observaciones
                                               WHERE pk_venta = :pk_venta");
                    
                    $stmtVenta->bindParam(":fk_salida", $datosVenta["fk_salida"], PDO::PARAM_INT);
                    $stmtVenta->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
                    $stmtVenta->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
                    $stmtVenta->bindParam(":pk_venta", $datosVenta["pk_venta"], PDO::PARAM_INT);
                    
                } else {
                    // Insertar nueva venta
                    $stmtVenta = $pdo->prepare("INSERT INTO venta (fk_salida, ingreso_total, observaciones, estado, fecha, hora) 
                                               VALUES (:fk_salida, :ingreso_total, :observaciones, 1, CURDATE(), CURTIME())");
                    
                    $stmtVenta->bindParam(":fk_salida", $datosVenta["fk_salida"], PDO::PARAM_INT);
                    $stmtVenta->bindParam(":ingreso_total", $datosVenta["ingreso_total"], PDO::PARAM_STR);
                    $stmtVenta->bindParam(":observaciones", $datosVenta["observaciones"], PDO::PARAM_STR);
                }
                
                if (!$stmtVenta->execute()) {
                    throw new Exception("Error al procesar datos de venta");
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

    // CAMBIAR ESTADO GASTO COMPLETO (activar/desactivar todas las tablas relacionadas)
    static public function cambiarEstadoGastoCompletoModelo($pk_gasto, $pk_gasto_llegada = null, $pk_gasto_salida = null, $pk_venta = null, $estado)
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
            
            // Actualizar estado en gasto_llegada o gasto_salida
            if ($pk_gasto_llegada) {
                $stmtEspecifico = $pdo->prepare("UPDATE gasto_llegada SET estado = :estado WHERE pk_gasto_llegada = :pk_gasto_llegada");
                $stmtEspecifico->bindParam(":estado", $estado, PDO::PARAM_INT);
                $stmtEspecifico->bindParam(":pk_gasto_llegada", $pk_gasto_llegada, PDO::PARAM_INT);
            } else {
                $stmtEspecifico = $pdo->prepare("UPDATE gasto_salida SET estado = :estado WHERE pk_gasto_salida = :pk_gasto_salida");
                $stmtEspecifico->bindParam(":estado", $estado, PDO::PARAM_INT);
                $stmtEspecifico->bindParam(":pk_gasto_salida", $pk_gasto_salida, PDO::PARAM_INT);
            }
            
            if (!$stmtEspecifico->execute()) {
                throw new Exception("Error al cambiar estado del gasto específico");
            }
            
            // Actualizar estado en venta (si existe)
            if ($pk_venta) {
                $stmtVenta = $pdo->prepare("UPDATE venta SET estado = :estado WHERE pk_venta = :pk_venta");
                $stmtVenta->bindParam(":estado", $estado, PDO::PARAM_INT);
                $stmtVenta->bindParam(":pk_venta", $pk_venta, PDO::PARAM_INT);
                
                if (!$stmtVenta->execute()) {
                    throw new Exception("Error al cambiar estado de la venta");
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

    // REGISTRO GASTO BÁSICO (mantener compatibilidad)
    static public function registroGastoModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (nombre, descripcion, tipo) 
                              VALUES (:nombre, :descripcion, :tipo)");
        
        $stmt->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosModelo["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datosModelo["tipo"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // MOSTRAR DATOS GASTOS BÁSICOS (mantener compatibilidad)
    static public function mostrarDatosGastosModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_gasto, nombre, descripcion, tipo 
                              FROM gasto 
                              WHERE estado = :estado 
                              ORDER BY nombre");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }
}

?>