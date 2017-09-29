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
                            <a class="navbar-brand" href="#">Recuperação de Informação</a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="index.php">Pesquisa</a></li>
                                <li><a href="indiceInvertido.php">Indice Invertido</a></li>
                                <li><a href="vetorDeDocumentos.php">Vetor de documentos</a></li>
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
            <div class="row text-center">
                <div class="col-md-6 col-lg-offset-3">
                    <form method="post" action="pesquisa.php">
                        <h2>Zehallan search</h2>
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
                        if(isset($_SESSION['vetor_consulta'])){
                            echo "<h2>Vetor da consulta</h2>";
                            debug($_SESSION['vetor_consulta']);
                        }
                        if(isset($_SESSION['indice_relevancia'])){
                            echo "<h2>Resultado</h2>";
                            foreach($_SESSION['indice_relevancia'] as $nome_doc => $relevancia){
                                if($relevancia > 0){
                                    echo '<h3><a href="documentos/'.$nome_doc.'">' .$nome_doc. '</a></h3>' . "<p>Similaridade = " .$relevancia. "</p>";
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>