<html>
    <head> 
        <title>Download Movies</title>
        <script src="./../assets/angular.min.js"></script>
        <script src="./../assets/jquery-1.9.1.js"></script>
        <link href="./../assets/jquery.dataTables.css" rel="stylesheet" type="text/css" />
        <script src="./../assets/jquery.dataTables.js"></script>
        <script src="./../assets/bootstrap.min.js"></script><!--No need-->
        <link rel="stylesheet" href = "./../assets/bootstrap.min.css">
        <style>
            input[type="search"] {
                background: lightgray;
            }
        </style>
    </head>
    <body>  
        <div class="navbar navbar-inverse" style="height: 10px;">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>

                </button>

                <a class="navbar-brand" href="#">List of Movies</a>


            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-left">
                    <li><a class="btn-success" style="color: white;" href="./multi-uploader/uploadm.php">Upload Movie</a></li>
                </ul>

            </div>
        </div>
        <h1>List of files</h1>
        <span id="broserError"></span>
        <?php
        $dir = '.';
        $files = scandir($dir);

        echo ' <a href="../" > <img width="20" src="./../images/up.png"/>Go up </a><br><br>';
        echo '<table id="table1">';
        echo '<thead><tr><th>Server Contents</th><th>Genre</th><th style="width:33%">Description</th><th></th><th>Options</th></tr></thead>';
        echo '<tbody>';

        $genres = array();
        $handle = fopen("./../assets/movie_data.dat", "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $genres[explode(' ', $line)[0]] = explode(' ', $line)[1];
                $temp = explode(' ', $line);
                $description = "";
                for ($j = 2; $j < count($temp); $j++) {
                    $description = $description . " " . $temp[$j];
                }
                $genres[explode(' ', $line)[0] . "d"] = $description;
            }
            fclose($handle);
        } else {
            echo "Error Reading genre file";
        }

        for ($i = 0; $i < count($files); $i++) {
            if (strlen($files[$i]) < 3 && strpos($files[$i], '.') === 0) {
                continue;
            } else if ($files[$i] === "index.php" || $files[$i] === "delete.php" || $files[$i] === "multi-uploader") {
                continue;
            }
            if (strpos($files[$i], '.') > 1 && strpos($files[$i], '~') < 1) {
                echo '<tr>';
                echo " <td><a id='" . substr($files[$i], 0, count($files[$i]) - 5) . "a' href='" . $files[$i] . "' download><img width='20' src='./../images/file.png'/> " . $files[$i] . "</a></td><td>" . $genres[$files[$i]] . "</td><td>" . $genres[$files[$i] . "d"] . "</td>";
                if (strpos($files[$i], '.mp4') > 1 || strpos($files[$i], '.avi') > 1 || strpos($files[$i], '.mkv') > 1 || strpos($files[$i], '.3gp') > 1) {
                    echo " <td><input type='button' class='btn-success' onclick='playvid(this.id)' id='" . substr($files[$i], 0, count($files[$i]) - 5) . "' value='Play' /></td><td><input type='button' style='margin-right: 10px;' class='btn-primary' onclick='download(this.id)' id='" . substr($files[$i], 0, count($files[$i]) - 5) . "' value='Download' /><input type='button' class='btn-danger' onclick='deletev(this.id)' id='" . $files[$i] . "' value='Delete' /></td></tr><div style='text-align:center;margin-left: 10px;' id='div" . substr($files[$i], 0, count($files[$i]) - 5) . "'> </div>";
                } else {
                    echo " <td></td><td><input type='button' class='btn-primary' style='margin-right: 10px;' onclick='download(this.id)' id='" . substr($files[$i], 0, count($files[$i]) - 5) . "' value='Download' /><input type='button' class='btn-danger' onclick='deletev(this.id)' id='" . $files[$i] . "' value='Delete' /></td></tr><br>";
                }
            } else if (!strpos($files[$i], '~') > 0) {
                echo " <td><a href='" . $files[$i] . "' ><img width='20' src='./../images/folder.png'/> " . $files[$i] . "/</a></td><br>";
            }
        }
        echo '</tbody>';
        echo '</table>';
        ?>



        <script type="text/javascript">
            var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
            var isChrome = !!window.chrome && !isOpera;
            var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
            var validBrow = false;
            if (isChrome || isSafari) {
                validBrow = true;
            }
            if (!validBrow) {
                $('#broserError').html("Videos might not play in this browser, please use google chrome <br><br>");
            }
            function playvid(id) {
                $("div[id^='div']").html("");
                $("#div" + id).html("<video style='width:480px;' controls><source src='" + id + "'>Your browser does not support HTML5 video.</video><br><input type='button' class='btn-danger' id='" + id + "' value='Close Player' onclick='stopvid(this.id)'/><br>");
            }
            function stopvid(id) {
                $("#div" + id).html("");
            }
            function download(id) {
                $("#" + id + "a").focus();
                document.getElementById(id + "a").click();
            }
            function deletev(id) {
                var passs = prompt("Enter password to continue");
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        if (xmlhttp.responseText == "1")
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(xmlhttp.responseText);
                        }
                    }
                }
                xmlhttp.open("GET", "delete.php?pass=" + passs + "&fn=" + id, true);
                xmlhttp.send();
            }

            var app = angular.module("myApp", []);
            app.controller("myCtrl", function ($scope) {
                $scope.content = "movie";
                $scope.genre = "n/a";
                $scope.playable = "true";
            });
            $(document).ready(function () {
                var table = $('#table1').DataTable();
                $('input').addClass('btn');
                $('select').addClass('btn');
            });
        </script>
    </body>
    <footer class="footer" style="position:fixed;bottom:0px;">
        <div class="container">
            <p class="text-muted">by Jamshad Ahmad for Coeus Solutions.</p>
        </div>
    </footer>
</html>
