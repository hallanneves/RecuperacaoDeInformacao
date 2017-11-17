<?php
    require_once 'utils.php';
    require_once 'leitorDeArquivos.php';

    function processaDocumentos ($documentos){
        $documentosProcessados = array();
        foreach($documentos as $nome => $palavras){
            $nomeSeparado = explode("_", $nome);
            $classe = explode(".", $nomeSeparado[3])[0];
            $documentosProcessados[$classe][$nomeSeparado[0]."_".$nomeSeparado[1]] = $palavras;
        }
        return $documentosProcessados;
    }

    function extraiVocabulario($documentosProcessados){
        $vocabulario = array();
        foreach($documentosProcessados as $documentos){
            foreach($documentos as $palavras){
                foreach($palavras as $palavra){
                    if(!in_array($palavra, $vocabulario)){
                        $vocabulario[] = $palavra;
                    }
                }
            }
        }
        return $vocabulario;
    }

    function contaDocumentos($documentosProcessados){
        $numeroDocumentos = 0;
        foreach($documentosProcessados as $documentos){
            foreach($documentos as $palavras){
                $numeroDocumentos ++;
            }
        }
        return $numeroDocumentos;
    }

    function concatenaTextoClasse($documentosProcessados, $classe){
        $palavras = array();
        foreach($documentosProcessados[$classe] as $documento){
            foreach($documento as $palavra){
                $palavras[] = $palavra;
            }
        }
        return $palavras;
    }

    function contaOcorrenciaDeUmTermo($textc, $t){
        $ocorrencias = 0;
        foreach($textc as $tc){
            if($tc == $t){
                $ocorrencias ++; 
            }
        }
        return $ocorrencias;
    }

    function extraiPalavrasDoDocumento($documento){
        return explode(" ", $documento);
    }

    function treinamento($documentosProcessados){
        $treinamento = array();
        $v = extraiVocabulario($documentosProcessados);
        $n = contaDocumentos($documentosProcessados);
        $prior = array();
        $contProb = array();
        foreach($documentosProcessados as $classe => $documentos){
            $nc = count($documentos);
            $prior[$classe] = $nc / $n;
            $textc = concatenaTextoClasse($documentosProcessados, $classe);
            foreach($v as $t){
                $tct[$classe][$t] = contaOcorrenciaDeUmTermo($textc, $t);
            }
            foreach($v as $t){
                $contProb [$t][$classe] = ($tct[$classe][$t] + 1) / (count($textc) + count($v));
            }
        }
        $treinamento['v'] = $v;
        $treinamento['prior'] = $prior;
        $treinamento['contProb'] = $contProb;
        return $treinamento;
    }

    function aplicarMultinomialNB($documentosProcessados, $treinamento, $documento){
        $w = extraiPalavrasDoDocumento($documento);
        $score = array();
        $resultado = array();
        foreach($documentosProcessados as $classe => $documentos){
            $score[$classe] = log($treinamento['prior'][$classe]);
            foreach($w as $t){
                $score[$classe] += log($treinamento['contProb'][$t][$classe]);
            }
        }
        $resultado['classe'] = array_keys($score, max($score));
        $resultado['score'] = $score;
        return $resultado;
    }
    if(!isset($_SESSION['treinamento'])){
        $documentos = leDocumentos("documentosNaiveBayes");
        
        $_SESSION['documentosNaiveBayesProcessados'] = processaDocumentos($documentos);
    
        $_SESSION['treinamento'] = treinamento($_SESSION['documentosNaiveBayesProcessados']);    
    }
    //debug($_POST['documento']);
    $_SESSION['multinomialNB'] = aplicarMultinomialNB($_SESSION['documentosNaiveBayesProcessados'], $_SESSION['treinamento'], $_POST['documento']);
    //die();
    header("Location: naiveBayes.php");
?>