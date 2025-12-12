function verDetalle(id) {
    // Aquí luego puedes traer datos por AJAX si quieres
    document.getElementById('detalleId').innerText = id;
    document.getElementById('detalleNombre').innerText = 'Actividad ' + id;
    document.getElementById('detalleEstado').innerHTML =
        '<span class="badge bg-success">Activo</span>';

    const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
    modal.show();
}

function editar(id) {
    document.getElementById('editId').value = id;
    document.getElementById('editNombre').value = 'Actividad ' + id;

    const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
}

function eliminar(id) {
    if (confirm('¿Está seguro de eliminar la actividad #' + id + '?')) {
        alert('Aquí puedes hacer el DELETE por AJAX');
    }
}

function guardarNuevo() {
    alert('Aquí puedes guardar la nueva actividad');
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevo'));
    modal.hide();
}

function guardarEdicion() {
    alert('Cambios guardados correctamente');
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditar'));
    modal.hide();
}
