<?php include 'head.php';?>

	<script type="text/javascript">

		function confirma(){
			if(confirm('CONFIRMA A ALTERAÇÃO DA SENHA? Lembre-se de anotar a nova senha, pois ela é irrecuperável. Clique em OK para confirmar a alteração.'))
				return true;
			else
				return false;
		}

		function validatrocasenha(){
			var valido = false;

			with(document.trocar){
				if ((novasenha.value.length) < 6){
					valido = false;
					novasenha.focus();
					alert ('A senha deve ter no mínimo 6 caracteres!');

				}else if ((novasenha.value) != (novasenha2.value)){
					valido = false;
					novasenha2.focus();
					alert ('Confirmação de senha diferente de Senha. Redigite...!');
				
				}else{
					valido = true;
				}
				return valido;
			}
			
		} 
		
	</script>

	<body>
		<div class='container-fluid'>

			<?php
			
			if(isset($_POST['acao'])){
				$acao = $_POST['acao'];
				
				if ($acao == 'CONFIRMARSENHA'){
					$NOVASENHA = $_POST['novasenha2'];
					$userid    = $_POST['useridentificacao'];
					
					$USERupdate = mysql_query("UPDATE usuarios SET USERSENHA = '$NOVASENHA', 
																										USERPRIMACESSO = 1,
																												ULTALTERON = '$datetime', 
																												ULTALTERBY = '$ident_session' WHERE USERIDENTIFICACAO = '$userid'") or print (mysql_error());			
					if($USERupdate){
						echo "<div id='alert' align='center' class='success'>Senha do Usuário <b>$userid</b> alterada com sucesso.</div>";
					}
				}
				
				if($acao == 'ATIVAR'){
				
					$USERIDENTIFICACAO = $_POST['userident'];

					$USERupdate = mysql_query("UPDATE usuarios SET USERSTATUS = 1, USERDTSTATUS = '$datetime', USERUSRSTATUS = '$ident_session', USERPRIMACESSO = 1 WHERE USERIDENTIFICACAO = '$USERIDENTIFICACAO'") or print (mysql_error());

					if($USERupdate){
						echo "<div id='alert' align='center' class='success'>Usuário <b>$USERIDENTIFICACAO</b> ATIVADO com sucesso.</div>";
					}
				}
				
				if($acao == 'INATIVAR'){
					$USERIDENTIFICACAO = $_POST['userident'];

					$USERupdate = mysql_query("UPDATE usuarios SET USERSTATUS = 0, USERDTSTATUS = '$datetime', USERUSRSTATUS = '$ident_session' WHERE USERIDENTIFICACAO = '$USERIDENTIFICACAO' AND USERSTATUS = 1") or print (mysql_error());

					if($USERupdate){
						echo "<div id='alert' align='center' class='success'>Usuário <b>$USERIDENTIFICACAO</b> INATIVADO com sucesso.</div>";
					}
				}
			}//Fim de ações
			
			$USUARIOSselect = mysql_query("SELECT * FROM usuarios WHERE USERSTATUS = 1 AND USERIDENTIFICACAO NOT IN ('admin','mestre')") or print (mysql_error());
			$QTDEUSRATIVOS  = mysql_num_rows($USUARIOSselect);
			$QTDEUSRDISPON  = $ACLIMITEUSUARIOS - $QTDEUSRATIVOS;
			
			?>
				
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<span class='subpageName'>Usuários do sistema</span>

					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
				</div>

				<div class='panel-body'>
				
					<?php
					
						if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
							echo "<div class='info'><font color='green'>Ativos:</font> <b>$QTDEUSRATIVOS</b> | <font color='blue'>Disponíveis:</font> <b>$QTDEUSRDISPON</b></div>";	
							
							$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO NOT IN('mestre') ORDER BY USERNOME") or print (mysql_error());
						
						}else if($perfil_session == 'TS'){
							$USERselect = mysql_query("SELECT * FROM usuarios WHERE (USERIDENTIFICACAO IN (SELECT UCIDCLIENTE FROM usuarios_clientes WHERE UCIDCLIENTE = '$ident_session') OR USERIDENTIFICACAO IN (SELECT UCIDUSUARIO FROM usuarios_clientes WHERE UCIDCLIENTE IN (SELECT UCIDCLIENTE FROM usuarios_clientes WHERE UCIDUSUARIO = '$ident_session' AND UCSITUACAO = 1))) ORDER BY USERNOME") or print (mysql_error());		
						
						}else if($perfil_session == 'GPC'){
							$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO IN (SELECT UCIDUSUARIO FROM usuarios_clientes WHERE UCIDCLIENTE IN (SELECT UCIDCLIENTE FROM usuarios_clientes WHERE UCIDUSUARIO = '$ident_session' AND UCSITUACAO = 1)) ORDER BY USERNOME") or print (mysql_error());
						}
						
						$USERtotal = mysql_num_rows($USERselect);
						
						if($USERtotal > 0){ ?>
							
							<table id='DataTables' class='display table table-responsive table-condensed table-action table-striped'>
								<thead>
									<tr>
										<th width='2%' ><div align='center'>             </div></th>
										<th width='2%' data-orderable='false'><div align='center'>             </div></th>
										<th width='18%'><div align='center'>Identificador</div></th>
										<th width='40%'><div >Nome         </div></th>
										<th width='15%'><div align='center'>Login        </div></th>
										<th width='10%'><div align='center'>Perfil       </div></th>
										<th width='10%'><div align='center'>Cadastro     </div></th>
										<th width='3%' data-orderable='false'></th>
									</tr>
								</thead>
								<tbody>
							
								<?php
								
								while($linha = mysql_fetch_array($USERselect)){
									$userid       = $linha['USERID'];
									$usernome     = $linha['USERNOME'];
									$userident    = $linha['USERIDENTIFICACAO'];
									$userperfil   = $linha['USERPERFIL'];
									$useremail    = $linha["USERLOGIN"];
									$usersenha    = $linha["USERSENHA"];
									$userstatus   = $linha['USERSTATUS'];
									$userfoto     = $linha['USERFOTO'];
									$usercadastro = $linha['USERCADASTRO'];
									$userdominio  = $linha['IDDOMINIO'];
									$useradm      = $linha['IDADMINISTRADOR'];
									
									$cadastro = date("d/m/Y H:i",strtotime($usercadastro));
									
									switch($userstatus){
										case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='INATIVO'/>";  break;
										case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='ATIVO'/>"; break;
									}
									
									if($userfoto == 1){
										$usericone = "<div class='circularP'><img src='../$dominio_session/imagens/Fotos/$userident.jpg'/></div>";

									}else{
										$usericone = "<div class='circularP'><img class='foto_perfilP' src='../imagens/Fotos/SEMFOTO.png'/></div>";
									}

									echo "
									<tr>
										<td data-order='$userstatus'><div align='center'>$situacao</div></td>
										<td><div align='center'>$usericone</div></td>
										<td><div align='center'><b>$userident</b></div></td>
										<td><div>$usernome</div></td>
										<td><div align='center'><a href='mailto:$useremail'>$useremail</a></div></td>
										<td><div align='center'>$userperfil</div></td>							
										<td data-order='$usercadastro'><div align='center'>$cadastro</div></td>
										<td>
											<div id='cssmenu' align='center'>
												<ul>
													<li class='has-sub'><a href='#'><span></span></a>
														<ul>";
															
															if($userstatus == 1){
																echo"
																<li>
																	<a href='#trocar_senha$userid' data-toggle='modal'><img src='../imagens/cadeado1.png' title='Trocar Senha'/></a>
																</li>";
															}
															
															if(($userstatus == 0 OR $userstatus == 2 OR $userstatus == 3) AND $QTDEUSRDISPON > 0){
																echo"
																<li>
																	<form name='ativar' method='POST' action='form_cadastro_usuarios.php'>
																		<input name='userident' type='hidden' value='$userident'/>
																		<input name='acao'      type='hidden' value='ATIVAR'/>
																		<input class='inputsrc' type='image' src='../imagens/icone_omitido.png' title=' Ativar '/>
																	</form>
																</li>";
															}
															
															if($userstatus == 1){
																echo"
																<li>
																	<form name='inativar' method='POST' action='form_cadastro_usuarios.php'>
																		<input name='userident' type='hidden' value='$userident'/>
																		<input name='acao'      type='hidden' value='INATIVAR'/>
																		<input class='inputsrc' type='image'  src='../imagens/icone_omitido.png' title=' Inativar '/>
																	</form>
																</li>";
															}
															
															echo"
															<li>
																<form name='permissoes' method='POST' action='form_usuarios_permissoes.php'>
																	<input name='userident'  type='hidden' value='$userident'/>
																	<input name='userstatus' type='hidden' value='$userstatus'/>
																	<input name='usernome'   type='hidden' value='$usernome'/>
																	<input class='inputsrc'  type='image'  src='../imagens/permissoes.png' title=' Permissões '/>
																</form>
															</li>
														</ul>
													</li>
												</ul>
											</div>
										</td>					
									</tr>
									
									<div id='trocar_senha$userid' class='modal fade' role='dialog' tabindex='-Z'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'>&times;</button>
													<h4 class='modal-title'>Troca de Senha...</h4>
												</div>

												<form name='trocar' method='POST' action='form_cadastro_usuarios.php' onSubmit='return confirma();'>
													<div class='modal-body'>
														<div align='center'><img class='imgnatural' src='../imagens/cadeado1.png'></div>

														<div class='form-group'>
															<label class='form-control-label'>Usuário:</label>
															<input class='form-control' name='useridentificacao' type='text' maxlength='100' value='$userident' readonly />
														</div>

														<div class='form-group'>
															<label class='form-control-label'>Nova senha:</label>
															<input class='form-control' name='novasenha' type='password' maxlength='15' placeholder='6 a 15 caracteres...' required />
														</div>

														<div class='form-group'>
															<label class='form-control-label'>Confirme nova senha:</label>
															<input class='form-control' name='novasenha2' type='password' maxlength='15' placeholder='6 a 15 caracteres...' required />
														</div>
													</div>
													
													<div class='modal-footer'>
														<div align='right'>
															<input name='acao' type='hidden' value='CONFIRMARSENHA'/>
															<input class='but but-azul' type='submit' onClick='return validatrocasenha();' value='      Confirmar      '/>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>";			
								}
								echo"
								</tbody>
							</table>";
						
						}else{
							echo "<div align='center' class='info'>Nenhum registro encontrado!</div>";                     
						}
						
					?>
				</div>
			</div>
		</div>
	</body>
</html>