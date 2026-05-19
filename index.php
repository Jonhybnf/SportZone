<?php
include_once('includes/header.php');
include_once('includes/conexion.php');
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-contenido">
        <span class="hero-tag">Nueva temporada 2026</span>
        <h1>EQUÍPATE<br>PARA <span class="hero-accent">GANAR</span></h1>
        <p>Todo el equipamiento deportivo que necesitas. Marcas líderes, precios directos.</p>
        <a href="productos.php" class="btn btn-hero">Ver productos</a>
    </div>
</section>

<!-- CATEGORÍAS -->
<section class="categorias-home">
    <h2 class="seccion-titulo">CATEGORÍAS</h2>
    <div class="categorias-grid">
        <a href="productos.php?cat=futbol" class="cat-card">
            <span class="cat-emoji">⚽</span>
            <span class="cat-nombre">Fútbol</span>
        </a>
        <a href="productos.php?cat=running" class="cat-card">
            <span class="cat-emoji">👟</span>
            <span class="cat-nombre">Running</span>
        </a>
        <a href="productos.php?cat=natacion" class="cat-card">
            <span class="cat-emoji">🏊</span>
            <span class="cat-nombre">Natación</span>
        </a>
        <a href="productos.php?cat=fitness" class="cat-card">
            <span class="cat-emoji">💪</span>
            <span class="cat-nombre">Fitness</span>
        </a>
        <a href="productos.php?cat=ciclismo" class="cat-card">
            <span class="cat-emoji">🚴</span>
            <span class="cat-nombre">Ciclismo</span>
        </a>
    </div>
</section>

<!-- PRODUCTOS DESTACADOS -->
<section class="destacados-home">
    <h2 class="seccion-titulo">PRODUCTOS DESTACADOS</h2>
    <div class="productos-grid">

        <?php
        $stmt = $pdo->query("SELECT p.*, c.nombre AS categoria
                                 FROM productos p
                                 LEFT JOIN categorias c ON c.id = p.categoria_id
                                 WHERE p.activo = 1
                                 LIMIT 4");
        $productos = $stmt->fetchAll();

        foreach ($productos as $prod): ?>

            <div class="producto-card">
                <div class="producto-img">
                    <?php
                    $imgStmt = $pdo->prepare("SELECT url FROM imagenes_producto WHERE producto_id = ? LIMIT 1");
                    $imgStmt->execute([$prod['id']]);
                    $img = $imgStmt->fetchColumn();
                    if ($img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                    <?php else: ?>
                        <span class="producto-sin-img">📦</span>
                    <?php endif; ?>

                    <?php if ($prod['stock'] == 0): ?>
                        <span class="badge badge-agotado">Sin stock</span>
                    <?php endif; ?>
                </div>
                <div class="producto-info">
                    <span class="producto-cat"><?= htmlspecialchars($prod['categoria'] ?? '—') ?></span>
                    <h3 class="producto-nombre"><?= htmlspecialchars($prod['nombre']) ?></h3>
                    <p class="producto-desc"><?= htmlspecialchars($prod['descripcion']) ?></p>
                    <div class="producto-footer">
                        <span class="producto-precio"><?= number_format($prod['precio'], 2, ',', '.') ?> €</span>
                        <a href="productos.php" class="btn btn-card">Ver más</a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
</section>

<?php include_once('includes/footer.php'); ?>