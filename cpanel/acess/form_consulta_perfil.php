<?php include 'head.php'; ?>

	<script type="text/javascript">

		function confirma(){
			if(confirm('CONFIRMA A ALTERAÇÃO DA SENHA? Lembre-se de anotar a nova senha, pois ela é irrecuperável. Clique em OK para confirmar a alteração.'))
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

		function consiste_extensao(formulario, arquivo){
			var valido = false;	
			extensoes_permitidas = new Array(".jpg");
			var tamanhoArquivo = parseInt(document.getElementById("arquivo_foto").files[0].size);

			if (!arquivo_foto){ 
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
				
				if ($acao == 'CONFIRMAR_SENHA'){
					$SENHAATUAL   = $_POST['senhaatual'];
					$SENHAATUALBD = $_POST['usersenha'];
					$NOVASENHA    = $_POST['novasenha2'];

					if ($SENHAATUAL != $SENHAATUALBD){
							echo "<div align='center' class='warning'>A Senha informada no campo <b>Senha atual</b> é inválida.</div>";

					}else{
						$USERupdate = mysqli_query($con,"UPDATE usuarios SET USERSENHA = '$NOVASENHA' WHERE USERIDENTIFICACAO='$ident_session'") or print (mysqli_error());

						if($USERupdate){
						echo "<div id='alert' class='success' align='center'>Senha alterada com sucesso!</div>";
						}
					}
				}
				
				if($acao == 'ALTERAR_FOTO'){
					//Pasta onde o arquivo vai ser salvo
					$_UP['pasta']     = "../images/Perfis/";//Pasta onde o arquivo vai ser salvo
					$_UP['renomeia']  = true;
								
					$extensao = explode('.', $_FILES['arquivo']['name']);
					$extensao = end($extensao);
						
					if($_UP['renomeia'] == true){// Verifica se deve trocar o nome do arquivo
						$nome_final = "$ident_session.$extensao"; // Monta o nome do arquivo concatenando os dados informados nos campos e com extensão...
					
					}else{
						$nome_final = $_FILES['arquivo']['name'];// Mantém o nome original do arquivo...
					}
					
					//Depois verifica se é possível mover o arquivo para a pasta escolhida...
					if (move_uploaded_file($_FILES['arquivo']['tmp_name'],$_UP['pasta'].$nome_final)){// se moveu, faz o insert... 
						
						$USERupdate = mysqli_query($con,"UPDATE usuarios SET USERFOTO = 1 WHERE USERIDENTIFICACAO = '$ident_session'") or print (mysqli_error());
						
						if($USERupdate){
							echo "<div id='alert' align='center' class='success'>Imagem do Perfil alterada com sucesso.</div>";
						}
					
					}else{
						echo "<div align='center' class='error'>Upload não realizado. Verifique extensão/Tamanho do arquivo submetido.</div>";
					}
				}
			}//Fim das açoes...
				
			if (isset($_POST['USERIDENTIFICACAO'])){
				$identificacao = $_POST['USERIDENTIFICACAO'];
				$USERselect = mysqli_query($con,"SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$identificacao'") or print(mysqli_error());
			
			}else{
				$USERselect = mysqli_query($con,"SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$ident_session'") or print(mysqli_error());
			}
			
			while($USRrow = mysqli_fetch_assoc($USERselect)){
				$USERIDENTIFICACAO = $USRrow["USERIDENTIFICACAO"];
				$USERNOME          = $USRrow["USERNOME"];
				$USERPERFIL        = $USRrow["USERPERFIL"];
				$USERLOGIN         = $USRrow["USERLOGIN"];
				$USERSENHA         = $USRrow["USERSENHA"];
				$USERSTATUS        = $USRrow["USERSTATUS"];
				$USERCADASTRO      = $USRrow["USERCADASTRO"];
				$USERFOTO          = $USRrow["USERFOTO"];
				
				$usercadastro = date("d/m/Y H:i:s",strtotime($USERCADASTRO));
				
				switch($USERSTATUS){
					case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO'/>";   break;
					case 1: $situacao = "<img src='../images/status/VERDE.png'    title='ATIVO'/>";     break;
					case 2: $situacao = "<img src='../images/status/ROXO.png'     title='BLOQUEADO'/>"; break;
				}
			}
		?>

			<div class='panel panel-default'>
				<div class='panel-heading'>
					<span class='subpageName'>Perfil de Usuário</span>

					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
				</div>

				<div class='panel-body'>
					<div class='row'>
						<div class='col-sm-4'>
              <br><br>
							<?php 

							if($USERFOTO == 1){
								echo "<div align='center'><img class='foto_perfilGG img-responsive' src='../images/Perfis/$USERIDENTIFICACAO.jpg'/></div>";
							
							}else{
								echo "<div align='center'><img class='foto_perfilGG img-responsive' src='../images/Perfis/SEMFOTO.png'/></div>";
							}
							?>
							<br>
							<div align='center'><button type='button' class='btn btn-default btn-sm' data-toggle='modal' data-target='#alterar_foto'><i class='fa fa-camera-retro' aria-hidden='true'></i>&nbsp;&nbsp;Alterar Foto</button></div>

						</div>
					
						<div class='col-sm-8'>
						
							<div class='row'>
								<div class='col-sm-12 subpageName'><h3><b><?php echo $USERNOME;?></b></h3></div>
							</div><br><br>
							
							<div class='row'>
								<label class='col-sm-2 control-label'>Identificador:</label>
								<div class='col-sm-2'><?php echo $USERIDENTIFICACAO;?></div>
							</div>
							
							<div class='row'>
								<label class='col-sm-2 control-label'>Perfil:</label>
								<div class='col-sm-2'><?php echo $USERPERFIL; ?></div>
							</div>
							
							<div class='row'>
								<label class='col-sm-2 control-label'>e-mail (login):</label>
								<div class='col-sm-2'><?php echo $USERLOGIN;?></div>
							</div>
							
							<div class='row'>
								<label class='col-sm-2 control-label'>Cadastrado desde:</label>
								<div class='col-sm-2'><?php echo $usercadastro;?></div>
							</div>
							
							<div class='row'>
								<label class='col-sm-2 control-label'>Situação:</label>
								<div class='col-sm-2'><?php echo $situacao; ?></div>
							</div>
							
							<div class='info'>Suas informações de usuário são gerenciadas pelo Administrador deste domínio.</div>
							
							<div align='right'><button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#alterar_senha'><i class='fa fa-lock' aria-hidden='true'></i>&nbsp;&nbsp;Alterar Senha</button></div>
						</div>
					</div>
					
				</div>
			</div>
		</div>

		<div id='alterar_senha' class='modal fade' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
						<h4 class='modal-title'><i class='fa fa-lock' aria-hidden='true'></i>&nbsp;&nbsp;Alterar Senha...</h4>
					</div>
					
					<form class='form-horizontal' id='trocar' name='trocar' method='POST' action='form_consulta_perfil.php' onSubmit='return confirma();'>
						<div class='modal-body'>
							<div align='center'><img class='imgnatural' src='../imagens/cadeado1.png'></div>
						
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Usuário:</label>
								<div class='col-sm-6'><input class='form-control' name='useridentificacao' type='text' id='useridentificacao' value='<?php echo $ident_session;?>' readonly /></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Senha atual:</label>
								<div class='col-sm-6'><input class='form-control' name='senhaatual' type='password' id='senhaatual' required maxlength='15'/></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Nova senha:</label>
								<div class='col-sm-6'><input class='form-control' name='novasenha'  type='password' id='novasenha' required maxlength='15' placeholder='6 a 15 caracteres...'/></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-4 control-label'>Confirme nova senha:</label>
								<div class='col-sm-6'><input class='form-control' name='novasenha2' type='password' id='novasenha2' required maxlength='15' placeholder='6 a 15 caracteres...'/></div>
							</div>
							
						</div>
						<div class='modal-footer'>
						  <input name='usersenha' type='hidden' id='usersenha'  value='<?php echo $USERSENHA;?>'/>
							<input name='acao'      type='hidden' value='CONFIRMAR_SENHA'/>
							<button type='submit' class='btn btn-info btn-sm' onclick='return validatrocasenha();'>Confirmar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div id='alterar_foto' class='modal fade' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
						<h4 class='modal-title'><i class='fa fa-camera-retro' aria-hidden='true'></i>&nbsp;&nbsp;Alterar Foto...</h4>
					</div>
					
					<div class='info'>A resolução ideal é de 300px X 300px, para que não haja distorções da sua imagem de perfil quando exibida em diferentes tamanhos.</div>
					
					<form class='form-horizontal' name='anexos' method='POST' enctype='multipart/form-data' action='form_consulta_perfil.php' onsubmit='return confirma_alterar_imagem()'>
						<div class='modal-body'>
							<div class='form-group'>
								<div class='col-sm-12'><input class='form-control' type='file' name='arquivo' id='arquivo_foto' required /></div>
							</div>
						</div>
						
						<div class='modal-footer'>
							<input name='acao' type='hidden' value='ALTERAR_FOTO'/>
							<button type='submit' class='btn btn-info btn-sm' onclick='return consiste_extensao(this.form, this.form.arquivo_foto.value)'>Confirmar</button>
						</div>
					</form>
					
				</div>
			</div>
		</div>
		
	</body>
</html>