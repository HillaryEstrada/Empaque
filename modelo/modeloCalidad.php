<?php

class ModeloCalidad
{
    // REGISTRO CALIDAD COMPLETA (transacción para las tres tablas)
    static public function registroCalidadCompletaModelo($datosRevision, $datosLote, $datosClasificacion)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en revision_calidad
            $stmtRevision = $pdo->prepare("INSERT INTO revision_calidad (fk_llegada, madurez, plagas, daños, contaminantes, observaciones, estado, fecha, hora) 
                                          VALUES (:fk_llegada, :madurez, :plagas, :danos, :contaminantes, :observaciones, 1, CURDATE(), CURTIME())");
            
            $stmtRevision->bindParam(":fk_llegada", $datosRevision["fk_llegada"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":madurez", $datosRevision["madurez"], PDO::PARAM_STR);
            $stmtRevision->bindParam(":plagas", $datosRevision["plagas"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":danos", $datosRevision["danos"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":contaminantes", $datosRevision["contaminantes"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":observaciones", $datosRevision["observaciones"], PDO::PARAM_STR);
            
            if (!$stmtRevision->execute()) {
                throw new Exception("Error al insertar revisión de calidad");
            }
            
            // 2. Insertar en lote
            $stmtLote = $pdo->prepare("INSERT INTO lote (fk_llegada, numero_lote, variedad, estado, fecha, hora) 
                                     VALUES (:fk_llegada, :numero_lote, :variedad, 1, CURDATE(), CURTIME())");
            
            $stmtLote->bindParam(":fk_llegada", $datosLote["fk_llegada"], PDO::PARAM_INT);
            $stmtLote->bindParam(":numero_lote", $datosLote["numero_lote"], PDO::PARAM_STR);
            $stmtLote->bindParam(":variedad", $datosLote["variedad"], PDO::PARAM_STR);
            
            if (!$stmtLote->execute()) {
                throw new Exception("Error al crear lote");
            }
            
            // 3. Obtener el ID del lote insertado
            $fk_lote = $pdo->lastInsertId();
            
            // 4. Insertar en clasificacion
            $stmtClasificacion = $pdo->prepare("INSERT INTO clasificacion (fk_lote, primera_calidad, segunda_calidad, descarte, uso, observaciones, estado, fecha, hora) 
                                              VALUES (:fk_lote, :primera_calidad, :segunda_calidad, :descarte, :uso, :observaciones, 1, CURDATE(), CURTIME())");
            
            $stmtClasificacion->bindParam(":fk_lote", $fk_lote, PDO::PARAM_INT);
            $stmtClasificacion->bindParam(":primera_calidad", $datosClasificacion["primera_calidad"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":segunda_calidad", $datosClasificacion["segunda_calidad"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":descarte", $datosClasificacion["descarte"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":uso", $datosClasificacion["uso"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":observaciones", $datosClasificacion["observaciones"], PDO::PARAM_STR);
            
            if (!$stmtClasificacion->execute()) {
                throw new Exception("Error al crear clasificación");
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

    // VERIFICAR SI LOTE EXISTE
    static public function verificarLoteExistenteModelo($numero_lote, $fk_llegada, $excludeId = null)
    {
        $pdo = Conexion::conectar();
        
        if ($excludeId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM lote WHERE numero_lote = :numero_lote AND fk_llegada = :fk_llegada AND pk_lote != :exclude_id");
            $stmt->bindParam(":exclude_id", $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM lote WHERE numero_lote = :numero_lote AND fk_llegada = :fk_llegada");
        }
        
        $stmt->bindParam(":numero_lote", $numero_lote, PDO::PARAM_STR);
        $stmt->bindParam(":fk_llegada", $fk_llegada, PDO::PARAM_INT);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();
        $pdo = null;
        
        return $count > 0;
    }

    // CARGAR LLEGADAS ACTIVAS
    static public function cargarLlegadasModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_llegada, fecha FROM llegada_mango WHERE estado = 1 ORDER BY fecha DESC");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR CALIDAD COMPLETA (con JOINs)
    static public function mostrarCalidadCompletaModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                rc.pk_revision,
                                rc.fk_llegada,
                                rc.madurez,
                                rc.plagas,
                                rc.daños as danos,
                                rc.contaminantes,
                                rc.observaciones as observaciones_revision,
                                l.pk_lote,
                                l.numero_lote,
                                l.variedad,
                                c.pk_clasificacion,
                                c.primera_calidad,
                                c.segunda_calidad,
                                c.descarte,
                                c.uso,
                                c.observaciones as observaciones_clasificacion
                              FROM revision_calidad rc
                              INNER JOIN lote l ON rc.fk_llegada = l.fk_llegada
                              INNER JOIN clasificacion c ON l.pk_lote = c.fk_lote
                              WHERE rc.estado = :estado AND l.estado = :estado AND c.estado = :estado
                              ORDER BY rc.fk_llegada DESC, l.numero_lote");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR CALIDAD COMPLETA (obtener datos)
    static public function editarCalidadCompletaModelo($pk_revision, $pk_lote, $pk_clasificacion)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                rc.pk_revision,
                                rc.fk_llegada,
                                rc.madurez,
                                rc.plagas,
                                rc.daños as danos,
                                rc.contaminantes,
                                rc.observaciones as observaciones_revision,
                                l.pk_lote,
                                l.numero_lote,
                                l.variedad,
                                c.pk_clasificacion,
                                c.primera_calidad,
                                c.segunda_calidad,
                                c.descarte,
                                c.uso,
                                c.observaciones as observaciones_clasificacion
                              FROM revision_calidad rc
                              INNER JOIN lote l ON rc.fk_llegada = l.fk_llegada
                              INNER JOIN clasificacion c ON l.pk_lote = c.fk_lote
                              WHERE rc.pk_revision = :pk_revision AND l.pk_lote = :pk_lote AND c.pk_clasificacion = :pk_clasificacion");
        
        $stmt->bindParam(":pk_revision", $pk_revision, PDO::PARAM_INT);
        $stmt->bindParam(":pk_lote", $pk_lote, PDO::PARAM_INT);
        $stmt->bindParam(":pk_clasificacion", $pk_clasificacion, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR SOLO REVISIÓN DE CALIDAD (compatibilidad)
    static public function editarRevisionCalidadModelo($pk_revision)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                pk_revision,
                                fk_llegada,
                                madurez,
                                plagas,
                                daños as danos,
                                contaminantes,
                                observaciones
                              FROM revision_calidad 
                              WHERE pk_revision = :pk_revision");
        
        $stmt->bindParam(":pk_revision", $pk_revision, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR CALIDAD COMPLETA (transacción)
    static public function actualizarCalidadCompletaModelo($datosRevision, $datosLote, $datosClasificacion)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar revision_calidad
            $stmtRevision = $pdo->prepare("UPDATE revision_calidad SET 
                                          fk_llegada = :fk_llegada,
                                          madurez = :madurez,
                                          plagas = :plagas,
                                          daños = :danos,
                                          contaminantes = :contaminantes,
                                          observaciones = :observaciones
                                          WHERE pk_revision = :pk_revision");
            
            $stmtRevision->bindParam(":fk_llegada", $datosRevision["fk_llegada"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":madurez", $datosRevision["madurez"], PDO::PARAM_STR);
            $stmtRevision->bindParam(":plagas", $datosRevision["plagas"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":danos", $datosRevision["danos"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":contaminantes", $datosRevision["contaminantes"], PDO::PARAM_INT);
            $stmtRevision->bindParam(":observaciones", $datosRevision["observaciones"], PDO::PARAM_STR);
            $stmtRevision->bindParam(":pk_revision", $datosRevision["pk_revision"], PDO::PARAM_INT);
            
            if (!$stmtRevision->execute()) {
                throw new Exception("Error al actualizar revisión de calidad");
            }
            
            // 2. Actualizar lote
            $stmtLote = $pdo->prepare("UPDATE lote SET 
                                      fk_llegada = :fk_llegada,
                                      numero_lote = :numero_lote,
                                      variedad = :variedad
                                      WHERE pk_lote = :pk_lote");
            
            $stmtLote->bindParam(":fk_llegada", $datosLote["fk_llegada"], PDO::PARAM_INT);
            $stmtLote->bindParam(":numero_lote", $datosLote["numero_lote"], PDO::PARAM_STR);
            $stmtLote->bindParam(":variedad", $datosLote["variedad"], PDO::PARAM_STR);
            $stmtLote->bindParam(":pk_lote", $datosLote["pk_lote"], PDO::PARAM_INT);
            
            if (!$stmtLote->execute()) {
                throw new Exception("Error al actualizar lote");
            }
            
            // 3. Actualizar clasificacion
            $stmtClasificacion = $pdo->prepare("UPDATE clasificacion SET 
                                               primera_calidad = :primera_calidad,
                                               segunda_calidad = :segunda_calidad,
                                               descarte = :descarte,
                                               uso = :uso,
                                               observaciones = :observaciones
                                               WHERE pk_clasificacion = :pk_clasificacion");
            
            $stmtClasificacion->bindParam(":primera_calidad", $datosClasificacion["primera_calidad"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":segunda_calidad", $datosClasificacion["segunda_calidad"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":descarte", $datosClasificacion["descarte"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":uso", $datosClasificacion["uso"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":observaciones", $datosClasificacion["observaciones"], PDO::PARAM_STR);
            $stmtClasificacion->bindParam(":pk_clasificacion", $datosClasificacion["pk_clasificacion"], PDO::PARAM_INT);
            
            if (!$stmtClasificacion->execute()) {
                throw new Exception("Error al actualizar clasificación");
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

    // ACTUALIZAR SOLO REVISIÓN DE CALIDAD (compatibilidad)
    static public function actualizarRevisionCalidadModelo($datosRevision)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE revision_calidad SET 
                              fk_llegada = :fk_llegada,
                              madurez = :madurez,
                              plagas = :plagas,
                              daños = :danos,
                              contaminantes = :contaminantes,
                              observaciones = :observaciones
                              WHERE pk_revision = :pk_revision");
        
        $stmt->bindParam(":fk_llegada", $datosRevision["fk_llegada"], PDO::PARAM_INT);
        $stmt->bindParam(":madurez", $datosRevision["madurez"], PDO::PARAM_STR);
        $stmt->bindParam(":plagas", $datosRevision["plagas"], PDO::PARAM_INT);
        $stmt->bindParam(":danos", $datosRevision["danos"], PDO::PARAM_INT);
        $stmt->bindParam(":contaminantes", $datosRevision["contaminantes"], PDO::PARAM_INT);
        $stmt->bindParam(":observaciones", $datosRevision["observaciones"], PDO::PARAM_STR);
        $stmt->bindParam(":pk_revision", $datosRevision["pk_revision"], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // CAMBIAR ESTADO CALIDAD COMPLETA (activar/desactivar las tres tablas)
    static public function cambiarEstadoCalidadCompletaModelo($pk_revision, $pk_lote, $pk_clasificacion, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en revision_calidad
            $stmtRevision = $pdo->prepare("UPDATE revision_calidad SET estado = :estado WHERE pk_revision = :pk_revision");
            $stmtRevision->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtRevision->bindParam(":pk_revision", $pk_revision, PDO::PARAM_INT);
            
            if (!$stmtRevision->execute()) {
                throw new Exception("Error al cambiar estado de la revisión de calidad");
            }
            
            // Actualizar estado en lote
            $stmtLote = $pdo->prepare("UPDATE lote SET estado = :estado WHERE pk_lote = :pk_lote");
            $stmtLote->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtLote->bindParam(":pk_lote", $pk_lote, PDO::PARAM_INT);
            
            if (!$stmtLote->execute()) {
                throw new Exception("Error al cambiar estado del lote");
            }
            
            // Actualizar estado en clasificacion
            $stmtClasificacion = $pdo->prepare("UPDATE clasificacion SET estado = :estado WHERE pk_clasificacion = :pk_clasificacion");
            $stmtClasificacion->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtClasificacion->bindParam(":pk_clasificacion", $pk_clasificacion, PDO::PARAM_INT);
            
            if (!$stmtClasificacion->execute()) {
                throw new Exception("Error al cambiar estado de la clasificación");
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

    // REGISTRO REVISIÓN CALIDAD (mantener compatibilidad)
    static public function registroRevisionCalidadModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (fk_llegada, madurez, plagas, daños, contaminantes, observaciones) 
                              VALUES (:fk_llegada, :madurez, :plagas, :danos, :contaminantes, :observaciones)");
        
        $stmt->bindParam(":fk_llegada", $datosModelo["fk_llegada"], PDO::PARAM_INT);
        $stmt->bindParam(":madurez", $datosModelo["madurez"], PDO::PARAM_STR);
        $stmt->bindParam(":plagas", $datosModelo["plagas"], PDO::PARAM_INT);
        $stmt->bindParam(":danos", $datosModelo["danos"], PDO::PARAM_INT);
        $stmt->bindParam(":contaminantes", $datosModelo["contaminantes"], PDO::PARAM_INT);
        $stmt->bindParam(":observaciones", $datosModelo["observaciones"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // REGISTRO LOTE (mantener compatibilidad)
    static public function registroLoteModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (fk_llegada, numero_lote, variedad) 
                              VALUES (:fk_llegada, :numero_lote, :variedad)");
        
        $stmt->bindParam(":fk_llegada", $datosModelo["fk_llegada"], PDO::PARAM_INT);
        $stmt->bindParam(":numero_lote", $datosModelo["numero_lote"], PDO::PARAM_STR);
        $stmt->bindParam(":variedad", $datosModelo["variedad"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // REGISTRO CLASIFICACIÓN (mantener compatibilidad)
    static public function registroClasificacionModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (fk_lote, primera_calidad, segunda_calidad, descarte, uso, observaciones) 
                              VALUES (:fk_lote, :primera_calidad, :segunda_calidad, :descarte, :uso, :observaciones)");
        
        $stmt->bindParam(":fk_lote", $datosModelo["fk_lote"], PDO::PARAM_INT);
        $stmt->bindParam(":primera_calidad", $datosModelo["primera_calidad"], PDO::PARAM_STR);
        $stmt->bindParam(":segunda_calidad", $datosModelo["segunda_calidad"], PDO::PARAM_STR);
        $stmt->bindParam(":descarte", $datosModelo["descarte"], PDO::PARAM_STR);
        $stmt->bindParam(":uso", $datosModelo["uso"], PDO::PARAM_STR);
        $stmt->bindParam(":observaciones", $datosModelo["observaciones"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // MOSTRAR REVISIONES DE CALIDAD (mantener compatibilidad)
    static public function mostrarRevisionesCalidadModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT rc.pk_revision, rc.fk_llegada, rc.madurez, rc.plagas, rc.daños as danos, rc.contaminantes, rc.observaciones 
                              FROM revision_calidad rc 
                              WHERE rc.estado = :estado 
                              ORDER BY rc.fk_llegada DESC");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR LOTES (mantener compatibilidad)
    static public function mostrarLotesModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_lote, fk_llegada, numero_lote, variedad 
                              FROM lote 
                              WHERE estado = :estado 
                              ORDER BY numero_lote");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR CLASIFICACIONES (mantener compatibilidad)
    static public function mostrarClasificacionesModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT c.pk_clasificacion, c.fk_lote, c.primera_calidad, c.segunda_calidad, c.descarte, c.uso, c.observaciones, l.numero_lote
                              FROM clasificacion c
                              INNER JOIN lote l ON c.fk_lote = l.pk_lote
                              WHERE c.estado = :estado 
                              ORDER BY l.numero_lote");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }
}

?>