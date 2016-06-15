<?php 
session_start();
include 'Conecta.php';
	
//Verifcar se ja foi atribuida a um evento
$stmt = odbc_prepare($conn,'SELECT * FROM QuestaoEvento WHERE codQuestao = ?');
odbc_execute($stmt, array($_GET['codQuestao']));
if(odbc_num_rows($stmt) == 0){//se nao tiver sido atribuida para um evento
	
	$stmt = odbc_prepare($conn,'SELECT * FROM Questao WHERE codQuestao = ?');
	odbc_execute($stmt, array($_GET['codQuestao']));
	$r = odbc_fetch_array($stmt);

	if($r['codProfessor'] == $_SESSION['codProfessor']){//se a questao for do usuario ativo na sessao pode apagar
		
		$stmt = odbc_prepare($conn,'DELETE FROM Alternativa WHERE codQuestao = ?');
		odbc_execute($stmt, array($_GET['codQuestao']));
		$stmt = odbc_prepare($conn,'DELETE FROM Questao WHERE codQuestao = ?');
		odbc_execute($stmt, array($_GET['codQuestao']));
		
		echo "Questão apagada com sucesso";
		
	}else{
		echo "Vocë não pode apagar uma questão inserida por outro professor!";		
	}
	
}else{//Se tiver sido atribuida, nao podemos apagar, temos que mudar ativo para 0
	
	$stmt = odbc_prepare($conn,'UPDATE Questao SET ativo = 0 WHERE codQuestao = ?');
	if(odbc_execute($stmt, array($_GET['codQuestao'])))
		echo "Questao desativada";
	else
		echo "Erro ao desativar a questao";
}	

echo "\n\n<br><br><a href='Listar.php'>Voltar</a>";
	
?>