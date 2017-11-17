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
            
            <?php
                require_once './utils.php';
           ?>

            <div class="row">
                <div class="col-md-10 col-lg-offset-1">
                    <h2>Índice Invertido (Matriz de Frequência)</h2>
                    <p>Os zeros não são armazenados. Os dados são armazenados em uma lista.</p>            
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Termo</th>
                                <?php
                                    foreach ($_SESSION['documentos'] as $documento => $termo) {
                                        echo "<th> $documento </th>";
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($_SESSION['indice_invertido'] as $termo => $documento_termo) {
                                echo"<tr>";
                                echo "<td>$termo</td>";

                                foreach ($_SESSION['documentos'] as $nome_docmento => $pl) {
                                    if (isset($documento_termo[$nome_docmento])) {
                                        echo"<td>" . $documento_termo[$nome_docmento]['frequencia'] . "</td>";
                                    } else {
                                        echo"<td>0</td>";
                                    }
                                }

                                echo"</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-10 col-lg-offset-1">
                        <h2>Índice Invertido (Lista)</h2>
                        <p>Armazena a frequência por documento que ocorre cada termo.</p>            
                        <?php
                            debug($_SESSION['indice_invertido']);
                        ?>
                    </div>
                </div>
            </div>
    </body>
</html>