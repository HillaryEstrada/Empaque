<?php

class ModeloUsuario
{
    // REGISTRO USUARIO COMPLETO (transacción para ambas tablas)
    static public function registroUsuarioCompletoModelo($datosPersona, $datosUsuario)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Insertar en dato_usuario
            $stmtPersona = $pdo->prepare("INSERT INTO dato_usuario (nombre, apellidos, edad, sexo, estado, fecha, hora) 
                                         VALUES (:nombre, :apellidos, :edad, :sexo, 1, CURDATE(), CURTIME())");
            
            $stmtPersona->bindParam(":nombre", $datosPersona["nombre"], PDO::PARAM_STR);
            $stmtPersona->bindParam(":apellidos", $datosPersona["apellidos"], PDO::PARAM_STR);
            $stmtPersona->bindParam(":edad", $datosPersona["edad"], PDO::PARAM_INT);
            $stmtPersona->bindParam(":sexo", $datosPersona["sexo"], PDO::PARAM_STR);
            
            if (!$stmtPersona->execute()) {
                throw new Exception("Error al insertar datos personales");
            }
            
            // 2. Obtener el ID insertado
            $fk_dato_usuario = $pdo->lastInsertId();
            
            // 3. Insertar en usuario
            $stmtUsuario = $pdo->prepare("INSERT INTO usuario (fk_dato_usuario, fk_rol, usuario, contrasena, estado, fecha, hora) 
                                         VALUES (:fk_dato_usuario, :fk_rol, :usuario, :contrasena, 1, CURDATE(), CURTIME())");
            
            $stmtUsuario->bindParam(":fk_dato_usuario", $fk_dato_usuario, PDO::PARAM_INT);
            $stmtUsuario->bindParam(":fk_rol", $datosUsuario["fk_rol"], PDO::PARAM_INT);
            $stmtUsuario->bindParam(":usuario", $datosUsuario["usuario"], PDO::PARAM_STR);
            $stmtUsuario->bindParam(":contrasena", $datosUsuario["contrasena"], PDO::PARAM_STR);
            
            if (!$stmtUsuario->execute()) {
                throw new Exception("Error al crear usuario");
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

    // VERIFICAR SI USUARIO EXISTE
    static public function verificarUsuarioExistenteModelo($usuario, $excludeId = null)
    {
        $pdo = Conexion::conectar();
        
        if ($excludeId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = :usuario AND pk_usuario != :exclude_id");
            $stmt->bindParam(":exclude_id", $excludeId, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE usuario = :usuario");
        }
        
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        
        $count = $stmt->fetchColumn();
        $pdo = null;
        
        return $count > 0;
    }

    // CARGAR ROLES ACTIVOS
    static public function cargarRolesModelo()
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_rol, nombre FROM rol WHERE estado = 1 ORDER BY nombre");
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // MOSTRAR USUARIOS COMPLETOS (con JOIN)
    static public function mostrarUsuariosCompletosModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                u.pk_usuario,
                                du.pk_dato_usuario,
                                du.nombre,
                                du.apellidos,
                                du.edad,
                                du.sexo,
                                u.usuario,
                                r.nombre as rol_nombre,
                                u.fk_rol
                              FROM usuario u
                              INNER JOIN dato_usuario du ON u.fk_dato_usuario = du.pk_dato_usuario
                              INNER JOIN rol r ON u.fk_rol = r.pk_rol
                              WHERE u.estado = :estado AND du.estado = :estado
                              ORDER BY du.nombre, du.apellidos");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR USUARIO COMPLETO (obtener datos)
    static public function editarUsuarioCompletoModelo($pk_usuario, $pk_dato_usuario)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT 
                                u.pk_usuario,
                                du.pk_dato_usuario,
                                du.nombre,
                                du.apellidos,
                                du.edad,
                                du.sexo,
                                u.usuario,
                                u.fk_rol
                              FROM usuario u
                              INNER JOIN dato_usuario du ON u.fk_dato_usuario = du.pk_dato_usuario
                              WHERE u.pk_usuario = :pk_usuario AND du.pk_dato_usuario = :pk_dato_usuario");
        
        $stmt->bindParam(":pk_usuario", $pk_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":pk_dato_usuario", $pk_dato_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZAR USUARIO COMPLETO (transacción)
    static public function actualizarUsuarioCompletoModelo($datosPersona, $datosUsuario)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // 1. Actualizar dato_usuario
            $stmtPersona = $pdo->prepare("UPDATE dato_usuario SET 
                                         nombre = :nombre,
                                         apellidos = :apellidos,
                                         edad = :edad,
                                         sexo = :sexo
                                         WHERE pk_dato_usuario = :pk_dato_usuario");
            
            $stmtPersona->bindParam(":nombre", $datosPersona["nombre"], PDO::PARAM_STR);
            $stmtPersona->bindParam(":apellidos", $datosPersona["apellidos"], PDO::PARAM_STR);
            $stmtPersona->bindParam(":edad", $datosPersona["edad"], PDO::PARAM_INT);
            $stmtPersona->bindParam(":sexo", $datosPersona["sexo"], PDO::PARAM_STR);
            $stmtPersona->bindParam(":pk_dato_usuario", $datosPersona["pk_dato_usuario"], PDO::PARAM_INT);
            
            if (!$stmtPersona->execute()) {
                throw new Exception("Error al actualizar datos personales");
            }
            
            // 2. Actualizar usuario (con o sin contraseña)
            if (isset($datosUsuario['contrasena'])) {
                $sqlUsuario = "UPDATE usuario SET 
                              usuario = :usuario,
                              fk_rol = :fk_rol,
                              contrasena = :contrasena
                              WHERE pk_usuario = :pk_usuario";
            } else {
                $sqlUsuario = "UPDATE usuario SET 
                              usuario = :usuario,
                              fk_rol = :fk_rol
                              WHERE pk_usuario = :pk_usuario";
            }
            
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindParam(":usuario", $datosUsuario["usuario"], PDO::PARAM_STR);
            $stmtUsuario->bindParam(":fk_rol", $datosUsuario["fk_rol"], PDO::PARAM_INT);
            $stmtUsuario->bindParam(":pk_usuario", $datosUsuario["pk_usuario"], PDO::PARAM_INT);
            
            if (isset($datosUsuario['contrasena'])) {
                $stmtUsuario->bindParam(":contrasena", $datosUsuario["contrasena"], PDO::PARAM_STR);
            }
            
            if (!$stmtUsuario->execute()) {
                throw new Exception("Error al actualizar usuario");
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

    // CAMBIAR ESTADO USUARIO COMPLETO (activar/desactivar ambas tablas)
    static public function cambiarEstadoUsuarioCompletoModelo($pk_usuario, $pk_dato_usuario, $estado)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            
            // Actualizar estado en usuario
            $stmtUsuario = $pdo->prepare("UPDATE usuario SET estado = :estado WHERE pk_usuario = :pk_usuario");
            $stmtUsuario->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtUsuario->bindParam(":pk_usuario", $pk_usuario, PDO::PARAM_INT);
            
            if (!$stmtUsuario->execute()) {
                throw new Exception("Error al cambiar estado del usuario");
            }
            
            // Actualizar estado en dato_usuario
            $stmtPersona = $pdo->prepare("UPDATE dato_usuario SET estado = :estado WHERE pk_dato_usuario = :pk_dato_usuario");
            $stmtPersona->bindParam(":estado", $estado, PDO::PARAM_INT);
            $stmtPersona->bindParam(":pk_dato_usuario", $pk_dato_usuario, PDO::PARAM_INT);
            
            if (!$stmtPersona->execute()) {
                throw new Exception("Error al cambiar estado de los datos personales");
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

    // REGISTRO PERSONA (mantener compatibilidad)
    static public function registroPersonaModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO $tabla (nombre, apellidos, edad, sexo) 
                              VALUES (:nombre, :apellidos, :edad, :sexo)");
        
        $stmt->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datosModelo["apellidos"], PDO::PARAM_STR);
        $stmt->bindParam(":edad", $datosModelo["edad"], PDO::PARAM_INT);
        $stmt->bindParam(":sexo", $datosModelo["sexo"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }

    // MOSTRAR DATOS PERSONAS (mantener compatibilidad)
    static public function mostrarDatosPersonasModelo($estado)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_dato_usuario, nombre, apellidos, edad, sexo 
                              FROM dato_usuario 
                              WHERE estado = :estado 
                              ORDER BY nombre, apellidos");
        
        $stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // EDITAR DATOS USUARIOS (mantener compatibilidad)
    static public function editarDatosUsuariosModelo($pk, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("SELECT pk_dato_usuario, nombre, apellidos, edad, sexo 
                              FROM $tabla 
                              WHERE pk_dato_usuario = :pk");
        
        $stmt->bindParam(":pk", $pk, PDO::PARAM_INT);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        $pdo = null;
        
        return $respuesta;
    }

    // ACTUALIZACIÓN DATOS USUARIOS (mantener compatibilidad)
    static public function actualizacionDatosUsuariosModelo($datosModelo, $tabla)
    {
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("UPDATE $tabla SET 
                              nombre = :nombre,
                              apellidos = :apellidos,
                              edad = :edad,
                              sexo = :sexo
                              WHERE pk_dato_usuario = :pk_dato_usuario");
        
        $stmt->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datosModelo["apellidos"], PDO::PARAM_STR);
        $stmt->bindParam(":edad", $datosModelo["edad"], PDO::PARAM_INT);
        $stmt->bindParam(":sexo", $datosModelo["sexo"], PDO::PARAM_STR);
        $stmt->bindParam(":pk_dato_usuario", $datosModelo["pk_dato_usuario"], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $respuesta = "ok";
        } else {
            $respuesta = "error";
        }
        
        $pdo = null;
        return $respuesta;
    }
}

?>