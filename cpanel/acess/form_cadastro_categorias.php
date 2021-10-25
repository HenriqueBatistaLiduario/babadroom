<?php include 'head.php';?>

	<script type="text/javascript">
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTA CATEGORIA? Chamados com esta categoria não serão afetados.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTA CATEGORIA?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		$(document).ready(function(){			
			$("#catvalorhora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#catcustohora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});			
		});

	</script> 
	
<body>
	<div class='container-fluid'>
	
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'>Categorias</span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
	<?php 
		
		if (isset($_POST['acao'])){
			$acao = $_POST['acao'];
			
			if ($acao == 'INATIVAR'){
				$CATCODIGO = $_POST['catcodigo'];
				$CATupdate = mysql_query("UPDATE categorias SET CATSTATUS = 0, CATDTSTATUS = '$datetime', CATUSRSTATUS = '$ident_session' WHERE CATCODIGO = '$CATCODIGO'") or print(mysql_error());
				
				if($CATupdate){
					echo "<div align='center' class='success'>Categoria <b>$CATCODIGO</b> desativada.</div>";
					
				}else{
					echo "<div align='center' class='error'>Categoria <b>$CATCODIGO</b> não pode ser desativada.</div>";
				}
			}
			
			if ($acao == 'ATIVAR'){
				$CATCODIGO = $_POST['catcodigo'];
				$CATupdate = mysql_query("UPDATE categorias SET CATSTATUS=1,CATDTSTATUS='$datetime',CATUSRSTATUS='$ident_session' WHERE CATCODIGO = '$CATCODIGO'") or print(mysql_error());
				
				if($CATupdate){
					echo "<div id='alert' align='center' class='success'>Categoria <b>$CATCODIGO</b> ativada com sucesso.</div>";
					
				}else{
					echo "<div align='center' class='error'>Categoria <b>$CATCODIGO</b> não pode ser ativada.</div>";
				}
			}
			
			if($acao == 'EDITAR'){
				$CATCODIGO = $_POST['catcodigo'];
				
				$CATSelectEdit = mysql_query("SELECT * FROM categorias WHERE CATCODIGO = '$CATCODIGO'") or print(mysql_error());
				$CATDESCRICAO = mysql_result($CATSelectEdit,0,"CATDESCRICAO");
				$CATAPLICAVEL = mysql_result($CATSelectEdit,0,"CATAPLICAVEL");
				$CATVALORHORA = mysql_result($CATSelectEdit,0,"CATVALORHORA");
				$CATCUSTOHORA = mysql_result($CATSelectEdit,0,"CATCUSTOHORA");
				
				?>
				
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar Categoria&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cadastro_categorias.php'>
							<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='editar_categoria' method='POST' action='form_cadastro_categorias.php'>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Código:</label>
							<div class='col-sm-2'><input class='form-control' name='catcodigo' type='text' id='catcodigo' onkeyup="maiuscula(this)" value='<?php echo $CATCODIGO;?>' maxlength="10" readonly /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Aplicável a:</label>
							<div class='col-sm-6'>
								<select class='form-control' name='cataplicavel' required >
									<option value='<?php echo $CATAPLICAVEL;?>'><?php echo $CATAPLICAVEL;?></option>
									<option value=1>Projetos</option>
									<option value=2>Atendimentos</option>
									<option value=3>Ambos</option>
									<option value=51>BlogPosts</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Título:</label>
							<div class='col-sm-6'><input class='form-control' name='catdescricao' type='text' id='catdescricao' value='<?php echo $CATDESCRICAO;?>' maxlength="50" required /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Valor/Hora (R$):</label>
							<div class='col-sm-2'><input class='form-control' name='catvalorhora' id='catvalorhora' type='text' value='<?php echo $CATVALORHORA;?>' required title='Valor/Hora cobrado pela Administradora na Prestação de Serviço desta Categoria'/></div>
							
							<label class='col-sm-2 control-label'>Custo/Hora (R$):</label>
							<div class='col-sm-2'><input class='form-control' name='catcustohora' id='catcustohora' type='text' value='<?php echo $CATCUSTOHORA;?>' required title='Custo/Hora da Administradora na Prestação de Serviço desta Categoria'/></div>
						</div>
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
						<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						
					</form>
				</fieldset><br>
				<?php
			}
			
			if ($acao == 'CONFIRMAR_EDICAO'){
				$CATCODIGO    = $_POST['catcodigo'];
				$CATDESCRICAO = $_POST['catdescricao'];
				$CATAPLICAVEL = $_POST['cataplicavel'];
				$CATVALORHORA = $_POST['catvalorhora'];
				$CATCUSTOHORA = $_POST['catcustohora'];
				
				$CATverifica  = mysql_query("SELECT * FROM categorias WHERE CATDESCRICAO='$CATDESCRICAO'") or print (mysql_error());
				$CATregistros = mysql_num_rows($CATverifica);
				
				if($CATregistros > 0){
					echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe categoria com esta Descrição.</div>";
				
				}else{
					$CATupdate = mysql_query("UPDATE categorias SET CATAPLICAVEL = '$CATAPLICAVEL', 
					                                                CATDESCRICAO = '$CATDESCRICAO', 
																													CATVALORHORA = '$CATVALORHORA',
																													CATCUSTOHORA = '$CATCUSTOHORA' WHERE CATCODIGO='$CATCODIGO'") or print (mysql_error());
					
					if($CATupdate){
					echo "<div id='alert' align='center' class='success'>Categoria <b>$CATCODIGO</b> alterada com sucesso.</div>";

					}else{
					echo "<div align='center' class='error'>Categoria <b>$CATCODIGO</b> não pode ser editada.</div>";
					}
				}
			}
			
			if ($acao == 'ADD'){
				?>
				
				<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir Categoria&nbsp;&nbsp;</legend>
					<div align='right'>
						<form name='fechar' method='POST' action='form_cadastro_categorias.php'>
							<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
						</form>
					</div>
					
					<form class='form-horizontal' name='categoria' method='POST' action='form_cadastro_categorias.php'>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Código:</label>
							<div class='col-sm-2'><input class='form-control' name='catcodigo' type='text' id='catcodigo' onkeyup="maiuscula(this)" maxlength="10" required /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Aplicável a:</label>
							<div class='col-sm-6'>
								<select class='form-control' name='cataplicavel' required >
									<option value=''>Selecione...</option>
									<option value=1>Projetos</option>
									<option value=2>Atendimentos</option>
									<option value=3>Ambos</option>
									<option value=51>BlogPosts</option>
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Título:</label>
							<div class='col-sm-6'><input class='form-control' name='catdescricao' type='text' id='catdescricao' maxlength="50" required /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Valor/Hora(R$):</label>
							<div class='col-sm-2'><input class='form-control' name='catvalorhora' id='catvalorhora' type='text' value='0.00' required title='Valor/Hora cobrado pela Administradora na Prestação de Serviço desta Categoria'/></div>
							
							<label class='col-sm-6 control-label'>Custo/Hora(R$):</label>
							<div class='col-sm-2'><input class='form-control' name='catcustohora' id='catcustohora' type='text' value='0.00' required title='Custo/Hora da Administradora na Prestação de Serviço desta Categoria'/></div>
						</div>
						
						<input name='acao' type='hidden' value='SALVAR'/></input>
						<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						
					</form>
				</fieldset><br>
				<?php
			}
			
			if ($acao == 'SALVAR'){
				
				$CATCODIGO    = $_POST['catcodigo'];
				$CATDESCRICAO = $_POST['catdescricao'];
				$CATAPLICAVEL = $_POST['cataplicavel'];
				$CATVALORHORA = $_POST['catvalorhora'];
				$CATCUSTOHORA = $_POST['catcustohora'];
				
				$CATverifica = mysql_query("SELECT * FROM categorias WHERE CATCODIGO = '$CATCODIGO' OR CATDESCRICAO = '$CATDESCRICAO'") or print (mysql_error());
				$CATregistros= mysql_num_rows($CATverifica);
				
				if($CATregistros > 0){
					echo "<div align='center' class='warning'>CADASTRO NÃO CONFIRMADO: Código ou Descrição já utilizados.</div>";
				
				}else{
					$CATinsert = mysql_query("INSERT INTO categorias (CATCODIGO,CATAPLICAVEL,CATDESCRICAO,CATSTATUS,CATDTSTATUS,CATUSRSTATUS,CATVALORHORA,CATCUSTOHORA,CATCADASTRO,CATCADBY)
					VALUES ('$CATCODIGO','$CATAPLICAVEL','$CATDESCRICAO',0,'$datetime','$ident_session','$CATVALORHORA','$CATCUSTOHORA','$datetime','$ident_session')") or print (mysql_error());
				
					if($CATinsert){
						echo "<div id='alert' align='center' class='success'>Categoria <b>$CATCODIGO</b> inserida com sucesso.</div>";
					
					}else{
						echo "<div align='center' class='error'>Erro na inserção da Categoria <b>$CATCODIGO</b><br>Entre em contato com o Administrador deste domínio.</div>";
					}
				}
			}
		}//Fim de ações

				$CATselect = mysql_query("SELECT * FROM categorias ORDER BY CATDESCRICAO");
				$CATtotal = mysql_num_rows($CATselect);

				if($CATtotal > 0){
					echo "
						<table id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
						<thead>
							<tr>
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='20%'><div align='center'>Código       </div></th>
								<th width='35%'><div>Descrição    </div></th>
								<th width='20%'><div align='center'>Aplicável a  </div></th>
								<th width='10%'><div align='center'>Custo/Hora(R$)</div></th>
								<th width='10%'><div align='center'>Valor/Hora(R$)</div></th>
								<th width='3%' data-orderable='false'>
									<div align='center'>
										<form name='novo' method='POST' action='form_cadastro_categorias.php'>
											<input name='acao'  type='hidden' value='ADD'/>
											<input name='editar' class='inputsrc' type='image' img src='../imagens/add3.png' title='Incluir Categoria'/>
										</form>
									</div>
								</th>
							</tr>
						</thead>
					<tbody>";
							
					while($linha = mysql_fetch_array($CATselect)){						
						$CATID        = $linha["CATID"];
						$CATAPLICAVEL = $linha["CATAPLICAVEL"];
						$CATCODIGO    = $linha["CATCODIGO"];
						$CATDESCRICAO = $linha["CATDESCRICAO"];
						$CATVALORHORA = $linha["CATVALORHORA"];
						$CATCUSTOHORA = $linha["CATCUSTOHORA"];
						$CATSTATUS    = $linha["CATSTATUS"];
						$CATDTSTATUS  = $linha["CATDTSTATUS"];
						$CATUSRSTATUS = $linha["CATUSRSTATUS"];
						
						switch($CATAPLICAVEL){
							case 1: $aplicavel  = "Projetos"; break;
							case 2: $aplicavel  = "Atendimentos"; break;
							case 3: $aplicavel  = "Ambos"; break;
							case 51: $aplicavel = "BlogPosts"; break;
						}
						
						$catvalorhora = number_format($CATVALORHORA,2,",",".");
			      $catcustohora = number_format($CATCUSTOHORA,2,",",".");
						
						switch($CATSTATUS){
							case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='Inativo'/>"; break;
							case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='Ativo'/>"; break;
						}
						
						echo "
							<tr>
								<td><div align='center'>$situacao</div></td>
								<td><div align='center'><b>$CATCODIGO</b></div></td>
								<td><div>$CATDESCRICAO</div></td>
								<td><div align='center'>$aplicavel</div></td>
								<td><div align='center'>$catcustohora</div></td>
								<td><div align='center'>$catvalorhora</div></td>
								<td>
									<div id='cssmenu' align='center'>
										<ul>
											<li class='has-sub'><a href='#'><span></span></a>
												<ul>
													<li>
														<form name='editar' method='POST' action='form_cadastro_categorias.php'>
															<input name='catcodigo' type='hidden' value='$CATCODIGO'/>
															<input name='acao'      type='hidden' value='EDITAR'/>
															<input class='inputsrc' name='editar' type='image'  src='../imagens/edit.png' title='Editar'/>
														</form>
													</li>";
									
													if($CATSTATUS == 1){
														echo"
														<li>
															<form name='inativar' method='POST' action='form_cadastro_categorias.php'>
																<input name='catcodigo' type='hidden' value='$CATCODIGO'/>
																<input name='acao'      type='hidden' value='INATIVAR'/>
																<input class='inputsrc' name='excluir' type='image' onclick='return confirma_inativar();' src='../imagens/ativar.png' title='Inativar'/></input>
															</form>
														</li>";
													
													}else{
														echo"
														<li>
															<form name='ativar' method='POST' action='form_cadastro_categorias.php'>
																<input name='catcodigo' type='hidden' value='$CATCODIGO'/>
																<input name='acao'      type='hidden' value='ATIVAR'/>
																<input class='inputsrc' name='excluir' type='image' onclick='return confirma_ativar();' src='../imagens/ativar.png' title='Ativar'/></input>
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
					 echo "<div align='center' class='info'>Nenhuma categoria cadastrada!</div>
					 <div align='center'>
						 <form name='novo' method='POST' action='form_cadastro_categorias.php'>
							<input name='acao' type='hidden' value='ADD'/>
							<input name='editar' class='inputsrc' type='image' img src='../imagens/add3.png' title='Incluir Categoria'/>
						</form>
					</div>";
				} 
				
				?>
				
			</div>
		</div>
	</div>
</body>
</html>