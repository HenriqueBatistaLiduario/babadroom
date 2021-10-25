<?php include "head.php";?>

	<script type="text/javascript">
		
		$(document).ready(function(){			
			$("#eqcustohora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
		});
		
	</script>

<body>
<div class='container-fluid'>
	<div class='panel panel-default'>

		<div class='panel-heading'>
			<span class='subpageName'>Equipes de Atendimento</span>

			<ul class='pull-right list-inline'>
				<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
			</ul>
		</div>

		<div class='panel-body'>

			<?php 
			 
			if(isset($_POST['acao'])){
				$acao = $_POST['acao'];

				if($acao == 'ADD'){
			 
					?>
					
					<fieldset><legend class='subpageName'>&nbsp;&nbsp;Criar equipe&nbsp;&nbsp;</legend>
						<div align='right'>
							<form name='voltar' method='POST' action='form_cadastro_equipes.php'>
								<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
							</form>
						</div>
				
						<form class='form-horizontal' name='incluir' method='POST' action='form_cadastro_equipes.php'>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Código:</label>
								<div class='col-sm-2'><input class='form-control' name='eqcodigo' type='text' id='eqcodigo' onkeyup='maiuscula(this)' maxlength='10' required /></div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Nome:</label>
								<div class='col-sm-4'><input class='form-control' name="eqnome" type="text" id="eqnome" maxlength="50" required /></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Apresentação:</label>
								<div class='col-sm-4'><textarea class='form-control' name='eqdescricao' type='text' id='eqdescricao' rows='5' cols='100' maxlength='500' placeholder='Breve descrição sobre a área de atuação desta Equipe, bem como sua capacidade técnica...'></textarea></div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Líder:</label>
								<div class='col-sm-4'>
									<select class='form-control' name='eqresponsavel' id='eqresponsavel' required >
										<?php
											
											echo "<option value=''>Selecione...</option>";
											
											$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERSTATUS = 1 AND USERPERFIL IN('PS','GP','ADM') AND USERIDENTIFICACAO NOT IN('mestre','master')") or print (mysql_error());
											
											while($linha = mysql_fetch_array($USERselect)){
												
												$USERIDENTIFICACAO = $linha["USERIDENTIFICACAO"];
												$USERLOGIN  = $linha["USERLOGIN"];
												$USERNOME   = $linha["USERNOME"];
												$USERPERFIL = $linha["USERPERFIL"];

												echo "<option value='$USERIDENTIFICACAO'>$USERNOME ($USERIDENTIFICACAO) | $USERPERFIL</option>";
												
											}
											
										?>
									</select>
								</div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Custo/Hora(R$):</label>
								<div class='col-sm-2'><input class='form-control' name='eqcustohora' id='eqcustohora' type="text" maxlength="10" value='0.00' required title='Custo/Hora desta Equipe para a Administradora.'/></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Horário de Trabalho:</label>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhoini1' type='time' value='08:00' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhofim1' type='time' value='12:00' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhoini2' type='time' value='12:01' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhofim2' type='time' value='18:00' required /></div>
							</div>
						
							<input name='acao' type='hidden' value='SALVAR'/>
							<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						</form>
					</fieldset><br>
					 
					<?php
				}
				
				if($acao == 'SALVAR'){
					$EQCODIGO      = $_POST['eqcodigo'];
					$EQNOME        = $_POST['eqnome'];
					$EQDESCRICAO   = $_POST['eqdescricao'];
					$EQRESPONSAVEL = $_POST['eqresponsavel'];
					$EQCUSTOHORA   = $_POST['eqcustohora'];
					$EQHRTRABALHOINI1 = $_POST['eqhrtrabalhoini1'];
					$EQHRTRABALHOFIM1 = $_POST['eqhrtrabalhofim1'];
					$EQHRTRABALHOINI2 = $_POST['eqhrtrabalhoini2'];
					$EQHRTRABALHOFIM2 = $_POST['eqhrtrabalhofim2'];		
					
					$EQverifica  = mysql_query("SELECT * FROM equipes WHERE EQCODIGO='$EQCODIGO' OR EQNOME ='$EQNOME'") or print (mysql_error());
					$EQregistros = mysql_num_rows($EQverifica);

					if ($EQregistros != 0){
						echo "<div id='alert' align='center' class='warning'>Código ou Nome de Equipe já cadastrados.</div>";

					}else{
						$EQinsert = mysql_query("INSERT INTO equipes (IDDOMINIO,IDADMINISTRADOR,EQCODIGO,EQNOME,EQDESCRICAO,EQLIDER,EQSITUACAO,EQDTSITUACAO,EQUSRSITUACAO,EQCUSTOHORA,EQHRTRABALHOINI1,EQHRTRABALHOFIM1,EQHRTRABALHOINI2,EQHRTRABALHOFIM2,EQCADASTRO,EQCADBY) VALUES ('$dominio_session','$adm_session','$EQCODIGO','$EQNOME','$EQDESCRICAO','$EQRESPONSAVEL',1,'$datetime','$ident_session','$EQCUSTOHORA','$EQHRTRABALHOINI1','$EQHRTRABALHOFIM1','$EQHRTRABALHOINI2','$EQHRTRABALHOFIM2','$datetime','$ident_session')") or print (mysql_error());
						
						if($EQinsert){
							
							$EUinsert1 = mysql_query("INSERT INTO equipes_usuarios (IDDOMINIO,IDADMINISTRADOR,EUCODEQUIPE,EUIDUSUARIO,EUSITUACAO,EUDTSITUACAO,EUUSRSITUACAO,EUCADASTRO,EUCADBY) VALUES ('$dominio_session','$adm_session','$EQCODIGO','mestre',1,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysql_error());
							
							$EUinsert2 = mysql_query("INSERT INTO equipes_usuarios (IDDOMINIO,IDADMINISTRADOR,EUCODEQUIPE,EUIDUSUARIO,EUSITUACAO,EUDTSITUACAO,EUUSRSITUACAO,EUCADASTRO,EUCADBY) VALUES ('$dominio_session','$ident_session','$EQCODIGO','$EQRESPONSAVEL',1,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysql_error());
						
							echo "<div id='alert' align='center' class='success'>Equipe <b>$EQCODIGO</b> cadastrada com sucesso.</div>";
						}
					}
				}
				
				if($acao == 'EDITAR'){
					
					$EQCODIGO = $_POST['eqcodigo'];
					
					$EQSelectEdit = mysql_query("SELECT * FROM equipes WHERE EQCODIGO = '$EQCODIGO'") or print(mysql_error());
					$EQNOME           = mysql_result($EQSelectEdit,0,"EQNOME");
					$EQDESCRICAO      = mysql_result($EQSelectEdit,0,"EQDESCRICAO");
					$EQRESPONSAVEL    = mysql_result($EQSelectEdit,0,"EQLIDER");
					$EQCUSTOHORA      = mysql_result($EQSelectEdit,0,"EQCUSTOHORA");
					$EQHRTRABALHOINI1 = mysql_result($EQSelectEdit,0,"EQHRTRABALHOINI1");
					$EQHRTRABALHOFIM1 = mysql_result($EQSelectEdit,0,"EQHRTRABALHOFIM1");
					$EQHRTRABALHOINI2 = mysql_result($EQSelectEdit,0,"EQHRTRABALHOINI2");
					$EQHRTRABALHOFIM2 = mysql_result($EQSelectEdit,0,"EQHRTRABALHOFIM2");	
					
					$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$EQRESPONSAVEL'") or print(mysql_error());
					$eqresponsavel = mysql_result($USERselect,0,"USERNOME");
					$USERPERFIL = mysql_result($USERselect,0,"USERPERFIL");
			 
					?>
					
					<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar equipe&nbsp;&nbsp;</legend>
						<div align='right'>
							<form name='voltar' method='POST' action='form_cadastro_equipes.php'>
								<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
							</form>
						</div>
				
						<form class='form-horizontal' name='editar' method='POST' action='form_cadastro_equipes.php'>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Código:</label>
								<div class='col-sm-2'><input class='form-control' name='eqcodigo' type='text' id='eqcodigo' onkeyup='maiuscula(this)' maxlength='10' value='<?php echo $EQCODIGO;?>' readonly /></div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Nome:</label>
								<div class='col-sm-4'><input class='form-control' name="eqnome" type="text" id="eqnome" maxlength="50" value='<?php echo $EQNOME;?>' required /></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Apresentação:</label>
								<div class='col-sm-4'>
									<textarea class='form-control' name='eqdescricao' type='text' id='eqdescricao' rows='5' cols='100' maxlength='500' placeholder='Breve descrição sobre a área de atuação desta Equipe, bem como sua capacidade técnica...'><?php echo $EQDESCRICAO;?></textarea>
								</div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Líder:</label>
								<div class='col-sm-4'>
									<select class='form-control' name='eqresponsavel' id='eqresponsavel' required >
									  <option value='<?php echo $EQRESPONSAVEL;?>'><?php echo "$eqresponsavel ($EQRESPONSAVEL) | $USERPERFIL";?></option>
									
										<?php
										
										$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERSTATUS = 1 AND USERPERFIL IN('PS','GP','ADM') AND USERIDENTIFICACAO <> 'mestre'") or print (mysql_error());
										
										while($linha = mysql_fetch_array($USERselect)){
											
											$USERIDENTIFICACAO = $linha["USERIDENTIFICACAO"];
											$USERLOGIN  = $linha["USERLOGIN"];
											$USERNOME   = $linha["USERNOME"];
											$USERPERFIL = $linha["USERPERFIL"];

											echo "<option value='$USERIDENTIFICACAO'>$USERNOME ($USERIDENTIFICACAO) | $USERPERFIL</option>";
										}
										
										?>
									</select>
								</div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Custo/Hora(R$):</label>
								<div class='col-sm-2'><input class='form-control' name='eqcustohora' id='eqcustohora' type="text" maxlength="10" value='<?php echo $EQCUSTOHORA;?>' required title='Custo/Hora desta Equipe para a Administradora.'/></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Horário de Trabalho:</label>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhoini1' type='time' value='<?php echo $EQHRTRABALHOINI1;?>' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhofim1' type='time' value='<?php echo $EQHRTRABALHOFIM1;?>' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhoini2' type='time' value='<?php echo $EQHRTRABALHOINI2;?>' required /></div>
								<div class='col-sm-1'><input class='form-control' name='eqhrtrabalhofim2' type='time' value='<?php echo $EQHRTRABALHOFIM2;?>' required /></div>
							</div>
						
							<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/>
							<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						</form>
					</fieldset><br>
					 
					<?php
				}
				
				if($acao == 'CONFIRMAR_EDICAO'){
					$EQCODIGO      = $_POST['eqcodigo'];
					$EQNOME        = $_POST['eqnome'];
					$EQDESCRICAO   = $_POST['eqdescricao'];
					$EQRESPONSAVEL = $_POST['eqresponsavel'];
					$EQCUSTOHORA   = $_POST['eqcustohora'];
					$EQHRTRABALHOINI1 = $_POST['eqhrtrabalhoini1'];
					$EQHRTRABALHOFIM1 = $_POST['eqhrtrabalhofim1'];
					$EQHRTRABALHOINI2 = $_POST['eqhrtrabalhoini2'];
					$EQHRTRABALHOFIM2 = $_POST['eqhrtrabalhofim2'];		
					
					$EQverifica  = mysql_query("SELECT * FROM equipes WHERE EQNOME ='$EQNOME' AND EQCODIGO <> '$EQCODIGO'") or print (mysql_error());
					$EQregistros = mysql_num_rows($EQverifica);

					if ($EQregistros != 0){
						echo "<div id='alert' align='center' class='warning'>Nome de Equipe já existente. Não pode haver mais de uma equipe com o mesmo nome.</div>";
						
					}else{
						$EQupdate = mysql_query("UPDATE equipes SET EQNOME = '$EQNOME',
						                                       EQDESCRICAO = '$EQDESCRICAO',
																								       EQLIDER = '$EQRESPONSAVEL',
																									 EQCUSTOHORA = '$EQCUSTOHORA',
																							EQHRTRABALHOINI1 = '$EQHRTRABALHOINI1',
																							EQHRTRABALHOFIM1 = '$EQHRTRABALHOFIM1',
																							EQHRTRABALHOINI2 = '$EQHRTRABALHOINI2',
																							EQHRTRABALHOFIM2 = '$EQHRTRABALHOFIM2' WHERE EQCODIGO = '$EQCODIGO'") or print(mysql_error());
					  if($EQupdate){
							echo "<div id='alert' class='success' align='center'>Alteração realizada com sucesso.</div>";
						
						}else{
							echo "<div id='alert' class='error' align='center'>Alteração não realizada.</div>";
						}
					}
				}
				
				if($acao == 'ENVIAR_MSGDEQUIPE'){
								
					$MSGAUTO = 1;
					$MAORIGEM = 3;
					$MSGDEQUIPE = 1;
					
					$MACODIGO = 'SGA091';
					
					$MSGDDESTINATARIO = $_POST['msgddestinatario'];
					$MSGDMENSAGEM     = $_POST['msgdmensagem'];
					
					include 'msgauto.php';
					
					if($mail->Send()){
						echo "<div align='center' id='alert' class='success'>Mensagem direta enviada com sucesso.</div>";
					}	
				}
				
				if($acao == 'INATIVAR'){
					$EQCODIGO = $_POST['eqcodigo'];
					$EQupdate = mysql_query("UPDATE equipes SET EQSITUACAO=0,EQDTSITUACAO='$datetime',EQUSRSITUACAO='$ident_session' WHERE EQCODIGO = '$EQCODIGO'") or print(mysql_error());
					
					if($EQupdate){
						echo "<div id='alert' align='center' class='success'>Equipe <b>$EQCODIGO</b> inativada com sucesso.</div>";
						
					}else{
						echo "<div id='alert' align='center' class='error'>Equipe <b>$EQCODIGO</b> não pode ser inativada.</div>";
					}
				}
				
				if($acao == 'ATIVAR'){
					$EQCODIGO = $_POST['eqcodigo'];
					$EQupdate = mysql_query("UPDATE equipes SET EQSITUACAO=1,EQDTSITUACAO='$datetime',EQUSRSITUACAO='$ident_session' WHERE EQCODIGO = '$EQCODIGO'") or print(mysql_error());
					
					if($EQupdate){
						echo "<div id='alert' align='center' class='success'>Equipe <b>$EQCODIGO</b> ativada com sucesso.</div>";
						
					}else{
						echo "<div id='alert' align='center' class='error'>Equipe <b>$EQCODIGO</b> não pode ser ativada.</div>";
					}
				}
			}//Fim de ações...

			$EQselect = mysql_query("SELECT * FROM equipes ORDER BY EQCODIGO") or print (mysql_error());
			$EQtotal  = mysql_num_rows($EQselect);

			if($EQtotal > 0){
				echo"
				
				<table width='100%' id='DataTables' class='display table-responsive table-condensed table-action table-striped'>
					<thead>
						<tr>
							<th width='2%' ><div align='center'>      </div></th>
							<th width='15%'><div>Código</div></th>
							<th width='30%'><div>Nome  </div></th>
							<th width='25%'><div>Líder </div></th>
							<th width='10%'><div align='center'>Custo/Hora(R$)</div></th>
							<th width='15%'><div align='center'>Membros ativos</div></th>
							<th width='3%' data-orderable='false'>
								<div align='center'>
									<form name='novo' method='POST' action='form_cadastro_equipes.php'>
										<input name='acao' type='hidden' value='ADD'/>
										<input class='inputsrc' type='image' src='../imagens/add3.png' title='Criar Equipe'/>
									</form>
								</div>
							</th>
						</tr>
					</thead>
				<tbody>";
						
				while($linha = mysql_fetch_array($EQselect)){
					$EQID          = $linha["EQID"];
					$EQCODIGO      = $linha["EQCODIGO"];
					$EQNOME        = $linha["EQNOME"];
					$EQDESCRICAO   = $linha["EQDESCRICAO"];
					$EQRESPONSAVEL = $linha["EQLIDER"];
					$EQCUSTOHORA   = $linha["EQCUSTOHORA"];
					$EQSITUACAO    = $linha["EQSITUACAO"];
					$EQDTSITUACAO  = $linha["EQDTSITUACAO"];
					$EQUSRSITUACAO = $linha["EQUSRSITUACAO"];
					
					switch($EQSITUACAO){
						case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='Inativo'/>"; break;
						case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='Ativo'/>"; break;
					}
					
					$eqcustohora = number_format($EQCUSTOHORA,2,",",".");
					
					$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$EQRESPONSAVEL'") or print(mysql_error());
					$USERNOME   = mysql_result($USERselect,0,"USERNOME");
					$USERLOGIN  = mysql_result($USERselect,0,"USERLOGIN");
					$USERTEL1   = mysql_result($USERselect,0,"USERTEL1");
					$USERFOTO   = mysql_result($USERselect,0,"USERFOTO");
											 
					if($USERFOTO == 1){
						$responsavel = "<div class='foto_perfilP' style='float: left;'><img src='../$dominio_session/imagens/Fotos/$EQRESPONSAVEL.jpg' title='$USERNOME ($EQRESPONSAVEL)\n$USERLOGIN'/></div>";

					}else{
						$responsavel = "<div class='foto_perfilP' style='float: left;'><img src='../imagens/Fotos/SEMFOTO.png' title='$USERNOME ($EQRESPONSAVEL)\n$USERLOGIN'/></div>";
					}
					
					$EUselect = mysql_query("SELECT EUID FROM equipes_usuarios WHERE EUCODEQUIPE = '$EQCODIGO' AND EUSITUACAO = 1 AND EUIDUSUARIO <> 'mestre'") or print (mysql_error());
					$membros_ativos = mysql_num_rows($EUselect);
					
					echo "
					<tr>
						<td><div align='center'>$situacao</div></td>
						<td><div><b>$EQCODIGO</b></div></td>
						<td><div title='Descrição:/n$EQDESCRICAO'>$EQNOME</div></td>
						<td><div>$responsavel&nbsp;&nbsp;$USERNOME</div></td>
						<td><div align='center'>$eqcustohora</div></td>
						<td><div align='center'><b>$membros_ativos</b></div></td>
						<td>
							<div id='cssmenu' align='center'>
								<ul>
									<li class='has-sub'><a href='#'><span></span></a>
										<ul>
											<li>
												<form name='membros' method='POST' action='form_membros_equipe.php'>
													<input name='eqcodigo' type='hidden' value='$EQCODIGO'/>
													<input name='eqnome'   type='hidden' value='$EQNOME'/>
													<input class='inputsrc' type='image' src='../imagens/team.png' title='Membros'/>
												</form>
											</li>
											<li>
												<form name='modulos' method='POST' action='form_modulos_equipe.php'>
													<input name='eqcodigo' type='hidden' value='$EQCODIGO'/>
													<input name='eqnome'   type='hidden' value='$EQNOME'/>
													<input class='inputsrc' type='image' src='../imagens/modulos.png' title='Módulos'/>
												</form>
											</li>
											<li>	
												<form name='categorias' method='POST' action='form_categorias_equipe.php'>
													<input name='eqcodigo' type='hidden' value='$EQCODIGO'/>
													<input name='eqnome'   type='hidden' value='$EQNOME'/>
													<input class='inputsrc' type='image' src='../imagens/categorias.png' title='Categorias de Atendimento'/>
												</form>
											</li>
											<li>
											  <a href='#nova_mensagem$EQID' data-toggle='modal' title='Mensagem direta'><i class='fa fa-envelope-o' aria-hidden='true'></i></a>
											</li>
											<li>
												<form name='editar' method='POST' action='form_cadastro_equipes.php'>
													<input name='eqcodigo'  type='hidden' value='$EQCODIGO'/>
													<input name='acao'      type='hidden' value='EDITAR'/>
													<input class='inputsrc' name='editar' type='image'  src='../imagens/edit.png' title='Editar'/>
												</form>
											</li>";
											
											if($EQSITUACAO == 1){
												echo"
												<li>
													<form name='inativar' method='POST' action='form_cadastro_equipes.php'>
														<input name='eqcodigo'  type='hidden' value='$EQCODIGO'/>
														<input name='acao'      type='hidden' value='INATIVAR'/>
														<input class='inputsrc' name='excluir' type='image' onclick='return confirma_inativar();' src='../imagens/ativar.png' title='Inativar'/>
													</form>
												</li>";											
											}
											
											if($EQSITUACAO == 0){
												echo"
												<li>
													<form name='ativar' method='POST' action='form_cadastro_equipes.php'>
														<input name='eqcodigo'  type='hidden' value='$EQCODIGO'/>
														<input name='acao'      type='hidden' value='ATIVAR'/>
														<input class='inputsrc' name='excluir' type='image' onclick='return confirma_ativar();' src='../imagens/ativar.png' title='Ativar'/></input>
													</form>
												</li>";
											}
											
											echo "
										</ul>
									</li>
								</ul>
							</div>
						</td>
					</tr>
					
					<div class='modal fade' id='nova_mensagem$EQID' tabindex='-Z' role='dialog' aria-labelledby='...' aria-hidden='true'>
						<div class='modal-dialog' role='document'>
							<div class='modal-content'>
								
								<div class='modal-header'>
									<h5 class='modal-title'><i class='fa fa-envelope-o' aria-hidden='true'></i>&nbsp;&nbsp;Mensagem direta</h5>
								</div>
								
								<form name='nova_msgd' method='POST' action='form_cadastro_equipes.php'>
									<div class='modal-body'>

										<div class='form-group'>
											<label class='form-control-label'>Destinatário:</label>
											<select class='form-control' name='msgddestinatario' readonly>
												<option value='$EQCODIGO'>$EQCODIGO | $EQNOME</option>
											</select>
										</div>
										
										<div class='form-group'>
											<label class='form-control-label'>Mensagem:</label>
											<textarea name='msgdmensagem' class='form-control' rows='6' required maxlength='500'></textarea>
										</div>

									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
										<input name='eqcodigo'      type='hidden' value='$EQCODIGO'/>
										<input name='eqnome'        type='hidden' value='$EQNOME'/>
										<input class='but but-azul' type='submit' value='      Enviar      '/>
										<input name='acao' type='hidden' value='ENVIAR_MSGDEQUIPE'/>
									</div>
								</form>
							</div>
						</div>
					</div>";
				}
				
				echo "</tbody>
			</table>";
					
			}else{
				echo "<div align='center' class='info'>Nenhum registro encontrado!</div>
				<div align='center'>
					<form name='novo' method='POST' action='form_cadastro_equipes.php'>
						<input name='acao'   type='hidden' value='ADD'/>
						<input name='editar' class='inputsrc' type='image' img src='../imagens/new.png' title='Criar Equipe'/>
					</form>
				</div>";
			}
	?>
		</div>
	</div>
</div>
</body>
</html>