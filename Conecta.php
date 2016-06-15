<?php
$dbhost="koo2dzw5dy.database.windows.net";
$db="SenaQuiz";
$user="TSI";
$password="SistemasInternet123";
	
$conninfo = "driver={SQL server};server=$dbhost;port=1433;Database=$db;";
if ($conn = odbc_connect($conninfo, $user, $password)) {
    //echo "Conectado.";
}
?>