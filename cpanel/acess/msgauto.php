<?php

    /*Variáveis necessárias:
		  
			$MSGAUTO  1. Dispara | 2. Não dispara
			$MACODIGO Código da Mensagem automática
			$MAORIGEM 1. Discussão em Ticket | 2. Referente a Projeto | 3. Mensagem Direta
			
		*/
		
	if($MSGAUTO == 1){
		
		if(isset($MACODIGO)){
			
			$MAselect = mysql_query("SELECT * FROM msgauto WHERE MACODIGO = '$MACODIGO'") or print (mysql_error());
			$MAID                = mysql_result($MAselect,0,"MAID");
			$MAAPLICACAO         = mysql_result($MAselect,0,"MAAPLICACAO");                   
			$MANOMEPROCESSO      = mysql_result($MAselect,0,"MANOMEPROCESSO");     
			$MANOMEPROCESSOCOMPL = mysql_result($MAselect,0,"MANOMEPROCESSOCOMPL");
			$MAASSUNTO           = mysql_result($MAselect,0,"MAASSUNTO");         
			$MATEXTOPADRAO       = mysql_result($MAselect,0,"MATEXTOPADRAO");      
			$MAENVIAHISTORICO    = mysql_result($MAselect,0,"MAENVIAHISTORICO");  
			$MASITUACAO          = mysql_result($MAselect,0,"MASITUACAO");         
			$MADTSITUACAO        = mysql_result($MAselect,0,"MADTSITUACAO");       
			$MAUSRSITUACAO       = mysql_result($MAselect,0,"MAUSRSITUACAO");
			
			########################## Dados do Remetente Padrão #############################################################################################
			
			$MANOMEREMETENTEPADRAO = 'PetsLittle';
			
			$caixaPostalServidorEmail = 'msgauto@petslittle.com.br';
			$caixaPostalServidorSenha = '@Khteco475869z@!';
			
			#################################################################################################################################################*/
			
			if($MASITUACAO == 1){
				$MSGREMETENTE = $ident_session;
				
				$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$MSGREMETENTE'") or print(mysql_error());
				$MANOMEREMETENTE  = mysql_result($USERselect,0,"USERNOME");
				$MAEMAILREMETENTE = mysql_result($USERselect,0,"USERLOGIN");
				
				$USERFOTO   = mysql_result($USERselect,0,"USERFOTO");

				if ($USERFOTO == 1){
					$usericone = "http://petslittle.com.br/fotos/usuarios/$MSGREMETENTE.jpg";

				}else{
					$usericone = "http://petslittle.com.br/fotos/usuarios/SEMFOTO.png";
				}
				
				if($MAORIGEM == 1){ //1. Discussão em Ticket
					
					$Oselect = mysql_query("SELECT * FROM ocorrencias WHERE OCODIGO = '$OCODIGO'") or print (mysql_error());
					$OSOLICITANTE = mysql_result($Oselect,0,"OIDSOLICITANTE");
					$ORESUMO      = mysql_result($Oselect,0,"ORESUMO");
					$ODISCUSSAO   = mysql_result($Oselect,0,"ODISCUSSAO");

					// Define a lista de destinatários... 
					
					$DESTINATARIOSselect = mysql_query("SELECT UCIDUSUARIO FROM usuarios_chamado WHERE UCCODTICKET = '$OCODIGO' AND UCMSGAUTO = 1 AND UCSTATUS = 1 AND UCIDUSUARIO != '$ident_session'") or print (mysql_error());
					
					if($MAENVIAHISTORICO == 1){
						$OHISTORICO = "<br><br>Discussão:<br><br><table border=1 bgcolor=#F8F8FF cellpadding=10>$ODISCUSSAO</table><br><br>";					
					
					}else{
						$OHISTORICO = '';
					}
				}
				
				if($MAORIGEM == 3){ //3. Mensagem Direta
				
				  if($MSGDINDIVIDUAL == 1){
				
						// Define a lista de destinatários... 
						$DESTINATARIOSselect = mysql_query("SELECT USERIDENTIFICACAO FROM usuarios WHERE USERLOGIN = '$MSGDDESTINATARIO'") or print(mysql_error());
					
					}					
				}
				
				while ($linha = mysql_fetch_array($DESTINATARIOSselect)){
					
					if($MAORIGEM == 1){
						$MADESTINATARIO = $linha["UCIDUSUARIO"];
						
						eval("\$MATEXTOPADRAO02 = \"$MATEXTOPADRAO02\";");
						eval("\$MATEXTOPADRAO03 = \"$MATEXTOPADRAO03\";");
						eval("\$MATEXTOPADRAO04 = \"$MATEXTOPADRAO04\";");
						eval("\$MATEXTOPADRAO05 = \"$MATEXTOPADRAO05\";");
						eval("\$MATEXTOPADRAO06 = \"$MATEXTOPADRAO06\";");
						eval("\$MATEXTOPADRAO07 = \"$MATEXTOPADRAO07\";");
						eval("\$MATEXTOPADRAO08 = \"$MATEXTOPADRAO08\";");
						eval("\$MATEXTOPADRAO09 = \"$MATEXTOPADRAO09\";");
						eval("\$MATEXTOPADRAO10 = \"$MATEXTOPADRAO10\";");
						eval("\$MATEXTOPADRAO11 = \"$MATEXTOPADRAO11\";");
						eval("\$MATEXTOPADRAO12 = \"$MATEXTOPADRAO12\";");
						eval("\$MATEXTOPADRAO13 = \"$MATEXTOPADRAO13\";");
						eval("\$MATEXTOPADRAO14 = \"$MATEXTOPADRAO14\";");
						
          }

          if($MAORIGEM == 3){
						
						if($MSGDINDIVIDUAL == 1){
							$MADESTINATARIO = $linha["USERIDENTIFICACAO"];
							
							eval("\$MATEXTOPADRAO15 = \"$MATEXTOPADRAO15\";");
						
						}
						
					}					
					
					$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$MADESTINATARIO'") or print(mysql_error());
					
					$MAEMAILDESTINATARIO = mysql_result($USERselect,0,"USERLOGIN");
					$MANOMEDESTINATARIO  = mysql_result($USERselect,0,"USERNOME");
					
					$MACONTEUDO = mysql_result($MAselect,0,"MACONTEUDO");

					eval("\$MAASSUNTO = \"$MAASSUNTO\";");
					eval("\$MATEXTOPADRAO = \"$MATEXTOPADRAO\";");
					eval("\$MACONTEUDO = \"$MACONTEUDO\";");
					
					$MABODY = "
					<!DOCTYPE html>
					<html>
						<div align='center'>
						  <div>
								<img src='http://petslittle.com.br/assets/images/other_images/logo-sem-coracao.png' alt='PetsLittle&copy' style='width:196px; height:130px;'/>
							</div><br>
							<div>$MACONTEUDO</div><br>
							<p style='font-size:11px; color: purple;'>Mensagem automática. Favor não responder.</p>
							<p style='font-size:10px; color: gray;'>&copy 2015 PetsLittle - Todos os direitos reservados</p>
            </div>						
					</html>";
					
					$WORKFLOW = mysql_query("INSERT INTO mensagens(MSGTIPO,IDANUNCIO,MSGWFDISPARADO,MSGSTATUS,MSGDESTINATARIO,MSGREMETENTE,MSGASSUNTO,MSGMENSAGEM,MSGCOMPLEMENTO,MSGDTENVIO,MSGCODTICKET,MSGCADASTRO,MSGCADBY)
					VALUES('$MACODIGO','$IDANUNCIO',0,0,'$MADESTINATARIO','$MSGREMETENTE','$MAASSUNTO','$MACONTEUDO','$INTERACAO','$datetime','$OCODIGO','$datetime','$ident_session')") or print (mysql_error());				
					
					if($WORKFLOW){
						
						############## PHP Mailer ##########################################################################################################################################################
						
						require_once('PHPMailer-master/PHPMailerAutoload.php');

						$mail = new PHPMailer();

						$mail->IsSMTP();
						$mail->SMTPAuth  = true;
						$mail->Charset   = 'iso-8859-1';
						$mail->Host  = 'smtp.'.substr(strstr($caixaPostalServidorEmail, '@'), 1);
						$mail->Port  = '587';
						$mail->Username  = $caixaPostalServidorEmail;
						$mail->Password  = $caixaPostalServidorSenha;
						$mail->From  = $caixaPostalServidorEmail;
						$mail->FromName  = $MANOMEREMETENTEPADRAO;
						$mail->IsHTML(true);
						$mail->Subject  = $MAASSUNTO;
						$mail->Body  = $MABODY;
						$mail->AddAddress($MAEMAILDESTINATARIO,$MANOMEDESTINATARIO);					
						
						if(!$mail->Send()){
							$MSGalert = 
							"<div class='alert alert-danger alert-dismissable'>
								<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
								<strong>Ops!</strong>Ocorreu um erro interno. E-mails não enviados.
							</div>";
						}				
					}
				}
			}
		}
	}