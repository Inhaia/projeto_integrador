<?php
include('Conecta.php');
include('Menu.php');
?>

<div class="container conteudo">
	<div class="row">

	<div class="col-md-">
		<div class="col-md-4">
			<a href="questao.php" class="btn btn-info" role="button"><span class='glyphicon glyphicon-plus' aria-hidden='true'></span> Adicionar</a>
		</div>
		<div class="col-md-6">
		<div class="input-group">
		      <input type="text" class="form-control" placeholder="Pesquisar questões">
		      <span class="input-group-btn">
		        <button class="btn btn-default" type="button" name='btnbuscar'>Buscar</button>
		      </span>
		</div>
		</div>
	</div>
	
<?php
if (isset($_GET['pg'])) $pag = $_GET['pg']; else $pag = 1;
$sql = "SELECT * 
FROM (SELECT ROW_NUMBER() OVER(ORDER BY codquestao) AS NUMBER, * FROM questao) AS TBL
WHERE NUMBER BETWEEN (($pag - 1) * 10 + 1) AND ($pag * 10)
ORDER BY codquestao";
$q_listar = odbc_exec($conn,$sql);
$n_listar = odbc_num_rows($q_listar);
?>
		<table class='table table-hover'>
			<thead>
				<th>Quest&atilde;o</th>
				<th>A&ccedil;&atilde;o</th>
			</thead>
<?php
while($r_listar = odbc_fetch_array($q_listar)){
	$listas[$r_listar['codQuestao']] = $r_listar['textoQuestao'];
		echo "<tr><td class='col-sm-9'>".utf8_encode($r_listar['textoQuestao'])."</td><td class='col-sm-3'><a href='atualizar.php?codQuestao={$r_listar['codQuestao']}' name='btnEditar' class='btn btn-primary'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Editar</a> | <button type='button' value='deletar.php?codQuestao={$r_listar['codQuestao']}' name= 'btnDeletar' class='btn btn-danger btn-del' data-toggle='modal' data-target='.bs-example-modal-sm'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> Apagar</button></td></tr>\n";
	  
}
?>

		</table>
		<div class="msg-erro-fim"></div>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" value="Teste 0001"><span aria-hidden="true">&times;</span></button>
<?php
	$btnProx = $pag + 1;
	$btnAnt = $pag - 1;
?>
		<nav>
		  <ul class="pager">
		  	<li><a href="Listar.php?pg=1">Começo</a></li>
		    <li id="btn-anterior"><a href="Listar.php?pg=<?php echo $btnAnt; ?>"><span aria-hidden="true">&larr;</span> Anterior</a></li>
    		<li id="btn-proximo"><a href="Listar.php?pg=<?php echo $btnProx; ?>">Próximo <span aria-hidden="true">&rarr;</span></a></li>
		  </ul>
		</nav>
<?php
	if ($pag <= 1) {
		echo "<script>
			$(\"#btn-anterior\").addClass (\"disabled\");
			$(\"#btn-anterior a\").click (function() { return false; });;
		</script>";
	}

	if ($n_listar < 10) {
		echo "<script>
			$(\"#btn-proximo\").addClass (\"disabled\");
			$(\"#btn-proximo a\").click (function() { return false; });
		</script>";
	}

	if ($n_listar == 0) {
		echo "<script>
			$(\".msg-erro-fim\").html (\"<div class='alert alert-danger alert-dismissible' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button> <strong>Atenção!</strong> Nenhuma questão foi encontrada. </div>\");
		</script>";
	}
?>

	</div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">Excluir questão</h4>
      </div>
      <div class="modal-body">
        <p>Você realmente deseja excluir essa questão?</p>
      </div>
      <div class="modal-footer">
        <a href="" class="btn btn-danger btn-deletar">Excluir</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$('#myModal').on('shown.bs.modal', function () {
  		$('#myInput').focus()
	})

	$(document).ready(function(){
		$(".btn-del").click (function(){
			var href = ($(this).val())
	    	$(".btn-deletar").attr ('href', href);
		});
	});

</script>

<?php
	include('footer.php');
?>