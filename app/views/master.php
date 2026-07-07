<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
</head>
<body>
<div id="header"> <?php require 'partials/header.php'; ?></div>
<div class="container"> <?php require VIEWS.$view; ?></div>
</body>
</html>
