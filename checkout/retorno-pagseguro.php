<?php

  header("access-control-allow-origin: https://pagseguro.uol.com.br");
	
	$VOLTAR = NULL;

	####### POST RECEBIDO VIA PAGSEGURO ####################################################################################################

	if(isset($_POST['notificationType']) AND $_POST['notificationType'] == 'transaction'){

		$notificationCode = $_POST['notificationCode'];
		
		$email = "pagseguro@babadroom.com";
		$token = "6d9d14dc-a0d5-4b4b-9c12-1015ff329cce8e9fd4db4b3ea3aa67b0c5906acfee27b69f-977e-44a0-a669-61a1c3c34845";
		
		$url = "https://ws.pagseguro.uol.com.br/v3/transactions/notifications/$notificationCode?email=$email&token=$token";

		$curl = curl_init($url);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$http = curl_getinfo($curl);
		
		curl_close($curl);
		
		$response = simplexml_load_string($response);
		
		$IDTRANSACAO    = $response->items->item->id;
		$CODPAGSEG      = $response->code;
		$STATUSPAGSEG   = $response->status;
		$DTSTATUSPAGSEG = $response->lastEventDate;
		$VALORPAGO      = $response->items->item->amount;
		$TIPOPAGPAGSEG  = $response->paymentMethod->code;
		$VALORLIQUIDO   = $response->netAmount;		
		$PAGADORNOME    = $response->sender->name;
		$PAGADOREMAIL   = $response->sender->email;
		$PAGADORDDD     = $response->sender->phone->areaCode;
		$PAGADORTEL     = $response->sender->phone->number;
		
		$DTST1 = str_ireplace("T"," ",$DTSTATUSPAGSEG);
		$DTST2 = explode(".",$DTST1); 
		
		$DTSTATUS = $DTST2[0];
		
		$TEL = "$PAGADORDDD$PAGADORTEL";
		
		/*echo "<h1>IDTRANSACAO    = $IDTRANSACAO
		        <br>CODPAGSEG      = $CODPAGSEG
						<br>STATUSPAGSEG   = $STATUSPAGSEG
						<br>DTSTATUSPAGSEG = $DTSTATUS
						<br>VALORPAGO      = $VALORPAGO
						<br>TIPOPAGSEG     = $TIPOPAGPAGSEG
						<br>VALORLIQUIDO   = $VALORLIQUIDO
						<br>PAGADORNOME    = $PAGADORNOME
						<br>PAGADOREMAIL   = $PAGADOREMAIL
						<br>PAGADORTEL     = $TEL</h1>"; */
		
		######## Atualização da Transação... #########################
		
		include '../timezone.php';
	  include '../connect.php';
					
		//Atualiza o Status no Banco...
		
		$Tupdate = mysqli_query($con,"UPDATE transacoes SET CODPAGSEG = '$CODPAGSEG', 
																							       STATUSPAGSEG = $STATUSPAGSEG,
																						       DTSTATUSPAGSEG = '$DTSTATUS',
																									      VALORPAGO = '$VALORPAGO',
																							      TIPOPAGPAGSEG = '$TIPOPAGPAGSEG', 
																							       VALORLIQUIDO = '$VALORLIQUIDO',
																							        PAGADORNOME = '$PAGADORNOME',
																							       PAGADOREMAIL = '$PAGADOREMAIL',
																							         PAGADORTEL = '$TEL',
																							      TULTALTERACAO = '$datetime' WHERE IDTRANSACAO = '$IDTRANSACAO' ");
		if($Tupdate){

      $SOselect = mysqli_query($con,"SELECT SOCODE FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO'");
			
			while($SOcol = mysqli_fetch_array($SOselect)){
				
				$SOCODE = $SOcol["SOCODE"];
				
			}
																		 
			if($STATUSPAGSEG == 3){ //Sempre que receber Status PAGO via PagSeguro, atualiza a Sale Order ...			
					
				mysqli_query($con,"UPDATE sales_orders SET SOSTATUS = 2,																		 
																								 SODTSTATUS = '$datetime',
																							SOUSRSITUACAO = 'robot1' WHERE SOCODE = '$SOCODE'");			
			}
			
			
			if(isset($_POST['manual'])){
	
				$IDTRANSACAO = $_POST['idtransacao'];
				echo "<script>location.href='https://legalbook.com.br/app/apo/LBADMtransacoes.php?sinc=1&id=$IDTRANSACAO';</script>";
				
			}
			
		}	
		
	/*if($liberar_livro == true){
	
		if($IDANUNCIO != NULL){
			
			//Publica automaticamente o anúncio conforme Plano comprado...
			
			if($CODPRODUTO == 'UP'){
				$ANupdate = mysql_query("UPDATE anuncios SET ANSTATUS = 1, 
				                                           ANDTSTATUS = '$datetime', 
																									ANUSRSTATUS = 'robot1', 
																									  ANDTULTUP = '$datetime', 
																								 ANPRIORIDADE = $ANPRIORIDADE, 
																								ANDTEXPIRACAO = '$ANDTEXPIRACAO' WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());
			
			}else{
				
				$ANupdate = mysql_query("UPDATE anuncios SET ANSTATUS = 1, 
				                                           ANDTSTATUS = '$datetime', 
																									ANUSRSTATUS = 'robot1', 
																									    ANPLANO = '$CODPRODUTO', 
																							ANDTULTALTPLANO = '$datetime', 
																							   ANPRIORIDADE = $ANPRIORIDADE, 
																								ANDTEXPIRACAO = '$ANDTEXPIRACAO' WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());					
				
				mysql_query("INSERT INTO extratos (IDUSUARIO,IDTRANSACAO,EXTTIPO,EXTDETALHES,EXTVALOR,EXTDATA,EXTCADBY) VALUES 
				('$IDUSUARIO','$IDTRANSACAO','D','Publicação do Anúncio $IDANUNCIO no Plano $CODPRODUTO','$VALOREXTRATO','$datetime','robot1')") or print (mysql_error());
			}
			
			if($ANupdate){
				$comunicar_atividade = true;
				$tipo = 'publicado';
			}
		}
		
	}	
	
	if($bloquear_livro == true){
	
		$CLIENTEselect = mysql_query("SELECT IDUSUARIO, IDANUNCIO, CODPRODUTO, PRODUTO, DESCRICAO FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO'") or print (mysql_error());
		$IDUSUARIO  = mysql_result($CLIENTEselect,0,"IDUSUARIO");
		$PRODUTO    = mysql_result($CLIENTEselect,0,"PRODUTO");
		$DESCRICAO  = mysql_result($CLIENTEselect,0,"DESCRICAO");
		$CODPRODUTO = mysql_result($CLIENTEselect,0,"CODPRODUTO");
		$IDANUNCIO  = mysql_result($CLIENTEselect,0,"IDANUNCIO");
		
		switch($CODPRODUTO){
			case 'UP':      $VALORDEBITO = 3.50;  break;
			case 'CLASS':   $VALORDEBITO = 12.50; break;
			case 'TOP':     $VALORDEBITO = 25.00; break;
			case 'PREMIUM': $VALORDEBITO = 37.50; break;
		}
		
		if($IDANUNCIO != NULL){
			
			//Despublica automaticamente o anúncio conforme Plano comprado...
		
			$ANupdate = mysql_query("UPDATE anuncios SET ANSTATUS = 2, ANDTSTATUS = '$datetime', ANUSRSTATUS = 'robot1' WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());
			
		}
		
		if($ANupdate){
			$comunicar_atividade = true;
			$tipo = 'despublicado';
		}
		
	}
	
	if($comunicar_pagamento == true){
		
		$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$IDUSUARIO'") or print (mysql_error());
		
		$USERNOME = explode(" ", mysql_result($USERselect,0,"USERNOME"));
		$MANOMEDESTINATARIO  = $USERNOME[0];
		$MAEMAILDESTINATARIO = mysql_result($USERselect,0,"USERLOGIN");
		
		if($tipo == 'PagamentoPagSeguro'){
			$MAASSUNTO = "Confirmação de Pagamento via PagSeguro";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Seu pagamento através do PagSeguro foi confirmado.<br>
			Valor do Pagamento: <b>$VALORPAGO</b><br>
			Forma de Pagamento: <b>$TIPOPAG</b><br><br>
			Acompanhe suas transações através do seu <a href='http://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
		}
		
		if($tipo == 'EstornoPagSeguro'){
			$MAASSUNTO = "Estorno de Pagamento via PagSeguro";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Fomos notificados de que um pagamento que você tinha realizado através do PagSeguro foi estornado.<br>
			Com isso seus anúncios, caso publicados, serão despublicados automaticamente.<br>
			Estamos a disposição para maiores esclarecimentos sobre nossos Termos de Uso.<br>
			Acompanhe suas transações através do seu <a href='http://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
		}	
		
	}
	
	if($comunicar_atividade == true){
		$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$IDUSUARIO'") or print (mysql_error());
		
		$USERNOME = explode(" ", mysql_result($USERselect,0,"USERNOME"));
		$MANOMEDESTINATARIO  = $USERNOME[0];
		$MAEMAILDESTINATARIO = mysql_result($USERselect,0,"USERLOGIN");
		
		if($tipo == 'publicado'){
			$MAASSUNTO  = "Anúncio publicado";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Parabéns! Seu anúncio <b>$IDANUNCIO</b> acaba de ser publicado na Galeria <b>$CODPRODUTO</b>.<br>
			Acompanhe suas transações através do seu <a href='http://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
		}
		
		if($tipo == 'despublicado'){
			$MAASSUNTO  = "Anúncio despublicado";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Seu anúncio <b>$IDANUNCIO</b> acaba de ser despublicado da Galeria <b>$CODPRODUTO</b>.<br>
			Isso ocorreu automaticamente devido a cancelamento do pagamento do respectivo crédito.<br>
			Qualquer dúvida entre em contato com nossa Central de Atendimento.";
		}
		
		include 'mailer-simples.php';
	
		}*/
		
	}
		
	if(isset($_GET['action'])){
	
		$acao = $_GET['action'];
		
		if($acao == 'excluir'){
			
			$IDTRANSACAO = $_GET['tid'];
			
			$Tdelete = mysql_query("DELETE FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO' ") or print (mysql_error());
		
		}
		
		if($acao == 'success'){
			
			$IDTRANSACAO = strtoupper($_GET['tid']);
			
			include '../LBtimezone.php';
	    include '../connect/LBconecta.php';
			
			@session_start();
			
			$id_session = $_SESSION['id_session'];
			
			$USERselect = mysql_query("SELECT USERNOME,USERLOGIN FROM usuarios WHERE USERIDENTIFICACAO = (SELECT SAIDUSUARIO FROM sessoes WHERE SAIDSESSION = '$id_session')") 
			or print "<div class='error'><b>FALHA NA OBTENÇÃO DOS DADOS DO USUÁRIO LOGADO.<br>ERRO DE BANCO DE DADOS: </b>".(mysql_error())."</div>";			 
			
			$MANOMEDESTINATARIO  = mysql_result($USERselect,0,"USERNOME");
			$MAEMAILDESTINATARIO = mysql_result($USERselect,0,"USERLOGIN");
			
			$UserPrimeiroNome = explode(" ", $MANOMEDESTINATARIO);
			$USERFIRSTNAME    = $UserPrimeiroNome[0];
			
			$MAASSUNTO  = "RECEBEMOS SEU PEDIDO";
			$MACONTEUDO = "Obrigado(a) $USERFIRSTNAME!<br><br>
			<b>RECEBEMOS SEU PEDIDO.</b> Código da Transação: <b>$IDTRANSACAO</b><br><br>
			<p align='justify'>O acesso aos recursos do app será liberado automaticamente para seu usuário assim que o pagamento for confirmado pela Instituição Financeira, o que geralmente ocorre em até 24 horas. Você será notificado por e-mail, e também pode consultar através do site, sobre o status dessa transação.</p>";
			
			include '../mailer-simples.php';
			
			echo "
			
			<!DOCTYPE html>

			<html lang='pt-BR'>
				<head>
				
					<title>LEGALBOOK | Pedido recebido!</title>    
					<meta charset='UTF-8'/>
					<meta name='viewport'    content='width=device-width, initial-scale=1'/>
					<meta name='description' content='LEGALBOOK Brasil'/>
					<meta name='author'      content='IMPERIALIZE Tecnologia da Informação LTDA'/>
					<meta name='theme-color' content='#002776'/>
					
					<link href='https://legalbook.com.br/assets/images/favicon.png' rel='icon' type='image/png'/>
					
					<link rel='stylesheet' href='LBcss/fonts/fontsgoogle.css'/>
					<link rel='stylesheet' href='LBcss/bootstrap.min.css'/>
					<link rel='stylesheet' href='LBcss/font-awesome/css/fontawesome.min.css'/>
					<link rel='stylesheet' href='LBcss/font-awesome/css/fontawesome-all.min.css'/>
					<link rel='stylesheet' href='LBcss/custom.css'/>
			
				</head>			
		
				<body>
					<div class='container'>
						<nav class='navbar navbar-light bg-light'>
							<a class='navbar-brand brand-oficial' href='https://legalbook.com.br/app'>
								<img src='https://legalbook.com.br/assets/images/logo-icone.png' width='30' height='30' class='d-inline-block align-top' alt='LB' /> LEGALBOOK <small><i>store</i></small>
							</a>
						</nav>
						
						<div class='alert alert-success alert-dismissable'>
							$MACONTEUDO
						</div>
						<br><div align='right'><a href='https://legalbook.com.br/app' class='btn btn-outline-success btn-lg'>OK!</a></div>					
					</div>
				</body>
			</html>";
			
		}
		
	}
	
	?>
	
	<br><br>
	<div class='alert alert-info' align='center'><strong>LEGALBOOK store - Retorno PagSeguro</strong></div>
	
</html>