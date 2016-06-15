<?php
include('Conecta.php');
include('Menu.php');

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
?>

<div class="container conteudo">

  
      
  <form id="formulario" class ="formulario" method="post" action="funcoes.php" enctype="multipart/form-data">

    <legend>Cadastrar nova Questão</legend>

    <div class="form-group">
        <label for="exampleInputEmail1" class="control-label">Escolha a area da questão</label>
        <select name="area" class="form-control input-sm" id="area">
          <option value=''>-- Escolha --</option>
          <?php
          foreach($areas as $cod => $descricao){
            echo "<option value='$cod'>$descricao</option>\n";      
          }?>
        </select>
    </div>

    <div class="form-group">
        <label for="assunto" class="control-label">Escolha assunto da questão</label>
        <select name="assunto" class="form-control input-sm" id="assunto">
          <option value=''>-- Escolha --</option>
          <?php
          foreach($assuntos as $cod => $descricao){
            echo "<option value='$cod'>$descricao</option>\n";    
          }?>
        </select>
    </div>

    <div class="form-group">
        <label for="tipo" class="control-label">Escolha tipo da questão</label>
        <select name="tipo" class="form-control input-sm" id="tipo">
          <option value=''>-- Escolha --</option>
          <?php
          foreach($tipos as $cod => $descricao){
            echo "<option value='$cod'>$descricao</option>\n";   
          }?>
        </select>
    </div>

    <div class="form-group">
        <label for="questao" class="control-label">Questão</label>
        <textarea class="form-control" rows="3" name="questao" id="questao"></textarea>
    </div>

    <div class="form-group" id="grupo-objetivo">
        <label for="resposta-questao" class="control-label">Resposta</label>
        <textarea class="form-control" rows="3" name="resposta-questao" id="resposta-questao"></textarea>
    </div>

    <div class="checkbox" id="grupo-vf">
      <label>
        <input type="checkbox" name="vf"> Esse questão é verdadeira
      </label>
    </div>

    <div class="form-group">
        <label for="dificuldade" class="control-label">Escolha tipo da questão</label>
        <select name="dificuldade" class="form-control input-sm" id="dificuldade">
          <option value="F"> Fácil </option>
          <option value="M"> Médio </option>
          <option value="D"> Difícil </option>
        </select>
    </div>

    <div class="form-group">
        <label for="TituloImagem" class="control-label">Insira o Titulo da Imagem</label>
        <input type="text" class="form-control input-sm" name="TituloImagem" id="TituloImagem">
        <p class="help-block">Selecione uma imagem.</p>
        <input type="file" name="Imagem">
    </div>

    <div class="form-group" id="grupo-alternativas">
          <label for="alternativa[1]" class="control-label">Alternativas</label>

          <div class="input-group">
            <span class="input-group-addon">A</span>
            <input type="text" class="form-control input-sm" name="alternativa[1]" id="alternativa[1]">
            <span class="input-group-addon">
              <input type="radio" name="Correta" value="1" checked> Correta
            </span>
          </div>

          <div class="input-group">
            <span class="input-group-addon">B</span>
            <input type="text" class="form-control" name="alternativa[2]" id="alternativa[2]">
            <span class="input-group-addon">
              <input type="radio" name="Correta" value="2"> Correta
            </span>
          </div>

          <div class="input-group">
            <span class="input-group-addon">C</span>
            <input type="text" class="form-control input-sm" name="alternativa[3]" id="alternativa[3]">
            <span class="input-group-addon">
              <input type="radio" name="Correta" value="3"> Correta
            </span>
          </div>

          <div class="input-group">
            <span class="input-group-addon">D</span>
            <input type="text" class="form-control input-sm" name="alternativa[4]" id="alternativa[4]">
            <span class="input-group-addon">
              <input type="radio" name="Correta" value="4"> Correta
            </span>
          </div>

          <div class="input-group">
            <span class="input-group-addon">E</span>
            <input type="text" class="form-control input-sm" name="alternativa[5]" id="alternativa[5]">
            <span class="input-group-addon">
              <input type="radio" name="Correta" value="5"> Correta
            </span>
          </div>
    </div>

    <div class="form-group">
        <input type="submit" name="btnSalvarQuestao" value="Salvar" class="btn btn-lg btn-form">
    </div>
   
  </form>

</div>

<script language ="javascript">

  $(document).ready(function(){

    $("#grupo-alternativas").hide();
    $("#grupo-vf").hide();
    $("#grupo-objetivo").hide();
    //---------------------------------------------------------------------------
    $('#tipo').change(function(){
      if($(this).val()==="A"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", false);
        $("#grupo-alternativas").show();
      }
    });

    $('#tipo').change(function(){
      if($(this).val() !="A"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", true);
        $("#grupo-alternativas").hide();
      }
    });
    //---------------------------------------------------------------------------
    $('#tipo').change(function(){
      if($(this).val()==="V"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", false);
        $("#grupo-vf").show();
      }
    });

    $('#tipo').change(function(){
      if($(this).val() !="V"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", true);
        $("#grupo-vf").hide();
      }
    });
    //---------------------------------------------------------------------------
    $('#tipo').change(function(){
      if($(this).val()==="T"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", false);
        $("#grupo-objetivo").show();
      }
    });

    $('#tipo').change(function(){
      if($(this).val() !="T"){
        //$("input[name*='alternativa'], input[name='Correta']").attr ("disabled", true);
        $("#grupo-objetivo").hide();
      }
    });

  });

</script>
        
<?php
include('footer.php');
?>