<!DOCTYPE html>
<html>
<link rel="shortcut icon" href="<?= asset('icon.png') ?>" type="image/x-icon">
<head>
    <title>My App</title>
</head>
<body>
    <header>
        <h1>Header Section</h1>
    </header>

    <main>
        <?= $content ?> <!-- This is where the view goes -->
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My App</p>
    </footer>
</body>
</html>
