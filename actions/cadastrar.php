	<?php
	
	$CTMIDENTIFICADOR = $_POST['ctmidentificador'];
	$CTMNOME          = $_POST['ctmnome'];
	$CTMEMAIL         = $_POST['ctmemail'];
	$CTMTEL1          = $_POST['ctmtel1'];
	$CTMCEP           = $_POST['ctmcep'];
	$USERSENHA        = $_POST['usersenha2'];

	$USERselect = mysqli_query($con,"SELECT USERID FROM usuarios WHERE USERIDENTIFICACAO = '$CTMIDENTIFICADOR'");
	$USERexiste = mysqli_num_rows($USERselect);

	if($USERexiste == 0){ //Apenas para evitar reação do Browser
		
		//Consiste e-mail e telefone...
		$USERconsiste = mysqli_query($con,"SELECT USERIDENTIFICACAO FROM usuarios WHERE USERLOGIN = '$CTMEMAIL' OR USERTEL1 = '$CTMTEL1'");
		$USERexiste   = mysqli_num_rows($USERconsiste);
		
		if($USERexiste > 0){
			
			echo "<script>alert('OPS! EMAIL OU TELEFONE JÁ EXISTEM NA BASE DE DADOS! Caso tenha esquecido a senha, clique em Esqueci a Senha para recuperá-la.');location.href='$LHREFwarning';</script>";
			
		}else{ //Inserir...
		
			$CTMinsert = mysqli_query($con,"INSERT INTO customers(CTMIDENTIFICADOR,CTMNOME,CTMEMAIL,CTMTEL1,CTMCEP) VALUES ('$CTMIDENTIFICADOR','$CTMNOME','$CTMEMAIL','$CTMTEL1','$CTMCEP')");
			
			if($CTMinsert){
				
				$USERinsert = mysqli_query($con,"INSERT INTO usuarios(USERIDENTIFICACAO,USERNOME,USERPERFIL,USERLOGIN,USERTEL1,USERSENHA) VALUES('$CTMIDENTIFICADOR','$CTMNOME','USR','$CTMEMAIL','$CTMTEL1','$USERSENHA')");
				
				if($USERinsert){
					
					$CTMNAME = explode(" ",$CTMNOME);
					$CTMFIRSTNAME = $CTMNAME[0];				
					
					//Abre imediatamente uma sessão com o usuário récem-criado...
					
					if(!empty($_SERVER['HTTP_CLIENT_IP'])){
						$SAIP = $_SERVER['HTTP_CLIENT_IP'];
					
					}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
						$SAIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
					
					}else{
						$SAIP = $_SERVER['REMOTE_ADDR'];
					}
					
					$SABROWSER = $_SERVER['HTTP_USER_AGENT'];
					
					$IDSESSAO = uniqid();
					
					$SESSIONquery = "INSERT INTO sessoes(SAIDSESSION,SAIDUSUARIO,SALOGINIP,SASTATUS,SALOGIN,SABROWSER) VALUES ('$IDSESSAO','$CTMIDENTIFICADOR','$SAIP',1,'$datetime','$SABROWSER')";
				
					$INSERTsessao = mysqli_query($con,$SESSIONquery) or print "<h1>ERRO DE BANCO DE DADOS!</h1>";
					
					if($INSERTsessao){
						
						session_start();
						
						$_SESSION["idsession"]     = $IDSESSAO;
						$_SESSION["ident_session"] = $CTMIDENTIFICADOR;
						
						mysqli_query($con,"UPDATE usuarios SET USERDTPRIMACESSO='$datetime', USERDTULTACESSO='$datetime' WHERE USERIDENTIFICACAO='$CTMIDENTIFICADOR'");						
						
						echo "<script>alert('CADASTRO REALIZADO COM SUCESSO! $CTMFIRSTNAME, seja bem-vindo a Babadroom');location.href='$LHREFsuccess';</script>";
						
					
					}
				
				}
			
			}
		
		}

	}

	?>	