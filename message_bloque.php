<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="styles/bloque.css">
    <link rel="icon" href="logo.png">
    <title>Oups !</title>
</head>
<body>
    <nav class="bandeau">
        <img src="logo.png" class="img">
        <div class="bandeautitle">BLOB</div>
        <div class="titrebandeau">Oups ! L'utilisateur vous a bloqué.</div>
        <input type="button" class="bouton" value="Retour" onclick="linkopener('abonne.php')" />
    </nav>
    <h1>Dommage pour vous</h1>
    <script>
    function linkopener(a) {
        window.open(a, '_self');
    }
    </script>
</body>
</html>