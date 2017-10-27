<?php
    require_once './utils.php';

    function acabou($probabilidade_anterior, $probabilidade_atual){
        $epsilon = 0.01;

        if (isset($_SESSION['epsilon'])){
            $epsilon = $_SESSION['epsilon'];
        }

        foreach ($probabilidade_atual as $documento => $probabilidade){
            if (abs($probabilidade_anterior[$documento] - $probabilidade) > $epsilon){
                return false;
            }
        }

        return true;
    }

    function calculaPageRank($grafo){
        $alpha = 0.1;

        if (isset($_SESSION['alpha'])){
            $alpha = $_SESSION['alpha'];
        }

        // a probabilidade inicial eh 100 dividido pelo numero de documentos
        $probabilidade_inicial = 1.0 / sizeof($grafo['grafo']['vindo']);
        
        // cada um começa com a mesmas probabilidade (inicial)
        foreach ($grafo['grafo']['vindo'] as $k => $links){
            $grafo['page_rank'][$k] = $probabilidade_inicial;
        }

        do {
            // o valor do page rank anterior eh atualizado
            $grafo['page_rank_anterior'] = $grafo['page_rank'];
            
            // para cada documento
            foreach ($grafo['grafo']['vindo'] as $documento => $links){
                // o novo page rank eh a probabilidade pulo alpha dividido pelo numero de documentos
                $nova_probabilidade = $alpha / sizeof($grafo['grafo']['vindo']);
                
                $soma = 0.0;
                // para cada link que chega nesse documento
                foreach ($links as $k => $value) {
                    // soma o valor do page_rank dividido por o numero de links que sai do documento
                    $soma += ($grafo['page_rank'][$value] / sizeof($grafo['grafo']['saindo'][$value]));
                }
                
                $nova_probabilidade += (1.0 - $alpha) * $soma;
                $grafo['page_rank'][$documento] = $nova_probabilidade;
            }
            
        } while (!acabou($grafo['page_rank_anterior'], $grafo['page_rank']));

        return $grafo;
    }

?>