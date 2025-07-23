<?php

    require_once "conexion.php";

    class ModeloUsuario extends Conexion
    {
        // Alta persona
        static public function registroPersonaModelo($datosModelo, $tabla) {

            try {
                $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, apellidos, edad, sexo) VALUES(:nombre, :apellidos, :edad, :sexo)");
                $consulta->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
                $consulta->bindParam(":apellidos", $datosModelo["apellidos"], PDO::PARAM_STR);
                $consulta->bindParam(":edad", $datosModelo["edad"], PDO::PARAM_INT);
                $consulta->bindParam(":sexo", $datosModelo["sexo"], PDO::PARAM_STR);

                if ($consulta->execute()) {
                    return 'ok';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Código de error para entrada duplicada
                    return 'duplicado';
                } else {
                    return 'error';
                }
            }
        }

        // Mostrar los datos de personas
        static public function mostrarDatosPersonasModelo($estado)
        {
            // Consulta SQL que varía según el estado
            $consulta = Conexion::conectar()->prepare("SELECT pk_dato_usuario, nombre, apellidos, edad, sexo FROM dato_usuario WHERE estado = :estado");

            // Ejecutamos la consulta con el parámetro estado (1 para activos, 0 para inactivos)
            $consulta->bindParam(':estado', $estado, PDO::PARAM_INT);
            $consulta->execute();

            // Retornamos los resultados
            return $consulta->fetchAll();
        }

        // Poder obtener los datos a actualizar de los usuarios
        static public function editarDatosUsuariosModelo($pk, $tabla)
            {
                $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE pk_dato_usuario = :pk");
            
                $consulta->bindParam(":pk", $pk, PDO::PARAM_INT);
            
                $consulta->execute();
            
                return $consulta->fetchAll();
            
                $consulta->close();
            }

        // Actualizar los datos de los usuarios
        static public function actualizacionDatosUsuariosModelo($datosModelo, $tabla)
        {
            try {
                $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, apellidos = :apellidos, edad = :edad, sexo = :sexo WHERE pk_dato_usuario = :pk");

                $consulta->bindParam(":nombre", $datosModelo['nombre'], PDO::PARAM_STR);
                $consulta->bindParam(":apellidos", $datosModelo['apellidos'], PDO::PARAM_STR);
                $consulta->bindParam(":edad", $datosModelo['edad'], PDO::PARAM_INT);
                $consulta->bindParam(":sexo", $datosModelo['sexo'], PDO::PARAM_STR);
                $consulta->bindParam(":pk", $datosModelo['pk_dato_usuario'], PDO::PARAM_INT);

                if ($consulta->execute()) {
                    $respuesta = 'ok';
                } else {
                    $respuesta = 'error';
                }
            } catch (Exception $e) {
                $respuesta = 'error: ' . $e->getMessage();
            }
            return $respuesta;
        }

    }
?>