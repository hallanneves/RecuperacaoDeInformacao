<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="css/bootstrap-theme.min.css.map">

        <!-- Latest compiled and minified JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Jquary --> 
        <script src = "js/jquary.min.js"></script>

        <script type="text/javascript" src="js/loader.js"></script>
        
        <!-- Optional theme -->
        <link rel="stylesheet" href="css/home.css">
    <head>
    <body>
        <div class="navbar-wrapper">
            <div class="container">
                <nav class="navbar navbar-inverse navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="index.php">Zehallan Search</a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="index.php">Pesquisa</a></li>
                                <li><a href="indiceInvertido.php">Índice Invertido</a></li>
                                <li><a href="vetorDeDocumentos.php">Modelo Vetorial</a></li>
                                <li><a href="naiveBayes.php"> Naïve Bayes</a></li>
                                <li><a href="config.php">Configurações</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="reprocessar.php" onclick="alert('Regerando indices!');">Reprocessar</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="container">
            <br>
            <br>
            <br>
            <br>
            <?php
                require_once "utils.php";
                require_once "pesquisa.php";

                if (!isset($_POST['documentos_retornados'])){
                    $_POST['documentos_retornados'] = array();
                }

                if (!isset($_POST['documentos_nao_retornados'])){
                    $_POST['documentos_nao_retornados'] = array();
                }
                
                // Funcao que calcula a precisao da resposta de uma consulta
                function calculaPrecision($todos_documentos, $relevantes){
                    $qtd_retornados = 0;
                    $qtd_relevantes = 0;

                    // Conta todos os documentos que possuem alguma similaridade, ou seja, que foram retornados
                    foreach ($todos_documentos as $documento => $similaridade){
                        if ($similaridade > 0 || in_array($documento, $relevantes)){
                            $qtd_retornados++;
                        }

                        if (in_array($documento, $relevantes)){
                            $qtd_relevantes++;
                        }
                    }

                    // Se nao retornou nada, significa que teve 100% de precisao
                    if ($qtd_retornados == 0) {
                        return 1;
                    }

                    // A precisao eh a quantidade de documentos retornados marcados como relevantes dividido pelo numero de documentos retornados
                    return $qtd_relevantes / $qtd_retornados;
                }

                // Funcao que calcula o recall do resultado de uma consulta
                function calculaRecall($documentos_retornados, $documentos_nao_retornados){
                    $qtd_retornados = count($documentos_retornados);
                    $qtd_nao_retornados =  count($documentos_nao_retornados);

                    // Se o numero de documentos relevantes eh zero, significa que tem 100% de recall
                    if ($qtd_retornados + $qtd_nao_retornados == 0){
                        return 1;
                    }

                    return $qtd_retornados / ($qtd_retornados + $qtd_nao_retornados);
                }

                // Funcao que calcula a medida F
                function calculaFMeasure($precision, $recall){
                    if ($precision + $recall == 0){
                        return 0;
                    }
                    
                    return 2 * (($precision * $recall) / ($precision + $recall));
                }

                // Funcao que calcula o AVG Precision
                function calculaAVGPrecision($ranking_documentos, $retornados_relevantes){
                    $avg_precision = 0;
                    $count = 1;
                    $relevantes = 0;
                    foreach ($ranking_documentos as $documento => $similaridade){
                        if ($similaridade > 0){
                            if (in_array($documento, $retornados_relevantes)){
                                $relevantes++;
                                $avg_precision += ($relevantes / $count);
                            }

                            $count++;
                        } else {
                            break;
                        }
                    }
                    
                    if ($relevantes == 0){
                        return 1;
                    }

                    $avg_precision /= $relevantes;
                    return $avg_precision;
                }

                // Funcao que calcula a area abaixo da curva (e da echo nas informacoes que devem ser usadas no grafico)
                function plotaRecallPrecision($ranking_documentos, $retornados_relevantes, $nao_retornados_relevantes){
                    $ranking_parcial = array();
                    $retornados_relevantes_parcial = array();
                    $nao_retornados_relevantes_parcial = array_merge($retornados_relevantes, $nao_retornados_relevantes);
                    $count = 1;

                    $area = 0;
                    $area_interpolada = 0;
                    $grafico = array();

                    $grafico[0] = array();
                    $grafico[0]['precision'] = 1;
                    $grafico[0]['precision_normal'] = 1;
                    $grafico[0]['recall'] = 0;

                    foreach ($ranking_documentos as $documento => $similaridade){
                        if ($similaridade > 0) {
                            $ranking_parcial[$documento] = $similaridade;

                            if (in_array($documento, $retornados_relevantes)){
                                array_push($retornados_relevantes_parcial, $documento);
                                unset($nao_retornados_relevantes_parcial[array_search($documento, $nao_retornados_relevantes_parcial)]);

                                $grafico[$count] = array();
                                $grafico[$count]['precision'] = $grafico[$count]['precision_normal'] = calculaPrecision($ranking_parcial, $retornados_relevantes_parcial);
                                $grafico[$count]['recall'] = calculaRecall($retornados_relevantes_parcial, $nao_retornados_relevantes_parcial);
    
                                $outro_count = $count - 1;
                                while ($grafico[$count]['precision'] > $grafico[$outro_count]['precision'] && $outro_count > 0){
                                    $grafico[$outro_count]['precision'] = $grafico[$count]['precision'];
                                    $outro_count--;
                                }
                            } else {
                                $grafico[$count] = array();
                                $grafico[$count]['precision'] = $grafico[$count]['precision_normal'] = calculaPrecision($ranking_parcial, $retornados_relevantes_parcial);;
                                $grafico[$count]['recall'] = $grafico[$count - 1]['recall'];
                            }
                        } else {
                            break;
                        }

                        $count++;
                    }

                    $result = "";
                    $count = 0;
                    foreach ($grafico as $indice => $map){
                        $result .= "[" . $map['recall'] . ", " . $map['precision_normal'] . ", " . $map['precision'] ."],";
                        
                        if ($count > 0){
                            $b_maior = $grafico[$count - 1]['precision_normal'];
                            $b_menor = $grafico[$count]['precision_normal'];
                            $h = $grafico[$count]['recall'] - $grafico[$count - 1]['recall'];
                            $area += (($b_maior + $b_menor) * $h) / 2.0;

                            $b_maior_interpolada = $grafico[$count - 1]['precision'];
                            $b_menor_interpolada = $grafico[$count]['precision'];
                            $h_interpolada = $grafico[$count]['recall'] - $grafico[$count - 1]['recall'];
                            $area_interpolada += (($b_maior_interpolada + $b_menor_interpolada) * $h_interpolada) / 2.0;
                        }

                        $count++;
                    }

                    $grafico_e_area = array();
                    $grafico_e_area['valores_grafico'] = $grafico;
                    $grafico_e_area['grafico'] = substr($result, 0, -1);
                    $grafico_e_area['area'] = $area;
                    $grafico_e_area['area_interpolada'] = $area_interpolada;

                    $grafico_onze_pontos = array();
                    $ponto = 0.0;
                    foreach ($grafico_e_area['valores_grafico'] as $k => $valores) {
                        while ($ponto <= $valores['recall']) {
                            $grafico_onze_pontos["" . $ponto] = $valores['precision'];
                            

                            if ($ponto + 0.1 > 1.05){
                                break;
                            }

                            $ponto += 0.1;
                        }
                        
                        if ($ponto + 0.1 > 1.05){
                            break;
                        }
                    }

                    if ($ponto < 0.95) {
                        while ($ponto < 1.05){
                            $grafico_onze_pontos["" . $ponto] = 0.0;
                            $ponto += 0.1;
                        }
                    }
                    
                    $result_pontos = "";
                    $count_pontos = 0;
                    $area_pontos = 0.0;
                    $anterior = 0.0;
                    foreach ($grafico_onze_pontos as $rec => $prec){
                        $result_pontos .= "[" .  $rec . ", " . $prec . "],";
                        
                        if ($count_pontos > 0){
                            $b_maior_pontos = $prec;
                            $b_menor_pontos = $anterior;
                            $area_pontos += (($b_maior_pontos + $b_menor_pontos) * 0.1) / 2.0; // altura sempre vai ser 0.1
                        }

                        $anterior = $prec;
                        $count_pontos++;
                    }
                    
                    $grafico_e_area['grafico_onze_pontos'] = substr($result_pontos, 0, -1);
                    $grafico_e_area['area_onze_pontos'] = $area_pontos;
                    
                    return $grafico_e_area;
                }

                // Função que calcula os centroides dos documentos relevantes e nao relevantes e a nova consulta
                function calculaRocchio($consulta_inicial, $documentos_relevantes, $documentos_modelo_vetorial){
                    $roc_alpha = $_SESSION['roc_alpha'] ?? 1.0;
                    $roc_beta = $_SESSION['roc_beta'] ?? 0.75;
                    $roc_gama = $_SESSION['roc_gama'] ?? 0.15;

                    $centroide_relevantes = array();
                    $contador_relevantes = 0;

                    $centroide_nao_relevantes = array();
                    $contador_nao_relevantes = 0;

                    foreach ($documentos_modelo_vetorial as $doc => $map) {
                        // Se o documento foi marcado como relevante
                        if (in_array($doc, $documentos_relevantes)){
                            // Adiciona o vetor ao centroide dos relevantes
                            foreach ($map as $word => $value){
                                if (isset($centroide_relevantes[$word])){
                                    $centroide_relevantes[$word] += $value;
                                } else {
                                    $centroide_relevantes[$word] = $value;
                                }
                            }

                            $contador_relevantes++;
                        } else {
                            // Senao adiciona no centroide de nao relevantes
                            foreach ($map as $word => $value){
                                if (isset($centroide_nao_relevantes[$word])){
                                    $centroide_nao_relevantes[$word] += $value;
                                } else {
                                    $centroide_nao_relevantes[$word] = $value;
                                }
                            }

                            $contador_nao_relevantes++;
                        }
                    }
                    
                    // Divide todas as dimensoes dos centroides pelo numero de documentos relevantes e nao relevantes
                    foreach ($centroide_relevantes as $palavra => $valor) {
                        $centroide_relevantes[$palavra] /= $contador_relevantes;
                    }

                    foreach ($centroide_nao_relevantes as $palavra => $valor) {
                        $centroide_nao_relevantes[$palavra] /= $contador_nao_relevantes;
                    }

                    $nova_consulta = array();

                    // Encontra a nova consulta
                    foreach ($consulta_inicial as $palavra => $valor) {
                        $nova_consulta[$palavra] = ($roc_alpha * $valor) + ($roc_beta * $centroide_relevantes[$palavra]) - ($roc_gama * $centroide_relevantes[$palavra]);
                        if ($nova_consulta[$palavra] < 0){
                            $nova_consulta[$palavra] = 0.0;
                        }
                    }

                    $resultado = array();
                    $resultado['centroide_relevantes'] = $centroide_relevantes;
                    $resultado['centroide_nao_relevantes'] = $centroide_nao_relevantes;
                    $resultado['nova_consulta'] = $nova_consulta;
                    
                    return $resultado;
                }

                // Chama o calculo do precision com ranking de documentos seguido de todos os documentos retornados que foram marcados como relevantes
                $resultado_precision = calculaPrecision($_SESSION['indice_relevancia'], $_POST['documentos_retornados']);
                echo "<h4>Precision: $resultado_precision</h4>";
                
                // Chama o calculo do recall com os documentos marcados como relevantes que foram retornados e com aqueles que nao foram 
                $resultado_recall = calculaRecall($_POST['documentos_retornados'], $_POST['documentos_nao_retornados']);
                echo "<h4>Recall: $resultado_recall</h4>";
                
                $resultado_fmeasure = calculaFMeasure($resultado_precision, $resultado_recall);
                echo "<h4>F-Measure: $resultado_fmeasure</h4>";

                $resultado_avg_precision = calculaAVGPrecision($_SESSION['indice_relevancia'], $_POST['documentos_retornados']);
                echo "<h4>AVG Precision: $resultado_avg_precision</h4>";

                $grafico_area = plotaRecallPrecision($_SESSION['indice_relevancia'], $_POST['documentos_retornados'], $_POST['documentos_nao_retornados']);

                $documentos_roc_relevantes = array();
                foreach ($_POST['documentos_retornados'] as $doc_ret){
                    array_push($documentos_roc_relevantes, $doc_ret);
                }

                $rocchio = calculaRocchio($_SESSION['vetor_consulta'], $documentos_roc_relevantes, $_SESSION['documento_vetorial']);
                $nova_ordem = indice_relevancia($rocchio['nova_consulta']);
            ?>

            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                    ['Recall', 'Precision', 'Precision Interpolado'],
                    
                    <?php
                        echo $grafico_area['grafico'];
                    ?>

                    ]);

                    var options = {
                        title: 'Curva Recall x Precision Interpolada',
                        curveType: 'line',
                        legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);
                }
            </script>

            <div id="curve_chart" style="width: 600px; height: 400px"></div>

            <?php
                echo '<h4><font color="blue">Área: ' . number_format($grafico_area['area'], 2) . '</font></h4><br />';
                echo '<h4><font color="red">Área: ' . number_format($grafico_area['area_interpolada'], 2) . '</font></h4><br />';
            ?>

            <script type="text/javascript">
                google.charts.setOnLoadCallback(drawChart2);

                function drawChart2() {
                    var data = google.visualization.arrayToDataTable([
                    ['Recall', 'Precision'],
                    
                    <?php
                        echo $grafico_area['grafico_onze_pontos'];
                    ?>

                    ]);

                    var options = {
                    title: 'Curva Recall x Precision - 11 Pontos',
                    curveType: 'line',
                    legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('another_curver_chart'));

                    chart.draw(data, options);
                }
            </script>

            <div id="another_curver_chart" style="width: 600px; height: 400px"></div>

            <?php
                echo '<h4>Área: ' . number_format($grafico_area['area_onze_pontos'], 2) . '</h4>';

                echo "<h3>Classificação usando Rocchio:</h3>";

                echo "<h3>Documentos retornados:</h3>";
                foreach($nova_ordem as $documento => $similaridade){
                    if ($similaridade > 0){
                        echo '<h4><a href="documentos/' . $documento . '">' . $documento . '</a></h4>' . "<p>Similaridade = " . $similaridade. "</p>" . "<p>PageRank = " . $_SESSION['page_rank'][$documento] . "</p>";
                    }
                }

                echo "<h3>Outros documentos:</h3>";
                foreach($nova_ordem as $documento => $similaridade){
                    if ($similaridade == 0){
                        echo '<h4><a href="documentos/' . $documento . '">' . $documento . '</a></h4>' . "<p>Similaridade = " . $similaridade. "</p>" . "<p>PageRank = " . $_SESSION['page_rank'][$documento] . "</p>";
                    }
                }

                debug($rocchio);
            ?>
        </div>
    </body>
</html>