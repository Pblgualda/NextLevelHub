<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
</head>
<body>
    <div class="container">
        <h1>Nuevo Contacto</h1>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="data[nombre]" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellido:</label>
                    <input type="text" id="apellidos" name="data[apellidos]" required>
                </div>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="data[correo]" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="data[telefono]">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <textarea id="direccion" name="data[direccion]"></textarea>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="data[fecha_nacimiento]">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit">Guardar Contacto</button>
                <button type="reset" class="btn-reset">Limpiar</button>
            </div>
        </form>
    </div>
</body>
</html>
