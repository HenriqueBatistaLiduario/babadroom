<?php

  /* Necessário:
	
	$MAASSUNTO
	$MACONTEUDO
	$MAEMAILDESTINATARIO
	$MANOMEDESTINATARIO 
	
	*/

	$MABODY = "					
	<!DOCTYPE html>
	<html>
		<div align='center'>
			<div>
				<img src='https://petslittle.com.br/assets/images/other_images/logo-sem-coracao.png' alt='PetsLittle&copy' width='196' height='130'/>
			</div><br>
			<div>$MACONTEUDO</div><br>
			<p style='font-size:11px; color: purple;'>Mensagem automática. Favor não responder.</p>
			<p style='font-size:10px; color: gray;'>&copy 2015 PetsLittle - Todos os direitos reservados</p>
		</div>						
	</html>";

	$MANOMEREMETENTEPADRAO = 'PetsLittle';
	
	$caixaPostalServidorEmail = 'msgauto@petslittle.com.br';
	$caixaPostalServidorSenha = '/SI=BFzn9oYskx';
	
	require_once('PHPMailer-master/PHPMailerAutoload.php');

	$mail = new PHPMailer();

	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Charset = 'utf8_decode';
	$mail->Host = 'mx1.hostinger.com.br';
	$mail->Port = '587';
	$mail->Username = $caixaPostalServidorEmail;
	$mail->Password = $caixaPostalServidorSenha;
	$mail->From = $caixaPostalServidorEmail;
	$mail->FromName = $MANOMEREMETENTEPADRAO;
	$mail->IsHTML(true);
	$mail->Subject = utf8_decode($MAASSUNTO);
	$mail->Body = utf8_decode($MABODY);
	$mail->AddAddress($MAEMAILDESTINATARIO,$MANOMEDESTINATARIO);						

	if(!$mail->Send()){
		
		echo 
		"<div class='alert alert-danger alert-dismissable'>
		<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
		<strong>Ops!</strong> ERRO ao enviar e-mail ao destinatário <b>$MANOMEDESTINATARIO</b>
		</div>";

	}else{
		
		if($MSGCadastro == 1){
		
			echo "<h6 class='text-center' style='color:#833ab4;'>Um link de confirmação foi enviado no e-mail cadastrado. Verifique sua Caixa de Entrada.</h6>
			
			<div align='center'>
				<img class='imgnatural img-responsive' src='assets/images/gifs/gifcadastro.gif'/>
			</div>	
			
			";
		
		}
		
	}	
	
?>