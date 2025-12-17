function verDetalle(id) {
    document.getElementById('detalleId').innerText = id;
    document.getElementById('detalleNombre').innerText = 'Actividad ' + id;
    document.getElementById('detalleEstado').innerHTML =
        '<span class="badge bg-success">Activo</span>';

    const modal = new bootstrap.Modal(
        document.getElementById('modalDetalle')
    );
    modal.show();
}

function editarActividad(id, nombre, estado) {

    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = nombre;

    // MOSTRAR ESTADO EN EL SELECT
    document.getElementById('editEstado').value = estado;

    // ENVIA EL ESTADO ACTUAL 
    document.getElementById('editEstadoHidden').value = estado;

    const modalElement = document.getElementById('modalEditar');
    modalEditar = bootstrap.Modal.getOrCreateInstance(modalElement);
    modalEditar.show();
}

// LIMPIAR CAMPOS AL CERRAR MODAL
document.getElementById('modalEditar')
    .addEventListener('hidden.bs.modal', function () {
        document.getElementById('editId').value = '';
        document.getElementById('editNombre').value = '';
        document.getElementById('editEstado').value = '';
        document.getElementById('editEstadoHidden').value = '';
    });

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
    alert('Cambios guardados correctamente');
    const modal = bootstrap.Modal.getInstance(
        document.getElementById('modalEditar')
    );
    modal.hide();
}
