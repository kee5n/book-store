
<?php
require('./database.php');


?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Lisa autor – Book Store</title>
</head>
<body>

<header class="site-header">
    <a href="index.php" class="logo">Book Store</a>

    <nav>
        <a href="index.php">Raamatud</a>
        <a href="add-author.php">Lisa autor</a>
    </nav>
</header>

<main class="edit-page">
    <a href="index.php" class="back-link">← Tagasi avalehele</a>

    <?php if (($_GET['error'] ?? '') === 'required'): ?>
        <div class="alert-error">
            Palun täida nii eesnime kui ka perekonnanime väli.
        </div>
    <?php endif; ?>

    <section class="edit-card">
        <p class="eyebrow">Raamatupood</p>
        <h1>Lisa uus autor</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">
                Autor lisati edukalt!
            </div>
        <?php endif; ?>

        <p class="edit-description">
            Täida vorm autori andmetega.
        </p>

        <form action="save-author.php" method="POST" class="edit-form">
            <div class="form-group">
                <label for="first_name">Eesnimi</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="last_name">Perekonnanimi</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Salvesta autor
            </button>
        </form>
    </section>
</main>

<footer class="site-footer">
    Book Store
</footer>

</body>
</html>
