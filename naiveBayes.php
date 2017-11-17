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
                    <form method="post" action="calculaNaiveBayes.php">
                        <h2>Naïve Bayes</h2>
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <?php
                                    echo '<input name="documento" type="text" class="form-control input-lg" placeholder="Documento" />';
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
                        if(isset($_SESSION['multinomialNB'])){
                            debug($_SESSION['multinomialNB']);
                        }
                        if(isset($_SESSION['treinamento'])){
                            debug($_SESSION['treinamento']);
                        }else{
                            header("Location: calculaNaiveBayes.php");
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>