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
                        }

                        $count++;
                    }

                    echo substr($result, 0, -1);
                    return $area;
                }

                // Funcao que calcula a area abaixo da curva (e da echo nas informacoes que devem ser usadas no grafico)
                function plotaRecallPrecisionOnzePontos($ranking_documentos, $retornados_relevantes, $nao_retornados_relevantes){
                    $ranking_parcial = array();
                    $retornados_relevantes_parcial = array();
                    $nao_retornados_relevantes_parcial = array_merge($retornados_relevantes, $nao_retornados_relevantes);
                    $count = 1;
                    $ponto = 0.1;

                    $area = 0;
                    $grafico = array();

                    $grafico[0] = array();
                    $grafico[0]['precision'] = 1;
                    $grafico[0]['precision_normal'] = 1;
                    $grafico[0]['recall'] = 0;

                    foreach ($ranking_documentos as $documento => $similaridade){
                        $ranking_parcial[$documento] = $similaridade;
                        
                        if (in_array($documento, $retornados_relevantes) || in_array($documento, $nao_retornados_relevantes)){
                            array_push($retornados_relevantes_parcial, $documento);
                            unset($nao_retornados_relevantes_parcial[array_search($documento, $nao_retornados_relevantes_parcial)]);
                            
                            if (calculaRecall($retornados_relevantes_parcial, $nao_retornados_relevantes_parcial) >= $ponto){
                                $grafico[$count] = array();
                                $grafico[$count]['precision'] = $grafico[$count]['precision_normal'] = calculaPrecision($ranking_parcial, $retornados_relevantes_parcial);
                                $grafico[$count]['recall'] = calculaRecall($retornados_relevantes_parcial, $nao_retornados_relevantes_parcial);
                                
                                $outro_count = $count - 1;
                                while ($grafico[$count]['precision'] > $grafico[$outro_count]['precision'] && $outro_count > 0){
                                    $grafico[$outro_count]['precision'] = $grafico[$count]['precision'];
                                    $outro_count--;
                                }
                                
                                $count++;
                                $ponto += 0.1;
                            }
                        } else {
                            if (calculaRecall($retornados_relevantes_parcial, $nao_retornados_relevantes_parcial) >= $ponto){
                                if ($count > 0){
                                    $grafico[$count] = array();
                                    $grafico[$count]['precision'] = $grafico[$count]['precision_normal'] = calculaPrecision($ranking_parcial, $retornados_relevantes_parcial);;
                                    $grafico[$count]['recall'] = $grafico[$count - 1]['recall'];
                                }

                                $count++;
                                $ponto += 0.1;
                            }
                        }

                    }

                    $result = "";
                    $count = 0;
                    foreach ($grafico as $indice => $map){
                        $result .= "[" . $map['recall'] . ", " . $map['precision_normal'] . ", " . $map['precision'] ."],";

                        if ($count > 0){
                            $b_maior = $grafico[$count - 1]['precision'];
                            $b_menor = $grafico[$count]['precision'];
                            $h = $grafico[$count]['recall'] - $grafico[$count - 1]['recall'];
                            $area += (($b_maior + $b_menor) * $h) / 2.0;
                        }
                        
                        $count++;
                    }

                    echo substr($result, 0, -1);
                    return $area;
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

                // $area_onze_pontos = plotaRecallPrecisionOnzePontos($_SESSION['indice_relevancia'], $_POST['documentos_retornados'], $_POST['documentos_nao_retornados']);
            ?>

            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                    ['Recall', 'Precision', 'Precision Interpolado'],
                    
                    <?php
                        $area = plotaRecallPrecision($_SESSION['indice_relevancia'], $_POST['documentos_retornados'], $_POST['documentos_nao_retornados']);
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

            <div id="curve_chart" style="width: 700px; height: 500px"></div>

            <?php
                echo "<h4>Área abaixo da curva: $area";
            ?>

            <script type="text/javascript">
                google.charts.setOnLoadCallback(drawChart2);

                function drawChart2() {
                    var data = google.visualization.arrayToDataTable([
                    ['Recall', 'Precision', 'Precision Interpolado'],
                    
                    <?php
                        $area_onze_pontos = plotaRecallPrecisionOnzePontos($_SESSION['indice_relevancia'], $_POST['documentos_retornados'], $_POST['documentos_nao_retornados']);;
                    ?>

                    ]);

                    var options = {
                    title: 'Curva Recall x Precision Interpolada em 11 Pontos',
                    curveType: 'line',
                    legend: { position: 'bottom' }
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('another_curver_chart'));

                    chart.draw(data, options);
                }
            </script>

            <div id="another_curver_chart" style="width: 700; height: 500px"></div>

            <?php
                echo "<h4>Área abaixo da curva: $area_onze_pontos";
            ?>
        </div>
    </body>
</html>