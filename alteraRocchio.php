<?php
    require_once "./utils.php";

    $_SESSION['roc_alpha'] = $_POST['roc_alpha'];
    $_SESSION['roc_beta'] = $_POST['roc_beta'];
    $_SESSION['roc_gama'] = $_POST['roc_gama'];

    header("Location: index.php");
?>