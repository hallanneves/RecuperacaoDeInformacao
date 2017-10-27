<?php

require_once "utils.php";

function leDocumentos($dir = 'documentos'){
    //lista os documentos do diretorio
    $files = scandir($dir);
    //cria array de documentos
    $documentos = array();
    //para cada documento
    foreach ($files as $ndoc => $arquivo) {
        //exclui o . e o ..
        if ($ndoc > 1){
            //carrega o conteudo do arquivo
            $html = file_get_contents($dir."/".$arquivo);
            //remove as tags
            $texto = strip_tags($html);
            //pre-processa e organiza em um vetor
            $texto_sem_acentos = tiraAcentos($texto);
            $texto_final = strtolower(removePontuacao($texto_sem_acentos));
            $documentos[$arquivo] = explode(" ",trim($texto_final));
        }
    }
    return $documentos;
}

function leStopWords($arquivo = "stopwords.txt"){
    //carrega o conteudo do arquivo
    $texto = file_get_contents($arquivo);
    //pre-processa e organiza em um vetor
    $texto_sem_acentos = removePontuacao(tiraAcentos($texto));
    $texto_final = strtolower(removePontuacao($texto_sem_acentos));
    //retorna as stop words
    $resultado = explode("\n",$texto_final);
    //remove espacos
    $resultado_final = array();
    foreach($resultado as $key => $val){
        $resultado_final[$key] = trim($val); 
    }
    return $resultado_final;
}

function montaGrafo($dir = 'documentos'){
    $files = scandir($dir);
    $grafo = array();

    foreach ($files as $ndoc => $arquivo) {
        if ($ndoc > 1){
            $conteudo = file_get_contents($dir."/".$arquivo);
            $dom = new DOMDocument;

            @$dom->loadHTML($conteudo);

            $links = $dom->getElementsByTagName('a');
            
            foreach ($links as $link){
                $destino = $link->getAttribute('href');

                if (!isset($grafo['grafo'][$destino])){
                    $grafo['grafo'][$destino] = array();
                }
                array_push($grafo['grafo'][$destino], $arquivo);
            }
        }
    }

    return $grafo;
}