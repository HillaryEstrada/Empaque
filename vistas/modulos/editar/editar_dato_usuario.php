<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Aquí traes la información para que el usuario vea los datos.
        $editar = new ControladorUsuario();
        $editar->editarDatosUsuariosControlador();
        ?>
        <input type="hidden" name="pk_dato_usuario" value="<?php echo isset($_POST['pk']) ? $_POST['pk'] : ''; ?>" />

        <!-- Campos hidden: Solo se llenan después del envío del formulario -->
        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" />
        </div>

        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>" />
        </div>

        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['edad']) ? htmlspecialchars($_POST['edad']) : ''; ?>" />
        </div>

        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['sexo']) ? htmlspecialchars($_POST['sexo']) : ''; ?>" />
        </div>
        
        <div class="form-group mb-3">
            <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        </div>

    </form>
</div>

<?php // este segundo para actualizar
    $registro = new ControladorUsuario();
    $registro -> actualizarDatosUsuariosControlador();
?>