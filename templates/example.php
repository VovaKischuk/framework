<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></p>
</body>
</html>