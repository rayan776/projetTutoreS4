<?php
    if (!defined('CONSTANTE'))
        die("Accès interdit");

?>

<!DOCTYPE HTML>
<HTML>

    <HEAD>
        <META CHARSET="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="stylesheets/mainstyle.css">
        <title> Nutri Bilan </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </HEAD>

    <BODY>
        <header> <?=$header?> </header>
        <nav> <?=$liensMenuPrincipal?> </nav>
        <main>
            <?php require_once $templateSecondaire; ?>
        </main>
        <footer> <?=$footer?> </footer>
    </BODY>

</HTML>