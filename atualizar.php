<?php
ini_set ('odbc.defaultlrl', 9000000);//muda configuração do PHP para trabalhar com imagens no DB

include 'Conecta.php';
include 'menu.php';
		
if(isset($_POST['questao'])){
		
	$consulta_preparada = odbc_prepare($conn,'UPDATE Questao SET textoQuestao = ?, codAssunto =?, 
	codTipoQuestao=?, codProfessor =?, ativo =?, dificuldade =? WHERE codQuestao = ?');
    if(odbc_execute($consulta_preparada,array($_POST['questao'],$_POST['assunto']
	,$_POST['tipo'],$_SESSION['codProfessor'],1,$_POST['dificuldade'],$_GET['codQuestao']))){
		$msg_sucesso = 'alterado com sucesso';
	}else{
		$msg_erro = 'ERRO ao salvar os dados';
	}
}

if(isset($_POST['btnEditar'])){
	
	foreach($_POST['alternativa'] as $codAlternativa => $textoAlternativa){
		
		if($_POST['Correta'] == $codAlternativa)
			$correta = '1';
		else	
			$correta = '0';
		
		$stmt = odbc_prepare($conn, "SELECT * FROM Alternativa where codQuestao = ? and codAlternativa = ?");
		odbc_execute($stmt, array($_GET['codQuestao'],$codAlternativa));
		if(odbc_num_rows($stmt) > 0){
			
			$stmt = odbc_prepare($conn, "UPDATE Alternativa SET textoAlternativa = ?, correta = ? where codQuestao = ? and codAlternativa = ?");
			if (!odbc_execute($stmt, array($textoAlternativa,$correta,$_GET['codQuestao'],$codAlternativa))){
				echo odbc_errormsg($conn);
				$msg_erro = 'ERRO ao salvar os dados';
				echo $msg_erro.'<br>';  
			}
			
		}else{
			
			$stmt = odbc_prepare($conn, "INSERT INTO Alternativa (textoAlternativa, correta, codQuestao, codAlternativa) VALUES (?,?,?,?)");
			if (!odbc_execute($stmt, array($textoAlternativa,$correta,$_GET['codQuestao'],$codAlternativa))){
				echo odbc_errormsg($conn);
				$msg_erro = 'ERRO ao salvar os dados';
				echo $msg_erro.'<br>';  
			}
			
		}
	}	
}

	if(isset($_FILES['Imagem'])){
		
		if(is_file($_FILES['Imagem']['tmp_name'])){
		
			$file = fopen($_FILES['Imagem']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['Imagem']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'SELECT questao.* FROM Imagem inner join Questao  on (imagem.codImagem = questao.codimagem) WHERE codQuestao = ?');
			$q_i = odbc_execute($stmt, array($_GET['codQuestao']));
			$ri = odbc_fetch_array($stmt);
			if(odbc_num_rows($stmt) > 0){
				
				$stmt = odbc_prepare($conn,'	UPDATE Imagem 
												SET bitmapImagem = ? WHERE codImagem = ?');			 
				odbc_execute($stmt, array($fileParaDB, $ri['codImagem']));
				
			}else{
			
				$stmt = odbc_prepare($conn,'	INSERT INTO Imagem (tituloImagem, bitmapImagem) OUTPUT INSERTED.codimagem VALUES (?,?)');			 
				
				if(odbc_execute($stmt, array($_POST['TituloImagem'],$fileParaDB))){
											
					$msg_sucesso .= '<br>Imagem armazenada no DB';	
						echo "ok";
						
					$img = odbc_fetch_array($stmt);
					$codImagem = $img['codimagem'];
					var_dump($img);
					$consulta_preparada = odbc_prepare($conn,'UPDATE Questao SET codImagem = ? WHERE codQuestao = ?');
					odbc_execute($consulta_preparada,array($codImagem, $_GET['codQuestao']));
					
				}else{
					$msg_erro .= 'Erro ao salvar a Imagem no DB.';
					echo "erro";   
				}		
			}
		}else{
		$codImagem = null;
	}
	}




$stmt = odbc_prepare($conn,'SELECT * FROM Questao JOIN Assunto ON Questao.codAssunto = Assunto.codAssunto WHERE codQuestao=?');
odbc_execute($stmt, array($_GET['codQuestao']));
//echo odbc_errormsg ($conn); 
$r = odbc_fetch_array($stmt);





$q_area = odbc_exec($conn,'SELECT * FROM Area');
while($r_area = odbc_fetch_array($q_area)){
	$areas[$r_area['codArea']] = $r_area['descricao'];
}

$q_assunto = odbc_exec($conn,'SELECT * FROM Assunto');
while($r_assunto = odbc_fetch_array($q_assunto)){
	$assuntos[$r_assunto['codAssunto']] = $r_assunto['descricao'];
}

$q_tipo = odbc_exec($conn,'SELECT * FROM TipoQuestao');
while($r_tipo = odbc_fetch_array($q_tipo)){
	$tipos[$r_tipo['codTipoQuestao']] = $r_tipo['descricao'];
}

$q_dificuldade = odbc_exec($conn,'SELECT dificuldade FROM Questao WHERE codQuestao = '.$r['codQuestao']);
//echo "<br>".'SELECT dificuldade FROM Questao WHERE codQuestao = '.$r['codQuestao']."<br>";
$r_dificuldade = odbc_fetch_array($q_dificuldade);
	

if(!empty($r['codImagem'])){
	$q_img = odbc_exec($conn,'SELECT * FROM Imagem WHERE codImagem = '.$r['codImagem']);
	$r_img = odbc_fetch_array($q_img);
}
?>

<div class="container conteudo">

	<form id="formulario" class ="formulario" method="post" action="atualizar.php?codQuestao=<?php echo $_GET['codQuestao']; ?>" enctype="multipart/form-data">
    	<legend>Atualizar Questão</legend>

    	<div class="form-group">  
	      	<label for="area" class="control-label">Escolha a area da questão</label>
	        <select name="area" class="form-control input-sm" id="area">
			<option value=''>-- Escolha --</option>
			<?php
			foreach($areas as $cod => $descricao){
				
				if($cod == $r['codArea'])
					$str_selected = 'selected';
				else
					$str_selected = '';
				
				echo "<option value='$cod' $str_selected>$descricao</option>\n";			
			}
			?>
	        </select>
	    </div>

	    <div class="form-group">
	      	<label for="assunto" class="control-label"> Escolha assunto da questão</label>
	    	<select name="assunto" class="form-control input-sm" id="assunto">
				<option value=''>--Escolha--</option>
				<?php
				foreach($assuntos as $cod => $descricao){
				
					if($cod == $r['codAssunto'])
						$str_selected = 'selected';
					else
						$str_selected = '';
					echo "<option value='$cod' $str_selected>$descricao</option>\n";			
				}
				?>
	        </select>
        </div>

        <div class="form-group">
	      	<label for="tipo" class="control-label"> Escolha tipo da questão</label>
	       	<select name="tipo"  class="form-control input-sm" id="tipo">
		        <option value=''>-- Escolha --</option>
				<?php
				foreach($tipos as $cod => $descricao){
					
					if($cod == $r['codTipoQuestao'])
						$str_selected = 'selected';
					else
						$str_selected = '';
					echo "<option value='$cod' $str_selected>$descricao</option>\n";			
				}
				?>
			</select>
		</div>

		<div class="form-group">   
			<label for="questao" class="control-label"> Questão</label>
        	<textarea  class="form-control" rows="3" name="questao" id="questao"><?php echo $r['textoQuestao']; ?></textarea>
        </div>
      	
      	<div class="form-group">
   			<label for="dificuldade" class="control-label"> Dificuldade</label>
   			<select name="dificuldade" class="form-control input-sm" id="dificuldade">
           		<option VALUE="F" <?php echo (strtoupper($r['dificuldade']) == 'F') ? 'selected' : ''; ?>> F </option>
		   		<option VALUE="M" <?php echo (strtoupper($r['dificuldade']) == 'M') ? 'selected' : ''; ?>> M </option>
		   		<option VALUE="D" <?php echo (strtoupper($r['dificuldade']) == 'D') ? 'selected' : ''; ?>> D </option>
	       	</select>
   		</div> 

   		<div class="form-group">
   			<label for="TituloImagem" class="control-label">Insira o Titulo da Imagem</label>
   			<input type="text" class="form-control input-sm" name="TituloImagem" id="TituloImagem" value="<?php if(isset($r_img['tituloImagem']))echo $r_img['tituloImagem']; ?>">
   			<?php
			if(isset($r_img['bitmapImagem'])){
				echo '<img width="30%" src="data:image/jpeg;base64,'.base64_encode($r_img['bitmapImagem']).'" /><br>';
			}else{
				echo '<br>Sem imagem vinculada<br>';
			}
			?>
			<p class="help-block">Selecione uma imagem.</p>
   			<input type="file" name="Imagem">
		</div>

		<div class="form-group">             
    		<label for="dificuldade" class="control-label"> Alternativa</label>
			<?php
			$q_alternativa = odbc_exec($conn,"SELECT textoAlternativa as Alternativa, correta FROM alternativa where codQuestao = {$r['codQuestao']}");
			while ($r_alternativa = odbc_fetch_array($q_alternativa)) {
				$alternativa[] = $r_alternativa['Alternativa'];
				$verdadeira[] = $r_alternativa['correta'];
						
			}
			if($r['codTipoQuestao'] == 'A'){
				?>
			<div class="input-group">
		    	<span class="input-group-addon">A</span>
		    	<input type="text" class="form-control input-sm" name="alternativa[1]" value ="<?php echo @$alternativa[0]; ?>">
		    	<span class="input-group-addon">
	              <input type="radio" name="Correta" value="1" <?php echo @$verdadeira[0] ? 'checked' : ''; ?> > Correta
	            </span>
	        </div>

	        <div class="input-group">
	        	<span class="input-group-addon">B</span>
				<input type="text" class="form-control input-sm" name="alternativa[2]" value ="<?php echo @$alternativa[1]; ?>">
				<span class="input-group-addon">
					<input type="radio" name="Correta" value="2" <?php echo @$verdadeira[1] ? 'checked' : ''; ?>> Correta
				</span>
			</div>

			<div class="input-group">
				<span class="input-group-addon">C</span>
				<input type="text" class="form-control input-sm" name="alternativa[3]" value ="<?php echo @$alternativa[2]; ?>">
				<span class="input-group-addon">
					<input type="radio" name="Correta" value="3" <?php echo @$verdadeira[2] ? 'checked' : ''; ?>> Correta
				</span>
			</div>

			<div class="input-group">
				<span class="input-group-addon">D</span>
				<input type="text" class="form-control input-sm" name="alternativa[4]" value ="<?php echo @$alternativa[3]; ?>">
				<span class="input-group-addon">
					<input type="radio" name="Correta" value="4" <?php echo @$verdadeira[3] ? 'checked' : ''; ?>> Correta
				</span>
			</div>

			<div class="input-group">
				<span class="input-group-addon">E</span>
				<input type="text" class="form-control input-sm" name="alternativa[5]" value ="<?php echo @$alternativa[4]; ?>">
				<span class="input-group-addon">
					<input type="radio" name="Correta" value="5" <?php echo @$verdadeira[4] ? 'checked' : ''; ?>> Correta
				</span>
			</div>

				<?php
				
			}elseif($r['codTipoQuestao'] == 'V'){
				if($verdadeira){
					?>
					<td>Verdadeiro <input type='radio' name='resposta' checked>    Falso <input type='radio' name='resposta'></td>
					<?php
				}else{
					?>
					<td>Verdadeiro <input type='radio' name='resposta'>    Falso <input type='radio' name='resposta' checked></td>
					<?php	
				}
			}elseif($r['codTipoQuestao'] == 'T'){
				
				echo "<td><textarea>{$alternativa[0]}</textarea></td>";
				
			}
			?>
		</div>
	 	
	 	<div class="form-group">
			<input type="submit" name="btnEditar" value="Editar" class="btn btn-lg btn-form">
		</div>
  </form>

</div>
        
	
		
<?php


include('footer.php');
?>