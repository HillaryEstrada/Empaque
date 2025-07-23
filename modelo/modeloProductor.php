<?php

    require_once "conexion.php";

    class ModeloProductor
    {
        // Alta productor
        static public function registroProductorModelo($datosModelo, $tabla) {

            try {
                $consulta = Conexion::conectar()->prepare("INSERT INTO $tabla(nombre, telefono) VALUES(:nombre, :telefono)");
                $consulta->bindParam(":nombre", $datosModelo["nombre"], PDO::PARAM_STR);
                $consulta->bindParam(":telefono", $datosModelo["telefono"], PDO::PARAM_STR);
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

         // Mostrar los datos de los productores
        static public function mostrarProductorModelo($estado)
        {
            // Consulta SQL que varía según el estado
            $consulta = Conexion::conectar()->prepare("SELECT pk_productor, nombre, telefono FROM productor WHERE estado = :estado");

            // Ejecutamos la consulta con el parámetro estado (1 para activos, 0 para inactivos)
            $consulta->bindParam(':estado', $estado, PDO::PARAM_INT);
            $consulta->execute();

            // Retornamos los resultados
            return $consulta->fetchAll();
        }

        // Poder obtener los datos a actualizar de los productores
        static public function editarProductorModelo($pk, $tabla)
            {
                $consulta = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE pk_productor = :pk");
            
                $consulta->bindParam(":pk", $pk, PDO::PARAM_INT);
            
                $consulta->execute();
            
                return $consulta->fetchAll();
            
                $consulta->close();
            }

            // Actualizar los datos de los productores
        static public function actualizacionProductorModelo($datosModelo, $tabla)
        {
            try {
                $consulta = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre, telefono = :telefono WHERE pk_productor = :pk");

                $consulta->bindParam(":nombre", $datosModelo['nombre'], PDO::PARAM_STR);
                $consulta->bindParam(":telefono", $datosModelo['telefono'], PDO::PARAM_STR);
                $consulta->bindParam(":pk", $datosModelo['pk_productor'], PDO::PARAM_INT);

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