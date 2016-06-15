<?php
session_start();
define('MAX_FILE_SIZE',9000000);
ini_set('odbc.defaultlrl',MAX_FILE_SIZE);
/* Servico de login*/
include 'Conecta.php';


/* Cadastro Questao e Imagem */
if (isset($_POST['btnSalvarQuestao'])){
	
	$areaQuestao = $_POST['area'];
	$assuntoQuestao = $_POST['assunto'];
	$tipoQuestao = $_POST['tipo'];
	$textoQuestao = $_POST['questao'];
	$dificuldadeQuestao = $_POST['dificuldade'];
	

	//Questões Alternativas
	if ($tipoQuestao == "A"){
		if(isset($_FILES['Imagem'])){
		
			$file = fopen($_FILES['Imagem']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['Imagem']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO Imagem 
											(tituloImagem, bitmapImagem) 
											VALUES 
											(?,?)');			 
			if(odbc_execute($stmt, array($_POST['TituloImagem'],
							$fileParaDB))){
										
				$msg_sucesso = '<br>Imagem armazenada no DB';	
					echo "ok";
					
				$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Imagem')");
				$r_codImagem = odbc_fetch_array($q);
				$img = each($r_codImagem);
				$codImagem = $img[0];
				
				
			}else{
				$msg_erro .= 'Erro ao salvar a Imagem no DB.';
				echo "erro";   
			}		
		}else{
			$codImagem = null;
		}
		
		$stmt = odbc_prepare($conn, "INSERT INTO Questao (textoQuestao, codAssunto, codImagem, codTipoQuestao, codProfessor, ativo, dificuldade) VALUES (?,?,?,?,?,?,?)");
		if(odbc_execute($stmt, array($textoQuestao ,$assuntoQuestao ,$codImagem ,$tipoQuestao ,$_SESSION['codProfessor'],1 ,$dificuldadeQuestao)))
		{
			echo "ok";	
		}else{
		echo "erro"	;}	
		
		


		$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Questao')");
		$r_codQuestao = odbc_fetch_array($q);
		$questao = each($r_codQuestao);
		$codQuestao = $questao[0];
				
		foreach ($_POST['alternativa'] as $codAlternativa => $textoAlternativa ){
			if ($codAlternativa == $_POST['Correta'])
				$correta = 1;
			else
				$correta = 0;
			
			$stmt = odbc_prepare($conn, "INSERT INTO Alternativa (codQuestao, codAlternativa,textoAlternativa,correta) VALUES (?,?,?,?)");
			odbc_execute($stmt, array($codQuestao,$codAlternativa,$textoAlternativa,$correta));
			echo odbc_errormsg($conn);
		}

	}

	//Questões verdadeiro ou falso

	if ($tipoQuestao == "V"){
		
		if(isset($_FILES['Imagem'])){
		
			$file = fopen($_FILES['Imagem']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['Imagem']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO Imagem 
											(tituloImagem, bitmapImagem) 
											VALUES 
											(?,?)');			 
			if(odbc_execute($stmt, array($_POST['TituloImagem'],
							$fileParaDB))){
										
				$msg_sucesso = '<br>Imagem armazenada no DB';	
					echo "ok";
					
				$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Imagem')");
				$r_codImagem = odbc_fetch_array($q);
				$img = each($r_codImagem);
				$codImagem = $img[0];
				
				
			}else{
				$msg_erro .= 'Erro ao salvar a Imagem no DB.';
				echo "erro";   
			}		
		}else{
			$codImagem = null;
		}


		if (isset($_POST['vf'])){
			$correta = 1;
		}else{
			$correta = 0;
		}

		$stmt = odbc_prepare($conn, "INSERT INTO Questao (textoQuestao, codAssunto, codImagem, codTipoQuestao, codProfessor, ativo, dificuldade) VALUES (?,?,?,?,?,?,?)");
		if(odbc_execute($stmt, array($textoQuestao ,$assuntoQuestao ,$codImagem ,$tipoQuestao ,$_SESSION['codProfessor'],1 ,$dificuldadeQuestao)))
		{
			echo "ok";	
		}else{
		echo "erro"	;}	

		$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Questao')");
		$r_codQuestao = odbc_fetch_array($q);
		$questao = each($r_codQuestao);
		$codQuestao = $questao[0];
		$textoAlternativa = "VERDADEIRO";

		$stmt = odbc_prepare($conn, "INSERT INTO Alternativa (codQuestao, codAlternativa,textoAlternativa,correta) VALUES (?,?,?,?)");
		odbc_execute($stmt, array($codQuestao,1,$textoAlternativa,$correta));
		echo odbc_errormsg($conn);
		
	}

	//Questões Objetiva
	if ($tipoQuestao == "T"){
		
		$respostaQuestao = $_POST['resposta-questao'];

		if(isset($_FILES['Imagem'])){
		
			$file = fopen($_FILES['Imagem']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['Imagem']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO Imagem 
											(tituloImagem, bitmapImagem) 
											VALUES 
											(?,?)');			 
			if(odbc_execute($stmt, array($_POST['TituloImagem'],
							$fileParaDB))){
										
				$msg_sucesso = '<br>Imagem armazenada no DB';	
					echo "ok";
					
				$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Imagem')");
				$r_codImagem = odbc_fetch_array($q);
				$img = each($r_codImagem);
				$codImagem = $img[0];
				
				
			}else{
				$msg_erro .= 'Erro ao salvar a Imagem no DB.';
				echo "erro";   
			}		
		}else{
			$codImagem = null;
		}

		$stmt = odbc_prepare($conn, "INSERT INTO Questao (textoQuestao, codAssunto, codImagem, codTipoQuestao, codProfessor, ativo, dificuldade) VALUES (?,?,?,?,?,?,?)");
		if(odbc_execute($stmt, array($textoQuestao ,$assuntoQuestao ,$codImagem ,$tipoQuestao ,$_SESSION['codProfessor'],1 ,$dificuldadeQuestao)))
		{
			echo "ok";	
		}else{
		echo "erro"	;}	

		$q = odbc_exec($conn, "SELECT IDENT_CURRENT('Questao')");
		$r_codQuestao = odbc_fetch_array($q);
		$questao = each($r_codQuestao);
		$codQuestao = $questao[0];

		$stmt = odbc_prepare($conn, "INSERT INTO Alternativa (codQuestao, codAlternativa,textoAlternativa,correta) VALUES (?,?,?,?)");
		odbc_execute($stmt, array($codQuestao,1,$respostaQuestao,1));
		echo odbc_errormsg($conn);
		
	}
}


	/*echo "<pre>";
	var_dump($_POST['alternativa']);
	var_dump($_FILES);
	echo "</pre>";*/

?>