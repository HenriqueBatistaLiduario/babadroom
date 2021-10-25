<?php include 'head.php';?>
<html>
	<title>Consultas SQL</title>
	
	<body>
		<div class='container-fluid'>
			<div class='panel panel-default'>

				<div class='panel-heading'>
					<span class='subpageName'>Executar comando em SQL</span>

					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
					
				</div>

				<div class='panel-body'>

					<?php
					
					if(isset($_POST['acao'])){
						$acao = $_POST['acao'];
						
						if($acao == 'EXECUTAR'){
							$sqlsentenca = $_POST['sqlsentenca'];
							
							mysqli_query($con,"$sqlsentenca") or print "<div align='center' class='warning'>".(mysql_error())."</div>";  
							$LinhasAfetadas = mysqli_affected_rows();
							
			        if($LinhasAfetadas >= 0){
								echo "<div id='alert' class='success' align='center'>Comando SQL executado com sucesso!<br>
								<b>$LinhasAfetadas linhas afetadas</b></div>";
							}
						}		
					}
					
					?>

					<div class='info'><font color='red'>ATENÇÃO:</font> A execução de procedimento diretamente no Banco de Dados é de inteira responsabilidade do usuário</div>
					<form class='form-horizontal' name='query' method='POST' action='executar_sql.php' onsubmit='return confirma_execucao()'>
						<div class='form-group'>
							<div class='col-sm-12'><textarea class='form-control' rows='30' name='sqlsentenca' required ></textarea></div>
						</div>
						<input name='acao' type='hidden' value='EXECUTAR'/>
						<div align='right'><input class='but but-azul' type='submit' value='      Executar      '/></div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html> 