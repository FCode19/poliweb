<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="app.php?view=usuarios" class="modal-content">

            <div class="modal-header text-white" style="background-color: #123d12;">
                <h5 class="modal-title" id="modalEliminarLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar al usuario <strong id="usuarioNombre"></strong>?</p>
                <input type="hidden" name="eliminar" id="eliminarCod">
            </div>

            <div class="modal-footer">
                <button type="submit" class="text-white px-4 py-2 border-0" style="background-color: #597739;">
                    Eliminar
                </button>
                <button type="button" class="px-4 py-2"
                    style="background-color: transparent; color: #597739; border: 1px solid #597739;"
                    data-bs-dismiss="modal">
                    Cancelar
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const cod = button.getAttribute('data-cod');
        const nombre = button.getAttribute('data-nombre');

        const inputCod = modalEliminar.querySelector('#eliminarCod');
        const spanNombre = modalEliminar.querySelector('#usuarioNombre');

        inputCod.value = cod;
        spanNombre.textContent = nombre;
    });
</script>
