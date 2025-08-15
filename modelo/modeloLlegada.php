<?php

class ModeloLlegada
{
    // REGISTRO LLEGADA COMPLETA (transacción para todas las tablas)
    static public function registroLlegadaCompletaModelo($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en llegada_mango
            $stmtLlegada = $pdo->prepare("INSERT INTO llegada_mango (fk_rancho, fk_usuario, tipo_llegada, estado, fecha, hora) 
                                         VALUES (:fk_rancho, :fk_usuario, :tipo_llegada, 1, CURDATE(), CURTIME())");
            
            $stmtLlegada->bindParam(":fk_rancho", $datosLlegada["fk_rancho"], PDO::PARAM_INT);
            $stmtLlegada->bindParam(":fk_usuario", $datosLlegada["fk_usuario"], PDO::PARAM_INT);
            $stmtLlegada->bindParam(":tipo_llegada", $datosLlegada["tipo_llegada"], PDO::PARAM_STR);
            
            if (!$stmtLlegada->execute()) {
                throw new Exception("Error al insertar llegada de mango");
            }
            
            // 2. Obtener el ID de la llegada insertada
            $fk_llegada = $pdo->lastInsertId();
            
            // 3. Insertar en detalle_llegada
            $stmtDetalle = $pdo->prepare("INSERT INTO detalle_llegada (fk_llegada, medio_transporte, tipo_envase, responsable, observaciones, estado, fecha, hora) 
                                         VALUES (:fk_llegada, :medio_transporte, :tipo_envase, :responsable, :observaciones, 1, CURDATE(), CURTIME())");
            
            $stmtDetalle->bindParam(":fk_llegada", $fk_llegada, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":medio_transporte", $datosDetalle["medio_transporte"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":tipo_envase", $datosDetalle["tipo_envase"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":responsable", $datosDetalle["responsable"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":observaciones", $datosDetalle["observaciones"], PDO::PARAM_STR);
            
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al insertar detalle de llegada");
            }
            
            // 4. Insertar en pesaje
            $stmtPesaje = $pdo->prepare("INSERT INTO pesaje (fk_llegada, peso_bruto, peso_envase, peso_neto, estado, fecha, hora) 
                                        VALUES (:fk_llegada, :peso_bruto, :peso_envase, :peso_neto, 1, CURDATE(), CURTIME())");
            
            $stmtPesaje->bindParam(":fk_llegada", $fk_llegada, PDO::PARAM_INT);
            $stmtPesaje->bindParam(":peso_bruto", $datosPesaje["peso_bruto"], PDO::PARAM_STR);
            $stmtPesaje->bindParam(":peso_envase", $datosPesaje["peso_envase"], PDO::PARAM_STR);
            $stmtPesaje->bindParam(":peso_neto", $datosPesaje["peso_neto"], PDO::PARAM_STR);
            
            if (!$stmtPesaje->execute()) {
                throw new Exception("Error al insertar datos de pesaje");
            }
            
            // 5. Insertar en compra_mango
            $stmtCompra = $pdo->prepare("INSERT INTO compra_mango (fk_llegada, precio_kilo, total_pagado, metodo_pago, estado, fecha, hora) 
                                        VALUES (:fk_llegada, :precio_kilo, :total_pagado, :metodo_pago, 1, CURDATE(), CURTIME())");
            
            $stmtCompra->bindParam(":fk_llegada", $fk_llegada, PDO::PARAM_INT);
            $stmtCompra->bindParam(":precio_kilo", $datosCompra["precio_kilo"], PDO::PARAM_STR);
            $stmtCompra->bindParam(":total_pagado", $datosCompra["total_pagado"], PDO::PARAM_STR);
            $stmtCompra->bindParam(":metodo_pago", $datosCompra["metodo_pago"], PDO::PARAM_STR);
            
            if (!$stmtCompra->execute()) {
                throw new Exception("Error al insertar datos de compra");
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

    // CARGAR RANCHOS ACTIVOS
    static public function cargarRanchosModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_rancho, nombre FROM rancho WHERE estado = 1 ORDER BY nombre");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // CARGAR USUARIOS ACTIVOS
    static public function cargarUsuariosModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                u.pk_usuario,
                                u.usuario,
                                du.nombre,
                                du.apellidos
                              FROM usuario u
                              INNER JOIN dato_usuario du ON u.fk_dato_usuario = du.pk_dato_usuario
                              WHERE u.estado = 1 AND du.estado = 1
                              ORDER BY du.nombre, du.apellidos");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR LLEGADAS COMPLETAS (con JOINS)
    static public function mostrarLlegadasCompletasModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                lm.pk_llegada,
                                lm.fk_rancho,
                                lm.fk_usuario,
                                lm.tipo_llegada,
                                lm.fecha,
                                r.nombre as rancho_nombre,
                                du.nombre as usuario_nombre,
                                du.apellidos as usuario_apellidos,
                                dl.pk_detalle_llegada,
                                dl.medio_transporte,
                                dl.tipo_envase,
                                dl.responsable,
                                dl.observaciones,
                                p.pk_pesaje,
                                p.peso_bruto,
                                p.peso_envase,
                                p.peso_neto,
                                cm.pk_compra,
                                cm.precio_kilo,
                                cm.total_pagado,
                                cm.metodo_pago
                              FROM llegada_mango lm
                              INNER JOIN rancho r ON lm.fk_rancho = r.pk_rancho
                              INNER JOIN usuario u ON lm.fk_usuario = u.pk_usuario
                              INNER JOIN dato_usuario du ON u.fk_dato_usuario = du.pk_dato_usuario
                              INNER JOIN detalle_llegada dl ON lm.pk_llegada = dl.fk_llegada
                              INNER JOIN pesaje p ON lm.pk_llegada = p.fk_llegada
                              INNER JOIN compra_mango cm ON lm.pk_llegada = cm.fk_llegada
                              WHERE lm.estado = :estado AND dl.estado = :estado AND p.estado = :estado AND cm.estado = :estado
                              ORDER BY lm.fecha DESC, lm.hora DESC");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR LLEGADA COMPLETA (obtener datos)
    static public function editarLlegadaCompletaModelo($pk_llegada)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                lm.pk_llegada,
                                lm.fk_rancho,
                                lm.fk_usuario,
                                lm.tipo_llegada,
                                dl.medio_transporte,
                                dl.tipo_envase,
                                dl.responsable,
                                dl.observaciones,
                                p.peso_bruto,
                                p.peso_envase,
                                p.peso_neto,
                                cm.precio_kilo,
                                cm.total_pagado,
                                cm.metodo_pago
                              FROM llegada_mango lm
                              INNER JOIN detalle_llegada dl ON lm.pk_llegada = dl.fk_llegada
                              INNER JOIN pesaje p ON lm.pk_llegada = p.fk_llegada
                              INNER JOIN compra_mango cm ON lm.pk_llegada = cm.fk_llegada
                              WHERE lm.pk_llegada = :pk_llegada");
        
        $stmt->bindParam(":pk_llegada", $pk_llegada, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR LLEGADA COMPLETA (transacción)
    static public function actualizarLlegadaCompletaModelo($datosLlegada, $datosDetalle, $datosPesaje, $datosCompra)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar llegada_mango
            $stmtLlegada = $pdo->prepare("UPDATE llegada_mango SET 
                                         fk_rancho = :fk_rancho,
                                         fk_usuario = :fk_usuario,
                                         tipo_llegada = :tipo_llegada
                                         WHERE pk_llegada = :pk_llegada");
            
            $stmtLlegada->bindParam(":fk_rancho", $datosLlegada["fk_rancho"], PDO::PARAM_INT);
            $stmtLlegada->bindParam(":fk_usuario", $datosLlegada["fk_usuario"], PDO::PARAM_INT);
            $stmtLlegada->bindParam(":tipo_llegada", $datosLlegada["tipo_llegada"], PDO::PARAM_STR);
            $stmtLlegada->bindParam(":pk_llegada", $datosLlegada["pk_llegada"], PDO::PARAM_INT);
            
            if (!$stmtLlegada->execute()) {
                throw new Exception("Error al actualizar llegada de mango");
            }
            
            // 2. Actualizar detalle_llegada
            $stmtDetalle = $pdo->prepare("UPDATE detalle_llegada SET 
                                         medio_transporte = :medio_transporte,
                                         tipo_envase = :tipo_envase,
                                         responsable = :responsable,
                                         observaciones = :observaciones
                                         WHERE fk_llegada = :fk_llegada");
            
            $stmtDetalle->bindParam(":medio_transporte", $datosDetalle["medio_transporte"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":tipo_envase", $datosDetalle["tipo_envase"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":responsable", $datosDetalle["responsable"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":observaciones", $datosDetalle["observaciones"], PDO::PARAM_STR);
            $stmtDetalle->bindParam(":fk_llegada", $datosLlegada["pk_llegada"], PDO::PARAM_INT);
            
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al actualizar detalle de llegada");
            }
            
            // 3. Actualizar pesaje
            $stmtPesaje = $pdo->prepare("UPDATE pesaje SET 
                                        peso_bruto = :peso_bruto,
                                        peso_envase = :peso_envase,
                                        peso_neto = :peso_neto
                                        WHERE fk_llegada = :fk_llegada");
            
            $stmtPesaje->bindParam(":peso_bruto", $datosPesaje["peso_bruto"], PDO::PARAM_STR);
            $stmtPesaje->bindParam(":peso_envase", $datosPesaje["peso_envase"], PDO::PARAM_STR);
            $stmtPesaje->bindParam(":peso_neto", $datosPesaje["peso_neto"], PDO::PARAM_STR);
            $stmtPesaje->bindParam(":fk_llegada", $datosLlegada["pk_llegada"], PDO::PARAM_INT);
            
            if (!$stmtPesaje->execute()) {
                throw new Exception("Error al actualizar datos de pesaje");
            }
            
            // 4. Actualizar compra_mango
            $stmtCompra = $pdo->prepare("UPDATE compra_mango SET 
                                        precio_kilo = :precio_kilo,
                                        total_pagado = :total_pagado,
                                        metodo_pago = :metodo_pago
                                        WHERE fk_llegada = :fk_llegada");
            
            $stmtCompra->bindParam(":precio_kilo", $datosCompra["precio_kilo"], PDO::PARAM_STR);
            $stmtCompra->bindParam(":total_pagado", $datosCompra["total_pagado"], PDO::PARAM_STR);
            $stmtCompra->bindParam(":metodo_pago", $datosCompra["metodo_pago"], PDO::PARAM_STR);
            $stmtCompra->bindParam(":fk_llegada", $datosLlegada["pk_llegada"], PDO::PARAM_INT);
            
            if (!$stmtCompra->execute()) {
                throw new Exception("Error al actualizar datos de compra");
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

    // CAMBIAR ESTADO LLEGADA COMPLETA (activar/desactivar todas las tablas)
    static public function cambiarEstadoLlegadaCompletaModelo($pk_llegada, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en llegada_mango
            $stmtLlegada = $pdo->prepare("UPDATE llegada_mango SET estado = :estado WHERE pk_llegada = :pk_llegada");
            $stmtLlegada->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtLlegada->bindParam(":pk_llegada", $pk_llegada, PDO::PARAM_INT);
            
            if (!$stmtLlegada->execute()) {
                throw new Exception("Error al cambiar estado de llegada de mango");
            }
            
            // Actualizar estado en detalle_llegada
            $stmtDetalle = $pdo->prepare("UPDATE detalle_llegada SET estado = :estado WHERE fk_llegada = :pk_llegada");
            $stmtDetalle->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtDetalle->bindParam(":pk_llegada", $pk_llegada, PDO::PARAM_INT);
            
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al cambiar estado de detalle de llegada");
            }
            
            // Actualizar estado en pesaje
            $stmtPesaje = $pdo->prepare("UPDATE pesaje SET estado = :estado WHERE fk_llegada = :pk_llegada");
            $stmtPesaje->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtPesaje->bindParam(":pk_llegada", $pk_llegada, PDO::PARAM_INT);
            
            if (!$stmtPesaje->execute()) {
                throw new Exception("Error al cambiar estado de pesaje");
            }
            
            // Actualizar estado en compra_mango
            $stmtCompra = $pdo->prepare("UPDATE compra_mango SET estado = :estado WHERE fk_llegada = :pk_llegada");
            $stmtCompra->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtCompra->bindParam(":pk_llegada", $pk_llegada, PDO::PARAM_INT);
            
            if (!$stmtCompra->execute()) {
                throw new Exception("Error al cambiar estado de compra de mango");
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