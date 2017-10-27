<?php
    require_once "./utils.php";

    $_SESSION['epsilon'] = $_POST['epsilon'];
    $_SESSION['alpha'] = $_POST['alpha'];

    header("Location: reprocessar.php");
?>