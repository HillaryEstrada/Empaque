<div class="container-mostrar">
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">Editar Roles</h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Aquí traes la información para que el usuario vea los datos.
        $editar = new controladorRol();
        $editar->editarRolControlador();
        ?>
        <input type="hidden" name="pk_rol" value="<?php echo isset($_POST['pk']) ? $_POST['pk'] : ''; ?>" />

        <!-- Campos hidden: Solo se llenan después del envío del formulario -->
        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" />
        </div>

        <div class="form-group mb-3">
            <input type="hidden" name="menu" value="<?php echo isset($_POST['descripcion']) ? htmlspecialchars($_POST['descripcion']) : ''; ?>" />
        </div>

        <div class="form-group mb-3">
            <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        </div>

    </form>
</div>
</div>

<?php // este segundo para actualizar
    $registro = new controladorRol();
    $registro -> actualizarRolControlador();
?>