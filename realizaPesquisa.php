<?php
    require_once "pesquisa.php";

    $pesquisa = processaPesquisa($_POST['pesquisa']);
    $_SESSION['vetor_consulta'] = $vetor_consulta = criaVetorPesquisa($pesquisa, $alpha);

    $_SESSION['indice_relevancia'] = indice_relevancia($vetor_consulta);
    header("Location:index.php?pesquisa=".$_POST['pesquisa']);
?>