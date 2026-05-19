<?php
include_once('includes/conexion.php');
include_once('includes/header.php');

// Filtro por categoría (conversión a int para evitar líos)
if (isset($_GET['cat'])) {
    $cat_id = (int) $_GET['cat'];
} else {
    $cat_id = 0;
}

// Sacar categorías raíz
$cats = $pdo->query("
    SELECT *
    FROM categorias
    WHERE padre_id IS NULL
    ORDER BY nombre
")->fetchAll();

// Sacar productos:
// - Si hay categoría seleccionada: productos de esa categoría
//   o de sus subcategorías (c.padre_id = ?)
// - Si no hay categoría: todos los activos
if ($cat_id > 0) {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON c.id = p.categoria_id
        WHERE p.activo = 1
          AND (p.categoria_id = ? OR c.padre_id = ?)
        ORDER BY p.nombre
    ");
    $stmt->execute([$cat_id, $cat_id]);
} else {
    $stmt = $pdo->query("
        SELECT p.*, c.nombre AS categoria
        FROM productos p
        LEFT JOIN categorias c ON c.id = p.categoria_id
        WHERE p.activo = 1
        ORDER BY p.nombre
    ");
}
$productos = $stmt->fetchAll();
?>

<!-- FILTROS -->
<section class="filtros-bar">
    <a href="productos.php" class="filtro-pill <?= $cat_id === 0 ? 'activo' : '' ?>">
        Todos
    </a>

    <?php foreach ($cats as $cat){ ?>
        <a href="productos.php?cat=<?= (int)$cat['id'] ?>"
           class="filtro-pill <?= $cat_id === (int)$cat['id'] ? 'activo' : '' ?>">
            <?= htmlspecialchars($cat['nombre']) ?>
        </a>
    <?php }; ?>
</section>

<!-- CABECERA -->
<section class="productos-seccion">
    <div class="productos-cabecera">
        <h1 class="seccion-titulo">PRODUCTOS</h1>
        <span class="productos-count"><?= count($productos) ?> artículos</span>
    </div>

    <?php if (empty($productos)){ ?>
        <div class="productos-vacio">
            <p>No hay productos en esta categoría.</p>
        </div>

    <?php }else{ ?>
        <div class="productos-grid">

            <?php foreach ($productos as $prod){ ?>

                <?php
                // Imagen del producto (simple)
                $imgStmt = $pdo->prepare("
                    SELECT url
                    FROM imagenes_producto
                    WHERE producto_id = ?
                    LIMIT 1
                ");
                $imgStmt->execute([$prod['id']]);
                $img = $imgStmt->fetchColumn();
                ?>

                <div class="producto-card">
                    <div class="producto-img">
                        <?php if ($img){ ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['nombre']) ?>">
                        <?php }else{ ?>
                            <span class="producto-sin-img">📦</span>
                        <?php }; ?>

                        <?php if ($prod['stock'] == 0){ ?>
                            <span class="badge badge-agotado">Sin stock</span>
                        <?php }elseif ($prod['stock'] < 10){ ?>
                            <span class="badge badge-pocas">Últimas unidades</span>
                        <?php }; ?>
                    </div>

                    <div class="producto-info">
                        <span class="producto-cat">
                            <?= htmlspecialchars($prod['categoria'] ?? '—') ?>
                        </span>

                        <h3 class="producto-nombre">
                            <?= htmlspecialchars($prod['nombre']) ?>
                        </h3>

                        <p class="producto-desc">
                            <?= htmlspecialchars($prod['descripcion']) ?>
                        </p>

                        <div class="producto-footer">
                            <span class="producto-precio">
                                <?= number_format($prod['precio'], 2, ',', '.') ?> €
                            </span>

                            <?php if ($prod['stock'] > 0){ ?>
                                <button class="btn btn-card">Añadir 🛒</button>
                            <?php }else{ ?>
                                <button class="btn btn-card btn-disabled" disabled>Agotado</button>
                            <?php }; ?>
                        </div>
                    </div>
                </div>

            <?php }; ?>

        </div>
    <?php }; ?>
</section>

<?php include_once('includes/footer.php'); ?>
