<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">
            <?php 
            if (isset($_POST['pk_usuario']) && isset($_POST['pk_dato_usuario'])) {
                echo "Editar Usuario Completo";
            } else {
                echo "Editar Datos de Usuario";
            }
            ?>
        </h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de usuario completo o solo datos personales
        if (isset($_POST['pk_usuario']) && isset($_POST['pk_dato_usuario'])) {
            // Edición de usuario completo - AQUÍ SE CARGAN TODOS LOS CAMPOS
            echo '<input type="hidden" name="pk_usuario" value="' . htmlspecialchars($_POST['pk_usuario']) . '" />';
            echo '<input type="hidden" name="pk_dato_usuario" value="' . htmlspecialchars($_POST['pk_dato_usuario']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_usuario_completo" />';
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorUsuario();
            $editar->editarUsuarioCompletoControlador();
            
        } elseif (isset($_POST['pk'])) {
            // Edición solo de datos personales (compatibilidad)
            echo '<input type="hidden" name="pk_dato_usuario" value="' . htmlspecialchars($_POST['pk']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_datos_usuario" />';
            
            // Mostrar formulario simple
            ?>
            <div class="row">
                <div class="col-12">
                    <h5 class="text-primary">Datos Personales</h5>
                    <?php
                    $editar = new ControladorUsuario();
                    $editar->editarDatosUsuariosControlador();
                    ?>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_usuario" />

    </form>
</div>

<!-- Script para validación en tiempo real -->
<script>
// Función para validar contraseñas
function validarPasswords() {
    const password = document.querySelector('input[name="contrasena"]');
    const confirmPassword = document.querySelector('input[name="confirmar_contrasena"]');
    
    if (password && confirmPassword) {
        function validar() {
            if (password.value !== '' || confirmPassword.value !== '') {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Las contraseñas no coinciden');
                } else if (password.value.length > 0 && password.value.length < 6) {
                    password.setCustomValidity('La contraseña debe tener al menos 6 caracteres');
                } else {
                    password.setCustomValidity('');
                    confirmPassword.setCustomValidity('');
                }
            } else {
                password.setCustomValidity('');
                confirmPassword.setCustomValidity('');
            }
        }
        
        password.addEventListener('input', validar);
        confirmPassword.addEventListener('input', validar);
        
        // Validación final en el submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (password.value !== '' || confirmPassword.value !== '') {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden'
                    });
                    return false;
                }
                
                if (password.value.length < 6) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña debe tener al menos 6 caracteres'
                    });
                    return false;
                }
            }
        });
    }
}

// Ejecutar validación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', validarPasswords);
</script>

</div>

<?php 
// Procesar la actualización según el tipo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorUsuario();
    
    switch ($_POST['accion']) {
        case 'actualizar_usuario_completo':
            $registro->actualizarUsuarioCompletoControlador();
            break;
            
        case 'actualizar_datos_usuario':
            $registro->actualizarDatosUsuariosControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_usuario'], $_POST['pk_dato_usuario'])) {
                $registro->actualizarUsuarioCompletoControlador();
            } elseif (isset($_POST['pk_dato_usuario'])) {
                $registro->actualizarDatosUsuariosControlador();
            }
            break;
    }
}
?>