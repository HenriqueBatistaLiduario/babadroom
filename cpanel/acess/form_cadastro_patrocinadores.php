<?php include 'head.php';?>

	<script type="text/javascript">	

		$(document).ready(function(){
			$("#pavalorhora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#pavalorkm").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
		});

		/*$(document).ready
		(function(){
			$('#uf').change
			(function(){
				$('#cidade').load('select_cidades.php?uf='+$('#uf').val());
			}
			);
			}
		);*/

		function confirma_ativar(){
			if(confirm('CONFIRMA ATIVAÇÃO deste Patrocinador? O acesso ao sistema será liberado imediatamente, e será necessário ativar cada contato individualmente.\n\nClique em OK para continuar...'))
				return true;
			else
				return false;
		}

		function confirma_inativar(){
			if(confirm('CONFIRMA INATIVAÇÃO deste Patrocinador? O acesso ao sistema será bloqueado imediatamente. Todos os contatos deste Patrocinador também serão desativados\n\nClique em OK para continuar...'))
				return true;
			else
				return false;
		}

		function confirma_alterar_imagem(){
			if(confirm('Confirma alteração da Imagem do Perfil?'))
				return true;
			else
				return false;
		}

		function consiste_extensao(formulario, arquivo){
			var valido = false;	
			extensoes_permitidas = new Array(".jpg");
			var tamanhoArquivo = parseInt(document.getElementById("arquivo_foto").files[0].size);

			if (!arquivo){ 
			valido = false;
				//Se não tenho arquivo, é porque não se selecionou um arquivo no formulário. 
					alert('Nenhum arquivo selecionado!');

			}else if(tamanhoArquivo > 26214400){ //25Mb.
				valido = false;
				alert("TAMANHO DO ARQUIVO NÃO PERMITIDO!\n\nTamanho máximo: 25Mb");				

			}else{ //Recupera a extensão deste nome de arquivo... 
			extensao = (arquivo.substring(arquivo.lastIndexOf("."))).toLowerCase(); 
			permitida = false; 

			for (var i = 0; i < extensoes_permitidas.length; i++){ 
				 if (extensoes_permitidas[i] == extensao){ 
				 permitida = true; 
				 break; 
				 } 
			} 

			if (!permitida){ 
				valido = false;
				alert('EXTENSÃO DE IMAGEM NÃO PERMITIDA!\n\nExtensões permitidas: ' + extensoes_permitidas.join()); 

			}else{ 
				valido = true;
			} 
			} 

			return valido; 
		}
		
	</script>

	
	<body>

		<div class='container-fluid'>

<?php

	if(isset($_POST['acao'])){
	  $acao = $_POST['acao'];
		
		if($acao == 'INCLUIR'){
			$ExibeForm = 1;
			
			if(!isset($_POST['CONFIRMAR'])){
				$PATIPO      = 'J'; $patipo = 'CNPJ';
				$PADOC       = NULL;
				$PAEMAIL     = NULL;
				$PANOME      = NULL;
				$PAIE        = 'ISENTO';
				$PAIM        = 'ISENTO';
				$PAFANTASIA  = NULL;
				$PASEGMENTO  = NULL; $pasegmento = 'Selecione...';
				$PACNAE      = NULL; $pacnae = 'Selecione...';
				$PASITE      = NULL;
				$PAVALORHORA = 0;
				$PAVALORKM   = 0;
			  
				$LTIPO      = 1; $ltipo = 'Endereço principal';
				$LDESCRICAO = NULL;
				$LLOG       = NULL;
				$LNUMERO    = NULL;
				$LCOMPL     = NULL;
				$LCEP       = NULL;
				$LBAIRRO    = NULL;
				$LUF        = NULL;
				$LCIDADE    = NULL;
				$LTEL1      = NULL;
				$LTEL2      = NULL;
				$LTEL3      = NULL;
				
				$PADISPSEGUNDA = 4; $padispsegunda = 'COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos';
				$PADISPTERCA   = 4; $padispterca   = 'COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos';
				$PADISPQUARTA  = 4; $padispquarta  = 'COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos';
				$PADISPQUINTA  = 4; $padispquinta  = 'COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos';
				$PADISPSEXTA   = 4; $padispsexta   = 'COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos';
				$PADISPSABADO  = 7; $padispsabado  = 'INDISPONIVEL';
				$PADISPDOMINGO = 7; $padispdomingo = 'INDISPONIVEL';
				
				$IDENTIFICADORexiste = NULL;
				$EMAILexiste = NULL;
				$IDBorderColor = NULL;
				$EMAILBorderColor = NULL;
				
				$checked = NULL;
			}
			
			if(isset($_POST['CONFIRMAR'])){
			
				$PATIPO = $_POST['patipo'];
				
				if($PATIPO == 'F'){
					$PADOC = $_POST['idcpf'];
					$patipo = 'CPF';
					$AlertInvalido = 'CPF inválido! Deve conter 11 caracteres e ser válido perante a Receita Federal';
					
					if(valida_CPF("$PADOC")){
						$DOCVALIDO = 1;
					}
				}
				
				if($PATIPO == 'J'){
					$PADOC = $_POST['idcnpj'];
					$patipo = 'CNPJ';
					$AlertInvalido = 'CNPJ inválido! Deve conter 14 caracteres e ser válido perante a Receita Federal';
					
					if(valida_CNPJ("$PADOC")){
						$DOCVALIDO = 1;
					}
				}
				
				$PAIDENTIFICADOR = $PADOC;
				$PAEMAIL       = $_POST['email'];
				$PANOME        = $_POST['panome'];
				$PAIE          = $_POST['paie'];
				$PAIM          = $_POST['paim'];
				$PAFANTASIA    = $_POST['pafantasia'];
				$PASEGMENTO    = $_POST['pasegmento'];
				$PACNAE        = $_POST['pacnae'];
				$PASITE        = $_POST['pasite'];
				$PAVALORHORA   = $_POST['pavalorhora'];
				$PAVALORKM     = $_POST['pavalorkm'];
				
				$PADISPSEGUNDA = $_POST['padispsegunda'];
				$PADISPTERCA   = $_POST['padispterca'];
				$PADISPQUARTA  = $_POST['padispquarta'];
				$PADISPQUINTA  = $_POST['padispquinta'];
				$PADISPSEXTA   = $_POST['padispsexta'];
				$PADISPSABADO  = $_POST['padispsabado'];
				$PADISPDOMINGO = $_POST['padispdomingo'];
				
				$pasegmento = "Não especificado (INDIFERENTE)";
				
				if($PASEGMENTO != '' AND $PASEGMENTO != NULL){
				
					$SEGMENTOselect = mysql_query("SELECT * FROM segmentos WHERE SEGCODIGO = '$PASEGMENTO'") or print(mysql_error());
					$SEGDESCRICAO = mysql_result($SEGMENTOselect,0,"SEGDESCRICAO");
					
					$pasegmento = "$PASEGMENTO | $SEGDESCRICAO";
				
				}
				
				switch($PACNAE){
					case "A": $pacnae = "A 01 .. 03 | AGRICULTURA, PECUÁRIA, PRODUÇÃO FLORESTAL, PESCA E AQÜICULTURA"; break;
					case "B": $pacnae = "B 05 .. 09 | INDÚSTRIAS EXTRATIVAS"; break;
					case "C": $pacnae = "C 10 .. 33 | INDÚSTRIAS DE TRANSFORMAÇÃO"; break;
					case "D": $pacnae = "D 35 .. 35 | ELETRICIDADE E GÁS"; break;
					case "E": $pacnae = "E 36 .. 39 | ÁGUA, ESGOTO, ATIVIDADES DE GESTÃO DE RESÍDUOS E DESCONTAMINAÇÃO"; break;
					case "F": $pacnae = "F 41 .. 43 | CONSTRUÇÃO"; break;
					case "G": $pacnae = "G 45 .. 47 | COMÉRCIO; REPARAÇÃO DE VEÍCULOS AUTOMOTORES E MOTOCICLETAS"; break;
					case "H": $pacnae = "H 49 .. 53 | TRANSPORTE, ARMAZENAGEM E CORREIO"; break;
					case "I": $pacnae = "I 55 .. 56 | ALOJAMENTO E ALIMENTAÇÃO"; break;
					case "J": $pacnae = "J 58 .. 63 | INFORMAÇÃO E COMUNICAÇÃO"; break;
					case "K": $pacnae = "K 64 .. 66 | ATIVIDADES FINANCEIRAS, DE SEGUROS E SERVIÇOS RELACIONADOS"; break;
					case "L": $pacnae = "L 68 .. 68 | ATIVIDADES IMOBILIÁRIAS"; break;
					case "M": $pacnae = "M 69 .. 75 | ATIVIDADES PROFISSIONAIS, CIENTÍFICAS E TÉCNICAS"; break;
					case "N": $pacnae = "N 77 .. 82 | ATIVIDADES ADMINISTRATIVAS E SERVIÇOS COMPLEMENTARES"; break;
					case "O": $pacnae = "O 84 .. 84 | ADMINISTRAÇÃO PÚBLICA, DEFESA E SEGURIDADE SOCIAL"; break;
					case "P": $pacnae = "P 85 .. 85 | EDUCAÇÃO"; break;
					case "Q": $pacnae = "Q 86 .. 88 | SAÚDE HUMANA E SERVIÇOS SOCIAIS"; break;
					case "R": $pacnae = "R 90 .. 93 | ARTES, CULTURA, ESPORTE E RECREAÇÃO"; break;
					case "S": $pacnae = "S 94 .. 96 | OUTRAS ATIVIDADES DE SERVIÇOS"; break;
					case "T": $pacnae = "T 97 .. 97 | SERVIÇOS DOMÉSTICOS"; break;
					case "U": $pacnae = "U 99 .. 99 | ORGANISMOS INTERNACIONAIS E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS"; break;
				}
				
				switch($PADISPSEGUNDA){
					case 1: $padispsegunda = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispsegunda = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispsegunda = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispsegunda = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispsegunda = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispsegunda = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispsegunda = "INDISPONIVEL";
				}	
				
				switch($PADISPTERCA){
					case 1: $padispterca = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispterca = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispterca = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispterca = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispterca = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispterca = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispterca = "INDISPONIVEL";
				}	
				
				switch($PADISPQUARTA){
					case 1: $padispquarta = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispquarta = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispquarta = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispquarta = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispquarta = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispquarta = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispquarta = "INDISPONIVEL";
				}	
				
				switch($PADISPQUINTA){
					case 1: $padispquinta = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispquinta = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispquinta = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispquinta = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispquinta = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispquinta = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispquinta = "INDISPONIVEL";
				}	
				
				switch($PADISPSEXTA){
					case 1: $padispsexta = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispsexta = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispsexta = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispsexta = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispsexta = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispsexta = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispsexta = "INDISPONIVEL";
				}	
				
				switch($PADISPSABADO){
					case 1: $padispsabado = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispsabado = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispsabado = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispsabado = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispsabado = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispsabado = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispsabado = "INDISPONIVEL";
				}		

				switch($PADISPDOMINGO){
					case 1: $padispdomingo = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $padispdomingo = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $padispdomingo = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $padispdomingo = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.";break;
					case 5: $padispdomingo = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $padispdomingo = "ESPORÁDICA: Disponibilidade para alocações esporádicas."; break;
					case 7: $padispdomingo = "INDISPONIVEL";
				}											
				
				if(isset($_POST['pavip'])){
					$pavip = 1;
					$checked = 'checked';

				}else{
					$pavip = 0;
					$checked = NULL;
				}
				
				$LIDPATROCINADOR = $PAIDENTIFICADOR;
				$LTIPO      = $_POST['ltipo']; $ltipo = 'Endereço principal';
				$LDESCRICAO = $_POST['ldescricao'];
				$LLOG       = $_POST['rua'];
				$LNUMERO    = $_POST['lnumero'];
				$LCOMPL     = $_POST['lcompl'];
				$LCEP       = $_POST['cep'];
				$LBAIRRO    = $_POST['bairro'];
				$LUF        = $_POST['estado'];
				$LCIDADE    = $_POST['cidade'];
				$LTEL1      = $_POST['ltel1'];
				$LTEL2      = $_POST['ltel2'];
				$LTEL3      = $_POST['ltel3'];
				
				if($PAIDENTIFICADOR == ' '){
					$IDENTIFICADORexiste = "<img src='../imagens/atention.png' title='CPF/CNPJ deve ser informado.'/>";
					$IDBorderColor = 'red';
					
				}else if($DOCVALIDO == 0){
					$IDENTIFICADORexiste = "<img src='../imagens/atention.png' title='$AlertInvalido'/>";
					$IDBorderColor = 'red';
					
				}else if($PAEMAIL == ' '){
					$EMAILexiste = "<img src='../imagens/atention.png' title='E-mail deve ser informado.'/>";
				  $EMAILBorderColor = 'red';
					
				}else{
				
					$PAverifica = mysql_query("SELECT PAID FROM patrocinadores WHERE PAIDENTIFICADOR = '$PAIDENTIFICADOR'") or print(mysql_error());
					$PAexiste = mysql_num_rows($PAverifica);
					
					$PAEMAILverifica = mysql_query("SELECT PAEMAIL FROM patrocinadores WHERE PAEMAIL = '$PAEMAIL' AND PAIDENTIFICADOR <> '$PAIDENTIFICADOR'") or print(mysql_error());
					$PAEMAILexiste = mysql_num_rows($PAEMAILverifica);
					
					if($PAexiste > 0){
						$IDENTIFICADORexiste = "<img src='../imagens/atention.png' title='Identificador(CPF/CNPJ) já cadastrado.'/>";
						$IDBorderColor = 'red';
					
					}else if($PAEMAILexiste > 0){
						$EMAILexiste = "<img src='../imagens/atention.png' title='E-mail já cadastrado em outro cliente.'/>";
						$EMAILBorderColor = 'red';
					
					}else{
			
						$PAinsert = mysql_query("INSERT INTO patrocinadores(IDDOMINIO,IDADMINISTRADOR,PATIPO,PAIDENTIFICADOR,PARAZAONOME,PACPFCNPJ,PAIE,PAIM,PANOMEFANT,PASEGMENTO,PACNAE,PAEMAIL,PASITE,PADISPSEGUNDA,PADISPTERCA,PADISPQUARTA,PADISPQUINTA,PADISPSEXTA,PADISPSABADO,PADISPDOMINGO,PAVALORHORA,PAVALORKM,PAVIP,PASTATUS,PADTSTATUS,PAUSRSTATUS,PACADASTRO,PACADBY) 
						VALUES('$dominio_session','$adm_session','$PATIPO','$PAIDENTIFICADOR','$PANOME','$PADOC','$PAIE','$PAIM','$PAFANTASIA','$PASEGMENTO','$PACNAE','$PAEMAIL','$PASITE',$PADISPSEGUNDA,$PADISPTERCA,$PADISPQUARTA,$PADISPQUINTA,$PADISPSEXTA,$PADISPSABADO,$PADISPDOMINGO,'$PAVALORHORA','$PAVALORKM',$pavip,1,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysql_error());
					
						if($PAinsert){
							echo "<div id='alert' align='center' class='success'>Patrocinador cadastrado com sucesso!</div>";
							
							$Linsert = mysql_query("INSERT INTO locais (LIDPATROCINADOR,LTIPO,LDESCRICAO,LLOG,LNUMERO,LCOMPL,LCEP,LBAIRRO,LUF,LCIDADE,LTEL1,LTEL2,LTEL3,LSTATUS,LDTSTATUS,LUSRSTATUS,LCADASTRO,LCADBY) VALUES ('$LIDPATROCINADOR','$LTIPO','$LDESCRICAO','$LLOG','$LNUMERO','$LCOMPL','$LCEP','$LBAIRRO','$LUF','$LCIDADE','$LTEL1','$LTEL2','$LTEL3',1,'$datetime','$ident_session','$datetime','$ident_session')") or print "<div class='error'><b>FALHA NO CADASTRO DO ENDEREÇO.<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";
							
							/* Trecho removido na 2.0: $USERinsert = mysql_query ("INSERT INTO usuarios(IDDOMINIO,IDADMINISTRADOR,USERNOME,USERPERFIL,USERIDENTIFICACAO,USERLOGIN,USERSENHA,USERPRIMACESSO,USERSTATUS,USERDTSTATUS,USERUSRSTATUS,USERCADASTRO,USERCADBY) VALUES('$dominio_session','$adm_session','$PANOME','TS','$PAIDENTIFICADOR','$PAEMAIL','$PAIDENTIFICADOR',1,0,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysql_error());*/

							if($Linsert){								
							
								if($_FILES['arquivo']['error'] != 4){
									
									 //Pasta onde o arquivo vai ser salvo
									$_UP['pasta'] = "../$dominio_session/imagens/Fotos/"; // Pasta onde o arquivo vai ser salvo
									$_UP['renomeia'] = true;
									
									$extensao = explode('.', $_FILES['arquivo']['name']);
									$extensao = end($extensao);
									
									if($_UP['renomeia'] == true){// Verifica se deve trocar o nome do arquivo
										$nome_final = "$paident.$extensao"; // Monta o nome do arquivo concatenando os dados informados nos campos e com extensão...
									
									}else{
										$nome_final = $_FILES['arquivo']['name'];// Mantém o nome original do arquivo...
									}
									
									// Depois verifica se é possível mover o arquivo para a pasta escolhida...
									if(move_uploaded_file($_FILES['arquivo']['tmp_name'],$_UP['pasta'].$nome_final)){// se moveu, faz o insert... 
										
										$PALOGOupdate = mysql_query("UPDATE patrocinadores SET PALOGO = 1 WHERE PAIDENTIFICADOR = '$PAIDENTIFICADOR'") or print (mysql_error());
										
										if(!$PALOGOupdate){
											echo "<div align='center' class='error'>Falha na associação da Logomarca.</div>";
										}
									
									}else{
										echo "<div align='center' class='error'>Upload de imagem (LOGOMARCA) não realizado.</div>";
									}
								}
								
								$ExibeForm = 0;
							
							}else{
								echo "<div align='center' class='error'>ERRO: Local não cadastrado!</div>";
							}
						
						}else{
							echo "<div align='center' class='error'>ERRO: Cliente (patrocinadores) não cadastrado!</div>";
						}
					}
				}
			}
			
			if($ExibeForm == 1){
			
				?>
				
				<div class='panel panel-default'>

					<div class='panel-heading'>
						<span class='subpageName'>Incluir Cliente</span>

						<ul class='pull-right list-inline'>
							<li>
								<ul class='pull-right list-inline'>
									<li>
										<form name='voltar' method='POST' action='form_cadastro_patrocinadores.php'>
											<input class='inputsrc'  type='image' src='../imagens/close.png' title='Fechar'/>
										</form>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					
					<div class='panel-body'>
						<div class='row'>
						
							<form class='form-horizontal' name='cadastro' method='POST' enctype='multipart/form-data' action='form_cadastro_patrocinadores.php' onsubmit='return cpfcnpj_informado(); return validar();'>

							<div class='col-sm-2'>
								<div class='row'>
									<div class='col-sm-12 col-md-12'>
										<div class='thumbnail'>
											<img src='../imagens/Fotos/SEMLOGO.jpg' alt='SEM LOGOMARCA' style='width:242px; height=200px;'>
											<div class='caption'>
												<p class='subpageName' align='center'>Nome Fantasia</p>
											</div>
										</div>
									</div>
								</div>
								<div class='col-sm-12 col-md-12'><input class='form-control' type='file' id='arquivo_foto' name='arquivo' /></div>
							</div>
						
							<div class='col-sm-10'>
							
									<div class='panel panel-default'>

										<div class='panel-heading'>
											
											<span class='subpageName'>Dados cadastrais</span>

											<ul class='pull-right list-inline'>
												<li>
													<ul class='pull-right list-inline'>
														<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_dc')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
													</ul>
												</li>
											</ul>
											
										</div>

										<div class='panel-body' id='painel_dc'>

											<div class='form-group'>
												<label class='col-sm-2 control-label'>Identificador:</label>
												
												<div class='col-sm-1'>
													<select class='form-control' id='patipo' name='patipo' onchange='enable();' required >
														<option value='<?php echo $PATIPO;?>'><?php echo $patipo;?></option>
														<option value='F'>CPF</option>
														<option value='J'>CNPJ</option>
													</select>
												</div>
												
												<div class='col-sm-2 col-md-2'>
													<input style='display: none; border-color:<?php echo $IDBorderColor;?>;'  class='form-control' name='idcpf'  type='text' id='cpf'  onkeypress='if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='11' placeholder='CPF (apenas números)...'  value='<?php echo $PADOC;?>'/>
													<input style='display: block; border-color:<?php echo $IDBorderColor;?>;' class='form-control' name='idcnpj' type='text' id='cnpj' onkeypress='if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;' maxlength='14' placeholder='CNPJ (apenas números)...' value='<?php echo $PADOC;?>'/>
												</div>
												
												<div class='col-sm-1 col-md-1 text-left'><?php echo $IDENTIFICADORexiste;?></div>
												
												<label class='col-sm-2 control-label'>e-mail:</label>
												<div class='col-sm-3 col-md-3'><input style='border-color:<?php echo $EMAILBorderColor;?>;' class='form-control' name='email' type='email' id='email' onKeyUp='return Valida_email(this.id)' value='<?php echo $PAEMAIL;?>' required /></div>
												<div class='col-sm-1 col-md-1 text-left'><?php echo $EMAILexiste;?></div>
											
											</div>
												
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Nome/Razão Social:</label>
												<div class='col-sm-4'><input class='form-control' name='panome' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $PANOME;?>' required /></div>
												
												<label class='col-sm-2 control-label'>Nome Fantasia:</label>
												<div class='col-sm-4'><input class='form-control' name='pafantasia' type='text' maxlength='100' onkeyup='maiuscula(this)' value='<?php echo $PAFANTASIA;?>'/></div>
											</div>
												
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Ins. Estadual:</label>
												<div class='col-sm-4'><input class='form-control' name='paie' type='text' maxlength='12' value='<?php echo $PAIE;?>' /></div>
											
												<label class='col-sm-2 control-label'>Ins. Municipal:</label>
												<div class='col-sm-4'><input class='form-control' name='paim' type='text' maxlength='12' value='<?php echo $PAIM;?>' /></div>
												
											</div>
													
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Segmento:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='pasegmento'>
														<option value='<?php echo $PASEGMENTO;?>'><?php echo $pasegmento;?></option>
                            <option value='SEG0000000'>INDIFERENTE (Não especificado)</option>														
														
														<?php 
														
															$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGSTATUS = 1 ORDER BY SEGCODIGO") or print (mysql_error());
															$SEGtotal = mysql_num_rows($SEGselect);
															
															if($SEGtotal > 0){
																echo "<option value=''>Selecione...</option>";
																
																while($linha = mysql_fetch_assoc($SEGselect)){
																	$SEGCODIGO = $linha["SEGCODIGO"];
																	$SEGDESCRICAO = $linha["SEGDESCRICAO"];
																	
																	echo "<option value='$SEGCODIGO'>$SEGCODIGO | $SEGDESCRICAO</option>";
																}
										
															}else{
																echo "<option value=''>Nenhum segmento cadastrado/ativo.</option>";
															}
															
														?>
														
													</select>
												</div>
											
												<label class='col-sm-2 control-label'>CNAE:</label>
												<div class='col-sm-4'>
													<select class='form-control' name="pacnae">
														<option value='<?php echo $PACNAE;?>'><?php echo $pacnae;?></option>
														<option value="A">A 01 .. 03 | AGRICULTURA, PECUÁRIA, PRODUÇÃO FLORESTAL, PESCA E AQÜICULTURA</option>
														<option value="B">B 05 .. 09 | INDÚSTRIAS EXTRATIVAS</option>
														<option value="C">C 10 .. 33 | INDÚSTRIAS DE TRANSFORMAÇÃO</option>
														<option value="D">D 35 .. 35 | ELETRICIDADE E GÁS</option>
														<option value="E">E 36 .. 39 | ÁGUA, ESGOTO, ATIVIDADES DE GESTÃO DE RESÍDUOS E DESCONTAMINAÇÃO</option>
														<option value="F">F 41 .. 43 | CONSTRUÇÃO</option>
														<option value="G">G 45 .. 47 | COMÉRCIO; REPARAÇÃO DE VEÍCULOS AUTOMOTORES E MOTOCICLETAS</option>
														<option value="H">H 49 .. 53 | TRANSPORTE, ARMAZENAGEM E CORREIO</option>
														<option value="I">I 55 .. 56 | ALOJAMENTO E ALIMENTAÇÃO</option>
														<option value="J">J 58 .. 63 | INFORMAÇÃO E COMUNICAÇÃO</option>
														<option value="K">K 64 .. 66 | ATIVIDADES FINANCEIRAS, DE SEGUROS E SERVIÇOS RELACIONADOS</option>
														<option value="L">L 68 .. 68 | ATIVIDADES IMOBILIÁRIAS</option>
														<option value="M">M 69 .. 75 | ATIVIDADES PROFISSIONAIS, CIENTÍFICAS E TÉCNICAS</option>
														<option value="N">N 77 .. 82 | ATIVIDADES ADMINISTRATIVAS E SERVIÇOS COMPLEMENTARES</option>
														<option value="O">O 84 .. 84 | ADMINISTRAÇÃO PÚBLICA, DEFESA E SEGURIDADE SOCIAL</option>
														<option value="P">P 85 .. 85 | EDUCAÇÃO</option>
														<option value="Q">Q 86 .. 88 | SAÚDE HUMANA E SERVIÇOS SOCIAIS</option>
														<option value="R">R 90 .. 93 | ARTES, CULTURA, ESPORTE E RECREAÇÃO</option>
														<option value="S">S 94 .. 96 | OUTRAS ATIVIDADES DE SERVIÇOS</option>
														<option value="T">T 97 .. 97 | SERVIÇOS DOMÉSTICOS</option>
														<option value="U">U 99 .. 99 | ORGANISMOS INTERNACIONAIS E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS</option>
													</select>
												</div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Website/Página:</label>
												<div class='col-sm-4'><input class='form-control' name='pasite' type='url' maxlength='50' onkeyup='minuscula(this)' placeholder='URL completo (exemplo: https://pms.net.br/cpanel)' value='<?php echo $PASITE;?>'/></div>
											
												<label class='col-sm-2 control-label'>Valor/Hora (R$):</label>
												<div class='col-sm-2'><input class='form-control' name='pavalorhora' id='pavalorhora' type='text' maxlength='10' required value='<?php echo $PAVALORHORA;?>'/></div>
											
											</div>
											
											<div class='form-group'>	
												<div class='col-sm-2'></div>
												<div class='col-sm-4 checkbox'>
												<label><input class='imputcheck' type='checkbox' name='pavip' title='VIP: Se marcado, indica que este é um cliente VIP, informação útil, por exemplo, na definição de SLAs.' <?php echo $checked;?>>Cliente VIP</label>
												</div>
												
												<label class='col-sm-2 control-label'>Valor/KM (R$):</label>
												<div class='col-sm-2'><input class='form-control' name='pavalorkm' id='pavalorkm' type='text' maxlength='10' required value='<?php echo $PAVALORKM;?>' /></div>
											</div>
											
										</div>
									</div>	
										
										
									<div class='panel panel-default'>

										<div class='panel-heading'>
											
											<span class='subpageName'>Endereço principal</span>

											<ul class='pull-right list-inline'>
												<li>
													<ul class='pull-right list-inline'>
														<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_ep')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
													</ul>
												</li>
											</ul>
											
										</div>

										<div class='panel-body' id='painel_ep'>
										
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Tipo:</label>
												<div class='col-sm-3'>
													<select class='form-control' name='ltipo' required >				 
														<option value='<?php echo $LTIPO;?>'><?php echo $ltipo;?></option>						
													</select>
												</div>
											
												<label class='col-sm-2 control-label'>Descrição:</label>
												<div class='col-sm-5'><input class='form-control' name='ldescricao' maxlength='100' type='text' value='<?php echo $LDESCRICAO;?>' required /></div>
											</div>
											
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>CEP:</label>
												<div class='col-sm-3'><input class='form-control' name='cep' id='cep' type="text" onblur="pesquisacep(this.value);" onkeypress="if (!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" maxlength='8' value='<?php echo $LCEP;?>' required /></div>
												<label class='col-sm-2 control-label'>Logradouro:</label>
												<div class='col-sm-5'><input class='form-control' name='rua' id='rua' type='text' maxlength='100' value='<?php echo $LLOG;?>' required /></div>
											</div>
											
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Número|Compl.:</label>
												<div class='col-sm-1'><input class='form-control' name='lnumero' type='text' maxlength='6' required value='<?php echo $LNUMERO;?>' placeholder='Nº...'/></div>
											
												<div class='col-sm-2'><input class='form-control' name='lcompl' type='text' maxlength='10' onkeyup='maiuscula(this)' value='<?php echo $LCOMPL;?>' placeholder='Complemento...'/></div>
											
												<label class='col-sm-2 control-label'>Bairro:</label>
												<div class='col-sm-5'><input class='form-control' name='bairro' id='bairro' type='text' maxlength='100' value='<?php echo $LBAIRRO;?>' required /></div>
											</div>
											
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Estado:</label>
												<div class='col-sm-3'><input class='form-control' name='estado' id='uf' type='text' maxlength='2' value='<?php echo $LUF;?>' required /></div>
												
												<label class='col-sm-2 control-label'>Município:</label>
												<div class='col-sm-5'><input class='form-control' name='cidade' id='cidade' type='text' maxlength='100' value='<?php echo $LCIDADE;?>' required /></div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-2 control-label'>Telefones:</label>
												
													<div class='col-sm-2'><input class='form-control' name='ltel1' type='text' maxlength='12' onkeypress="formatar(this,'## ####-####'); if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $LTEL1;?>' placeholder ='## ####-####' required  /></div>
													<div class='col-sm-2'><input class='form-control' name='ltel2' type='text' maxlength='13' onkeypress="formatar(this,'## #####-####');if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $LTEL2;?>' placeholder ='Celular (## #####-####)'/></div>
													<div class='col-sm-2'><input class='form-control' name='ltel3' type='text' maxlength='13' onkeypress="formatar(this,'## #####-####');if(!isNaN(String.fromCharCode(window.event.keyCode))) return true; else return false;" value='<?php echo $LTEL3;?>' placeholder ='Celular (## #####-####)'/></div>
											</div>
										</div>
									</div>
									
									<div class='panel panel-default'>

										<div class='panel-heading'>
											
											<span class='subpageName'>Matriz de Disponibilidade</span>

											<ul class='pull-right list-inline'>
												<li>
													<ul class='pull-right list-inline'>
														<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_md')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
													</ul>
												</li>
											</ul>
											
										</div>

										<div class='panel-body' id='painel_md'>
										
											<div class='info'>Períodos diários em que poderás dedicar integralmente a projetos da Administradora.</div>
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Segunda-feira:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispsegunda' required >
														<option value='<?php echo $PADISPSEGUNDA;?>'><?php echo $padispsegunda;?>   </option>
														<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.   </option>
														<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.   </option>
														<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.   </option>
														<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.</option>
														<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.     </option>
														<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
										
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Terça-feira:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispterca' required >
														<option value='<?php echo $PADISPTERCA;?>'><?php echo $padispterca;?>       </option>
														<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.   </option>
														<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.   </option>
														<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.   </option>
														<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.</option>
														<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.     </option>
														<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Quarta-feira:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispquarta' required >
														<option value='<?php echo $PADISPQUARTA;?>'><?php echo $padispquarta;?>     </option>
														<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.   </option>
														<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.   </option>
														<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.   </option>
														<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.</option>
														<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.     </option>
														<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Quinta-feira:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispquinta' required >
														<option value='<?php echo $PADISPQUINTA;?>'><?php echo $padispquinta;?>     </option>
														<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.   </option>
														<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.   </option>
														<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.   </option>
														<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.</option>
														<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.     </option>
														<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
											
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Sexta-feira:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispsexta' required >
															<option value='<?php echo $PADISPSEXTA;?>'><?php echo $padispsexta;?>       </option>
															<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.   </option>
															<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.   </option>
															<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.   </option>
															<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.</option>
															<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.     </option>
															<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
												
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Sábado:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispsabado' required >
															<option value='<?php echo $PADISPSABADO;?>'><?php echo $padispsabado;?>      </option>
															<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.    </option>
															<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.    </option>
															<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.</option>
															<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos. </option>
															<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.      </option>
															<option value=7>INDISPONIVEL</option>
													</select>
												</div>
											</div>
												
											<div class='form-group'>
												<label class='col-sm-4 control-label'>Domingo/Feriados:</label>
												<div class='col-sm-4'>
													<select class='form-control' name='padispdomingo' required >
														<option value='<?php echo $PADISPDOMINGO;?>'><?php echo $padispdomingo;?>    </option>
														<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.    </option>
														<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.    </option>
														<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.    </option>
														<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.</option>
														<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos. </option>
														<option value=6>ESPORÁDICA: Disponibilidade para alocações esporádicas.      </option>
														<option value=7>INDISPONÍVEL</option>
													</select>
												</div>
											</div>
								
										</div>
									</div>
									<input name='CONFIRMAR' type='hidden' value=1 />
								  <input name='acao' type='hidden' value='INCLUIR'>
								  <div id='salvar_inclusao' align='right'><input class='but but-rc but-azul disabled' type='submit' value='      Concluir Cadastro     ' onclick="return consiste_extensao(this.form, this.form.arquivo_foto.value);"/></div>
								
							</form>
						</div>
						</div>
					</div>
				</div>		
		
				<?php
				
			}
			
		}
	   
	  if($acao == 'EDITAR'){
	    $PAID = $_POST['paid'];
		 
			$PAselect = mysql_query("SELECT * FROM patrocinadores WHERE PAID=$PAID") or print(mysql_error());
		 
		 while($pabusca = mysql_fetch_array($PAselect)){
			  $PAID          = $pabusca['PAID'];
				$PAIDENTIFICADOR = $pabusca['PAIDENTIFICADOR'];
				$PATIPO        = $pabusca['PATIPO'];
				$PARAZAONOME   = $pabusca['PARAZAONOME'];
				$PACPFCNPJ     = $pabusca['PACPFCNPJ'];
				$PAIE          = $pabusca['PAIE'];
				$PAIM          = $pabusca['PAIM'];
				$PANOMEFANT    = $pabusca['PANOMEFANT'];
				$PACNAE        = $pabusca['PACNAE'];
				$PALOG         = $pabusca['PALOG'];
				$PANUMERO      = $pabusca['PANUMERO'];
				$PACOMPL       = $pabusca['PACOMPL'];
				$PABAIRRO      = $pabusca['PABAIRRO'];
				$PAUF          = $pabusca['PAUF'];
				$PACIDADE      = $pabusca['PACIDADE'];
				$PACEP         = $pabusca['PACEP'];
				$PATEL1        = $pabusca['PATEL1'];
				$PATEL2        = $pabusca['PATEL2'];
				$PATEL3        = $pabusca['PATEL3'];
				$PAEMAIL       = $pabusca['PAEMAIL'];
				$PASITE        = $pabusca['PASITE'];
				$PASEGMENTO    = $pabusca['PASEGMENTO'];
				$PADISPSEGUNDA = $pabusca['PADISPSEGUNDA'];
				$PADISPTERCA   = $pabusca['PADISPTERCA'];
				$PADISPQUARTA  = $pabusca['PADISPQUARTA'];
				$PADISPQUINTA  = $pabusca['PADISPQUINTA'];
				$PADISPSEXTA   = $pabusca['PADISPSEXTA'];
				$PADISPSABADO  = $pabusca['PADISPSABADO'];
				$PADISPDOMINGO = $pabusca['PADISPDOMINGO'];
				$PAVALORHORA   = $pabusca['PAVALORHORA'];
				$PAVALORKM     = $pabusca['PAVALORKM'];
				$PAVIP         = $pabusca['PAVIP'];
				$PALOGO        = $pabusca['PALOGO'];
				
				if($PAVIP == 1){
					$pavip = 'checked';
				
				}else{
					$pavip = '';
				}
				
				switch ($PATIPO){
					case 'F': $patipo = 'CPF'; break;
					case 'J': $patipo = 'CNPJ'; break;
				}
				
				if ($PALOGO == 1){
					$palogo = "<img src='../$dominio_session/imagens/Fotos/$PAIDENTIFICADOR.jpg' alt='LOGOMARCA' style='width:242px; height=200px;'/>";

				}else{
					$palogo = "<img src='../imagens/Fotos/SEMLOGO.jpg' alt='SEM LOGOMARCA' style='width:242px; height=200px;'/>";
				}

        switch($PADISPSEGUNDA){// Status do Projeto... 
					case 1: $PADISPSEGUNDA2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPSEGUNDA2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPSEGUNDA2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPSEGUNDA2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPSEGUNDA2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPSEGUNDA2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPSEGUNDA2 = "INDISPONIVEL";
					}	
                switch($PADISPTERCA){// Status do Projeto... 
					case 1: $PADISPTERCA2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPTERCA2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPTERCA2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPTERCA2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPTERCA2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPTERCA2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPTERCA2 = "INDISPONIVEL";
					}	
                switch($PADISPQUARTA){// Status do Projeto... 
					case 1: $PADISPQUARTA2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPQUARTA2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPQUARTA2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPQUARTA2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPQUARTA2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPQUARTA2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPQUARTA2 = "INDISPONIVEL";
					}	
                switch($PADISPQUINTA){// Status do Projeto... 
					case 1: $PADISPQUINTA2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPQUINTA2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPQUINTA2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPQUINTA2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPQUINTA2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPQUINTA2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPQUINTA2 = "INDISPONIVEL";
					}	
                switch($PADISPSEXTA){// Status do Projeto... 
					case 1: $PADISPSEXTA2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPSEXTA2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPSEXTA2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPSEXTA2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPSEXTA2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPSEXTA2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPSEXTA2 = "INDISPONIVEL";
					}	
                switch($PADISPSABADO){// Status do Projeto... 
					case 1: $PADISPSABADO2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPSABADO2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPSABADO2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPSABADO2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPSABADO2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPSABADO2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPSABADO2 = "INDISPONIVEL";
					}		

            switch($PADISPDOMINGO){// Status do Projeto... 
					case 1: $PADISPDOMINGO2 = "MANHÃ: Disponibilidade de 06:00 às 12:00, sem intervalos."; break;
					case 2: $PADISPDOMINGO2 = "TARDE: Disponibilidade de 12:00 às 18:00, sem intervalos."; break;
					case 3: $PADISPDOMINGO2 = "NOITE: Disponibilidade de 18:00 às 24:00, sem intervalos."; break;
					case 4: $PADISPDOMINGO2 = "COMERCIAL: Disponibilidade de 08:00 às 18:00, com 2 horas de intervalo*.";break;
					case 5: $PADISPDOMINGO2 = "INTEGRAL: Disponibilidade de 06:00 às 24:00.";break;
					case 6: $PADISPDOMINGO2 = "NÃO DEFINIDA: Receber contato do Moderador para alocações esporádicas."; break;
					case 7: $PADISPDOMINGO2 = "INDISPONIVEL";
					}											
		 }
		 
		
		 ?>
			<div class='panel panel-default'>

				<div class='panel-heading'>
					<span class='subpageName'>Editar Cliente</span>

					<ul class='pull-right list-inline'>
						<li>
							<ul class='pull-right list-inline'>
								<li>
									<form name='voltar' method='POST' action='form_cadastro_patrocinadores.php'>
										<input class='inputsrc'  type='image' src='../imagens/close.png' title='Fechar'/>
									</form>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				
				<div class='panel-body'>
				<div class='row'>
				
					<form class='form-horizontal' name='cadastro' method='POST' enctype='multipart/form-data' action='form_cadastro_patrocinadores.php' onsubmit='return validar();'>

					<div class='col-sm-2'>
						<div class='row'>
						<div class='col-sm-12 col-md-12'>
							<div class='thumbnail'>
								<?php echo $palogo;?>
								<div class='caption'>
									<p class='subpageName' align='center'><?php echo $PANOMEFANT;?></p>
								</div>
							</div>
						</div>
						</div>
						<div class='col-sm-12'><input class='form-control' type='file' id='arquivo_foto' name='arquivo' /></div>
					</div>
				
					<div class='col-sm-10'>
						<div id='alert_consistencia_id'></div>
						<div id='alert_consistencia_email'></div>

					<div class='panel panel-default'>

						<div class='panel-heading'>
							
							<span class='subpageName'>Dados cadastrais</span>

							<ul class='pull-right list-inline'>
								<li>
									<ul class='pull-right list-inline'>
										<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_dc')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
									</ul>
								</li>
							</ul>
							
						</div>

						<div class='panel-body' id='painel_dc'>

							<div class='form-group'>
								<label class='col-sm-2 control-label'>Identificador:</label>
								
								<div class='col-sm-1'>
									<select class='form-control' id='patipo' name='patipo' required readonly >
										<option value='<?php echo $PATIPO;?>'><?php echo $patipo;?></option>
									</select>
								</div>
								
								<div class='col-sm-3' >
									<input class='form-control' name='paident' type='text' value='<?php echo $PAIDENTIFICADOR;?>' readonly />
									
								</div>
								
								<label class='col-sm-2 control-label'>e-mail:</label>
								<div class='col-sm-4'><input class='form-control' name='email' type='email' id='email' value='<?php echo $PAEMAIL;?>' onKeyUp='return Valida_email(this.id)' required /></div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Nome/Razão Social:</label>
								<div class='col-sm-4'><input class='form-control' name="panome" type="text" maxlength='100' value='<?php echo $PARAZAONOME;?>' onkeyup="maiuscula(this)" required autofocus /></div>
								
								<label class='col-sm-2 control-label'>Nome Fantasia:</label>
								<div class='col-sm-4'><input class='form-control' name="pafantasia" type="text" maxlength='100' value='<?php echo $PANOMEFANT;?>' onkeyup="maiuscula(this)"/></div>
							</div>
								
								
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Ins. Estadual:</label>
									<div class='col-sm-4'><input class='form-control' name="paie" type="text" maxlength='12' value='<?php echo $PAIE;?>' /></div>
									
									
									<label class='col-sm-2 control-label'>Ins. Municipal:</label>
									<div class='col-sm-4'><input class='form-control' name="paim" type="text" maxlength='12' value='<?php echo $PAIM;?>' /></div>
									
								</div>
									
								<div class='form-group'>
									<label class='col-sm-2 control-label'>Segmento:</label>
									<div class='col-sm-4'>
										<select class='form-control' name='pasegmento'>
											<option value='<?php echo $PASEGMENTO;?>'><?php echo $PASEGMENTO;?></option>
											<option value='SEG0000000'>INDIFERENTE (Não especificado)</option>
																 
											<?php 
											
												$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGSTATUS = 1 AND SEGCODIGO <> 'SEG0000000' ORDER BY SEGCODIGO") or print (mysql_error());
												$SEGtotal = mysql_num_rows($SEGselect);
												
												if($SEGtotal > 0){
													
													while($linha = mysql_fetch_assoc($SEGselect)){
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
								
									<label class='col-sm-2 control-label'>CNAE:</label>
									<div class='col-sm-4'>
										<select class='form-control' name="pacnae">
											<option value='<?php echo $PACNAE;?>'><?php echo $PACNAE;?></option>
											<option value="A">A 01 .. 03 | AGRICULTURA, PECUÁRIA, PRODUÇÃO FLORESTAL, PESCA E AQÜICULTURA</option>
											<option value="B">B 05 .. 09 | INDÚSTRIAS EXTRATIVAS</option>
											<option value="C">C 10 .. 33 | INDÚSTRIAS DE TRANSFORMAÇÃO</option>
											<option value="D">D 35 .. 35 | ELETRICIDADE E GÁS</option>
											<option value="E">E 36 .. 39 | ÁGUA, ESGOTO, ATIVIDADES DE GESTÃO DE RESÍDUOS E DESCONTAMINAÇÃO</option>
											<option value="F">F 41 .. 43 | CONSTRUÇÃO</option>
											<option value="G">G 45 .. 47 | COMÉRCIO; REPARAÇÃO DE VEÍCULOS AUTOMOTORES E MOTOCICLETAS</option>
											<option value="H">H 49 .. 53 | TRANSPORTE, ARMAZENAGEM E CORREIO</option>
											<option value="I">I 55 .. 56 | ALOJAMENTO E ALIMENTAÇÃO</option>
											<option value="J">J 58 .. 63 | INFORMAÇÃO E COMUNICAÇÃO</option>
											<option value="K">K 64 .. 66 | ATIVIDADES FINANCEIRAS, DE SEGUROS E SERVIÇOS RELACIONADOS</option>
											<option value="L">L 68 .. 68 | ATIVIDADES IMOBILIÁRIAS</option>
											<option value="M">M 69 .. 75 | ATIVIDADES PROFISSIONAIS, CIENTÍFICAS E TÉCNICAS</option>
											<option value="N">N 77 .. 82 | ATIVIDADES ADMINISTRATIVAS E SERVIÇOS COMPLEMENTARES</option>
											<option value="O">O 84 .. 84 | ADMINISTRAÇÃO PÚBLICA, DEFESA E SEGURIDADE SOCIAL</option>
											<option value="P">P 85 .. 85 | EDUCAÇÃO</option>
											<option value="Q">Q 86 .. 88 | SAÚDE HUMANA E SERVIÇOS SOCIAIS</option>
											<option value="R">R 90 .. 93 | ARTES, CULTURA, ESPORTE E RECREAÇÃO</option>
											<option value="S">S 94 .. 96 | OUTRAS ATIVIDADES DE SERVIÇOS</option>
											<option value="T">T 97 .. 97 | SERVIÇOS DOMÉSTICOS</option>
											<option value="U">U 99 .. 99 | ORGANISMOS INTERNACIONAIS E OUTRAS INSTITUIÇÕES EXTRATERRITORIAIS</option>
										</select>
									</div>
								</div>
							
							<div class='form-group'>
								<label class='col-sm-2 control-label'>Website/Página:</label>
								<div class='col-sm-4'><input class='form-control' name='pasite' type='url' maxlength='50' onkeyup='minuscula(this)' value='<?php echo $PASITE;?>'/></div>
							
							<label class='col-sm-2 control-label'>Valor/Hora (R$):</label>
								<div class='col-sm-1'><input class='form-control' name='pavalorhora' id='pavalorhora' type='text' maxlength='10' required value='<?php echo $PAVALORHORA;?>' /></div>
							
								<label class='col-sm-2 control-label'>Valor/KM (R$):</label>
								<div class='col-sm-1'><input class='form-control' name='pavalorkm' id='pavalorkm' type='text' maxlength='10' required value='<?php echo $PAVALORKM;?>'/></div>
							</div>
							
							<div class='form-group'>	
								<div class='col-sm-2'></div>
								<div class='col-sm-4 checkbox'>
								<label><input type='checkbox' name='pavip' <?php echo $pavip;?> title='VIP: Se marcado, indica que este é um cliente VIP, informação útil, por exemplo, na definição de SLAs.'>VIP</label>
								</div>
							</div>
						</div>
				  </div>	
	
	        <div class='panel panel-default'>

						<div class='panel-heading'>
							
							<span class='subpageName'>Matriz de Disponibilidade</span>

							<ul class='pull-right list-inline'>
								<li>
									<ul class='pull-right list-inline'>
										<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_md')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
									</ul>
								</li>
							</ul>
							
						</div>

						<div class='panel-body' id='painel_md'>
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Segunda-feira:</label>
								<div class='col-sm-4'>
									<select class='form-control' name='padispsegunda' required >
										<option value='<?php echo $PADISPSEGUNDA ;?>'><?php echo $PADISPSEGUNDA2 ;?></option>
										<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
										<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
										<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
										<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
										<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
										<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
										<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
						
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Terça-feira:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispterca" required >
										<option value='<?php echo $PADISPTERCA ;?>'><?php echo $PADISPTERCA2 ;?></option>
										<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
										<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
										<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
										<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
										<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
										<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
										<option value=7>INDISPONIVEL</option>
									</select>
                </div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Quarta-feira:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispquarta" required >
										<option value='<?php echo $PADISPQUARTA ;?>'><?php echo $PADISPQUARTA2 ;?></option>
										<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
										<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
										<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
										<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
										<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
										<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
										<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Quinta-feira:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispquinta" required >
										<option value='<?php echo $PADISPQUINTA ;?>'><?php echo $PADISPQUINTA2 ;?></option>
										<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
										<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
										<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
										<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
										<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
										<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
										<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Sexta-feira:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispsexta" required >
											<option value='<?php echo $PADISPSEXTA ;?>'><?php echo $PADISPSEXTA2 ;?></option>
											<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
											<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
											<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
											<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
											<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
											<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
											<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Sábado:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispsabado" required >
											<option value='<?php echo $PADISPSABADO ;?>'><?php echo $PADISPSABADO2 ;?></option>
											<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
											<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
											<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
											<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
											<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
											<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
											<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
								
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Domingo:</label>
								<div class='col-sm-4'>
								  <select class='form-control' name="padispdomingo" required >
										<option value='<?php echo $PADISPDOMINGO ;?>'><?php echo $PADISPDOMINGO2 ;?></option>
										<option value=1>MANHÃ: Disponibilidade de 06:01 às 12:00, sem intervalos.           </option>
										<option value=2>TARDE: Disponibilidade de 12:01 às 18:00, sem intervalos.           </option>
										<option value=3>NOITE: Disponibilidade de 18:01 às 24:00, sem intervalos.           </option>
										<option value=4>COMERCIAL: Disponibilidade de 08:00 às 18:00, com intervalos.       </option>
										<option value=5>INTEGRAL: Disponibilidade de 06:01 às 24:00, com intervalos.        </option>
										<option value=6>ESPORÁDICA: Receber contato do Moderador para alocações esporádicas.</option>
										<option value=7>INDISPONIVEL</option>
									</select>
								</div>
							</div>
						</div>
					</div>
			<input name='paid' type='hidden' value='<?php echo $PAID;?>'/>
			<input name='acao' type='hidden' value='EDITAR2'/>
			<div align='right'>
		<input class='but but-rc but-azul' type='submit' value='     Salvar     ' onclick="validarDados('email', document.getElementById('email').value);return consiste_extensao(this.form, this.form.arquivo_foto.value)"/>
		<input class='but but-rc but-azul' type='reset'  value='     Restaurar     '/>
	</div>
</form>
</div>

</div>
</div>
</div>

<?php
	   
	}
	   
	  if($acao == 'EDITAR2'){
	    $PAID       = $_POST['paid'];
			$paident    = $_POST['paident'];
			$panome     = $_POST['panome'];
			$paie       = $_POST['paie'];
			$paim       = $_POST['paim'];
			$pafantasia = $_POST['pafantasia'];
			$pasegmento = $_POST['pasegmento'];
			$pacnae     = $_POST['pacnae'];
			$paemail    = $_POST['email'];
			$pasite     = $_POST['pasite'];
			
			$padispsegunda = $_POST['padispsegunda'];
			$padispterca   = $_POST['padispterca'];
			$padispquarta  = $_POST['padispquarta'];
			$padispquinta  = $_POST['padispquinta'];
			$padispsexta   = $_POST['padispsexta'];
			$padispsabado  = $_POST['padispsabado'];
			$padispdomingo = $_POST['padispdomingo'];
			$pavalorhora   = $_POST['pavalorhora'];
			$pavalorkm     = $_POST['pavalorkm'];
			
			if(isset($_POST['pavip'])){
				$pavip = 1;

			}else{
				$pavip = 0;
			}
			
			$USERverifica = mysql_query("SELECT USERIDENTIFICACAO FROM usuarios WHERE USERLOGIN = '$paemail'") or print (mysql_error());
			$USERLOGIN = mysql_num_rows($USERverifica);
			
			$PAselect = mysql_query("SELECT PAEMAIL FROM patrocinadores WHERE PAEMAIL = '$paemail'") or print (mysql_error());
			$PAEMAIL = mysql_result($PAselect,0,"PAEMAIL");
			
			if($USERLOGIN > 0 AND ($paemail != $PAEMAIL)){
				echo "<div id='alert' align='center' class='warning'>ALTERAÇÃO NÃO PERMITIDA! O e-mail informado já está cadastrado para outro usuário.</div>";
				
			}else{
			
			  $PAupdate = mysql_query("UPDATE patrocinadores SET
				PARAZAONOME   = '$panome',
				PAIE       	  = '$paie',
				PAIM       	  = '$paim',
				PANOMEFANT 	  = '$pafantasia',
				PACNAE     	  = '$pacnae',
				PAEMAIL    	  = '$paemail',
				PASITE     	  = '$pasite',
				PASEGMENTO 	  = '$pasegmento',
				PADISPSEGUNDA = $padispsegunda,
				PADISPTERCA   = $padispterca,
				PADISPQUARTA  = $padispquarta,
				PADISPQUINTA  = $padispquinta,
				PADISPSEXTA   = $padispsexta,
				PADISPSABADO  = $padispsabado,
				PADISPDOMINGO = $padispdomingo,
				PAVALORHORA   = '$pavalorhora',
        PAVALORKM     = '$pavalorkm',
        PAVIP         = $pavip	WHERE PAID = $PAID") or print(mysql_error());
				
				if ($PAupdate){
					
				  echo "<div id='alert' align='center' class='success'>Cadastro do Patrocinador <b>$paident</b> alterado com sucesso!</div>";
					
					if($_FILES['arquivo']['error'] != 4){
						
						 //Pasta onde o arquivo vai ser salvo
						$_UP['pasta']    = "../$dominio_session/imagens/Fotos/"; //Pasta onde o arquivo vai ser salvo
						$_UP['renomeia'] = true;
						
						$extensao = explode('.', $_FILES['arquivo']['name']);
						$extensao = end($extensao);
						
						if($_UP['renomeia'] == true){// Verifica se deve trocar o nome do arquivo
							$nome_final = "$paident.$extensao"; // Monta o nome do arquivo concatenando os dados informados nos campos e com extensão...
						
						}else{
							$nome_final = $_FILES['arquivo']['name'];// Mantém o nome original do arquivo...
						}
						
						// Depois verifica se é possível mover o arquivo para a pasta escolhida...
						if (move_uploaded_file($_FILES['arquivo']['tmp_name'],$_UP['pasta'].$nome_final)){// se moveu, faz o insert... 
						
						  $PALOGOupdate = mysql_query("UPDATE patrocinadores SET PALOGO = 1 WHERE PAID = $PAID") or print (mysql_error());
							
							if(!$PALOGOupdate){
								echo "<div id='alert' align='center' class='error'>Falha na associação da Logomarca.</div>";
							}
							
						}else{
							echo "<div align='center' class='error'>Upload de imagem (LOGOMARCA) não realizado.</div>";
						}
					}
				}
			}
	  }
		 
		if($acao == 'ATIVAR'){
			
			$PAID = $_POST['paid'];
			
			$PAupdate = mysql_query("UPDATE patrocinadores SET PASTATUS = 1, PADTSTATUS = '$datetime', PAUSRSTATUS = '$ident_session' WHERE PAID = $PAID ") or print(mysql_error());
			
			if($PAupdate){
			  echo "<div id='alert' align='center' class='success'>Patrocinador ativado com sucesso.</div>";
			}
		}

		if($acao == 'INATIVAR'){
			
			$PAID = $_POST['paid'];
			
			$PAupdate = mysql_query("UPDATE patrocinadores SET PASTATUS = 0, PADTSTATUS = '$datetime', PAUSRSTATUS = '$ident_session' WHERE PAID = $PAID ") or print(mysql_error());
			
			if($PAupdate){
			  echo "<div id='alert' align='center' class='success'>Patrocinador inativado com sucesso.</div>";
			}
		}		
		
	}//Fim das ações...
	
	?>
	
	<div class='panel panel-default'>

		<div class='panel-heading'>
			<span class='subpageName'>Clientes (Patrocinadores)</span>
			
			<ul class='pull-right list-inline'>
				<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
			</ul>
			
		</div>

		<div class='panel-body'>
		
		<style>
		
		
		</style>
	

	<?php
	
	  if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
	
			$PAselect = mysql_query("SELECT * FROM patrocinadores ORDER BY PARAZAONOME") or print(mysql_error());
		
		}else{
			
			$PAselect = mysql_query("SELECT * FROM patrocinadores WHERE PAIDENTIFICADOR IN (SELECT UCIDCLIENTE FROM usuarios_clientes WHERE UCIDUSUARIO = '$ident_session' AND UCSITUACAO = 1)") or print (mysql_error());
		
		}
		
		$PAtotal  = mysql_num_rows($PAselect);
	
	  if($PAtotal > 0){
	    echo"
			
			<table width='100%' id='DataTables' class='display table table-responsive table-condensed table-action table-striped'>	
				<thead>			
					<th width='2%' ><div align='center'></div>                      </th>
					<th width='2%' data-orderable='false'><div align='center'></div></th>
					<th width='10%'><div align='center'>ID</div>                    </th>
					<th width='35%'><div>Razão Social</div>            </th>
					<th width='28%'><div>Nome Fantasia</div>           </th>
					<th width='20%' data-orderable='false'><div align='center'>e-mail</div></th>
					<th width='3%'  data-orderable='false'>
						<div align='center'>";
						
						if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
							echo "
							<form name='addpatrocinador' method='POST' action='form_cadastro_patrocinadores.php'>
								<input name='acao' type='hidden' value='INCLUIR'/>
								<input class='inputsrc' type='image' src='../imagens/add3.png' title='Incluir'/>
							</form>";
						}
						
						echo"
						</div>
					</th>
				</thead>
			<tbody>";
			
			while($linha = mysql_fetch_array($PAselect)){
			  $PAID            = $linha['PAID'];
				$PAIDENTIFICADOR = $linha['PAIDENTIFICADOR'];
				$PARAZAONOME     = $linha['PARAZAONOME'];
				$PACPFCNPJ       = $linha['PACPFCNPJ'];
				$PAIE            = $linha['PAIE'];
				$PAIM            = $linha['PAIM'];
				$PANOMEFANT      = $linha['PANOMEFANT'];
				$PACNAE          = $linha['PACNAE'];
				$PAEMAIL         = $linha['PAEMAIL'];
				$PASITE          = $linha['PASITE'];
				$PATEL1          = $linha['PATEL1'];
				$PATEL2          = $linha['PATEL2'];
				$PASEGMENTO      = $linha['PASEGMENTO'];
				$PASTATUS        = $linha['PASTATUS'];
				$PALOGO          = $linha['PALOGO'];
				
				switch($PASTATUS){
					case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='INATIVO'/>"; break;
					case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='ATIVO'/>";  break;
				}
	
				if ($PALOGO == 1){
					$palogo = "<div class='foto_perfilP'><img src='../$dominio_session/imagens/Fotos/$PAIDENTIFICADOR.jpg'/></div>";
	
				}else{
					$palogo = "<div class='foto_perfilP'><img src='../imagens/Fotos/SEMLOGO.jpg'/></div>";
				}
					
				echo"
					<tr>
					  <td data-order='$PASTATUS'><div align='center'>$situacao</div></td>
						<td><div align='center'>$palogo</div></td>
						<td><div align='center'><b>$PAIDENTIFICADOR</b></div></td>
						<td><div>$PARAZAONOME</div></td>
						<td><div>$PANOMEFANT</div></td>
						<td><div align='center'><a href='mailto:$PAEMAIL'>$PAEMAIL</a></div></td>
						<td>
							<div id='cssmenu' align='center'>
								<ul>
									<li class='has-sub'><a href='#'><span></span></a>
										<ul>
											<li>
												<form name='locais' method='POST' action='form_cadastro_locais.php'>
													<input name='lidpatrocinador' type='hidden' value='$PAIDENTIFICADOR'/>
													<input class='inputsrc'       type='image'  src='../imagens/icon-location.png' title='Estabelecimentos'/>
												</form>
											</li>
											<li>
												<form name='contatos' method='POST' action='form_cadastro_contatos.php'>
													<input name='paidentificador' type='hidden' value='$PAIDENTIFICADOR'/>
													<input name='parazaonome'     type='hidden' value='$PARAZAONOME'/>
													<input class='inputsrc'       type='image'  src='../imagens/team.png' title='Pessoas/Contatos'/>
												</form>
											</li>
											<li>
												<form name='produtos' method='POST' action='produtos_clientes.php'>
													<input name='paidentificador' type='hidden' value='$PAIDENTIFICADOR'/>
													<input name='parazaonome'     type='hidden' value='$PARAZAONOME'/>
													<input class='inputsrc'       type='image'  src='../imagens/modulos.png' title='Produtos/Módulos'/>
												</form>
											</li>
											<li>
												<form name='editar' method='POST' action='form_cadastro_patrocinadores.php'>
													<input name='paid'      type='hidden' value='$PAID'/>
													<input name='acao'      type='hidden' value='EDITAR'/>
													<input class='inputsrc' type='image' src='../imagens/edit.png' title='Editar'/>
												</form>
											</li>";
						
											if ($perfil_session == 'ADM' AND $PASTATUS == 1){//BLOQUEAR
											echo "
											<li>
												<form name='bloquear' method='POST' action='form_cadastro_patrocinadores.php' onsubmit='return confirma_inativar()'>
													<input name='paid'      type='hidden' value='$PAID'/>
													<input name='acao'      type='hidden' value='INATIVAR'/>
													<input class='inputsrc' type='image' src='../imagens/icone_omitido.png' title=' Inativar '/>
												</form>
											</li>";
											}

											if ($perfil_session == 'ADM' AND $PASTATUS == 0){//DESBLOQUEAR
											echo "
											<li>
												<form name='desbloquear' method='POST' action='form_cadastro_patrocinadores.php' onsubmit='return confirma_ativar()'>
													<input name='paid'      type='hidden' value='$PAID'/>
													<input name='acao'      type='hidden' value='ATIVAR'/>
													<input class='inputsrc' type='image'  src='../imagens/icone_omitido.png' title=' Ativar '/>
												</form>
											</li>";
											}
											
											if($apl_session == 'GOCF'){
												echo "
												<li>
													<form name='ocf' method='POST' action='empresa_obrigacoes.php'>
														<input name='paidentificador' type='hidden' value='$PAIDENTIFICADOR'/>
														<input class='inputsrc' type='image' src='../imagens/gocf.png' title=' Obrigações Contábeis '/>
													</form>
												</li>
												
												<li>
													<form name='ocf' method='POST' action='agendatr.php'>
														<input name='paidentificador' type='hidden' value='$PAIDENTIFICADOR'/>
														<input class='inputsrc' type='image' src='../imagens/agenda3.png' title=' Calendário de Tarefas '/>
													</form>
												</li>
												";
											}
					
										echo"
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
		  echo "<div align='center' class='info'>Nenhum cliente cadastrado!</div>";
			
			if($perfil_session == 'ADM' OR $perfil_session == 'GP'){
				echo"
				<div align='center'>
					<form name='addpatrocinador' method='POST' action='form_cadastro_patrocinadores.php'>
						<input name='acao' type='hidden' value='INCLUIR'/>
						<input class='inputsrc' type='image' src='../imagens/add3.png' title='Incluir'/>
					</form>
				</div>";
			}
		}
?>
</div>
</div>
</div>
</body>
</html>