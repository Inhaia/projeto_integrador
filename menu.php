<?php 
session_start();

if (!isset($_SESSION['codProfessor'])){
	$continua = $_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI'];
	header ("Location: index.php?continua=http://$continua");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>MENU-CRUD</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="css/estilos.css"/>
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>

<body>

<div class="menu">
<div class="container">
	<nav class="navbar">
		<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		
			<a class="navbar-brand" href="index.php"> <img src="img/logo.jpg" class="img-responsive logo" alt="Logo SenaQuiz"> </a>
			<p class="navbar-text" >Olá, <?php echo $_SESSION['nomeProfessor']; ?></p>
		</div>
		<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li role="presentation" class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> Questões <span class="caret"></span> </a>
					<ul class="dropdown-menu">
					  <li><a href="Listar.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> Listar</a></li>
					  <li><a href="questao.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Criar</a></li>
					  <li><a href="http://www.mediafire.com/download/dpiv109even596e/PI2.pptx"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Ajuda</a></li>
					</ul>
				</li>
				<li> <a href="finaliza.php"> <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Sair</a> </li>
			</ul>
		</div>
		</div>
	</nav>
</div>
</div>

