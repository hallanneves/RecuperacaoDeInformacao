<?php
    require_once './utils.php';
    require_once './leitorDeArquivos.php';
    require_once './processador.php';

    $_SESSION['documentos'] = $documentos = leDocumentos();
    $stopwords = leStopWords();

    $_SESSION['indice_invertido'] = $indice_invertido = montaIndiceInvertido($documentos, $stopwords);
    $_SESSION['indice_invertido_com_tf_idf'] = $indice_invertido_com_tf_idf = calculaTFIDF($indice_invertido,$documentos);
    $_SESSION['documento_sem_stopword'] = $documento_sem_stopword = removeStopWords($documentos, $stopwords);
    $_SESSION['documento_vetorial'] = $documento_vetorial = calculaTFIDFDocumento($indice_invertido_com_tf_idf,$documento_sem_stopword);
    $_SESSION['sum_tfidf_2_documento'] = $sum_tfidf_2_documento = calculaSomaTFIDFD2($documento_vetorial);

    header("Location: index.php");
?>