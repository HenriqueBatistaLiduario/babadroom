<?php include 'head.php';?>

	<script type='text/javascript'>

		$(document).ready(function(){
		$("#pavalorhora").maskMoney({showSymbol:false, decimal:".", thousands:""});
		});

		$(document).ready
		(function(){
			$('#uf').change
			(function(){
				$('#cidade').load('select_cidades.php?uf='+$('#uf').val());
			}
			);
			}
		);

		function confirma_editar(){
			if(confirm('CONFIRMA ALTERAÇÃO? Operação irreversível...'))
				return true
			else
				return false
		}

		function validar(){
			var valido = false;

			with(document.verificar) {
			if (((cpf.value.length == 0) && (cnpj.value.length == 0)) || ((cpf.value.length > 0) && (cnpj.value.length > 0))){
			valido = false;
			cpf.focus();
			alert ('CPF ou CNPJ deve ser informado!');
			
			}else {
				valido = true;
				submit();
			}
				return valido;
			} 
		}

		function valida_tamanho(){
			var valido = false;

			with(document.verificar){
				if (identificador.value.length < 5){
					valido = false;
					identificador.focus();
					alert ('IDENTIFICADOR INVÁLIDO! O domínio deve possuir entre 5 e 15 caracteres!');
				
				}else{
					valido = true;
				}
				return valido;
			}
		}

	</script>
	
	<script src='../js/cep.js'></script>
	
<body>
<div class='container-fluid'>

	<div class='panel panel-default'>
		
		<div class='panel-heading'>
			<span class='subpageName'>Cadastro de Fornecedores</b></span>

			<ul class='pull-right list-inline'>
				<li>
				
				</li>
			</ul>
		</div>
		
		<div class='panel-body'>

	<?php

 	if (isset($_POST['acao'])){
	  $acao = $_POST['acao'];
		
		if($acao == 'NOVA_PVD'){
			?>
			
			<div class='panel panel-default'>

				<div class='panel-heading'>
					
					<span class='subpageName'>Incluir Fornecedor</span>

					<ul class='pull-right list-inline'>
						<li>
							<ul class='pull-right list-inline'>
								<li><a href='form_cad_providers.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
							</ul>
						</li>
					</ul>
					
				</div>

				<div class='panel-body'>
			
					<form class='form-horizontal' name='cadastro' method='POST' action='form_cad_providers.php'>
						<div class='form-group'>
							<label class='col-sm-6 control-label'>CNPJ:</label>
							
							<div class='col-sm-4' >
								<input class='form-control' name='idcnpj' type='text' id='cnpj' onkeypress='if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='14' placeholder='CNPJ (Somente números)...' required />
							</div>
						</div>
						
						<div class='form-group'>	
							<label class='col-sm-6 control-label'>e-mail:</label>
							<div class='col-sm-4'><input class='form-control' name='email' type='email' id='email' onKeyUp='return Valida_email(this.id)' required /></div>
						</div>
						
						<input name='acao'  type='hidden' value='AVANCAR'/>
					  <div align='right'><button type='submit' class='btn btn-info btn-lg'>Avançar</div>
					</form>
					
				</div>
			</div>
			 
			
			<?php
			
		}
		
		if($acao == 'AVANCAR'){
			
			$DOCUMENTO = $_POST['idcnpj'];
			$email     = $_POST['email'];
			
			$DOCVALIDO = 0;
			$ExibeForm = 0;
			
			if($DOCUMENTO == ''){
				echo "<div class='warning' align='center'>O CNPJ deve ser informado!</div>";			
			
			}else if(!valida_CNPJ("$DOCUMENTO")){
				echo "<div class='warning' align='center'>O CNPJ informado é inválido.!</div>";
			
			}else{
				
				$CNPJ = $DOCUMENTO;
				
				include 'wsrfb.php';
				
				if(!$json_file){
					
					echo "<div class='warning' align='center'>O CNPJ informado não consta na Base de Dados da Receita Federal do Brasil!</div>";
				
				}else{
					
					//Verifica se o CNPJ já está cadastrado...
			
			    $PVDselect = mysqli_query($con,"SELECT PVDIDENTIFICADOR FROM providers WHERE PVDCNPJ = '$DOCUMENTO'") or print(mysqli_error());
					$PVDtotal  = mysqli_num_rows($PVDselect);
					
					if($PVDtotal > 0){
					  echo "<div class='warning' align='center'>O CNPJ informado já existe na Base de dados do sistema!</div>";
					
					}else{ //Passou em todas as validações, exibe o formulário de cadastro...
						
						include 'wsrfb.php';
										
						if(!empty($Tipo)){
							$readonly = 'readonly';
							$options  = "<option value='$Tipo'>$Tipo</option>";
							
						}else{
							$readonly = 'required';
							$options  = "
							<option value=''>Selecione...</option>
							<option value='MATRIZ'>MATRIZ</option>
							<option value='FILIAL'>FILIAL</option>";
						}
						
						$NJselect = mysqli_query($con,"SELECT NJDESCRICAO FROM natjur WHERE NJCODIGO = '$NJCODIGO'") or print(mysqli_error());
						$NJexiste = mysqli_num_rows($NJselect);
						
						if($NJexiste > 0){
							while($NJrow = mysqli_fetch_assoc($NJselect)){
								$NJDESCRICAO = $NJrow["NJDESCRICAO"];
							}
						
						}else{
							$NJDESCRICAO = NULL;
						}
						
						$CNAEselect = mysqli_query($con,"SELECT * FROM cnae20 WHERE CLASSECODIGO = '$CNAECLASSECODIGO'") or print(mysqli_error());
						$CNAEtotal  = mysqli_num_rows($CNAEselect);
						
						if($CNAEtotal > 0){
							while($CNAErow = mysqli_fetch_assoc($CNAEselect)){
								$CLASSEDESCRICAO = $CNAErow["CLASSEDESCRICAO"];
								$classedescricao = "$CNAECLASSECODIGO | $CLASSEDESCRICAO";
							}
						
						}else{
							$CNAECLASSECODIGO = NULL;
							$classedescricao = "Não obtido da RFB. Selecione...";
						}
										
						?>
						
						<div class='panel panel-default'>

							<div class='panel-heading'>
								
								<span class='subpageName'>Inclusão de Fornecedor</span>

								<ul class='pull-right list-inline'>
									<li>
										<ul class='pull-right list-inline'>
											<li><a href='form_cad_providers.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
										</ul>
									</li>
								</ul>
								
							</div>

							<div class='panel-body'>
								
								<form class='form-horizontal' name='cadastro' method='POST' action='form_cad_providers.php' onsubmit='return cpfcnpj_informado(); return confirma_incluir();'>
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Identificador:</label>
										
										<div class='col-sm-4' >
											<input class='form-control' name='pvdidentificador' type='text' value='<?php echo $CNPJ;?>' readonly />
										</div>
										
										<label class='col-sm-1 control-label'>e-mail:</label>
										<div class='col-sm-5'><input class='form-control' name='pvdemail' type='email' value='<?php echo $email;?>' readonly /></div>
										<div id='alert_consistencia_email'></div>
									</div>
									
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Número de Inscrição:</label>
										<div class='col-sm-4' >
											<input class='form-control' name='pvdcnpj' type='text' value='<?php echo $Cnpj;?>' readonly />
										</div>
										
										<label class='col-sm-1 control-label'>Tipo:</label>
										<div class='col-sm-2'>
											<select class='form-control' name='pvdtipocnpj' <?php echo $readonly;?> >
											<?php echo $options;?>
											</select>										
										</div>
										
										<label class='col-sm-1 control-label'>Abertura:</label>
										<div class='col-sm-2'><input class='form-control' name='pvddtabertura' type='date' value='<?php echo $DataAbertura;?>' required /></div>
									</div>
										
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Nome Empresarial:</label>
										<div class='col-sm-4'><input class='form-control' name="pvdrazaonome" type="text" maxlength='100' onkeyup="maiuscula(this)" value='<?php echo $RazaoSocial;?>' required /></div>
										
										<label class='col-sm-2 control-label'>Nome Fantasia:</label>
										<div class='col-sm-4'><input class='form-control' name="pvdfantasia" type="text" maxlength='100' onkeyup="maiuscula(this)" value='<?php echo $NomeFantasia;?>' required /></div>
									</div>
										
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Ins. Estadual:</label>
										<div class='col-sm-2'><input class='form-control' name="pvdie" type="text" maxlength='12' /></div>
										<div class='col-sm-2'></div>
										
										<label class='col-sm-2 control-label'>Ins. Municipal:</label>
										<div class='col-sm-2'><input class='form-control' name="pvdim" type="text" maxlength='12' /></div>
										<div class='col-sm-2'></div>
									</div>
											
									<div class='form-group'>
										
										<label class='col-sm-2 control-label'>Segmento:</label>
										<div class='col-sm-4'>
											<select class='form-control' name='pvdsegmento'>
																	 
												<?php 
									
												$SEGselect = mysqli_query($con,"SELECT * FROM segments WHERE SEGSTATUS = 1") or print (mysqli_error());
												$SEGtotal  = mysqli_num_rows($SEGselect);
												
												if($SEGtotal > 0){
													echo "<option value=''>Selecione...</option>";
													
													while($linha = mysqli_fetch_assoc($SEGselect)){
														$SEGCOD  = $linha["SEGCOD"];
														$SEGNAME = $linha["SEGNAME"];
														
														echo "<option value='$SEGCOD'>$SEGNAME ($SEGCOD)</option>";
													}
							
												}else{
													echo "<option value=''>Nenhum segmento cadastrado/ativo.</option>";
												}
												
												?>
											</select>
										</div>
										
										<label class='col-sm-2 control-label'>Natureza Jurídica:</label>
										<div class='col-sm-4'>
											<select class='form-control' name='pvdnj' required >
												<option value='<?php echo $NJCODIGO;?>'><?php echo "$NJCODIGO | $NJDESCRICAO";?></option>
												
												<?php
												
												$NJGRUPOselect = mysqli_query($con,"SELECT DISTINCT NJGRUPO FROM natjur ORDER BY NJGRUPO") or print(mysqli_error());
												$NJGRUPOtotal  = mysqli_num_rows($NJGRUPOselect);
												
												if($NJGRUPOtotal > 0){
													
													while($NJGRUPOrow = mysqli_fetch_array($NJGRUPOselect)){
														$NJGRUPO = $NJGRUPOrow["NJGRUPO"];
														
														echo "<optgroup label='$NJGRUPO'>";
														
														$NJselect = mysqli_query($con,"SELECT * FROM natjur WHERE NJGRUPO = '$NJGRUPO' AND NJSITUACAO = 1 ORDER BY NJCODIGO") or print(mysqli_error());
													  $NJtotal  = mysqli_num_rows($NJselect);
														
														if($NJtotal > 0){
															while($NJrow = mysqli_fetch_array($NJselect)){
																$NJCODIGO = $NJrow["NJCODIGO"];
																$NJDESCRICAO = $NJrow["NJDESCRICAO"];
																
																echo "<option value='$NJCODIGO'>$NJCODIGO | $NJDESCRICAO</option>";
																
															}
															
														}else{
															echo "<option value=''>Nenhuma Natureza Jurídica ativa neste Grupo.</option>";
														}
														
														echo "</optgroup>";
													
													}													
													
												}else{
													echo "<option value=''>Nenhuma outra Natureza Jurídica ativa.</option>";
												}												
												
												?>
												
											</select>										
										</div>
										
									</div>
									
									<div class='form-group'>
									
										<label class='col-sm-2 control-label'>Atividade Principal:</label>
										<div class='col-sm-10'>
											<select class='form-control' name='pvdcnae' required >
												<option value='<?php echo $CNAECLASSECODIGO;?>'><?php echo $classedescricao;?></option>		
                        
												<?php
												
												$CNAEDIVISAOselect = mysqli_query($con,"SELECT DISTINCT DIVISAOCODIGO FROM cnae20 WHERE CNAESITUACAO = 1 ORDER BY DIVISAOCODIGO") or print(mysqli_error());
												$CNAEDIVISAOtotal  = mysqli_num_rows($CNAEDIVISAOselect);
												
												if($CNAEDIVISAOtotal > 0){
													
													while($CNAEDIVISAOrow = mysqli_fetch_array($CNAEDIVISAOselect)){
														$DIVISAOCODIGO = $CNAEDIVISAOrow["DIVISAOCODIGO"];
														
														$DIVISAODESCRICAOselect = mysqli_query($con,"SELECT DIVISAODESCRICAO FROM cnae20 WHERE DIVISAOCODIGO = '$DIVISAOCODIGO' LIMIT 1") or print(mysqli_error());
														
														while($DIVrow = mysqli_fetch_assoc($DIVISAODESCRICAOselect)){
															$DIVISAODESCRICAO = $DIVrow["DIVISAODESCRICAO"];
														}
														
														echo "<optgroup label='$DIVISAOCODIGO | $DIVISAODESCRICAO'>";														
														
														$CLASSEselect = mysqli_query($con,"SELECT CLASSECODIGO,CLASSEDESCRICAO FROM cnae20 WHERE DIVISAOCODIGO = '$DIVISAOCODIGO' AND CNAESITUACAO = 1 ORDER BY CLASSECODIGO") or print(mysqli_error());
													  $CLASSEtotal  = mysqli_num_rows($CLASSEselect);
														
														if($CLASSEtotal > 0){
															while($CLASSErow = mysqli_fetch_array($CLASSEselect)){
																$CLASSECODIGO    = $CLASSErow["CLASSECODIGO"];
																$CLASSEDESCRICAO = $CLASSErow["CLASSEDESCRICAO"];
																
																echo "<option value='$CLASSECODIGO'>$CLASSECODIGO | $CLASSEDESCRICAO</option>";
																
															}
															
														}else{
															echo "<option value=''>Nenhuma classe ativa nesta Divisão.</option>";
														}
													
													}													
													
												}else{
													echo "<option value=''>Nenhuma outra Divisão CNAE ativa.</option>";
												}

                        ?>	
												
											</select>
										</div>
										
									</div>
									
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Website/Página:</label>
										<div class='col-sm-4'><input class='form-control' name='pvdsite' type='url' maxlength="50" onkeyup="minuscula(this)"/></div>
									</div>
											
									<div class='panel panel-default'>

										<div class='panel-heading'><span class='subpageName'>Endereço Fiscal</span></div>

										<div class='panel-body'>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>CEP:</label>
												<div class='col-sm-3'><input class='form-control' name='cep' id='cep' type="text" onkeypress="formatar(this, '##.###-###');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" maxlength='10' value='<?php echo $Cep;?>' required /></div>
												<label class='col-sm-2 control-label'>Logradouro:</label>
												<div class='col-sm-5'><input class='form-control' name='rua' id='rua' type='text' maxlength='100' value='<?php echo $Logradouro;?>' required /></div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Número | Compl.:</label>
												<div class='col-sm-1'><input class='form-control' name="numero" type="text" maxlength='6' required placeholder='Nº...' value='<?php echo $Numero;?>'/></div>
											
												<div class='col-sm-2'><input class='form-control' name="compl" type="text" maxlength="10" onkeyup="maiuscula(this)" placeholder='Complemento...'/></div>
											
												<label class='col-sm-2 control-label'>Bairro:</label>
												<div class='col-sm-5'><input class='form-control' name='bairro' id='bairro' type='text' maxlength='100' value='<?php echo $Bairro;?>' required /></div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Estado:</label>
												<div class='col-sm-3'><input class='form-control' name='estado' id='estado' type='text' maxlength='2' value='<?php echo $Uf;?>' required /></div>
												
												<label class='col-sm-2 control-label'>Município:</label>
												<div class='col-sm-5'><input class='form-control' name='cidade' id='cidade' type='text' maxlength='100' value='<?php echo $Municipio;?>' required /></div>
											</div>
											
											<div class='form-group'>
												
												<label class='col-sm-2 control-label'>Telefones:</label>
												
												<div class='col-sm-2'><input class='form-control' name="pvdtel1" type="text" maxlength="13" onkeypress="formatar(this, '## ####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;"  placeholder ='## ####-####' value='<?php echo $Telefone;?>' required /></div>
												<div class='col-sm-2'><input class='form-control' name="pvdtel2" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" placeholder ='Celular (## #####-####)' /></div>
												<div class='col-sm-2'><input class='form-control' name="pvdtel3" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" placeholder ='Celular (## #####-####)' /></div>
											
											</div>
										</div>
									</div>
									
									<input name='acao' type='hidden' value='CONFIRMAR_INCLUSAO'/>
									<div align='right'><input class='but but-rc but-azul' type='submit' value='     Confirmar     '/></div>
								</form>
							</div>
						</div>	
						
						<?php					
						
					}
				}				
			}
		}				
		
		if($acao == 'CONFIRMAR_INCLUSAO'){
			
			$pvdident      = $_POST['pvdidentificador'];	
      $pvdcnpj       = $_POST['pvdcnpj'];	
      $pvdtipocnpj   = $_POST['pvdtipocnpj'];
      $pvddtabertura = $_POST['pvddtabertura'];			
			$pvdrazaonome  = $_POST['pvdrazaonome'];
			$pvdie         = $_POST['pvdie'];
			$pvdim         = $_POST['pvdim'];
			$pvdnomefant   = $_POST['pvdfantasia'];
			$pvdcnae       = $_POST['pvdcnae'];
			$pvdnj         = $_POST['pvdnj'];
			$pvdlog        = $_POST['rua'];
			$pvdnumero     = $_POST['numero'];
			$pvdcompl      = $_POST['compl'];
			$pvdbairro     = $_POST['bairro'];
			$pvduf         = $_POST['estado'];
			$pvdcidade     = $_POST['cidade'];
			$pvdcep        = $_POST['cep'];
			$pvdtel1       = $_POST['pvdtel1'];
			$pvdtel2       = $_POST['pvdtel2'];
			$pvdtel3       = $_POST['pvdtel3'];
			$pvdemail      = $_POST['pvdemail'];
			$pvdsite       = $_POST['pvdsite'];
			$pvdsegmento   = $_POST['pvdsegmento'];
			
			$PVDinsert = mysqli_query($con,"INSERT INTO providers(PVDIDENTIFICADOR,PVDRAZAONOME,PVDCNPJ,PVDTIPOCNPJ,PVDDTABERTURA,PVDIE,PVDIM,PVDNOMEFANT,PVDCNAE,PVDNJ,PVDLOG,PVDNUMERO,PVDCOMPL,PVDBAIRRO,PVDUF,PVDCIDADE,PVDCEP,PVDTEL1,PVDTEL2,PVDTEL3,PVDEMAIL,PVDSITE,PVDSEGMENTO,PVDCADASTRO,PVDCADBY) VALUES
      ('$pvdident','$pvdrazaonome','$pvdcnpj','$pvdtipocnpj','$pvddtabertura','$pvdie','$pvdim','$pvdnomefant','$pvdcnae','$pvdnj','$pvdlog','$pvdnumero','$pvdcompl','$pvdbairro','$pvduf','$pvdcidade','$pvdcep','$pvdtel1','$pvdtel2','$pvdtel3','$pvdemail','$pvdsite','$pvdsegmento','$datetime','$ident_session')") or print (mysqli_error());
			
			if($PVDinsert){
				
				echo "<div id='alert' class='success' align='center'>Empresa <b>$pvdident</b> cadastrada com sucesso.</div>";
				
				/*@mysqli_close($conn); // Se incluiu na base local, inclui no Painel de Controle da PMS.net para que possa ser gerenciada por lá.

				include "../Conexoes/conecta_cpane.php";

				$PVDinsertPC = mysqli_query($con,"INSERT INTO providers(PVDDOMINIO,PVDIDENTIFICADOR,PVDRAZAONOME,PVDCNPJ,PVDIE,PVDIM,PVDNOMEFANT,PVDCNAE,PVDLOG,PVDNUMERO,PVDCOMPL,PVDBAIRRO,PVDUF,PVDCIDADE,PVDCEP,PVDTEL1,PVDTEL2,PVDTEL3,PVDEMAIL,PVDSITE,PVDSEGMENTO,PVDCADASTRO,PVDCADBY) VALUES
       ('$dominio_session','$pvdident','$pvdrazaonome','$pvdident','$pvdie','$pvdim','$pvdnomefant','$pvdcnae','$pvdlog','$pvdnumero','$pvdcompl','$pvdbairro','$pvduf','$pvdcidade','$pvdcep','$pvdtel1','$pvdtel2','$pvdtel3','$pvdemail','$pvdsite','$pvdsegmento','$datetime','$ident_session')") or print (mysqli_error());

				@mysqli_close($conn);

				include "../Conexoes/conecta_$dominio_session.php";
				
				if(!$PVDinsertPC){
					echo "<div class='error' align='center'>ATENÇÃO: A pvdinistradora <b>$pvdidentificador</b> não foi incluída com sucesso no Painel de Controle.<br>Assim, será necessário entrar em contato com a Central de Atendimento para que esta Administradora seja ativada em seu domínio, antes que possa ser utilizada.</div>";
				}*/
				
			}
		}
		
		if ($acao == 'EDITAR'){
	    $PVDID = $_POST['pvdid'];
		 
		  $PVDEDITselect = mysqli_query($con,"SELECT * FROM providers WHERE PVDID = $PVDID") or print(mysqli_error());
			
			while($PVDEDITrow = mysqli_fetch_assoc($PVDEDITselect)){
		 
				$PVDIDENTIFICADOR = $PVDEDITrow["PVDIDENTIFICADOR"];	
				$PVDCNPJ          = $PVDEDITrow["PVDCNPJ"];	
				$PVDTIPOCNPJ      = $PVDEDITrow["PVDTIPOCNPJ"];
				$PVDDTABERTURA    = $PVDEDITrow["PVDDTABERTURA"];			
				$PVDRAZAONOME     = $PVDEDITrow["PVDRAZAONOME"];
				$PVDIE            = $PVDEDITrow["PVDIE"];
				$PVDIM            = $PVDEDITrow["PVDIM"];
				$PVDNOMEFANT      = $PVDEDITrow["PVDNOMEFANT"];
				$PVDCNAE          = $PVDEDITrow["PVDCNAE"];
				$PVDNJ            = $PVDEDITrow["PVDNJ"];
				$PVDLOG           = $PVDEDITrow["PVDLOG"];
				$PVDNUMERO        = $PVDEDITrow["PVDNUMERO"];
				$PVDCOMPL         = $PVDEDITrow["PVDCOMPL"];
				$PVDBAIRRO        = $PVDEDITrow["PVDBAIRRO"];
				$PVDUF            = $PVDEDITrow["PVDUF"];
				$PVDCIDADE        = $PVDEDITrow["PVDCIDADE"];
				$PVDCEP           = $PVDEDITrow["PVDCEP"];
				$PVDTEL1          = $PVDEDITrow["PVDTEL1"];
				$PVDTEL2          = $PVDEDITrow["PVDTEL2"];
				$PVDTEL3          = $PVDEDITrow["PVDTEL3"];
				$PVDEMAIL         = $PVDEDITrow["PVDEMAIL"];
				$PVDSITE          = $PVDEDITrow["PVDSITE"];
				$PVDSEGMENTO      = $PVDEDITrow["PVDSEGMENTO"];
				
			}
			
			$NJselect = mysqli_query($con,"SELECT NJDESCRICAO FROM natjur WHERE NJCODIGO = '$PVDNJ'") or print(mysqli_error());
			$NJexiste = mysqli_num_rows($NJselect);

			if($NJexiste > 0){
				while($NJrow = mysqli_fetch_assoc($NJselect)){
					$NJDESCRICAO = $NJrow["NJDESCRICAO"];
				}
			}else{
				$NJDESCRICAO = NULL;
			}

			$CNAEselect = mysqli_query($con,"SELECT * FROM cnae20 WHERE CLASSECODIGO = '$PVDCNAE'") or print(mysqli_error());
			$CNAEtotal  = mysqli_num_rows($CNAEselect);

			if($CNAEtotal > 0){
				while($CNAErow = mysqli_fetch_assoc($CNAEselect)){
					$CLASSEDESCRICAO = $CNAErow["CLASSEDESCRICAO"];
					$classedescricao = "$PVDCNAE | $CLASSEDESCRICAO";
				}
				

			}else{
				$PVDCNAE = NULL;
				$classedescricao = "Não obtido da RFB. Selecione...";
			}
			
			$SEGselect = mysqli_query($con,"SELECT SEGDESCRICAO FROM segments WHERE SEGCODIGO = '$PVDSEGMENTO'") or print (mysqli_error());
			
			while($SEGrow = mysqli_fetch_assoc($SEGselect)){
				$SEGDESCRICAO = $SEGrow["SEGDESCRICAO"];
			}		  
			
		 ?>
		 
			<div class='panel panel-default'>

				<div class='panel-heading'>
					
					<span class='subpageName'>Editando o Cadastro do Fornecedor <b><?php echo $PVDIDENTIFICADOR;?></b> ...</span>

					<ul class='pull-right list-inline'>
						<li>
							<ul class='pull-right list-inline'>
								<li><a href='form_cad_providers.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
							</ul>
						</li>
					</ul>
					
				</div>

				<div class='panel-body'>
					
					<form class='form-horizontal' name='cadastro' method='POST' action='form_cad_providers.php' onsubmit='return cpfcnpj_informado(); return confirma_incluir();'>
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Identificador:</label>
							
							<div class='col-sm-4' >
								<input class='form-control' name='pvdidentificador' type='text' value='<?php echo $PVDIDENTIFICADOR;?>' readonly />
							</div>
							
							<label class='col-sm-1 control-label'>e-mail:</label>
							<div class='col-sm-5'><input class='form-control' name='pvdemail' type='email' value='<?php echo $PVDEMAIL;?>' required /></div>
							<div id='alert_consistencia_email'></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 control-label'>CNPJ:</label>
							<div class='col-sm-4' >
								<input class='form-control' name='pvdcnpj' type='text' value='<?php echo $PVDCNPJ;?>' readonly />
							</div>
							
							<label class='col-sm-1 control-label'>Tipo:</label>
							<div class='col-sm-2'>
								<select class='form-control' name='pvdtipocnpj'>
									<option value='<?php echo $PVDTIPOCNPJ;?>'><?php echo $PVDTIPOCNPJ;?></option>
									<option value='MATRIZ'>MATRIZ</option>
									<option value='FILIAL'>FILIAL</option>
								</select>							
							</div>
							
							<label class='col-sm-1 control-label'>Abertura:</label>
							<div class='col-sm-2'><input class='form-control' name='pvddtabertura' type='date' value='<?php echo $PVDDTABERTURA;?>' readonly /></div>
						</div>
							
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Nome Empresarial:</label>
							<div class='col-sm-4'><input class='form-control' name='pvdrazaonome' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $PVDRAZAONOME;?>' required /></div>
							
							<label class='col-sm-2 control-label'>Nome Fantasia:</label>
							<div class='col-sm-4'><input class='form-control' name='pvdfantasia' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $PVDNOMEFANT;?>' required /></div>
						</div>
							
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Ins. Estadual:</label>
							<div class='col-sm-2'><input class='form-control' name='pvdie' type='text' maxlength='12' value='<?php echo $PVDIE;?>'/></div>
							<div class='col-sm-2'></div>
							
							<label class='col-sm-2 control-label'>Ins. Municipal:</label>
							<div class='col-sm-2'><input class='form-control' name='pvdim' type='text' maxlength='12' value='<?php echo $PVDIM;?>'/></div>
							<div class='col-sm-2'></div>
						</div>
								
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Segmento:</label>
							<div class='col-sm-4'>
								<select class='form-control' name='pvdsegmento'>
									<option value='<?php echo $PVDSEGMENTO;?>'><?php echo "$SEGDESCRICAO ($PVDSEGMENTO)";?></option>					 
									
										<?php 
									
										$SEGselect = mysqli_query($con,"SELECT * FROM segments WHERE SEGSTATUS = 1") or print (mysqli_error());
										$SEGtotal = mysqli_num_rows($SEGselect);
										
										if($SEGtotal > 0){
											echo "<option value=''>Selecione...</option>";
											
											while($linha = mysqli_fetch_assoc($SEGselect)){
												$SEGCOD  = $linha["SEGCOD"];
												$SEGNAME = $linha["SEGNAME"];
												
												echo "<option value='$SEGCOD'>$SEGNAME ($SEGCOD)</option>";
											}
					
										}else{
											echo "<option value=''>Nenhum segmento cadastrado/ativo.</option>";
										}
										
										?>
										
								</select>
							</div>
							
							<label class='col-sm-2 control-label'>Natureza Jurídica:</label>
							<div class='col-sm-4'>
								<select class='form-control' name='pvdnj' required>
									<option value='<?php echo $PVDNJ;?>'><?php echo "$PVDNJ | $NJDESCRICAO";?></option>
									
									<?php
												
									$NJGRUPOselect = mysqli_query($con,"SELECT DISTINCT NJGRUPO FROM natjur WHERE NJCODIGO NOT IN('$PVDNJ') ORDER BY NJGRUPO") or print(mysqli_error());
									$NJGRUPOtotal  = mysqli_num_rows($NJGRUPOselect);
									
									if($NJGRUPOtotal > 0){
										
										while($NJGRUPOrow = mysqli_fetch_array($NJGRUPOselect)){
											$NJGRUPO = $NJGRUPOrow["NJGRUPO"];
											
											echo "<optgroup label='$NJGRUPO'>";
											
											$NJselect = mysqli_query($con,"SELECT * FROM natjur WHERE NJGRUPO = '$NJGRUPO' AND NJSITUACAO = 1 ORDER BY NJCODIGO") or print(mysqli_error());
											$NJtotal  = mysqli_num_rows($NJselect);
											
											if($NJtotal > 0){
												while($NJrow = mysqli_fetch_array($NJselect)){
													$NJCODIGO = $NJrow["NJCODIGO"];
													$NJDESCRICAO = $NJrow["NJDESCRICAO"];
													
													echo "<option value='$NJCODIGO'>$NJCODIGO | $NJDESCRICAO</option>";
													
												}
												
											}else{
												echo "<option value=''>Nenhuma Natureza Jurídica ativa neste Grupo.</option>";
											}
											
											echo "</optgroup>";
										
										}													
										
									}else{
										echo "<option value=''>Nenhuma outra Natureza Jurídica ativa.</option>";
									}												
									
									?>									
									
								</select>											
							</div>
						</div>
						
						<div class='form-group'>
						
							<label class='col-sm-2 control-label'>Atividade Principal:</label>
							<div class='col-sm-10'>
								<select class='form-control' name='pvdcnae'>
									<option value='<?php echo $PVDCNAE;?>'><?php echo $classedescricao;?></option>
									<?php
												
									$CNAEDIVISAOselect = mysqli_query($con,"SELECT DISTINCT DIVISAOCODIGO FROM cnae20 WHERE CNAESITUACAO = 1 ORDER BY DIVISAOCODIGO") or print(mysqli_error());
									$CNAEDIVISAOtotal  = mysqli_num_rows($CNAEDIVISAOselect);
									
									if($CNAEDIVISAOtotal > 0){
										
										while($CNAEDIVISAOrow = mysqli_fetch_array($CNAEDIVISAOselect)){
											$DIVISAOCODIGO = $CNAEDIVISAOrow["DIVISAOCODIGO"];
											
											$DIVISAODESCRICAOselect = mysqli_query($con,"SELECT DIVISAODESCRICAO FROM cnae20 WHERE DIVISAOCODIGO = '$DIVISAOCODIGO' LIMIT 1") or print(mysqli_error());
											
											while($DIVrow = mysqli_fetch_assoc($DIVISAODESCRICAOselect)){
												$DIVISAODESCRICAO = $DIVrow["DIVISAODESCRICAO"];
											}
											
											echo "<optgroup label='$DIVISAOCODIGO | $DIVISAODESCRICAO'>";														
											
											$CLASSEselect = mysqli_query($con,"SELECT CLASSECODIGO,CLASSEDESCRICAO FROM cnae20 WHERE DIVISAOCODIGO = '$DIVISAOCODIGO' AND CNAESITUACAO = 1 ORDER BY CLASSECODIGO") or print(mysqli_error());
											$CLASSEtotal  = mysqli_num_rows($CLASSEselect);
											
											if($CLASSEtotal > 0){
												while($CLASSErow = mysqli_fetch_array($CLASSEselect)){
													$CLASSECODIGO    = $CLASSErow["CLASSECODIGO"];
													$CLASSEDESCRICAO = $CLASSErow["CLASSEDESCRICAO"];
													
													echo "<option value='$CLASSECODIGO'>$CLASSECODIGO | $CLASSEDESCRICAO</option>";
													
												}
												
											}else{
												echo "<option value=''>Nenhuma classe ativa nesta Divisão.</option>";
											}
										
										}													
										
									}else{
										echo "<option value=''>Nenhuma outra Divisão CNAE ativa.</option>";
									}

									?>	
								</select>						
							</div>
							
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Website/Página:</label>
							<div class='col-sm-4'><input class='form-control' name='pvdsite' type='url' maxlength="50" onkeyup="minuscula(this)" value='<?php echo $PVDSITE;?>'/></div>
						</div>
								
							<fieldset><legend class='subpageName'><i class='fa fa-home'></i>&nbsp;&nbsp;Endereço Fiscal&nbsp;&nbsp;</legend>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>CEP:</label>
									<div class='col-sm-3'><input class='form-control' name='cep' id='cep' type="text" onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" maxlength='8' value='<?php echo $PVDCEP;?>' required /></div>
									<label class='col-sm-2 control-label'>Logradouro:</label>
									<div class='col-sm-5'><input class='form-control' name='rua' id='rua' type='text' maxlength='100' value='<?php echo $PVDLOG;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Número | Compl.:</label>
									<div class='col-sm-1'><input class='form-control' name='numero' type='text' maxlength='6' required placeholder='Nº...' value='<?php echo $PVDNUMERO;?>'/></div>
								
									<div class='col-sm-2'><input class='form-control' name='compl' type='text' maxlength='10' onkeyup='maiuscula(this)' placeholder='Complemento...' value='<?php echo $PVDCOMPL;?>'/></div>
								
									<label class='col-sm-2 control-label'>Bairro:</label>
									<div class='col-sm-5'><input class='form-control' name='bairro' id='bairro' type='text' maxlength='100' value='<?php echo $PVDBAIRRO;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Estado:</label>
									<div class='col-sm-3'><input class='form-control' name='estado' id='estado' type='text' maxlength='2' value='<?php echo $PVDUF;?>' required /></div>
									
									<label class='col-sm-2 control-label'>Município:</label>
									<div class='col-sm-5'><input class='form-control' name='cidade' id='cidade' type='text' maxlength='100' value='<?php echo $PVDCIDADE;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Telefones:</label>
									
									<div class='col-sm-2'><input class='form-control' name="pvdtel1" type="text" maxlength="15" onkeypress="formatar(this, '## ####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;"  value='<?php echo $PVDTEL1;?>' required /></div>
									<div class='col-sm-2'><input class='form-control' name="pvdtel2" type="text" maxlength="15" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $PVDTEL2;?>' /></div>
									<div class='col-sm-2'><input class='form-control' name="pvdtel3" type="text" maxlength="15" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $PVDTEL3;?>' /></div>
								</div>
							</fieldset><br>
							
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/>
						<div align='right'><input class='but but-rc but-azul' type='submit' value='     Salvar     '/></div>
					</form>
				</div>
			</div>	
									
		<?php
	   
	  }
	   
	  if($acao == 'CONFIRMAR_EDICAO'){
	    $pvdidentificador = $_POST['pvdidentificador'];
			$pvdrazaonome = $_POST['pvdrazaonome'];
			$pvdcnpj      = $_POST['pvdcnpj'];
			$pvdtipocnpj  = $_POST['pvdtipocnpj'];
			$pvdie        = $_POST['pvdie'];
			$pvdim        = $_POST['pvdim'];
			$pvdnj        = $_POST['pvdnj'];
			$pvdfantasia  = $_POST['pvdfantasia'];
			$pvdcnae      = $_POST['pvdcnae'];
			$pvdlog       = $_POST['rua'];
			$pvdnumero    = $_POST['numero'];
			$pvdsegmento  = $_POST['pvdsegmento'];
			$pvdcompl     = $_POST['compl'];
			$pvduf        = $_POST['estado'];
			$pvdbairro    = $_POST['bairro'];
			$pvdcep       = $_POST['cep'];
			$pvdcidade    = $_POST['cidade'];
			$pvdtel1      = $_POST['pvdtel1'];
			$pvdtel2      = $_POST['pvdtel2'];
			$pvdtel3      = $_POST['pvdtel3'];
			$pvdemail     = $_POST['pvdemail'];
			$pvdsite      = $_POST['pvdsite'];
			
			$PVDupdate = mysqli_query($con,"UPDATE providers SET PVDCNPJ = '$pvdcnpj',
																											 PVDTIPOCNPJ = '$pvdtipocnpj',
																														 PVDIE = '$pvdie',
																														 PVDIM = '$pvdim',
																											PVDRAZAONOME = '$pvdrazaonome',
																											 PVDNOMEFANT = '$pvdfantasia',
																													 PVDCNAE = '$pvdcnae',
																														 PVDNJ = '$pvdnj',
																														PVDLOG = '$pvdlog',
																												 PVDNUMERO = '$pvdnumero',
																													PVDCOMPL = '$pvdcompl',
																												 PVDBAIRRO = '$pvdbairro',
																														 PVDUF = '$pvduf',
																												 PVDCIDADE = '$pvdcidade',
																														PVDCEP = '$pvdcep',
																													 PVDTEL1 = '$pvdtel1',
																													 PVDTEL2 = '$pvdtel2',
																													 PVDTEL3 = '$pvdtel3',
																													PVDEMAIL = '$pvdemail',
																													 PVDSITE = '$pvdsite',
																											 PVDSEGMENTO = '$pvdsegmento'	WHERE PVDIDENTIFICADOR = '$pvdidentificador'") or print "<div class='error'><b>FALHA NA ATUALIZAÇÃO DOS DADOS DA EMPRESA.<br>ERRO DE BANCO DE DADOS: </b>".(mysqli_error())."</div>";				
			if($PVDupdate){
				
				echo "<div id='alert' align='center' class='success'>Cadastro do Fornecedor <b>$pvdidentificador</b> alterado com sucesso!</div>";
			
			}
	  }
	}//Fim das ações...
	
	$PVDselect = mysqli_query($con,"SELECT * FROM providers") or print(mysqli_error());	
	$PVDtotal  = mysqli_num_rows($PVDselect);

	if($PVDtotal > 0){
		echo"
		<table id='DataTables' class='display table table-responsive table-condensed table-action table-striped'>
			<thead>			
				<tr>
					<th width='2%' ><div align='center'>      </div>       </th>
					<th width='27%'><div>Razão Social</div> </th>
					<th width='10%'><div align='center'>Identificador</div></th>
					<th width='23%'><div>Nome Fantasia</div></th>
					<th width='10%'><div align='center'>Cidade</div>       </th>
					<th width='10%'data-orderable='false'><div align='center'>Telefones</div>    </th>
					<th width='15%'data-orderable='false'><div align='center'>e-mail</div>       </th>
					<th width='3%' data-orderable='false'>
						<div align='center'>
							<form name='incluir' method='POST' action='form_cad_providers.php' >
								<input name='acao' type='hidden' value='NOVA_PVD'/>
							  <button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
							</form>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>";
		
		while ($PVDrow = mysqli_fetch_array($PVDselect)){
			$PVDID            = $PVDrow["PVDID"];
			$PVDIDENTIFICADOR = $PVDrow["PVDIDENTIFICADOR"];
			$PVDRAZAONOME     = $PVDrow["PVDRAZAONOME"];
			$PVDCNPJ          = $PVDrow["PVDCNPJ"];
			$PVDIE            = $PVDrow["PVDIE"];
			$PVDIM            = $PVDrow["PVDIM"];
			$PVDNOMEFANT      = $PVDrow["PVDNOMEFANT"];
			$PVDCNAE          = $PVDrow["PVDCNAE"];
			$PVDLOG           = $PVDrow["PVDLOG"];
			$PVDNUMERO        = $PVDrow["PVDNUMERO"];
			$PVDCOMPL         = $PVDrow["PVDCOMPL"];
			$PVDBAIRRO        = $PVDrow["PVDBAIRRO"];
			$PVDUF            = $PVDrow["PVDUF"];
			$PVDCIDADE        = $PVDrow["PVDCIDADE"];
			$PVDCEP           = $PVDrow["PVDCEP"];
			$PVDTEL1          = $PVDrow["PVDTEL1"];
			$PVDTEL2          = $PVDrow["PVDTEL2"];
			$PVDTEL3          = $PVDrow["PVDTEL3"];
			$PVDEMAIL         = $PVDrow["PVDEMAIL"];
			$PVDSITE          = $PVDrow["PVDSITE"];
			$PVDSEGMENTO      = $PVDrow["PVDSEGMENTO"];
			$PVDSTATUS        = $PVDrow["PVDSTATUS"];
			
			switch($PVDSTATUS){			
				case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO'/>";  break;
				case 1: $situacao = "<img src='../images/status/VERDE.png'    title='ATIVO'/>";  break;
			}
				
			echo"
			<tr>
				<td><div align='center'>$situacao</div></td>
				<td><div>$PVDRAZAONOME</div></td>
				<td><div align='center'><b>$PVDIDENTIFICADOR</b></div></td>
				<td><div>$PVDNOMEFANT</div></td>
				<td><div align='center' title='Endereço: $PVDLOG, $PVDNUMERO, $PVDCOMPL Bairro $PVDBAIRRO' >$PVDCIDADE - $PVDUF</div></td>
				<td><div align='center' title='Demais Telefones: $PVDTEL2 | $PVDTEL3'>$PVDTEL1</div></td>
				<td><div align='center'>$PVDEMAIL</div></td>
				<td>
					<div id='cssmenu' align='center'>
						<ul>
							<li class='has-sub'><a href='#'><span></span></a>
								<ul>
									<li>
										<form name='editar' method='POST' action='form_cad_providers.php'>
											<input name='pvdid' type='hidden' value='$PVDID'/>
											<input name='acao'  type='hidden' value='EDITAR'/>
											<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
										</form>
									</li>
									<li>
										<form name='dados_bancarios' method='POST' action='form_cadastro_cb.php' >
											<input name='cbidtitular'   type='hidden' value='$PVDIDENTIFICADOR'/>
											<input name='cbnometitular' type='hidden' value='$PVDRAZAONOME'/>
											<input name='cbtipotitular' type='hidden' value='PVD'/>
											<input name='cbcgctitular'  type='hidden' value='$PVDCNPJ'/>
											<input name='voltar'        type='hidden' value='form_cad_providers.php'/>
											<button class='btn btn-default btn-xs' title='Contas bancárias'><i class='fa fa-credit-card' aria-hidden='true'></i></button>
										</form>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</td>
			</tr>";
		}
		
		echo "</tbody></table>";
	
	}else{ 
		echo "<div align='center' class='info'>Nenhum Fornecedor cadastrado!</div>
		<div align='center'>
			<form name='incluir' method='POST' action='form_cad_providers.php' >
				<input name='acao' type='hidden' value='NOVA_PVD'/>
				<input class='inputsrc' type='image' src='../images/add.png' title=' Incluir '/>
			</form>
		</div>";
	}
	
  ?>
</div>
</div>
</div>
</body>
</html>