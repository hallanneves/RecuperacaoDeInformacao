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
        <script src = "js/jquary.min.js" ></script>

        <!-- Optional theme -->
        <link rel="stylesheet" href="css/home.css">
    <head>
    <body>
        <?php
            require_once "utils.php";
        ?>
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
                                <li><a href="configRocchio.php">Rocchio</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">

                                <li><a href="reprocessar.php" onclick="alert('Reprocessando os índices!');">Reprocessar</a></li>
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
            <div class="row text-center">
                <div class="col-md-6 col-lg-offset-3">
                    <form method="post" action="realizaPesquisa.php">
                        <h2>Zehallan Search</h2>
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <?php
                                    echo '<input name="pesquisa" type="text" class="form-control input-lg" placeholder="Pesquisa" />';
                                ?>
                                <span class="input-group-btn">
                                    <button class="btn btn-info btn-lg" type="submit">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php
                        if (!isset($_SESSION['indice_invertido'])){
                            header("Location: reprocessar.php");
                        }

                        if(isset($_SESSION['vetor_consulta'])){
                            echo "<h3>Vetor da consulta</h3>";
                            debug($_SESSION['vetor_consulta']);
                        }

                        if(isset($_SESSION['indice_relevancia'])){
                            echo '<form action="calculaMetricas.php" method="POST">';
                            echo "<h3>Documentos retornados pela consulta:</h3>";
                            foreach($_SESSION['indice_relevancia'] as $documento => $similaridade){
                                if ($similaridade > 0){
                                    echo '<h4><input type="checkbox" name="documentos_retornados[]" value="' . $documento . '"> <a href="documentos/' . $documento . '">' . $documento . '</a></h4>' . "<p>Similaridade = " . $similaridade. "</p>" . "<p>PageRank = " . $_SESSION['page_rank'][$documento] . "</p>";
                                }
                            }

                            echo "<h3>Outros documentos:</h3>";
                            foreach($_SESSION['indice_relevancia'] as $documento => $similaridade){
                                if ($similaridade == 0){
                                    echo '<h4><input type="checkbox" name="documentos_nao_retornados[]" value="' . $documento . '"> <a href="documentos/' . $documento . '">' . $documento . '</a></h4>' . "<p>Similaridade = " . $similaridade. "</p>" . "<p>PageRank = " . $_SESSION['page_rank'][$documento] . "</p>";
                                }
                            }

                            echo '<input type="submit" name="submit" value="Marcar como relevante">';
                            echo '</form>';
                        }

                        echo "<h3>Estrutura do grafo:</h3>";
                        if (isset($_SESSION['grafo'])){
                            debug($_SESSION['grafo']);
                        }

                        if (isset($_SESSION['alpha'])){
                            echo "<h4>Alfa:</h4> " . $_SESSION['alpha'];
                        }

                        if (isset($_SESSION['epsilon'])){
                            echo "<h4>Epsilon:</h4> " . $_SESSION['epsilon'];
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>