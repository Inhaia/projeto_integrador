<?php
	session_start();
	if (isset($_SESSION['codProfessor'])){
    	header ("Location: Listar.php");
 	}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SenaQuiz Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="css/estilos.css"/>
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body id="body-login">
	
<div class="login">
    
	<div>
		<img src="img/logo.jpg" class="img-responsive logo-login" alt="Logo SenaQuiz">
	</div>
	
    <form method="post" action="">
        <div class="form-group input-group">
			<div class="input-group-addon"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span> </div>
			<input class="form-control" type="text" name="email" placeholder="Usuário" id="user" required="">
		</div>
		<div class="form-group input-group">
			<div class="input-group-addon"> <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> </div>
            <input class="form-control" type="password" name="senha" placeholder="Senha" id="password" required="">
		</div>
		<div class="form-group">
            <input class="form-control btn btn-default" type="submit" name="button-login" value="Entrar" id="button-login">
             <p class="ajuda"><a href="http://www.mediafire.com/download/dpiv109even596e/PI2.pptx"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Ajuda</a></p>
		</div>
    </form>
	
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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
echo $numero;
if ($numero == 1) {
    session_start(); 
    $_SESSION['showMenu'] = true;
    $_SESSION['codProfessor'] = odbc_result($q,'codProfessor');
    $_SESSION['tipoProfessor'] = odbc_result($q,'tipo');
    $_SESSION['nomeProfessor'] = odbc_result($q,'nome');
    //print_r($_SESSION);
    if (isset($_GET['continua'])){
    	$continuapg = $_GET['continua'];
    	header("Location: $continuapg");
    }else{
    	header("Location: Listar.php");
  	}
    
}  else {
    echo "<script>
			$(\"form\").prepend (\"<div class='alert alert-danger alert-dismissible' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Atenção!</strong> Usuário ou senha inválidos. </div>\");
		</script>";
     
}
}

?>
</body>
</html>