    </main>
</div>
</div>
<!-- Scripts básicos -->
<script>
// Confirmación sencilla para botones de eliminar
document.querySelectorAll('.form-eliminar').forEach(formulario => {
    formulario.addEventListener('submit', function(evento) {
        if (!confirm('¿Está seguro de que desea realizar esta acción?')) {
            evento.preventDefault();
        }
    });
});
</script>
</body>
</html>
