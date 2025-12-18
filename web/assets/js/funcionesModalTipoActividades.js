document.addEventListener('DOMContentLoaded', () => {

    console.log('JS TipoActividades cargado');


    const buscador = document.getElementById('buscadorNombre');
    const tabla = document.getElementById('tablaActividades');
    if (!tabla) return;

    const tbody = tabla.querySelector('tbody');

    let ordenActual = {
        columna: null,
        asc: true
    };


    // FILA SIN RESULTADOS

    const filaMensaje = document.createElement('tr');
    filaMensaje.innerHTML = `
        <td colspan="5" class="text-center text-muted py-3">
            No se encontraron resultados
        </td>
    `;


    //BUSCADOR

    buscador.addEventListener('keyup', () => {
        const texto = buscador.value.toLowerCase().trim();
        const filas = Array.from(tbody.querySelectorAll('tr'));

        let visibles = 0;

        filas.forEach(fila => {

            if (fila.cells.length < 2) return;

            const nombre = fila.cells[1].textContent.toLowerCase();

            if (nombre.includes(texto)) {
                fila.style.display = '';
                visibles++;
            } else {
                fila.style.display = 'none';
            }
        });

        if (visibles === 0) {
            if (!tbody.contains(filaMensaje)) {
                tbody.appendChild(filaMensaje);
            }
        } else {
            filaMensaje.remove();
        }
    });


    // ORDENAR TABLA

    window.ordenarTabla = (columnaIndex) => {

        const filas = Array.from(tbody.querySelectorAll('tr'))
            .filter(f => f.cells.length > columnaIndex);

        if (ordenActual.columna === columnaIndex) {
            ordenActual.asc = !ordenActual.asc;
        } else {
            ordenActual.columna = columnaIndex;
            ordenActual.asc = true;
        }

        filas.sort((a, b) => {
            let aVal = a.cells[columnaIndex].textContent.trim();
            let bVal = b.cells[columnaIndex].textContent.trim();

            // ID
            if (columnaIndex === 0) {
                aVal = Number(aVal);
                bVal = Number(bVal);
            }

            // FECHA
            if (columnaIndex === 2) {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            }

            return ordenActual.asc ? aVal - bVal : bVal - aVal;
        });

        tbody.innerHTML = '';
        filas.forEach(fila => tbody.appendChild(fila));
    };
});


// MODALES Y BOTONES
function verDetalle(id) {
    document.getElementById('detalleId').innerText = id;
    document.getElementById('detalleNombre').innerText = 'Actividad ' + id;
    document.getElementById('detalleEstado').innerHTML =
        '<span class="badge bg-success">Activo</span>';

    new bootstrap.Modal(
        document.getElementById('modalDetalle')
    ).show();
}

function editarActividad(id, nombre, estado) {
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEstadoHidden').value = estado;

    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('modalEditar')
    ).show();
}

// LIMPIAR CAMPOS AL CERRAR MODAL
document.getElementById('modalEditar')?.addEventListener(
    'hidden.bs.modal',
    function () {
        document.getElementById('editId').value = '';
        document.getElementById('editNombre').value = '';
        document.getElementById('editEstadoHidden').value = '';
    }
);

function actividadInhabilitada() {
    Swal.fire({
        icon: 'info',
        title: 'Actividad inhabilitada',
        text: 'Este registro está inhabilitado y no se puede editar.',
        confirmButtonColor: '#6f63ff'
    });
}

function eliminarActividad(id) {
    Swal.fire({
        title: '¿Inhabilitar actividad?',
        text: 'Esta acción cambiará el estado a INACTIVO',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, inhabilitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href =
                'index.php?modulo=TipoActividades' +
                '&controlador=ConsultarTipoDeActividades' +
                '&funcion=postInhabilitar' +
                '&id=' + id;
        }
    });
}

function guardarEdicion() {
    Swal.fire({
        icon: 'success',
        title: 'Cambios guardados correctamente',
        timer: 1500,
        showConfirmButton: false
    });

    bootstrap.Modal.getInstance(
        document.getElementById('modalEditar')
    ).hide();
}
