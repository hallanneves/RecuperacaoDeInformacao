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
            <?php
            require_once './utils.php';
            ?>
            <div class="row">
                <div class="col-md-10 col-lg-offset-1">
                    <h2>Vetor de documentos</h2>
                    <p>Cada coluna representa um vetor de documento.</p>            
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Palavras</th>
                                <?php
                                foreach ($_SESSION['documentos'] as $nome_docmento => $palavras) {
                                    echo "<th>$nome_docmento</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>


                            <?php
                            foreach ($_SESSION['indice_invertido'] as $palavra => $documentos_palavra) {
                                echo"<tr>";
                                echo "<td>$palavra</td>";
                                foreach ($_SESSION['documentos'] as $nome_docmento => $pl) {
                                        echo"<td>" . $_SESSION['documento_vetorial'][$nome_docmento][$palavra] . "</td>";
                                    
                                }

                                echo"</tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
                <div class="row">
                    <div class="col-md-10 col-lg-offset-1">
                        <h2>Vetor de documentos(Lista)</h2>
                        <p>Estrutura de armazenamento dos vetores dos documentos.</p>            
                        <?php
                        debug($_SESSION['documento_vetorial']);
                        ?>
                        </table>
                    </div>
                </div>
            </div>
    </body>
</html>