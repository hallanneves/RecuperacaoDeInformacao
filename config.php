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
            <div class="row ">
                <div class="col-md-6 col-lg-offset-3">
                    <form method="post" action="alteraEpsilon.php">
                        <h2>Configurações</h2>
                        <div class="input-group col-md-12">
                            <?php
                                echo 'Epsilon: <input name="epsilon" type="text" class="form-control " placeholder="0.01" />';
                            ?>
                        </div>
                        <br/>
                        <div class="input-group col-md-12">
                            <?php
                                echo 'Alfa: <input name="alpha" type="text" class="form-control " placeholder="0.1" />';
                            ?>
                        </div>
                        <div class="input-group col-md-12">
                            <br/>
                            <button class="btn btn-info " type="submit">Alterar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php

                    ?>
                </div>
            </div>
        </div>
    </body>
</html>