<?php
    require_once "utils.php";
    require_once "leitorDeArquivos.php";

    function montaIndiceInvertido($documentos, $stopwords){
        $indice_invertido = array();
        
        // Percorre os documentos lidos
        foreach($documentos as $nome_documento => $palavras_documento){
            // Percorre as palavras de cada documento
            foreach($palavras_documento as $palavra){
                // Verifica se a palavra é uma stopword
                if(!in_array($palavra,$stopwords)){
                    // Verifica se a palavra não é repetida no documento
                    if(isset($indice_invertido[$palavra][$nome_documento]['frequencia'])){
                        // Se a palavra ja é conhecida aumenta a frequência daquela palavra no documento
                        $indice_invertido[$palavra][$nome_documento]['frequencia'] += 1;                
                    } else {
                        // Se a palavra é nova, adiciona o documento ao índice da palavra
                        $indice_invertido[$palavra][$nome_documento]['frequencia'] = 1;                    
                    }
                }
            }
        }
        
        return $indice_invertido;
    }

    function calculaTFIDF($indice_invertido, $documentos){
        foreach($indice_invertido as $key => $val){
            $indice_invertido[$key]['df'] = count($val);
            $indice_invertido[$key]['idf'] = log10(count($documentos)/$indice_invertido[$key]['df']);
        }

        return $indice_invertido;
    }

    function removeStopWords($documentos, $stopwords){
        $documento_sem_stopword = array();
        foreach($documentos as $doc => $palavras){
            foreach($palavras as $palavra){
                if(!in_array($palavra,$stopwords)){
                    if(!isset($documento_sem_stopword[$doc])){
                        $documento_sem_stopword[$doc] = array();
                    }
                    $documento_sem_stopword[$doc][] = $palavra;
                }
            }
        }
        return $documento_sem_stopword;
    }

    function calculaTFIDFDocumento($indice_invertido_com_tf_idf,$documento_sem_stopword){
        $documentos_vetorial = array();

        foreach($documento_sem_stopword as $doc => $palavras){
            $maior = 0;
            foreach($palavras as $palavra){
                if(isset($indice_invertido_com_tf_idf[$palavra][$doc])){
                    if($indice_invertido_com_tf_idf[$palavra][$doc]['frequencia'] > $maior){
                        $maior = $indice_invertido_com_tf_idf[$palavra][$doc]['frequencia'];
                    }
                }
            }

            foreach($indice_invertido_com_tf_idf as $palavra => $docs){
                if(isset($indice_invertido_com_tf_idf[$palavra][$doc])){
                    $documentos_vetorial[$doc][$palavra] = ($indice_invertido_com_tf_idf[$palavra][$doc]['frequencia'] / $maior) * $indice_invertido_com_tf_idf[$palavra]['idf'];
                } else{
                    $documentos_vetorial[$doc][$palavra] = 0;
                }
            }
        }

        return $documentos_vetorial;
    }

    function calculaSomaTFIDFD2($documento_vetorial){
        $documentos_TFIDF2 = array();

        foreach($documento_vetorial as $doc => $palavras){
            $soma = 0; 
            foreach($palavras as $palavra => $tfidf){
                $soma += $tfidf * $tfidf;
            }

            $documentos_TFIDF2[$doc] = sqrt($soma); 
        }
        
        debug($documentos_TFIDF2);
        return $documentos_TFIDF2;
    }

?>