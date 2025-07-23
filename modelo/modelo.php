<?php

    require_once "conexion.php";

    class Modelo extends Conexion
    {
        public static function obtenerRutaPorNombre($nombre) {
            $conexion = Conexion::conectar();
            if (!$conexion) {
                return null; // O lanza una excepción
            }
            $stmt = $conexion->prepare("SELECT ruta FROM ruta WHERE nombre = :nombre AND estado = 1");
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        }

        // FUNCION GENERICA PARA DESACTIVAR:  SOLO CON ESTA FUNCION AQUI:
        // Función para desactivar un elemento en una tabla específica
        static public function desactivarElementoModelo($pk, $tabla, $campo)
        {
            // Prepara la consulta SQL para actualizar el estado del elemento a desactivado
            $consulta = conexion::conectar()->prepare("UPDATE $tabla SET estado = 0 WHERE $campo = :pk");
            
            // Vincula el valor del parámetro :pk con el valor de la clave primaria proporcionada
            $consulta->bindParam(":pk", $pk, PDO::PARAM_INT);

            // Ejecuta la consulta preparada y verifica si fue exitosa
            if($consulta->execute())
            {
                // Si la consulta fue exitosa, asigna 'ok' a la variable de respuesta
                $respuesta = 'ok';
            }
            else 
            {
                // Si la consulta falló, asigna 'error' a la variable de respuesta
                $respuesta = 'error';
            }
            // Cierra el cursor de la consulta para liberar recursos
            $consulta->closeCursor();

            // Devuelve la respuesta indicando si la operación fue exitosa ('ok') o no ('error')
            return $respuesta;
        }

         // Función para activar un elemento en una tabla específica
        static public function activarElementoModelo($pk, $tabla, $campo)
        {
            $consulta = conexion::conectar()->prepare("UPDATE $tabla SET estado = 1 WHERE $campo = :pk");
            
            $consulta->bindParam(":pk", $pk, PDO::PARAM_INT);

            if($consulta->execute())
            {
                $respuesta = 'ok';
            }
            else 
            {
                $respuesta = 'error';
            }

            $consulta->closeCursor();

            return $respuesta;
        }
    }
?>