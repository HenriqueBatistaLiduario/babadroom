<?php include 'head.php';?>

	<script type="text/javascript">

		function confirma_ativar(){
			if(confirm('CONFIRMA ATIVAÇÃO deste Cartão? Lançamentos Financeiros já gravados não serão afetados.\n\nClique em OK para continuar...'))
				return true;
			else
				return false;
		}

		function confirma_inativar(){
			if(confirm('CONFIRMA INATIVAÇÃO deste Cartão? Lançamentos Financeiros já gravados não serão afetados.\n\nClique em OK para continuar...'))
				return true;
			else
				return false;
		}

		function confirma_cc(){
			if(confirm('CONFIRMA CADASTRO DE CARTÃO?\nCertifique-se de ter revisto todos os dados informados. Ao confirmar você assume a exclusiva responsabilidade legal sobre estas informações.\n\nClique em OK para continuar...'))
				return true;
			else
				return false;
		}
	</script>

	<body>
	<div class='container-fluid'>

	<?php 

	$CCTIPOUTILIZADOR = $_POST['cctipoutilizador'];
	$CCIDUTILIZADOR   = $_POST['ccidutilizador'];

	$voltar = $_POST['voltar'];
	
	echo "
	
	<div class='panel panel-default'>
									
			<div class='panel-heading'>
				<span class='subpageName'>Cartões do $CCTIPOUTILIZADOR $CCIDUTILIZADOR</span>

				<ul class='pull-right list-inline'>
					<li>
						<form id='fechar' name='fechar' method='POST' action='$voltar'>
							<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
						</form>
					</li>
				</ul>
			</div>
			
			<div class='panel-body'>";

			if(isset($_POST['acao'])){
				$acao = $_POST['acao'];
				 
				if($acao == 'SALVAR'){
					
					$CCNOMEIMPRESSO = $_POST["ccnomeimpresso"];
					$CCBANDEIRA     = $_POST["ccbandeira"];
					$CCFUNCAO       = $_POST["ccfuncao"];
					$CCNUMBER       = $_POST["ccnumber"];
					$CCVALIDADE     = $_POST["ccvalidade"];
					$CCCGCTITULAR   = $_POST["cccgctitular"];
					$CCTELTITULAR   = $_POST["ccteltitular"];
					
					$CCselect = mysqli_query($con,"SELECT CCID FROM credit_cards WHERE CCIDUTILIZADOR = '$CCIDUTILIZADOR' AND CCNUMBER = '$CCNUMBER'") or print (mysqli_error());
					$CCtotal  = mysqli_num_rows($CCselect);
					
					if($CCtotal == 0){										
					
						$CCinsert = mysqli_query ($con,"INSERT INTO credit_cards(IDDOMINIO,IDADMINISTRADOR,CCTIPOUTILIZADOR,CCIDUTILIZADOR,CCNOMEIMPRESSO,CCBANDEIRA,CCFUNCAO,CCNUMBER,CCVALIDADE,CCCGCTITULAR,CCTELTITULAR,CCUSRSTATUS,CCCADBY) 
						VALUES('$dominio_session','$adm_session','$CCTIPOUTILIZADOR','$CCIDUTILIZADOR','$CCNOMEIMPRESSO','$CCBANDEIRA','$CCFUNCAO','$CCNUMBER','$CCVALIDADE','$CCCGCTITULAR','$CCTELTITULAR','$ident_session','$ident_session')") or print (mysqli_error($con));
					
						if($CCinsert){
							
							echo "<div id='alert' align='center' class='success'>Cartão $CCFUNCAO cadastrado com sucesso.</div>";
						
						}
						
					}
					
				} 
				
				if($acao == 'INCLUIR_CC'){
					
					?>
					
					<fieldset><legend class='subpageName'>&nbsp;&nbsp;Cadastrar Cartão&nbsp;&nbsp;</legend>

						<div align='right'>
							<form name='dados_bancarios' method='POST' action='form_cadastro_cc.php' >
								<input name='cctipoutilizador' type='hidden' value='<?php echo $CCTIPOUTILIZADOR;?>'/>
								<input name='ccidutilizador'   type='hidden' value='<?php echo $CCIDUTILIZADOR;?>'/>
								<input name='voltar'           type='hidden' value='<?php echo $voltar;?>'/>
								<input class='inputsrc'        type='image' src='../images/close.png' title='Fechar'/>
							</form>
						</div>
						
						<div class='panel-body'>
					
							<form class='form-horizontal' name='incluir_cc' method='POST' action='form_cadastro_cc.php' onsubmit='return confirma_cc();'>
							  <div class='row'>
									<div class='col-sm-6 col-md-6'>				
                    
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Bandeira:</label>
											<div class='col-sm-8 col-md-8'>
												<select class='form-control' name='ccbandeira' required >
													<option value=''>Selecione...</option>					 
													<option value='VISA'>Visa</option>
													<option value='MASTER'>MasterCard</option>
													<option value='ELO'>Elo</option>
													<option value='AMEX'>American Express</option>	
													<option value='DINERS'>Diners Club</option>	
													<option value='HIPERCARD'>Hipercard</option>
												</select>
											</div>
										</div>
										
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Função:</label>
											<div class='col-sm-8 col-md-8'>
												<select class='form-control' name='ccfuncao' required >
													<option value=''>Selecione...</option>					 
													<option value='CREDITO'>CRÉDITO</option>
													<option value='DEBITO'>DÉBITO</option>
													<option value='MULTIPLO'>MÚLTIPLO</option>
												</select>
											</div>
										</div>			
										
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Nome Impresso:</label>
											<div class='col-sm-8 col-md-8'><input class='form-control' name='ccnomeimpresso' type='text' maxlength='100' onkeyup='maiuscula(this);' required /></div>
										</div>									
										
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Número:</label>
											<div class='col-sm-8 col-md-8'><input class='form-control' name='ccnumber' type='tel' maxlength='19' onkeypress="formatar(this, '#### #### #### ####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" required /></div>
										</div>												
										
										<div class='form-group'>
											<label class='col-sm-4 col-md-4 control-label'>Validade:</label>
											<div class='col-sm-3 col-md-3'><input class='form-control' name='ccvalidade' type='text' maxlength='5' onkeypress="formatar(this, '##/##');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" placeholder='mm/aa' required /></div>
										
											<label class='col-sm-2 col-md-2 control-label'>CVV:</label>
											<div class='col-sm-3 col-md-3'><input class='form-control' name='cccvv' type='tel' maxlength='3' placeholder='###' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;'/></div>
										</div>						
									</div>

									<div class='col-sm-6 col-md-6'>
										<div class='form-group'>
											<label class='col-sm-6 control-label'>Tipo Utilizador:</label>
											<div class='col-sm-6 col-md-6'><input class='form-control' name='cctipoutilizador' type='text' value='<?php echo $CCTIPOUTILIZADOR;?>' readonly /></div>
										</div>
									
										<div class='form-group'>
											<label class='col-sm-6 col-md-6 control-label'>ID. Utilizador:</label>
											<div class='col-sm-6 col-md-6'><input class='form-control' name='ccidutilizador' type='text' value='<?php echo $CCIDUTILIZADOR;?>' readonly /></div>
										</div>								

										<div class='form-group'>
											<label class='col-sm-6 col-md-6 control-label'>CPF/CNPJ do Titular:</label>
											<div class='col-sm-6 col-md-6'><input class='form-control' name='cccgctitular' type='text' maxlength='14' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' placeholder='Somente números...' required /></div>
										</div>	

										<div class='form-group'>
											<label class='col-sm-6 col-md-6 control-label'>Telefone do Titular:</label>
											<div class='col-sm-6 col-md-6'><input class='form-control' name='ccteltitular' type='tel' maxlength='15' onkeypress='if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;'/></div>
										</div>	

										<input name='voltar' type='hidden' value='<?php echo $voltar;?>'/>
										<input name='acao'   type='hidden' value='SALVAR'>
										<div align='right'><button class='btn btn-info btn-sm' type='submit'>Salvar</button></div>									
									</div>
								</div>
								
								
								
							</form>
						</div>
					</div>
					
					<?php
					
				}
				
				if($acao == 'ATIVAR'){
					
					$CCID = $_POST['ccid'];
					$CCupdate = mysqli_query($con,"UPDATE credit_cards SET CCSTATUS = 1, CCDTSTATUS = '$datetime', CCUSRSTATUS = '$ident_session' WHERE CCID = $CCID ") or print(mysqli_error());

					if($CCupdate){
						echo "<div id='alert' align='center' class='success'>Cartão ativado com sucesso.</div>";
					}
					
				}

				if($acao == 'INATIVAR'){
					
					$CCID = $_POST['ccid'];
					$CCupdate = mysqli_query($con,"UPDATE credit_cards SET CCSTATUS = 0, CCDTSTATUS = '$datetime', CCUSRSTATUS = '$ident_session' WHERE CCID = $CCID ") or print(mysqli_error());

					if($CCupdate){
						echo "<div id='alert' align='center' class='success'>Cartão desativado com sucesso.</div>";
					}
					
				}
				
			}//Fim das ações.

		$CCselect = mysqli_query($con,"SELECT * FROM credit_cards WHERE CCIDUTILIZADOR = '$CCIDUTILIZADOR'") or print (mysqli_error());
		$CCtotal  = mysqli_num_rows($CCselect);

		if ($CCtotal > 0){
			echo "
			<table id='DataTables' width='100%' class='display table-responsive table-condensed table-action table-striped'>
				<thead>
					<th width='2%' ><div align='center'></div></th>
					<th width='25%'><div>Nome impresso</div></th>							
					<th width='5%'><div align='center'>Bandeira</div></th>
					<th width='10%'><div align='center'>Função</div></th>
					<th width='15%'><div align='center'>Number</div></th>
					<th width='10%'><div align='center'>Validade</div></th>
					<th width='15%'><div align='center'>CGC.Titular</div></th>
					<th width='15%'><div align='center'>Tel.Titular</div></th>
					<th width='3%' data-orderable='false'>
						<div align='center'>		
							<form name='newcard' method='POST' action='form_cadastro_cc.php' >
								<input name='cctipoutilizador' type='hidden' value='$CCTIPOUTILIZADOR'/>
								<input name='ccidutilizador'   type='hidden' value='$CCIDUTILIZADOR'/>
								<input name='voltar'        type='hidden' value='$voltar'/>
								<input name='acao'          type='hidden' value='INCLUIR_CC'/>
								<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
							</form>
						</div>
					</th>
				</thead>
				<tbody>";	
					
					while($CCrow = mysqli_fetch_array($CCselect)){
						$CCID           = $CCrow["CCID"];
						$CCNOMEIMPRESSO = $CCrow["CCNOMEIMPRESSO"];
						$CCBANDEIRA     = $CCrow["CCBANDEIRA"];
						$CCFUNCAO       = $CCrow["CCFUNCAO"];
						$CCNUMBER       = $CCrow["CCNUMBER"];
						$CCVALIDADE     = $CCrow["CCVALIDADE"];
						$CCCGCTITULAR   = $CCrow["CCCGCTITULAR"];
						$CCTELTITULAR   = $CCrow["CCTELTITULAR"];
						$CCSTATUS       = $CCrow["CCSTATUS"];
						$CCDTSTATUS     = $CCrow["CCDTSTATUS"];
						$CCUSRSTATUS    = $CCrow["CCUSRSTATUS"];
						$CCCADASTRO     = $CCrow["CCCADASTRO"];
						$CCCADBY        = $CCrow["CCCADBY"];
						
						$ccdtstatus = date("d/m/Y H:i", strtotime($CCDTSTATUS));
						
						switch($CCSTATUS){
							case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO desde $ccdtstatus por $CCUSRSTATUS'/>";break;
							case 1: $situacao = "<img src='../images/status/VERDE.png' title='ATIVO desde $ccdtstatus'/>";break;
						}
						
						echo "
						<tr>
						<td><div align='center'>$situacao</div></td>
						<td><div>$CCNOMEIMPRESSO</div></td>							
						<td><div align='center'>$CCBANDEIRA</div></td>
						<td><div align='center'>$CCFUNCAO</div></td>
						<td><div align='center'>$CCNUMBER</div></td>
						<td><div align='center'>$CCVALIDADE</div></td>
						<td><div align='center'>$CCCGCTITULAR</div></td>
						<td><div align='center'>$CCTELTITULAR</div></td>
						<td data-orderable='false'>
							
								<div id='cssmenu' align='center'>
									<ul>
										<li class='has-sub'><a href='#'><span></span></a>
											<ul>";
						
											if ($CCSTATUS == 1){//BLOQUEAR
												echo "
												<li>
													<form name='inativar' method='POST' action='form_cadastro_cc.php' onsubmit='return confirma_inativar();'>
														<input name='ccid'             type='hidden' value='$CCID'/>
														<input name='cctipoutilizador' type='hidden' value='$CCTIPOUTILIZADOR'/>
														<input name='ccidutilizador'   type='hidden' value='$CCIDUTILIZADOR'/>
														<input name='voltar'           type='hidden' value='$voltar'/>
														<input name='acao'             type='hidden' value='INATIVAR'/>
														<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
													</form>
												</li>";
											}
												
											if ($CCSTATUS == 0){//DESBLOQUEAR
												echo "
												<li>
													<form name='ativar' method='POST' action='form_cadastro_cc.php' onsubmit='return confirma_ativar();'>
														<input name='ccid'             type='hidden' value='$CCID'/>
														<input name='cctipoutilizador' type='hidden' value='$CCTIPOUTILIZADOR'/>
														<input name='ccidutilizador'   type='hidden' value='$CCIDUTILIZADOR'/>
														<input name='voltar'           type='hidden' value='$voltar'/>
														<input name='acao'             type='hidden' value='ATIVAR'/>
														<button class='btn btn-default btn-xs' title='ATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
													</form>
												</li>";
											}
							
									echo "
											</ul>
										</li>
									</ul>
								</div>
							</td>
						</tr>";
					}
										
					echo "</tbody>
					</table>";
						
				}else{
					
					echo "<div align='center' class='info'>Nenhum Cartão cadastrada para este $CCTIPOUTILIZADOR</div>
						<div align='center'>		
							<form name='dados_bancarios' method='POST' action='form_cadastro_cc.php' >
								<input name='cctipoutilizador' type='hidden' value='$CCTIPOUTILIZADOR'/>
								<input name='ccidutilizador'   type='hidden' value='$CCIDUTILIZADOR'/>
								<input name='voltar'        type='hidden' value='$voltar'/>
								<input name='acao'          type='hidden' value='INCLUIR_CC'/>
								<button type='submit' class='btn btn-info btn-sm'><i class='fa fa-plus'></i>&nbsp;&nbsp;Incluir</button>
							</form>
						</div>";	
				}
				?>
			</div>
		</div>
		</div>
	</body>
</html>