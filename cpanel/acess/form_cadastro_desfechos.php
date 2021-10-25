<?php include 'head.php';?>

	<script type="text/javascript">
	
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTE DESFECHO? Se inativado, não poderá ser utilizado nem mesmo em visitas já agendadas.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTE DESFECHO?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

	</script> 
	
	<body>

	<div class='container-fluid'>
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'>Desfechos padrões para apontamentos</span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
		
		<?php 
		
		if (isset($_POST['acao'])){
			$acao = $_POST['acao'];
			
			if($acao == 'INATIVAR'){
				$DESFCODIGO = $_POST['desfcodigo'];
				$DESFupdate = mysql_query("UPDATE desfechos SET DESFSITUACAO = 0, DESFDTSITUACAO = '$datetime', DESFUSRSITUACAO = '$ident_session' WHERE DESFCODIGO = '$DESFCODIGO'") or print "<div class='error'><b>FALHA NA INATIVAÇÃO DO DESFECHO $DESFCODIGO<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";
				
				if($DESFupdate){
					echo "<div align='center' class='success'>Desfecho <b>$DESFCODIGO</b> inativado com sucesso.</div>";					
				}
			}
			
			if($acao == 'ATIVAR'){
				$DESFCODIGO = $_POST['desfcodigo'];
				$DESFupdate = mysql_query("UPDATE desfechos SET DESFSITUACAO = 1, DESFDTSITUACAO = '$datetime', DESFUSRSITUACAO = '$ident_session' WHERE DESFCODIGO = '$DESFCODIGO'") or print "<div class='error'><b>FALHA NA ATIVAÇÃO DO DESFECHO $DESFCODIGO<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";
				
				if($DESFupdate){
					echo "<div align='center' class='success'>Desfecho <b>$DESFCODIGO</b> ativado com sucesso.</div>";					
				}
			}
			
			if($acao == 'EDITAR'){
				$DESFCODIGO    = $_POST['desfcodigo'];
				
				$DESFEditSelect = mysql_query("SELECT * FROM desfechos WHERE DESFCODIGO = '$DESFCODIGO'") or print(mysql_error());
				$DESFTITULO    = mysql_result($DESFEditSelect,0,'DESFTITULO');
				$DESFDESCRICAO = mysql_result($DESFEditSelect,0,'DESFDESCRICAO');
				$DESFAPLICAVEL = mysql_result($DESFEditSelect,0,'DESFAPLICAVEL');
				
				switch($DESFAPLICAVEL){
					case '99': $desfaplicavel = "GENÉRICO"; break;
					case 'VC': $desfaplicavel = "Visitas a Clientes"; break;
					case 'AP': $desfaplicavel = "Atividades de Projetos"; break;
					case 'ET': $desfaplicavel = "Encerramento de Tickets"; break;
				}
				
				?>
				
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar Desfecho padrão&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cadastro_desfechos.php'>
							<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='editar_tipo' method='POST' action='form_cadastro_desfechos.php'>
						
						<div class='form-group'>
							<label class='col-sm-6 col-md-6 control-label'>Código:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='desfcodigo' type='text' onkeyup="maiuscula(this)" value='<?php echo $DESFCODIGO;?>' maxlength="10" readonly /></div>
						
							<label class='col-sm-1 col-md-1 control-label'>Aplicável a:</label>
							<div class='col-sm-3 col-md-3'>
								<select class='form-control' name='desfaplicavel' required >
									<option value='<?php echo $DESFAPLICAVEL;?>'><?php echo $desfaplicavel;?></option>
									<option value='99'>GENÉRICO</option>
									<option value='VC'>Visitas a clientes</option>
									<option value='AP'>Atividades de Projetos</option>
									<option value='ET'>Encerramento de Tickets</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Título:</label>
							<div class='col-sm-6 col-md-6'>
								<input class='form-control' name='desftitulo' type='text' id='desftitulo' value='<?php echo $DESFTITULO;?>' maxlength="50" required />
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Especificação:</label>
							<div class='col-sm-6 col-md-6'>
								<textarea class='form-control' name='desfdescricao' rows='5' maxlength='500' ><?php echo $DESFDESCRICAO;?></textarea>
							</div>
						</div>
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
						<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						
					</form>
				</fieldset><br>
				<?php
			}
			
			if($acao == 'CONFIRMAR_EDICAO'){
				$DESFCODIGO    = $_POST['desfcodigo'];
				$DESFTITULO    = $_POST['desftitulo'];
				$DESFDESCRICAO = $_POST['desfdescricao'];
				$DESFAPLICAVEL = $_POST['desfaplicavel'];
				
				$DESFverifica  = mysql_query("SELECT * FROM desfechos WHERE DESFTITULO = '$DESFTITULO' AND DESFAPLICAVEL IN('$DESFAPLICAVEL') AND DESFCODIGO NOT IN('$DESFCODIGO')") or print (mysql_error());
				$DESFregistros = mysql_num_rows($DESFverifica);
				
				if($DESFregistros > 0){
					echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe registro com este Título.</div>";
				
				}else{
					$DESFupdate = mysql_query("UPDATE desfechos SET DESFAPLICAVEL = '$DESFAPLICAVEL', 
																														 DESFTITULO = '$DESFTITULO',
																													DESFDESCRICAO = '$DESFDESCRICAO' WHERE DESFCODIGO='$DESFCODIGO'") or print "<div class='error'><b>FALHA NA ALTERAÇÃO DE DADOS DO DESFECHO $DESFCODIGO<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";
					if($DESFupdate){
						echo "<div id='alert' align='center' class='success'>Desfecho <b>$DESFCODIGO</b> alterado com sucesso.</div>";
					}
				}
			}
			
			if($acao == 'ADD'){
				?>
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir Desfecho Padrão&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cadastro_desfechos.php'>
							<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='incluir_desf' method='POST' action='form_cadastro_desfechos.php'>
						
						<div class='form-group'>
							<label class='col-sm-6 col-md-6 control-label'>Código:</label>
							<div class='col-sm-2 col-md-2'><input class='form-control' name='desfcodigo' type='text' id='tdcodigo' onkeyup="maiuscula(this)" maxlength="10" required /></div>
						
							<label class='col-sm-1 col-md-1 control-label'>Aplicável a:</label>
							<div class='col-sm-3 col-md-3'>
								<select class='form-control' name='desfaplicavel' required >
									<option value=''>Selecione...</option>
									<option value='99'>GENÉRICO</option>
									<option value='VC'>Visitas a clientes</option>
									<option value='AP'>Atividades de Projetos</option>
									<option value='ET'>Encerramento de Tickets</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-4 col-md-4 control-label'>Título:</label>
							<div class='col-sm-8 col-md-8'>
								<input class='form-control' name='desftitulo' type='text' id='desftitulo' maxlength="100" required />
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-4 col-md-4 control-label'>Especificação:</label>
							<div class='col-sm-8 col-md-8'>
								<textarea class='form-control' name='desfdescricao' rows='5' maxlength='500' ></textarea>
							</div>
						</div>
						
						<input name='acao' type='hidden' value='INCLUIR'/></input>
						<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						
					</form>
				</fieldset><br>
				<?php
			}
			
			if($acao == 'INCLUIR'){
				
				$DESFCODIGO    = $_POST['desfcodigo'];
				$DESFAPLICAVEL = $_POST['desfaplicavel'];
				$DESFTITULO    = $_POST['desftitulo'];
				$DESFDESCRICAO = $_POST['desfdescricao'];
				
				$DESFverifica  = mysql_query("SELECT * FROM desfechos WHERE DESFCODIGO = '$DESFCODIGO' OR (DESFTITULO = '$DESFTITULO' AND DESFAPLICAVEL = '$DESFAPLICAVEL')") or print (mysql_error());
				$DESFregistros = mysql_num_rows($DESFverifica);
				
				if($DESFregistros > 0){
					echo "<div align='center' class='warning'>INCLUSÃO NÃO PROCESSADA: Código ou Descrição já utilizados.</div>";
				
				}else{
					$DESFinsert = mysql_query("INSERT INTO desfechos (IDDOMINIO,IDADMINISTRADOR,DESFCODIGO,DESFAPLICAVEL,DESFTITULO,DESFDESCRICAO,DESFSITUACAO,DESFDTSITUACAO,DESFUSRSITUACAO,DESFCADASTRO,DESFCADBY)
					VALUES('$dominio_session','$adm_session','$DESFCODIGO','$DESFAPLICAVEL','$DESFTITULO','$DESFDESCRICAO',1,'$datetime','$ident_session','$datetime','$ident_session')") 
					or print "<div class='error'><b>FALHA NA INSERÇÃO DO DESFECHO $DESFCODIGO<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";
				
					if($DESFinsert){
						echo "<div id='alert' align='center' class='success'>Desfecho Padrão <b>$DESFCODIGO</b> inserido com sucesso.</div>";				
					}
				}
			}
		}//Fim de ações...

		$DESFselect = mysql_query("SELECT * FROM desfechos ORDER BY DESFCODIGO");
		$DESFtotal  = mysql_num_rows($DESFselect);

		if($DESFtotal > 0){
			echo "
			<table id='DataTables' class='table table-responsive table-action table-condensed table-striped'>
				<thead>
					<tr>
						<th width='2%' data-orderable='false'><div align='center'>   </div></th>
						<th width='20%'><div align='center'>Código       </div></th>
						<th width='50%'><div>Descrição    </div></th>
						<th width='20%'><div align='center'>Aplicável a</div></th>
						<th width='3%' data-orderable='false'>
							<div align='center'>
								<form name='novo' method='POST' action='form_cadastro_desfechos.php'>
									<input name='acao'   type='hidden' value='ADD'/>
									<input name='editar' class='inputsrc' type='image' img src='../imagens/add3.png' title='Incluir'/>
								</form>
							</div>
						</th>
					</tr>
				</thead>
				<tbody>";
					
				while($linha = mysql_fetch_array($DESFselect)){					
					$DESFID        = $linha["DESFID"];
					$DESFAPLICAVEL = $linha["DESFAPLICAVEL"];
					$DESFCODIGO    = $linha["DESFCODIGO"];
					$DESFTITULO    = $linha["DESFTITULO"];
					$DESFDESCRICAO = $linha["DESFDESCRICAO"];
					$DESFSITUACAO  = $linha["DESFSITUACAO"];
					
					switch($DESFAPLICAVEL){
						case '99': $desfaplicavel = "GENÉRICO"; break;
						case 'VC': $desfaplicavel = "Visitas a Clientes"; break;
						case 'AP': $desfaplicavel = "Atividades de Projetos"; break;
						case 'ET': $desfaplicavel = "Encerramento de Tickets"; break;
					}
					
					switch($DESFSITUACAO){
						case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='Inativo'/>"; break;
						case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='Ativo'/>"; break;
					}
					
					echo "
						<tr>
							<td><div align='center'>$situacao</div></td>
							<td><div align='center'><b>$DESFCODIGO</b></div></td>
							<td><div title='$DESFDESCRICAO'>$DESFTITULO</div></td>
							<td><div align='center'>$desfaplicavel</div></td>
							<td>
								<div id='cssmenu' align='center'>
									<ul>
										<li class='has-sub'><a href='#'><span></span></a>
											<ul>
												<li>
													<form name='editar' method='POST' action='form_cadastro_desfechos.php'>
														<input name='desfcodigo' type='hidden' value='$DESFCODIGO'/>
														<input name='acao'       type='hidden' value='EDITAR'/>
														<input class='inputsrc' name='editar' type='image'  src='../imagens/edit.png' title='Editar'/>
													</form>
												</li>";
								
												if($DESFSITUACAO == 1){
													echo"
													<li>
														<form name='inativar' method='POST' action='form_cadastro_desfechos.php' onsubmit='return confirma_inativar();'>
															<input name='desfcodigo' type='hidden' value='$DESFCODIGO'/>
															<input name='acao'       type='hidden' value='INATIVAR'/>
															<input class='inputsrc' name='excluir' type='image' src='../imagens/ativar.png' title='Inativar'/>
														</form>
													</li>";
												
												}
												
												if($DESFSITUACAO == 0){
													echo"
													<li>
														<form name='ativar' method='POST' action='form_cadastro_desfechos.php' onsubmit='return confirma_ativar();'>
															<input name='desfcodigo' type='hidden' value='$DESFCODIGO'/>
															<input name='acao'       type='hidden' value='ATIVAR'/>
															<input class='inputsrc' name='excluir' type='image' src='../imagens/ativar.png' title='Ativar'/>
														</form>
													</li>";
												}
												
												echo"
											</ul>
										</li>
									</ul>
								</div>
							</td>
						</tr>";
				}
				
				echo "<tbody>
				</table>";
		
			}else{
				 echo "<div align='center' class='info'>Nenhum registro!</div>
				 <div align='center'>
					 <form name='novo' method='POST' action='form_cadastro_desfechos.php'>
						<input name='acao' type='hidden' value='ADD'/>
						<input name='editar' class='inputsrc' type='image' img src='../imagens/add3.png' title='Incluir'/>
					</form>
				</div>";
			}
		?>
	</div>
	</div>
	</div>
	</body>
</html>