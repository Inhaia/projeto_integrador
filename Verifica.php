<?php
	session_start();
        include 'Conecta.php';
	
	if ($conn = odbc_connect($conninfo, $user, $password)) {
    //echo "Conectado.";
}
	$senha = $_POST["senha"];
	//print_r ($_POST);
        $email = $_POST["email"];
	

$sql = "SELECT * FROM Professor where email='$email' and senha=hashbytes('SHA1', '".$senha."')";

$q=odbc_exec($conn,$sql);

	
$numero = odbc_num_rows($q);
if ($numero == 1) { 
	$_SESSION['showMenu'] = true;
	$_SESSION['codProfessor'] = odbc_result($q,'codProfessor');
	$_SESSION['tipoProfessor'] = odbc_result($q,'tipo');
	$_SESSION['nomeProfessor'] = odbc_result($q,'nome');
	$_SESSION['msg'] = "";
	//print_r($_SESSION);
	header('Location: Menu.php');
	echo "login válido";
	
}  else {
	$_SESSION['msg'] = "login inválido";
    header('Location: index.php'); 
	
	 
}

?>