	<?php
	
	$USERLOGIN = $_POST["email"];
	$USERSENHA = $_POST["senha"];

	$USERselect = mysqli_query($con,"SELECT * FROM usuarios WHERE (USERIDENTIFICACAO = '$USERLOGIN' OR USERLOGIN = '$USERLOGIN') AND USERSTATUS <> 4") or print (mysqli_error());
	$USERtotal  = mysqli_num_rows($USERselect);

	if($USERtotal > 0){					
		
		while($USERrow = mysqli_fetch_assoc($USERselect)){
			$USERSTATUS = $USERrow["USERSTATUS"];
			
			if($USERSTATUS == 1){
				
				$USERSENHABD = $USERrow["USERSENHA"];
				
				if($USERSENHA != $USERSENHABD){ //Valida senha...
				
					echo "<script>alert('OPS! SENHA INVÁLIDA. Tente novamente.');location.href='$LHREFwarning';</script>";

				}else{
					
					$USERIDENTIFICACAO = $USERrow["USERIDENTIFICACAO"];
					$USERNOME          = $USERrow["USERNOME"];
					$USERPERFIL        = $USERrow["USERPERFIL"];
					$USERPRIMACESSO    = $USERrow["USERPRIMACESSO"];
						
					if(!empty($_SERVER['HTTP_CLIENT_IP'])){
						$SAIP = $_SERVER['HTTP_CLIENT_IP'];
					
					}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
						$SAIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
					
					}else{
						$SAIP = $_SERVER['REMOTE_ADDR'];
					}
					
					$SABROWSER = $_SERVER['HTTP_USER_AGENT'];
					
					$IDSESSAO = uniqid();
					
					$SESSIONquery = "INSERT INTO sessoes(SAIDSESSION,SAIDUSUARIO,SALOGINIP,SASTATUS,SALOGIN,SABROWSER) VALUES ('$IDSESSAO','$USERIDENTIFICACAO','$SAIP',1,'$datetime','$SABROWSER')";
				
					$INSERTsessao = mysqli_query($con,$SESSIONquery) or print "<h1>ERRO DE BANCO DE DADOS!</h1>";
					
					if($INSERTsessao){
						
						$_SESSION["idsession"] = $IDSESSAO;
						$_SESSION["ident_session"] = $USERIDENTIFICACAO;
						
						if($USERPRIMACESSO == 1){
							
							mysqli_query($con,"UPDATE usuarios SET USERDTPRIMACESSO='$datetime' WHERE USERIDENTIFICACAO='$USERIDENTIFICACAO'") or print (mysqli_error());
							
						}
						
						mysqli_query($con,"UPDATE usuarios SET USERDTULTACESSO='$datetime' WHERE USERIDENTIFICACAO='$USERIDENTIFICACAO'") or print (mysqli_error());
						
						echo "<script>location.href='$LHREFsuccess';</script>";
							
					}
				}								

			}else{
			
				switch ($USERSTATUS){
					case 0: $usersituacao = "Seu cadastro ainda não foi confirmado.<br>Acesse seu e-mail e faça a validação conforme as instruções enviadas.";break;
					case 2: $usersituacao = "Usuário DESATIVADO.";break;
					case 3: $usersituacao = "Usuário BANIDO conforme os Termos de Uso desta plataforma.";break;
				}
				
				echo "<script> alert('OPS! $usersituacao.');location.href='$HREFwarning';</script>";
				
			}
		}							
		
	}else{
		
		echo "<script> alert('OPS! USUÁRIO INEXISTENTE. Utilize o e-mail informado no cadastro.');location.href='$HREFwarning';</script>";
		
	}
	
	?>