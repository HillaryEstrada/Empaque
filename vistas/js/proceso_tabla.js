document.addEventListener("DOMContentLoaded", function () {
        let tablaBody = document.getElementById("tabla-body");
        let filas = Array.from(tablaBody.getElementsByTagName("tr"));
        let totalFilas = filas.length;
        let filasPorPagina = 10;
        let paginaActual = 1;
        let totalPaginas = Math.ceil(totalFilas / filasPorPagina);

        let btnPrimero = document.getElementById("btn-primero");
        let btnAnterior = document.getElementById("btn-anterior");
        let btnSiguiente = document.getElementById("btn-siguiente");
        let btnUltimo = document.getElementById("btn-ultimo");
        let paginaInfo = document.getElementById("pagina-info");
        let paginaInput = document.getElementById("pagina-input");
        let registroInfo = document.getElementById("registro-info");

        function mostrarPagina(pagina) {
            let inicio = (pagina - 1) * filasPorPagina;
            let fin = inicio + filasPorPagina;

            filas.forEach((fila, index) => {
                fila.style.display = (index >= inicio && index < fin) ? "" : "none";
            });

            paginaInfo.textContent = `Página ${pagina} de ${totalPaginas}`;
            paginaInput.value = pagina;
            btnAnterior.disabled = (pagina === 1);
            btnPrimero.disabled = (pagina === 1);
            btnSiguiente.disabled = (pagina === totalPaginas);
            btnUltimo.disabled = (pagina === totalPaginas);

            // Actualizar el contador de registros
            registroInfo.textContent = `${inicio + 1} - ${Math.min(fin, totalFilas)} de ${totalFilas} registros`;
        }

        mostrarPagina(paginaActual);

        btnPrimero.addEventListener("click", function () {
            paginaActual = 1;
            mostrarPagina(paginaActual);
        });

        btnAnterior.addEventListener("click", function () {
            if (paginaActual > 1) {
                paginaActual--;
                mostrarPagina(paginaActual);
            }
        });

        btnSiguiente.addEventListener("click", function () {
            if (paginaActual < totalPaginas) {
                paginaActual++;
                mostrarPagina(paginaActual);
            }
        });

        btnUltimo.addEventListener("click", function () {
            paginaActual = totalPaginas;
            mostrarPagina(paginaActual);
        });

        paginaInput.addEventListener("change", function () {
            let nuevaPagina = parseInt(paginaInput.value);
            if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
                paginaActual = nuevaPagina;
                mostrarPagina(paginaActual);
            } else {
                paginaInput.value = paginaActual;
            }
        });
    });

    
    // NOTA: Por lo pronto esto se queda aqui luego se busca como seprarlo y optimizarlo 
    function mostrarFormulario(btn) {
    let nuevoTitulo = btn.getAttribute('data-title'); // Obtiene el título del botón
    document.getElementById('titulo').innerText = nuevoTitulo;  
    document.getElementById('tabla-catalogo').style.display = 'none';
    document.getElementById('formulario-alta').style.display = 'block';
    }

    function ocultarFormulario() {
        document.getElementById('formulario-alta').style.display = 'none';
        document.getElementById('tabla-catalogo').style.display = 'block';
        location.reload(); // Recargar la página para actualizar la tabla
    }


    function enviarFormulario(event) {
        event.preventDefault(); // Evitar que el formulario se recargue

        let formData = new FormData(document.getElementById('form-alta'));

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Se aguardo el registro correctamente'
            });
            // Limpiar el formulario después de guardar
            document.getElementById('form-alta').reset();
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al registrar. Por favor, inténtalo de nuevo.'
            });
        });
    }