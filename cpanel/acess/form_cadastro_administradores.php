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
		
		$(document).ready( function() {
			/* Executa a requisição quando o campo CEP perder o foco */
			$('#cep').blur(function(){
			/* Configura a requisição AJAX */
			$.ajax({
				url: 'consultar_cep.php', /* URL que será chamada */ 
				type: 'POST', /* Tipo da requisição */ 
				data: 'address=' + $('#cep').val(), /* dado que será enviado via POST */
				dataType: 'json', /* Tipo de transmissão */
				success: function(data){
					if(data.sucesso == 1){
						$('#rua').val(data.rua);
						$('#bairro').val(data.bairro);
						$('#cidade').val(data.cidade);
						$('#estado').val(data.estado);
						$('#numero').focus();
					}
				}
			});  

			return false; 

			})
		});

	</script>
	
<body>
<div class='container-fluid'>

	<div class='panel panel-default'>
		
		<div class='panel-heading'>
			<span class='subpageName'>Empresas (administradoras) do Domínio <b><?php echo $dominio_session?></b></span>

			<ul class='pull-right list-inline'>
				<li>
				
				</li>
			</ul>
		</div>
		
		<div class='panel-body'>

	<?php

 	if (isset($_POST['acao'])){
	  $acao = $_POST['acao'];
		
		if($acao == 'NOVA_ADM'){
			?>
			
			<div class='panel panel-default'>

				<div class='panel-heading'>
					
					<span class='subpageName'>Incluir Empresa no Domínio <b><?php echo $dominio_session;?></b></span>

					<ul class='pull-right list-inline'>
						<li>
							<ul class='pull-right list-inline'>
								<li><a href='form_cadastro_administradores.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
							</ul>
						</li>
					</ul>
					
				</div>

				<div class='panel-body'>
			
					<form class='form-horizontal' name='cadastro' method='POST' action='form_cadastro_administradores.php'>
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Número de Inscrição:</label>
							
							<div class='col-sm-4' >
								<input class='form-control' name='idcnpj' type='text' id='cnpj' onkeypress='if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='14' placeholder='CNPJ (Somente números)...' required />
							</div>
						</div>
						
						<div class='form-group'>	
							<label class='col-sm-6 control-label'>e-mail:</label>
							<div class='col-sm-4'><input class='form-control' name='email' type='email' id='email' onKeyUp='return Valida_email(this.id)' required /></div>
						</div>
						
						<input name='acao'  type='hidden' value='AVANCAR'>
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
			
			    $ADMselect = mysqli_query($con,"SELECT ADMIDENTIFICADOR FROM administradores WHERE ADMCNPJ = '$DOCUMENTO'") or print(mysqli_error());
					$ADMtotal  = mysqli_num_rows($ADMselect);
					
					if($ADMtotal > 0){
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
								
								<span class='subpageName'>Inclusão de Empresa no Domínio <b><?php echo $dominio_session;?></b></span>

								<ul class='pull-right list-inline'>
									<li>
										<ul class='pull-right list-inline'>
											<li><a href='form_cadastro_administradores.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
										</ul>
									</li>
								</ul>
								
							</div>

							<div class='panel-body'>
								
								<form class='form-horizontal' name='cadastro' method='POST' action='form_cadastro_administradores.php' onsubmit='return cpfcnpj_informado(); return confirma_incluir();'>
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Identificador:</label>
										
										<div class='col-sm-4' >
											<input class='form-control' name='admidentificador' type='text' value='<?php echo $CNPJ;?>' readonly />
										</div>
										
										<label class='col-sm-1 control-label'>e-mail:</label>
										<div class='col-sm-5'><input class='form-control' name='admemail' type='email' value='<?php echo $email;?>' readonly /></div>
										<div id='alert_consistencia_email'></div>
									</div>
									
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Número de Inscrição:</label>
										<div class='col-sm-4' >
											<input class='form-control' name='admcnpj' type='text' value='<?php echo $Cnpj;?>' readonly />
										</div>
										
										<label class='col-sm-1 control-label'>Tipo:</label>
										<div class='col-sm-2'>
											<select class='form-control' name='admtipocnpj' <?php echo $readonly;?> >
											<?php echo $options;?>
											</select>										
										</div>
										
										<label class='col-sm-1 control-label'>Abertura:</label>
										<div class='col-sm-2'><input class='form-control' name='admdtabertura' type='date' value='<?php echo $dtabertura;?>' readonly /></div>
									</div>
										
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Nome Empresarial:</label>
										<div class='col-sm-4'><input class='form-control' name="admrazaonome" type="text" maxlength='100' onkeyup="maiuscula(this)" value='<?php echo $RazaoSocial;?>' required /></div>
										
										<label class='col-sm-2 control-label'>Nome Fantasia:</label>
										<div class='col-sm-4'><input class='form-control' name="admfantasia" type="text" maxlength='100' onkeyup="maiuscula(this)" value='<?php echo $NomeFantasia;?>' required /></div>
									</div>
										
									<div class='form-group'>
										<label class='col-sm-2 control-label'>Ins. Estadual:</label>
										<div class='col-sm-2'><input class='form-control' name="admie" type="text" maxlength='15' /></div>
										<div class='col-sm-2'></div>
										
										<label class='col-sm-2 control-label'>Ins. Municipal:</label>
										<div class='col-sm-2'><input class='form-control' name="admim" type="text" maxlength='15' /></div>
										<div class='col-sm-2'></div>
									</div>
											
									<div class='form-group'>
										
										<label class='col-sm-2 control-label'>Segmento:</label>
										<div class='col-sm-4'>
											<select class='form-control' name='admsegmento'>
																	 
												<?php 
									
												$SEGselect = mysqli_query($con,"SELECT * FROM segmentos WHERE SEGSTATUS = 1 ORDER BY SEGDESCRICAO") or print (mysqli_error());
												$SEGtotal = mysqli_num_rows($SEGselect);
												
												if($SEGtotal > 0){
													echo "<option value=''>Selecione...</option>";
													
													while($linha = mysqli_fetch_assoc($SEGselect)){
														$SEGCODIGO = $linha["SEGCODIGO"];
														$SEGDESCRICAO = $linha["SEGDESCRICAO"];
														
														echo "<option value='$SEGCODIGO'>$SEGDESCRICAO ($SEGCODIGO)</option>";
													}
							
												}else{
													echo "<option value=''>Nenhum segmento cadastrado/ativo.</option>";
												}
												
												?>
											</select>
										</div>
										
										<label class='col-sm-2 control-label'>Natureza Jurídica:</label>
										<div class='col-sm-4'>
											<select class='form-control' name='admnj' required >
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
											<select class='form-control' name='admcnae' required >
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
										<div class='col-sm-4'><input class='form-control' name='admsite' type='url' maxlength="50" onkeyup="minuscula(this)"/></div>
									</div>
											
									<div class='panel panel-default'>

										<div class='panel-heading'><span class='subpageName'>Endereço Fiscal</span></div>

										<div class='panel-body'>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>CEP:</label>
												<div class='col-sm-3'><input class='form-control' name='cep' id='cep' type="text" onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" maxlength='8' value='<?php echo $Cep;?>' required /></div>
												
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
												
												<div class='col-sm-2'><input class='form-control' name="admtel1" type="text" maxlength="13" onkeypress="formatar(this, '## ####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;"  placeholder ='## ####-####' value='<?php echo $Telefone;?>' required /></div>
												<div class='col-sm-2'><input class='form-control' name="admtel2" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" placeholder ='Celular (## #####-####)' /></div>
												<div class='col-sm-2'><input class='form-control' name="admtel3" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" placeholder ='Celular (## #####-####)' /></div>
											
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
			
			$admident      = $_POST['admidentificador'];	
      $admcnpj       = $_POST['admcnpj'];	
      $admtipocnpj   = $_POST['admtipocnpj'];
      $admdtabertura = $_POST['admdtabertura'];			
			$admrazaonome  = $_POST['admrazaonome'];
			$admie         = $_POST['admie'];
			$admim         = $_POST['admim'];
			$admnomefant   = $_POST['admfantasia'];
			$admcnae       = $_POST['admcnae'];
			$admnj         = $_POST['admnj'];
			$admlog        = $_POST['rua'];
			$admnumero     = $_POST['numero'];
			$admcompl      = $_POST['compl'];
			$admbairro     = $_POST['bairro'];
			$admuf         = $_POST['estado'];
			$admcidade     = $_POST['cidade'];
			$admcep        = $_POST['cep'];
			$admtel1       = $_POST['admtel1'];
			$admtel2       = $_POST['admtel2'];
			$admtel3       = $_POST['admtel3'];
			$admemail      = $_POST['admemail'];
			$admsite       = $_POST['admsite'];
			$admsegmento   = $_POST['admsegmento'];
			
			$ADMinsert = mysqli_query($con,"INSERT INTO administradores(ADMDOMINIO,ADMIDENTIFICADOR,ADMRAZAONOME,ADMCNPJ,ADMTIPOCNPJ,ADMDTABERTURA,ADMIE,ADMIM,ADMNOMEFANT,ADMCNAE,ADMNJ,ADMLOG,ADMNUMERO,ADMCOMPL,ADMBAIRRO,ADMUF,ADMCIDADE,ADMCEP,ADMTEL1,ADMTEL2,ADMTEL3,ADMEMAIL,ADMSITE,ADMSEGMENTO,ADMCADASTRO,ADMCADBY) VALUES
      ('$dominio_session','$admident','$admrazaonome','$admcnpj','$admtipocnpj','$admdtabertura','$admie','$admim','$admnomefant','$admcnae','$admnj','$admlog','$admnumero','$admcompl','$admbairro','$admuf','$admcidade','$admcep','$admtel1','$admtel2','$admtel3','$admemail','$admsite','$admsegmento','$datetime','$ident_session')") or print (mysqli_error());
			
			if($ADMinsert){
				
				echo "<div id='alert' class='success' align='center'>Empresa <b>$admident</b> cadastrada com sucesso.</div>";
				
				/*@mysqli_close($conn); // Se incluiu na base local, inclui no Painel de Controle da PMS.net para que possa ser gerenciada por lá.

				include "../Conexoes/conecta_cpane.php";

				$ADMinsertPC = mysqli_query($con,"INSERT INTO administradores(ADMDOMINIO,ADMIDENTIFICADOR,ADMRAZAONOME,ADMCNPJ,ADMIE,ADMIM,ADMNOMEFANT,ADMCNAE,ADMLOG,ADMNUMERO,ADMCOMPL,ADMBAIRRO,ADMUF,ADMCIDADE,ADMCEP,ADMTEL1,ADMTEL2,ADMTEL3,ADMEMAIL,ADMSITE,ADMSEGMENTO,ADMCADASTRO,ADMCADBY) VALUES
       ('$dominio_session','$admident','$admrazaonome','$admident','$admie','$admim','$admnomefant','$admcnae','$admlog','$admnumero','$admcompl','$admbairro','$admuf','$admcidade','$admcep','$admtel1','$admtel2','$admtel3','$admemail','$admsite','$admsegmento','$datetime','$ident_session')") or print (mysqli_error());

				@mysqli_close($conn);

				include "../Conexoes/conecta_$dominio_session.php";
				
				if(!$ADMinsertPC){
					echo "<div class='error' align='center'>ATENÇÃO: A administradora <b>$admidentificador</b> não foi incluída com sucesso no Painel de Controle.<br>Assim, será necessário entrar em contato com a Central de Atendimento para que esta Administradora seja ativada em seu domínio, antes que possa ser utilizada.</div>";
				}*/
				
			}
		}
		
		if ($acao == 'EDITAR'){
	    $ADMID = $_POST['admid'];
		 
		  $ADMEDITselect = mysqli_query($con,"SELECT * FROM administradores WHERE ADMID = $ADMID") or print(mysqli_error());
			
			while($ADMEDITrow = mysqli_fetch_array($ADMEDITselect)){
		 
				$ADMIDENTIFICADOR = $ADMEDITrow['ADMIDENTIFICADOR'];	
				$ADMCNPJ          = $ADMEDITrow['ADMCNPJ'];	
				$ADMTIPOCNPJ      = $ADMEDITrow['ADMTIPOCNPJ'];
				$ADMDTABERTURA    = $ADMEDITrow['ADMDTABERTURA'];			
				$ADMRAZAONOME     = $ADMEDITrow['ADMRAZAONOME'];
				$ADMIE            = $ADMEDITrow['ADMIE'];
				$ADMIM            = $ADMEDITrow['ADMIM'];
				$ADMNOMEFANT      = $ADMEDITrow['ADMNOMEFANT'];
				$ADMCNAE          = $ADMEDITrow['ADMCNAE'];
				$ADMNJ            = $ADMEDITrow['ADMNJ'];
				$ADMLOG           = $ADMEDITrow['ADMLOG'];
				$ADMNUMERO        = $ADMEDITrow['ADMNUMERO'];
				$ADMCOMPL         = $ADMEDITrow['ADMCOMPL'];
				$ADMBAIRRO        = $ADMEDITrow['ADMBAIRRO'];
				$ADMUF            = $ADMEDITrow['ADMUF'];
				$ADMCIDADE        = $ADMEDITrow['ADMCIDADE'];
				$ADMCEP           = $ADMEDITrow['ADMCEP'];
				$ADMTEL1          = $ADMEDITrow['ADMTEL1'];
				$ADMTEL2          = $ADMEDITrow['ADMTEL2'];
				$ADMTEL3          = $ADMEDITrow['ADMTEL3'];
				$ADMEMAIL         = $ADMEDITrow['ADMEMAIL'];
				$ADMSITE          = $ADMEDITrow['ADMSITE'];
				$ADMSEGMENTO      = $ADMEDITrow['ADMSEGMENTO'];
			
			}
			
			$NJselect = mysqli_query($con,"SELECT NJDESCRICAO FROM natjur WHERE NJCODIGO = '$ADMNJ'") or print(mysqli_error());
			$NJexiste = mysqli_num_rows($NJselect);

			if($NJexiste > 0){
				while($NJrow = mysqli_fetch_assoc($NJselect)){
					$NJDESCRICAO = $NJrow["NJDESCRICAO"];
				}
			}else{
				$NJDESCRICAO = NULL;
			}

			$CNAEselect = mysqli_query($con,"SELECT * FROM cnae20 WHERE CLASSECODIGO = '$ADMCNAE'") or print(mysqli_error());
			$CNAEtotal  = mysqli_num_rows($CNAEselect);

			if($CNAEtotal > 0){
				
				while($CNAErow = mysqli_fetch_assoc($CNAEselect)){
					$CLASSEDESCRICAO = $CNAErow["CLASSEDESCRICAO"];
					$classedescricao = "$ADMCNAE | $CLASSEDESCRICAO";
				}				

			}else{
				
				$ADMCNAE = NULL;
				$classedescricao = "Não obtido da RFB. Selecione...";
				
			}
			
			$SEGselect = mysqli_query($con,"SELECT SEGDESCRICAO FROM segments WHERE SEGCODIGO = '$ADMSEGMENTO'") or print (mysqli_error());
			
			while($SEGrow = mysqli_fetch_assoc($SEGselect)){
				$SEGDESCRICAO = $SEGrow["SEGDESCRICAO"];
			}		  
		  
			
		 ?>
		 
			<div class='panel panel-default'>

				<div class='panel-heading'>
					
					<span class='subpageName'>Editando o cadastro da Administradora <b><?php echo $ADMIDENTIFICADOR;?></b> ...</span>

					<ul class='pull-right list-inline'>
						<li>
							<ul class='pull-right list-inline'>
								<li><a href='form_cadastro_administradores.php' title='Fechar'><i class='fa fa-times' aria-hidden='true'></i></a></li>
							</ul>
						</li>
					</ul>
					
				</div>

				<div class='panel-body'>
					
					<form class='form-horizontal' name='cadastro' method='POST' action='form_cadastro_administradores.php' onsubmit='return cpfcnpj_informado(); return confirma_incluir();'>
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Identificador:</label>
							
							<div class='col-sm-4' >
								<input class='form-control' name='admidentificador' type='text' value='<?php echo $ADMIDENTIFICADOR;?>' readonly />
							</div>
							
							<label class='col-sm-1 control-label'>e-mail:</label>
							<div class='col-sm-5'><input class='form-control' name='admemail' type='email' value='<?php echo $ADMEMAIL;?>' required /></div>
							<div id='alert_consistencia_email'></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-2 control-label'>CNPJ:</label>
							<div class='col-sm-4' >
								<input class='form-control' name='admcnpj' type='text' value='<?php echo $ADMCNPJ;?>' readonly />
							</div>
							
							<label class='col-sm-1 control-label'>Tipo:</label>
							<div class='col-sm-2'>
								<select class='form-control' name='admtipocnpj'>
									<option value='<?php echo $ADMTIPOCNPJ;?>'><?php echo $ADMTIPOCNPJ;?></option>
									<option value='MATRIZ'>MATRIZ</option>
									<option value='FILIAL'>FILIAL</option>
								</select>							
							</div>
							
							<label class='col-sm-1 control-label'>Abertura:</label>
							<div class='col-sm-2'><input class='form-control' name='admdtabertura' type='date' value='<?php echo $ADMDTABERTURA;?>' readonly /></div>
						</div>
							
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Nome Empresarial:</label>
							<div class='col-sm-4'><input class='form-control' name='admrazaonome' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $ADMRAZAONOME;?>' required /></div>
							
							<label class='col-sm-2 control-label'>Nome Fantasia:</label>
							<div class='col-sm-4'><input class='form-control' name='admfantasia' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $ADMNOMEFANT;?>' required /></div>
						</div>
							
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Ins. Estadual:</label>
							<div class='col-sm-2'><input class='form-control' name='admie' type='text' maxlength='15' value='<?php echo $ADMIE;?>'/></div>
							<div class='col-sm-2'></div>
							
							<label class='col-sm-2 control-label'>Ins. Municipal:</label>
							<div class='col-sm-2'><input class='form-control' name='admim' type='text' maxlength='15' value='<?php echo $ADMIM;?>'/></div>
							<div class='col-sm-2'></div>
						</div>
								
						<div class='form-group'>
							<label class='col-sm-2 control-label'>Segmento:</label>
							<div class='col-sm-4'>
								<select class='form-control' name='admsegmento'>
									<option value='<?php echo $ADMSEGMENTO;?>'><?php echo "$SEGDESCRICAO ($ADMSEGMENTO)";?></option>					 
									
										<?php 
									
										$SEGselect = mysqli_query($con,"SELECT * FROM segmentos WHERE SEGSTATUS = 1 ORDER BY SEGDESCRICAO") or print (mysqli_error());
										$SEGtotal = mysqli_num_rows($SEGselect);
										
										if($SEGtotal > 0){
											echo "<option value=''>Selecione...</option>";
											
											while($linha = mysqli_fetch_assoc($SEGselect)){
												$SEGCODIGO = $linha["SEGCODIGO"];
												$SEGDESCRICAO = $linha["SEGDESCRICAO"];
												
												echo "<option value='$SEGCODIGO'>$SEGDESCRICAO ($SEGCODIGO)</option>";
											}
					
										}else{
											echo "<option value=''>Nenhum segmento cadastrado/ativo.</option>";
										}
										
										?>
										
								</select>
							</div>
							
							<label class='col-sm-2 control-label'>Natureza Jurídica:</label>
							<div class='col-sm-4'>
								<select class='form-control' name='admnj' required>
									<option value='<?php echo $ADMNJ;?>'><?php echo "$ADMNJ | $NJDESCRICAO";?></option>
									
									<?php
												
									$NJGRUPOselect = mysqli_query($con,"SELECT DISTINCT NJGRUPO FROM natjur WHERE NJCODIGO NOT IN('$ADMNJ') ORDER BY NJGRUPO") or print(mysqli_error());
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
								<select class='form-control' name='admcnae'>
									<option value='<?php echo $ADMCNAE;?>'><?php echo $classedescricao;?></option>
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
							<div class='col-sm-4'><input class='form-control' name='admsite' type='url' maxlength="50" onkeyup="minuscula(this)" value='<?php echo $ADMSITE;?>'/></div>
						</div>
								
							<fieldset><legend class='subpageName'><i class='fa fa-home'></i>&nbsp;&nbsp;Endereço Fiscal&nbsp;&nbsp;</legend>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>CEP:</label>
									<div class='col-sm-3'><input class='form-control' name='cep' id='cep' type="text" onkeypress="formatar(this, '##.###-###');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" maxlength='10' value='<?php echo $ADMCEP;?>' required /></div>
									<label class='col-sm-2 control-label'>Logradouro:</label>
									<div class='col-sm-5'><input class='form-control' name='rua' id='rua' type='text' maxlength='100' value='<?php echo $ADMLOG;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Número | Compl.:</label>
									<div class='col-sm-1'><input class='form-control' name='numero' type='text' maxlength='6' required placeholder='Nº...' value='<?php echo $ADMNUMERO;?>'/></div>
								
									<div class='col-sm-2'><input class='form-control' name='compl' type='text' maxlength='10' onkeyup='maiuscula(this)' placeholder='Complemento...' value='<?php echo $ADMCOMPL;?>'/></div>
								
									<label class='col-sm-2 control-label'>Bairro:</label>
									<div class='col-sm-5'><input class='form-control' name='bairro' id='bairro' type='text' maxlength='100' value='<?php echo $ADMBAIRRO;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Estado:</label>
									<div class='col-sm-3'><input class='form-control' name='estado' id='estado' type='text' maxlength='2' value='<?php echo $ADMUF;?>' required /></div>
									
									<label class='col-sm-2 control-label'>Município:</label>
									<div class='col-sm-5'><input class='form-control' name='cidade' id='cidade' type='text' maxlength='100' value='<?php echo $ADMCIDADE;?>' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Telefones:</label>
									
									<div class='col-sm-2'><input class='form-control' name="admtel1" type="text" maxlength="13" onkeypress="formatar(this, '## ####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;"  value='<?php echo $ADMTEL1;?>' required /></div>
									<div class='col-sm-2'><input class='form-control' name="admtel2" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $ADMTEL2;?>' /></div>
									<div class='col-sm-2'><input class='form-control' name="admtel3" type="text" maxlength="13" onkeypress="formatar(this, '## #####-####');if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $ADMTEL3;?>' /></div>
								</div>
							</fieldset><br>
							
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/>
						<div align='right'><button class='btn btn-info btn-sm' type='submit'>Salvar</button></div>
					</form>
				</div>
			</div>	
									
		<?php
	   
	  }
	   
	  if($acao == 'CONFIRMAR_EDICAO'){
	    $admidentificador = $_POST['admidentificador'];
			$admrazaonome = $_POST['admrazaonome'];
			$admcnpj      = $_POST['admcnpj'];
			$admtipocnpj  = $_POST['admtipocnpj'];
			$admie        = $_POST['admie'];
			$admim        = $_POST['admim'];
			$admnj        = $_POST['admnj'];
			$admfantasia  = $_POST['admfantasia'];
			$admcnae      = $_POST['admcnae'];
			$admlog       = $_POST['rua'];
			$admnumero    = $_POST['numero'];
			$admsegmento  = $_POST['admsegmento'];
			$admcompl     = $_POST['compl'];
			$admuf        = $_POST['estado'];
			$admbairro    = $_POST['bairro'];
			$admcep       = $_POST['cep'];
			$admcidade    = $_POST['cidade'];
			$admtel1      = $_POST['admtel1'];
			$admtel2      = $_POST['admtel2'];
			$admtel3      = $_POST['admtel3'];
			$admemail     = $_POST['admemail'];
			$admsite      = $_POST['admsite'];
			
			$ADMupdate = mysqli_query($con,"UPDATE administradores SET ADMCNPJ = '$admcnpj',
																											 ADMTIPOCNPJ = '$admtipocnpj',
																														 ADMIE = '$admie',
																														 ADMIM = '$admim',
																											ADMRAZAONOME = '$admrazaonome',
																											 ADMNOMEFANT = '$admfantasia',
																													 ADMCNAE = '$admcnae',
																														 ADMNJ = '$admnj',
																														ADMLOG = '$admlog',
																												 ADMNUMERO = '$admnumero',
																													ADMCOMPL = '$admcompl',
																												 ADMBAIRRO = '$admbairro',
																														 ADMUF = '$admuf',
																												 ADMCIDADE = '$admcidade',
																														ADMCEP = '$admcep',
																													 ADMTEL1 = '$admtel1',
																													 ADMTEL2 = '$admtel2',
																													 ADMTEL3 = '$admtel3',
																													ADMEMAIL = '$admemail',
																													 ADMSITE = '$admsite',
																											 ADMSEGMENTO = '$admsegmento'	WHERE ADMIDENTIFICADOR = '$admidentificador'") or print "<div class='error'><b>FALHA NA ATUALIZAÇÃO DOS DADOS DA EMPRESA.<br>ERRO DE BANCO DE DADOS: </b>".(mysqli_error())."</div>";				
			if($ADMupdate){
				
				echo "<div id='alert' align='center' class='success'>Cadastro da Empresa <b>$admidentificador</b> alterado com sucesso!</div>";
			
			}
	  }
	}//Fim das ações...
	
	$ADMselect = mysqli_query($con,"SELECT * FROM administradores ORDER BY ADMCADASTRO DESC") or print(mysqli_error());
	$ADMtotal  = mysqli_num_rows($ADMselect);

	if($ADMtotal > 0){
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
							<form name='incluir' method='POST' action='form_cadastro_administradores.php' >
								<input name='acao' type='hidden' value='NOVA_ADM'/>
							  <button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
							</form>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>";
		
		while ($ADMrow = mysqli_fetch_array($ADMselect)){
			$ADMID            = $ADMrow['ADMID'];
			$ADMIDENTIFICADOR = $ADMrow['ADMIDENTIFICADOR'];
			$ADMRAZAONOME     = $ADMrow['ADMRAZAONOME'];
			$ADMCNPJ          = $ADMrow['ADMCNPJ'];
			$ADMIE            = $ADMrow['ADMIE'];
			$ADMIM            = $ADMrow['ADMIM'];
			$ADMNOMEFANT      = $ADMrow['ADMNOMEFANT'];
			$ADMCNAE          = $ADMrow['ADMCNAE'];
			$ADMLOG           = $ADMrow['ADMLOG'];
			$ADMNUMERO        = $ADMrow['ADMNUMERO'];
			$ADMCOMPL         = $ADMrow['ADMCOMPL'];
			$ADMBAIRRO        = $ADMrow['ADMBAIRRO'];
			$ADMUF            = $ADMrow['ADMUF'];
			$ADMCIDADE        = $ADMrow['ADMCIDADE'];
			$ADMCEP           = $ADMrow['ADMCEP'];
			$ADMTEL1          = $ADMrow['ADMTEL1'];
			$ADMTEL2          = $ADMrow['ADMTEL2'];
			$ADMTEL3          = $ADMrow['ADMTEL3'];
			$ADMEMAIL         = $ADMrow['ADMEMAIL'];
			$ADMSITE          = $ADMrow['ADMSITE'];
			$ADMSEGMENTO      = $ADMrow['ADMSEGMENTO'];
			$ADMSTATUS        = $ADMrow['ADMSTATUS'];
			
			switch($ADMSTATUS){
				case 0: $situacao = "<img src='../images/status/AZUL.png'     title='NOVO - Aguardando Confirmação de Contratação.'/>"; break;
				case 1: $situacao = "<img src='../images/status/AMARELO.png'  title='NOVO - Aguardando Instalação.'/>";  break;
				case 2: $situacao = "<img src='../images/status/VERDE.png'    title='ATIVO'/>";  break;
				case 3: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO'/>";  break;
			}
				
			echo"
			<tr>
				<td><div align='center'>$situacao</div></td>
				<td><div>$ADMRAZAONOME</div></td>
				<td><div align='center'><b>$ADMIDENTIFICADOR</b></div></td>
				<td><div>$ADMNOMEFANT</div></td>
				<td><div align='center' title='Endereço: $ADMLOG, $ADMNUMERO, $ADMCOMPL Bairro $ADMBAIRRO' >$ADMCIDADE - $ADMUF</div></td>
				<td><div align='center' title='Demais Telefones: $ADMTEL2 | $ADMTEL3'>$ADMTEL1</div></td>
				<td><div align='center'>$ADMEMAIL</div></td>
				<td>
					<div id='cssmenu' align='center'>
						<ul>
							<li class='has-sub'><a href='#'><span></span></a>
								<ul>
									<li>
										<form name='editar' method='POST' action='form_cadastro_administradores.php'>
											<input name='admid' type='hidden' value='$ADMID'/>
											<input name='acao'  type='hidden' value='EDITAR'/>
											<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
										</form>
									</li>
									<li>
										<form name='dados_bancarios' method='POST' action='form_cadastro_cb.php' >
											<input name='cbidtitular'   type='hidden' value='$ADMIDENTIFICADOR'/>
											<input name='cbnometitular' type='hidden' value='$ADMRAZAONOME'/>
											<input name='cbtipotitular' type='hidden' value='ADM'/>
											<input name='cbcgctitular'  type='hidden' value='$ADMCNPJ'/>
											<input name='voltar'        type='hidden' value='form_cadastro_administradores.php'/>
											<button class='btn btn-default btn-xs' title='Contas bancárias'><i class='fa fa-university' aria-hidden='true'></i></button>
										</form>
									</li>
									<li>
										<form name='cartoes' method='POST' action='form_cadastro_cc.php' >
											<input name='cctipoutilizador' type='hidden' value='ADM'/>
											<input name='ccidutilizador'   type='hidden' value='$ADMIDENTIFICADOR'/>											
											<input name='voltar'           type='hidden' value='form_cadastro_administradores.php'/>
											<button class='btn btn-default btn-xs' title='Cartões'><i class='fa fa-credit-card' aria-hidden='true'></i></button>
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
		echo "<div align='center' class='info'>Nenhum administrador cadastrado!</div>
		<div align='center'>
			<form name='incluir' method='POST' action='form_cadastro_administradores.php' >
				<input name='acao' type='hidden' value='NOVA_ADM'/>
				<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
			</form>
		</div>";
	}
	
  ?>
</div>
</div>
</div>
</body>
</html>