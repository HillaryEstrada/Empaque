<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">Editar Rancho Completo</h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de rancho completo
        if (isset($_POST['pk_rancho']) && isset($_POST['pk_productor'])) {
            // Edición de rancho completo - AQUÍ SE CARGAN TODOS LOS CAMPOS
            echo '<input type="hidden" name="pk_rancho" value="' . htmlspecialchars($_POST['pk_rancho']) . '" />';
            echo '<input type="hidden" name="pk_productor" value="' . htmlspecialchars($_POST['pk_productor']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_rancho_completo" />';
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorRancho();
            $editar->editarRanchoCompletoControlador();
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_rancho" />

    </form>
</div>

</div>

<?php 
// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorRancho();
    
    if ($_POST['accion'] == 'actualizar_rancho_completo') {
        $registro->actualizarRanchoCompletoControlador();
    }
}
?>