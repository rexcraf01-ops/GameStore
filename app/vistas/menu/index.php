<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($titulo ?? 'Catálogo de Videojuegos | Level Up Games') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/levelup.css">
    <style>
        .public-nav {
            background-color: var(--color-dark);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .public-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 20px 60px;
        }
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        .category-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .pill-btn {
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            background: #FFFFFF;
            border: 1px solid var(--color-border);
            color: var(--color-text);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        .pill-btn:hover, .pill-btn.active {
            background-color: var(--color-purple);
            color: #FFFFFF;
            border-color: var(--color-purple);
        }
    </style>
</head>
<body>

<!-- Barra de navegación pública -->
<header class="public-nav">
    <div style="font-size: 20px; font-weight: 800; color: #FFFFFF; display: flex; align-items: center; gap: 8px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#CDF56E" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><path d="M6 12h4"></path><path d="M8 10v4"></path><line x1="15" y1="11" x2="15.01" y2="11"></line><line x1="18" y1="13" x2="18.01" y2="13"></line></svg>
        LEVEL UP <span style="color: var(--color-lime);">GAMES</span>
    </div>
    <div>
        <a href="index.php?accion=login" class="btn btn-secondary btn-sm" style="background: transparent; color: #FFFFFF; border-color: rgba(255,255,255,0.2);">
            Acceso Personal &rarr;
        </a>
    </div>
</header>

<div class="public-container">
    <!-- Banner Principal / Hero (Figma Pantalla #10) -->
    <div class="hero-banner">
        <div class="hero-content">
            <div class="hero-tag">Sube de nivel tu tienda</div>
            <h1 class="hero-title">Encuentra los mejores videojuegos y consolas.</h1>
            <p class="hero-desc">Consulta disponibilidad en tiempo real, precios actualizados y acércate a nuestro local para comprar.</p>
            <a href="#catalogo" class="btn btn-lime">Explorar Catálogo</a>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="filter-bar" id="catalogo">
        <div class="category-pills">
            <a href="index.php?accion=menu" class="pill-btn <?= empty($idCategoria) ? 'active' : '' ?>">
                Todas las categorías
            </a>
            <?php foreach ($categorias as $cat): ?>
                <a href="index.php?accion=menu&categoria=<?= $cat['id_categoria'] ?>" 
                   class="pill-btn <?= ($idCategoria == $cat['id_categoria']) ? 'active' : '' ?>">
                    <?= escapar($cat['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <form action="index.php" method="GET" style="display: flex; gap: 8px;">
            <input type="hidden" name="accion" value="menu">
            <?php if (!empty($idCategoria)): ?>
                <input type="hidden" name="categoria" value="<?= $idCategoria ?>">
            <?php endif; ?>
            <input type="text" name="buscar" value="<?= escapar($busqueda ?? '') ?>" class="form-control input-pill" placeholder="Buscar juego o consola...">
            <button type="submit" class="btn btn-secondary btn-sm">Buscar</button>
        </form>
    </div>

    <!-- Grilla de Productos -->
    <?php if (empty($productos)): ?>
        <div class="card" style="text-align: center; padding: 48px;">
            <h3>No se encontraron productos disponibles.</h3>
            <p style="color: var(--color-text-muted); margin-top: 8px;">Intenta cambiar el término de búsqueda o categoría.</p>
            <a href="index.php?accion=menu" class="btn btn-primary btn-sm" style="margin-top: 16px;">Ver todos los productos</a>
        </div>
    <?php else: ?>
        <div class="catalog-grid">
            <?php foreach ($productos as $prod): ?>
                <div class="product-card">
                    <div class="product-img-placeholder">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="6" width="20" height="12" rx="2"></rect><path d="M6 12h4"></path><path d="M8 10v4"></path><line x1="15" y1="11" x2="15.01" y2="11"></line><line x1="18" y1="13" x2="18.01" y2="13"></line></svg>
                    </div>

                    <div class="product-category"><?= escapar($prod['categoria_nombre']) ?></div>
                    <div class="product-title"><?= escapar($prod['nombre']) ?></div>
                    <?php if (!empty($prod['plataforma'])): ?>
                        <div class="product-platform"><?= escapar($prod['plataforma']) ?></div>
                    <?php endif; ?>

                    <div class="product-footer">
                        <div class="product-price"><?= formatearDinero($prod['precio']) ?></div>
                        <div>
                            <?php if ($prod['stock'] == 0): ?>
                                <span class="badge badge-danger">Agotado</span>
                            <?php elseif ($prod['stock'] <= $prod['stock_minimo']): ?>
                                <span class="badge badge-warning">Pocas unidades</span>
                            <?php else: ?>
                                <span class="badge badge-success">Disponible</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>