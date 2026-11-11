<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($titulo ?? 'Iniciar Sesión | Level Up Games') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/levelup.css">
    <style>
        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .login-banner {
            flex: 1;
            background-color: var(--color-dark);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
        }
        .login-form-side {
            width: 480px;
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 48px;
        }
        @media (max-width: 850px) {
            .login-wrapper { flex-direction: column; }
            .login-banner { padding: 40px 24px; }
            .login-form-side { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Columna Izquierda: Banner Promocional de Marca -->
    <div class="login-banner">
        <div style="font-size: 24px; font-weight: 800; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#CDF56E" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><path d="M6 12h4"></path><path d="M8 10v4"></path><line x1="15" y1="11" x2="15.01" y2="11"></line><line x1="18" y1="13" x2="18.01" y2="13"></line></svg>
            LEVEL UP <span style="color: var(--color-lime);">GAMES</span>
        </div>
        <h1 style="font-size: 38px; font-weight: 800; line-height: 1.2; margin-bottom: 20px;">
            El siguiente nivel de tu tienda de videojuegos.
        </h1>
        <p style="color: #A3A2B3; font-size: 16px; max-width: 460px; line-height: 1.6;">
            Gestiona pedidos al instante, controla el stock en tiempo real y brinda a los clientes la mejor experiencia gamer.
        </p>
    </div>

    <!-- Columna Derecha: Formulario de Ingreso -->
    <div class="login-form-side">
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">Iniciar Sesión</h2>
            <p style="color: var(--color-text-muted); font-size: 14px;">Ingresa tus datos para acceder a tu panel de trabajo</p>
        </div>

        <?php 
        $mensaje = obtenerMensaje();
        if ($mensaje): 
        ?>
            <div class="alert alert-<?= ($mensaje['tipo'] === 'peligro') ? 'danger' : 'success' ?>">
                <span><?= escapar($mensaje['texto']) ?></span>
            </div>
        <?php endif; ?>

        <form action="index.php?accion=login" method="POST">
            <div class="form-group">
                <label class="form-label" for="correo">Correo Electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" placeholder="ejemplo@levelup.com" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="••••••••" required>
            </div>

            <div style="margin-bottom: 24px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                    Ingresar al Sistema
                </button>
            </div>
        </form>

        <div style="text-align: center; font-size: 13px;">
            <span style="color: var(--color-text-muted);">¿Solo deseas explorar los juegos disponibles?</span><br>
            <a href="index.php?accion=menu" style="font-weight: 600;">Ver Catálogo Público Digital &rarr;</a>
        </div>
    </div>
</div>

</body>
</html>
