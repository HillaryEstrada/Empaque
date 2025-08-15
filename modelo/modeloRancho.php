<?php

class ModeloRancho
{
    // REGISTRO RANCHO COMPLETO (transacción para ambas tablas)
    static public function registroRanchoCompletoModelo($datosProductor, $datosRancho)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en productor
            $stmtProductor = $pdo->prepare("INSERT INTO productor (nombre, telefono, estado, fecha, hora) 
                                         VALUES (:nombre, :telefono, 1, CURDATE(), CURTIME())");
            
            $stmtProductor->bindParam(":nombre", $datosProductor["nombre"], PDO::PARAM_STR);
            $stmtProductor->bindParam(":telefono", $datosProductor["telefono"], PDO::PARAM_STR);
            
            if (!$stmtProductor->execute()) {
                throw new Exception("Error al insertar datos del productor");
            }
            
            // 2. Obtener el ID del productor insertado
            $fk_productor = $pdo->lastInsertId();
            
            // 3. Insertar en rancho
            $stmtRancho = $pdo->prepare("INSERT INTO rancho (fk_productor, nombre, ubicacion, estado, fecha, hora) 
                                       VALUES (:fk_productor, :nombre, :ubicacion, 1, CURDATE(), CURTIME())");
            
            $stmtRancho->bindParam(":fk_productor", $fk_productor, PDO::PARAM_INT);
            $stmtRancho->bindParam(":nombre", $datosRancho["nombre"], PDO::PARAM_STR);
            $stmtRancho->bindParam(":ubicacion", $datosRancho["ubicacion"], PDO::PARAM_STR);
            
            if (!$stmtRancho->execute()) {
                throw new Exception("Error al crear rancho");
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

    // MOSTRAR RANCHOS COMPLETOS (con JOIN)
    static public function mostrarRanchosCompletosModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                r.pk_rancho,
                                p.pk_productor,
                                p.nombre as nombre_productor,
                                p.telefono,
                                r.nombre as nombre_rancho,
                                r.ubicacion
                              FROM rancho r
                              INNER JOIN productor p ON r.fk_productor = p.pk_productor
                              WHERE r.estado = :estado AND p.estado = :estado
                              ORDER BY p.nombre, r.nombre");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR RANCHO COMPLETO (obtener datos)
    static public function editarRanchoCompletoModelo($pk_rancho, $pk_productor)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                r.pk_rancho,
                                p.pk_productor,
                                p.nombre as nombre_productor,
                                p.telefono,
                                r.nombre as nombre_rancho,
                                r.ubicacion
                              FROM rancho r
                              INNER JOIN productor p ON r.fk_productor = p.pk_productor
                              WHERE r.pk_rancho = :pk_rancho AND p.pk_productor = :pk_productor");
        
        $stmt->bindParam(":pk_rancho", $pk_rancho, PDO::PARAM_INT);
        $stmt->bindParam(":pk_productor", $pk_productor, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR RANCHO COMPLETO (transacción)
    static public function actualizarRanchoCompletoModelo($datosProductor, $datosRancho)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar productor
            $stmtProductor = $pdo->prepare("UPDATE productor SET 
                                         nombre = :nombre,
                                         telefono = :telefono
                                         WHERE pk_productor = :pk_productor");
            
            $stmtProductor->bindParam(":nombre", $datosProductor["nombre"], PDO::PARAM_STR);
            $stmtProductor->bindParam(":telefono", $datosProductor["telefono"], PDO::PARAM_STR);
            $stmtProductor->bindParam(":pk_productor", $datosProductor["pk_productor"], PDO::PARAM_INT);
            
            if (!$stmtProductor->execute()) {
                throw new Exception("Error al actualizar datos del productor");
            }
            
            // 2. Actualizar rancho
            $stmtRancho = $pdo->prepare("UPDATE rancho SET 
                                       nombre = :nombre,
                                       ubicacion = :ubicacion
                                       WHERE pk_rancho = :pk_rancho");
            
            $stmtRancho->bindParam(":nombre", $datosRancho["nombre"], PDO::PARAM_STR);
            $stmtRancho->bindParam(":ubicacion", $datosRancho["ubicacion"], PDO::PARAM_STR);
            $stmtRancho->bindParam(":pk_rancho", $datosRancho["pk_rancho"], PDO::PARAM_INT);
            
            if (!$stmtRancho->execute()) {
                throw new Exception("Error al actualizar rancho");
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

    // CAMBIAR ESTADO RANCHO COMPLETO (activar/desactivar ambas tablas)
    static public function cambiarEstadoRanchoCompletoModelo($pk_rancho, $pk_productor, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en rancho
            $stmtRancho = $pdo->prepare("UPDATE rancho SET estado = :estado WHERE pk_rancho = :pk_rancho");
            $stmtRancho->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtRancho->bindParam(":pk_rancho", $pk_rancho, PDO::PARAM_INT);
            
            if (!$stmtRancho->execute()) {
                throw new Exception("Error al cambiar estado del rancho");
            }
            
            // Actualizar estado en productor
            $stmtProductor = $pdo->prepare("UPDATE productor SET estado = :estado WHERE pk_productor = :pk_productor");
            $stmtProductor->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtProductor->bindParam(":pk_productor", $pk_productor, PDO::PARAM_INT);
            
            if (!$stmtProductor->execute()) {
                throw new Exception("Error al cambiar estado del productor");
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