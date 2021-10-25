<!DOCTYPE html>

<html>
	<head>
	
		<title>BABADROOM | CPanel</title>
		<meta name='viewport' content='width=device-width,initial-scale=1.0' charset='utf-8'>
		<link rel='shortcut icon' href='imagens/favicon.ico' type='image/x-icon'/>
		
		<link rel='stylesheet' href='css/form-login.css'>
		<link rel='stylesheet' href='css/bootstrap.css'/>
		<link rel='stylesheet' href='css/jquery.smartmenus.bootstrap.css'/>
		<link rel='stylesheet' href='css/font-awesome.min.css'/>
		<link rel='stylesheet' href='css/css.css'/>
		<link rel='stylesheet' href='css/navbar-fixed-top.css' >
		
		<script src='js/jquery-3.2.1.min.js'></script>
		<script src='js/bootstrap.min.js'></script>	
		<script src='js/retina-1.1.0.min.js'></script>
		
	</head>

  <nav class='navbar navbar-default navbar-fixed-top'>
		<div class='container-fluid'>
			<div class='navbar-header'>	
				
			</div>
		</div>
	</nav>
	
	<body>

		<?php

		//Verifica a situação contratual do domínio no Painel de Controle...
		
		include "connect/conecta_cpanel.php";
		include "InfoContratuais.php";		
		include "timezone.php";
		
		if(isset($_POST["acao"])){
			$acao = $_POST["acao"];
			
			if($acao == "LOGIN"){
				
				$USERLOGIN = $_POST["login"];
				$USERSENHA = $_POST["senha"];
				
				$USERselect = mysqli_query($con,"SELECT * FROM usuarios WHERE USERLOGIN = '$USERLOGIN' AND USERSTATUS <> 4") or print (mysqli_error());
				$USERtotal  = mysqli_num_rows($USERselect);
				
				if($USERtotal > 0){ //Se existe o usuário...
				
				  while($row = mysqli_fetch_assoc($USERselect)){
				
						$USERNOME          = $row["USERNOME"];
						$USERPERFIL        = $row["USERPERFIL"];
						$USERSTATUS        = $row["USERSTATUS"];
						$USERIDENTIFICACAO = $row["USERIDENTIFICACAO"];
						$USERSENHABD       = $row["USERSENHA"];
						$USERPRIMACESSO    = $row["USERPRIMACESSO"];
						
						if($USERSTATUS == 1){
							
							if($USERSENHA == $USERSENHABD){ //Valida senha...
							
								if(!empty($_SERVER["HTTP_CLIENT_IP"])){
									$SAIP = $_SERVER["HTTP_CLIENT_IP"];
								
								}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
									$SAIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
								
								}else{
									$SAIP = $_SERVER["REMOTE_ADDR"];
								}
								
								$SAselect = mysqli_query($con,"SELECT * FROM sessoes WHERE IDDOMINIO = '$ADDOMINIO' AND SAIDUSUARIO = '$USERIDENTIFICACAO' AND SASTATUS = 1") or print (mysqli_error());
								$SAconexoes = mysqli_num_rows($SAselect);
								
								if($SAconexoes > 0){ //Derruba sessões ativas deste usuário automaticamente, antes de estabelecer nova...
									$SALOGIN = mysql_result($SAselect,0,"SALOGIN");
								
									$H = date('H',strtotime($SALOGIN));
									$i = date('i',strtotime($SALOGIN));
									$s = date('s',strtotime($SALOGIN));
									$m = date('m',strtotime($SALOGIN));
									$d = date('d',strtotime($SALOGIN));
									$Y = date('Y',strtotime($SALOGIN));

									$H2 = date('H');
									$i2 = date('i');
									$s2 = date('s');
									$m2 = date('m');
									$d2 = date('d');
									$Y2 = date('Y');

									$LOGIN  = mktime($H,$i,$s,$m,$d,$Y);
									$LOGOUT = mktime($H2,$i2,$s2,$m2,$d2,$Y2);
									
									$DuracaoSegundos = $LOGOUT - $LOGIN; //Em segundos...
									$DuracaoMinutos  = $DuracaoSegundos/60; //Em minutos...
									
									//Exibir duração no formato de horas...
									$DURACAOminutos = $DuracaoMinutos%60;
									$DURACAOhoras =($DuracaoMinutos-$DURACAOminutos)/60;

									if($DURACAOminutos < 10){
										$DURACAOminutos = "0$DURACAOminutos";
									}
									
									$SADURACAO = "$DURACAOhoras:$DURACAOminutos";
									
									$SESSAOupdate = mysqli_query($con,"UPDATE sessoes SET SALOGOUT = '$datetime', 
																																				SASTATUS = 3,
																																			SADTSTATUS = '$datetime',
																																			 SADURACAO = '$SADURACAO',
																																			SALOGOUTIP = '$SAIP' WHERE SAIDUSUARIO = '$USERIDENTIFICACAO' AND SASTATUS = 1") or print "<div class='error'><b>FALHA NO LOGOUT DE SESSÕES ANTERIORES.<br>ERRO DE BANCO DE DADOS: </b>".(mysqli_error())."</div>";								
									if(!$SESSAOupdate){
										exit;
									}									
								}
								
								$SAIDSESSION = uniqid();
								
								$INSERTsessao = mysqli_query($con,"INSERT INTO sessoes(IDDOMINIO,IDADMINISTRADOR,SAIDSESSION,SAIDUSUARIO,SALOGINIP,SASTATUS,SALOGIN) VALUES ('$ADDOMINIO','ND','$SAIDSESSION','$USERIDENTIFICACAO','$SAIP',1,'$datetime')") or print "<div class='error'><b>FALHA NA ABERTURA DE SESSÃO DE USUÁRIO.<br>ERRO DE BANCO DE DADOS: </b>".(mysqli_error())."</div>";
								
								if($INSERTsessao){
									
									mysqli_query($con,"UPDATE usuarios SET USERDTULTACESSO = '$datetime' WHERE USERIDENTIFICACAO = '$USERIDENTIFICACAO'") or print (mysqli_error());
									
									@session_start();
									
									$_SESSION["id_session"]      = $SAIDSESSION;
									$_SESSION["ident_session"]   = $USERIDENTIFICACAO;
									$_SESSION["dominio_session"] = $ADDOMINIO;
									$_SESSION["adm_session"]     = $ADMSESSION;
									$_SESSION["apl_session"]     = $APLSESSION;
									$_SESSION["cnt_session"]     = $CNTSESSION;
									
									if(isset($_SESSION['id_session'])){
									
										if($USERPRIMACESSO == 1){
											
											mysqli_query($con,"UPDATE usuarios SET USERDTPRIMACESSO = '$datetime' WHERE USERIDENTIFICACAO = '$USERIDENTIFICACAO'") or print (mysqli_error());
										
										}
											
										echo "<script>location.href='acess/home.php';</script>";
									
									}else{
										
										echo "<div align='right' class='error'>Ops! Falha em <b>session_start</b></div>";
										
									}
								
								}					
								
							}else{
								
								echo "<div align='center' class='warning'>USUÁRIO ATIVO: Senha inválida.</div>";
								
							}
						
						}else{
							
							switch ($USERSTATUS){
								case 0: $usersituacao = "<div align='center' class='info'>PREZADO <b>$USERNOME</b>,<br>Seu acesso ainda não foi liberado.</div>";break;
								case 2: $usersituacao = "<div align='center' class='warning'>Usuário DESATIVADO.</div>";break;
								case 3: $usersituacao = "<div align='center' class='warning'>Usuário SUSPENSO.  </div>";break;
							}
							
							echo $usersituacao;
						}
					}
					
				}else{
					echo "<div align='center' class='warning'>USUÁRIO INEXISTENTE.</div>";
				}
			}
		}

		if($ADSITUACAO == 5){
				
			echo "
			<div class='container'>
				<div class='top-content'>
					<div class='inner-bg'>
					
						<div class='row'>
							<div class='col-sm-6'>
								<div class='text-center'>
									<img class='imgnatural img-responsive' src='images/Logo550x363.png' />							
								</div>							
							</div>
							
							<div class='col-sm-6 form-box'>
								<div class='form-top'>
									<div class='form-top-left'>
										<h3><strong>CPanel</strong> <small>Área restrita</small></h3>
											<p>Informe os dados de acesso a plataforma:</p>
									</div>
									<div class='form-top-right'><i class='fa fa-lock'></i></div>
								</div>
								<div class='form-bottom'>
									<form role='form' class='registration-form' method='POST' action='index.php' >
										<div class='form-group'>
											<label class='sr-only' for='form-email'>e-mail</label>
											<input type='email' name='login' placeholder='e-mail...' class='form-control' required />
										</div>
										<div class='form-group'>
											<label class='sr-only' for='form-first-name'>Senha</label>
											<input type='password' name='senha' placeholder='Senha...' class='form-control' />
										</div>
										<input name='acao'    type='hidden' value='LOGIN'/>
										<input name='dominio' type='hidden' value='$ADDOMINIO'/>
										<div align='right'><button type='submit' class='btn'><i class='fa fa-sign-in' aria-hidden='true'></i>&nbsp;Entrar</button></div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>";
		
		}else{
			
			switch($ADSITUACAO){ //Status do Domínio...
				case 0: $adsituacao = "<img src='../imagens/status/AZUL.png'    />"; $adstatus = 'RESERVADO';                           break;
				case 1: $adsituacao = "<img src='../imagens/status/AZUL.png'    />"; $adstatus = 'CONTRATADO - Aguardando confirmação'; break;
				case 2: $adsituacao = "<img src='../imagens/status/AMARELO.png' />"; $adstatus = 'SUBMETIDO - Aguardando Instalação';   break;
				case 3: $adsituacao = "<img src='../imagens/status/AMARELO.png' />"; $adstatus = 'INSTALADO - Aguardando Liberação';    break;
				case 4: $adsituacao = "<img src='../imagens/status/LARANJA.png' />"; $adstatus = 'LIBERADO  - Aguardando Ativação';     break;
				case 6: $adsituacao = "<img src='../imagens/status/BRANCO.png'  />"; $adstatus = 'BLOQUEADO';                           break;
				case 7: $adsituacao = "<img src='../imagens/status/ROXO.png'    />"; $adstatus = 'CANCELADO';                           break;
			}
			
			echo "
			<br>
			<br>
			<div class='container' align='center'>
				<div class='warning' align='center'>DOMÍNIO INDISPONÍVEL!</div>
				<br>
				<br>
				<hr></hr>
				<h1><i class='fa fa-cloud'></i></h1>
				<hr></hr>
				<br>
				<br>
				<div class='info'>No momento este domínio encontra-se<br><br>$adsituacao&nbsp;&nbsp;<b>$adstatus</b><br>Para maiores informações, entre em contato com o Administrador deste domínio.</div>
				<br>
				<br>				
			</div>";
			
		}
		
		?>

	</body>	<!--<img class='imgnatural img-responsive img-logoindex' src='imagens/LOGOindex.png' alt='$ADDOMINIO'>-->
</html>