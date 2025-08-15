<?php
// editar_llegada_mango.php
?>
<div class="container-mostrar"> 
<div align="center">
    <div class="alert alert-primary mt-5" role="alert">
        <h1 id="titulo">Editar Llegada de Mango</h1>
    </div>
</div>

<div class="separator-mango"></div>

<div class="row mt-5">
    <form class="form-control" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
        <?php
        // Verificar si es edición de llegada completa
        if (isset($_POST['pk_llegada'])) {
            echo '<input type="hidden" name="pk_llegada" value="' . htmlspecialchars($_POST['pk_llegada']) . '" />';
            echo '<input type="hidden" name="accion" value="actualizar_llegada_completa" />';
            
            // Llamar al controlador para mostrar el formulario completo
            $editar = new ControladorLlegada();
            $editar->editarLlegadaCompletaControlador();
        }
        ?>

        <!-- Campos hidden adicionales para mantener el estado -->
        <input type="hidden" name="opcion" value="<?php echo isset($_POST['opcion']) ? htmlspecialchars($_POST['opcion']) : ''; ?>" />
        <input type="hidden" name="menu" value="editar_llegada_mango" />

    </form>
</div>

<!-- Scripts para cálculos automáticos en edición -->
<script>
function calcularPesoNeto() {
    var pesoBruto = parseFloat(document.getElementsByName('peso_bruto')[0].value) || 0;
    var pesoEnvase = parseFloat(document.getElementsByName('peso_envase')[0].value) || 0;
    var pesoNeto = pesoBruto - pesoEnvase;
    
    if (pesoNeto < 0) pesoNeto = 0;
    
    document.getElementsByName('peso_neto')[0].value = pesoNeto.toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    var pesoNeto = parseFloat(document.getElementsByName('peso_neto')[0].value) || 0;
    var precioKilo = parseFloat(document.getElementsByName('precio_kilo')[0].value) || 0;
    var total = pesoNeto * precioKilo;
    
    document.getElementsByName('total_pagado')[0].value = total.toFixed(2);
}

// Función para validación
function validarFormulario() {
    // Validación final en el submit
    document.querySelector('form').addEventListener('submit', function(e) {
        var pesoBruto = parseFloat(document.getElementsByName('peso_bruto')[0].value) || 0;
        var pesoEnvase = parseFloat(document.getElementsByName('peso_envase')[0].value) || 0;
        
        if (pesoBruto <= pesoEnvase) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El peso bruto debe ser mayor al peso del envase'
            });
            return false;
        }
    });
}

// Ejecutar validación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    validarFormulario();
    
    // Agregar eventos a los campos de peso para cálculo automático
    const pesoBruto = document.getElementsByName('peso_bruto')[0];
    const pesoEnvase = document.getElementsByName('peso_envase')[0];
    const precioKilo = document.getElementsByName('precio_kilo')[0];
    
    if (pesoBruto) pesoBruto.addEventListener('input', calcularPesoNeto);
    if (pesoEnvase) pesoEnvase.addEventListener('input', calcularPesoNeto);
    if (precioKilo) precioKilo.addEventListener('input', calcularTotal);
});
</script>

</div>

<?php 
// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $registro = new ControladorLlegada();
    
    switch ($_POST['accion']) {
        case 'actualizar_llegada_completa':
            $registro->actualizarLlegadaCompletaControlador();
            break;
            
        default:
            // Si no hay acción específica, determinar por los campos POST
            if (isset($_POST['pk_llegada'])) {
                $registro->actualizarLlegadaCompletaControlador();
            }
            break;
    }
}
?>