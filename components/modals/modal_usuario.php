<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="app.php?view=usuarios">
            <input type="hidden" name="pagina" value="<?= $_GET['page'] ?? 1 ?>">

            <div class="modal-header text-white" style="background-color: #123d12;">
                <h5 class="modal-title" id="modalUsuarioLabel">Registrar nuevo usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="cod" id="formCod">
                <div class="mb-3">
                    <label for="formNombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="formNombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="formApellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="formApellido" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="formUsuario" class="form-label">Usuario</label>
                    <input type="text" name="usuario" id="formUsuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="formContra" class="form-label">Contraseña</label>
                    <input type="password" name="contra" id="formContra" class="form-control" required>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" id="modalSubmitButton" name="registrar" class="text-white px-4 py-2 border-0"
                    style="background-color: #597739;">
                    Registrar
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
    const modalUsuario = document.getElementById('modalUsuario');
    modalUsuario.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const modo = button.getAttribute('data-modo');

        const form = modalUsuario.querySelector('form');

        // Asignar valores o limpiar
        document.getElementById('formCod').value = button.getAttribute('data-cod') || '';
        document.getElementById('formNombre').value = button.getAttribute('data-nombre') || '';
        document.getElementById('formApellido').value = button.getAttribute('data-apellido') || '';
        document.getElementById('formUsuario').value = button.getAttribute('data-usuario') || '';
        document.getElementById('formContra').value = button.getAttribute('data-contra') || '';

        const submitBtn = modalUsuario.querySelector('#modalSubmitButton');

        if (modo === 'editar') {
            submitBtn.textContent = 'Guardar cambios';
            submitBtn.name = 'guardarEdicion';
            modalUsuario.querySelector('.modal-title').textContent = 'Editar usuario';
            form.action = 'app.php?view=usuarios&action=editar';
        } else {
            submitBtn.textContent = 'Registrar';
            submitBtn.name = 'registrar';
            modalUsuario.querySelector('.modal-title').textContent = 'Registrar nuevo usuario';
            form.action = 'app.php?view=usuarios&action=crear';
            form.reset(); // importante para limpiar si ya se abrió antes
        }
    });
</script>
