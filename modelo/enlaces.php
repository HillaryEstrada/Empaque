<?php

    require_once "conexion.php";
    class Paginas
    {
        #Metodo para obtener la ruta de un módulo desde  la base de datos
        static public function enlacesPaginaModelo($enlace_recibido)
        {
            try
            {
            //obtener la conexion a la base de datos
            $conexion = Conexion::conectar();

            //preparar la consulta para obtener la ruta del módulo
            $eleccion = $conexion->prepare("SELECT ruta FROM ruta WHERE nombre = :nombre AND estado = 1");

            //vincular los parametros
            $eleccion->bindParam(":nombre", $enlace_recibido, PDO::PARAM_STR);

            //ejecutamos la consulta
            $eleccion->execute();

            //verificamos si se encontró algun resultado
            if($renglon = $eleccion->fetch(PDO::FETCH_ASSOC))
            {
                //Si se encontró el resultado, asignar la ruta obtenida al avariable $modulo
                $modulo = $renglon["ruta"];
            }
            else
            {
                //si no se encontró asignar una ruta por defecto (error)
                $modulo = "vistas/modulos/404_notfound.php";
            }

            //retornar la variable con la ruta solicitada
            return $modulo;
            }
            catch(PDOException $e)
            {
                echo "Error: ".$e -> getMessage();

                return "vistas/modulos/404_notfound.php";
            }
        }
    }

?>