<?php
    require_once './utils.php';

    $alpha = 0.5;

    function processaPesquisa($texto){
        $texto_limpo = tiraAcentos(removePontuacao(trim(strtolower($texto))));
        $palavras = explode(" ", $texto_limpo);
        $consulta = array();
        
        foreach ($palavras as $palavra) {
            if (isset($consulta[$palavra])){
                $consulta[$palavra] += 1;
            } else {
                $consulta[$palavra] = 1;
            }
        }
        return $consulta;
    }

    function criaVetorPesquisa($pesquisa, $alpha){
        $vetor = array();
        $max = 0;
        foreach ($_SESSION['indice_invertido_com_tf_idf'] as $palavra => $dados){
            if (isset($pesquisa[$palavra]) && $pesquisa[$palavra] > $max){
                $max = $pesquisa[$palavra];
            }
        }
        foreach ($_SESSION['indice_invertido_com_tf_idf'] as $palavra => $dados){
            if (!isset($pesquisa[$palavra])){
                $vetor[$palavra] = 0;
            } else {
                $vetor[$palavra] = ($alpha + (1-$alpha) * $pesquisa[$palavra]/$max) * $dados['idf'];
            }
        }
        return $vetor;
    }

    function indice_relevancia($vetor_consulta){
        $vetor_consulta_quadrado = array();
        $raiz_soma_quadrado = 0;

        foreach ($vetor_consulta as $palavra => $valor){
            $vetor_consulta_quadrado[$palavra] = $valor * $valor;
            $raiz_soma_quadrado += ($valor * $valor);
        }
        
        $raiz_soma_quadrado = sqrt($raiz_soma_quadrado);
        $consulta_vezes_documento = array();
        $consulta_doc_soma = array();
        
        foreach ($_SESSION['documento_vetorial'] as $nome_doc => $documento){
            $consulta_vezes_documento[$nome_doc] = array();
            $soma = 0;
        
            foreach ($documento as $termo => $valor) {
                $consulta_vezes_documento[$nome_doc][$termo] = $valor * $vetor_consulta[$termo];
                $soma += $consulta_vezes_documento[$nome_doc][$termo];
            }
        
            $consulta_doc_soma[$nome_doc] = $soma;
        }
            
        $resultado = array();
        foreach ($consulta_doc_soma as $nome_doc => $soma){
            if($_SESSION['sum_tfidf_2_documento'][$nome_doc] * $raiz_soma_quadrado > 0){
                $resultado[$nome_doc] = $soma / ($_SESSION['sum_tfidf_2_documento'][$nome_doc] * $raiz_soma_quadrado);
            }else{
                $resultado[$nome_doc] = 0;
            }
        }
        
        arsort($resultado);
        return $resultado;
    }


?>