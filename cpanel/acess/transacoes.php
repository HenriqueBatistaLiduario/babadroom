<?php include 'head.php'; ?>

<!DOCTYPE html>
<html>
  <head>
	  <title>Transações</title>
		<meta name='viewport'    content='width=device-width,height=device-height,initial-scale=0.9'>
    <meta name='description' content=''>
    <meta name='author'      content=''>
    <meta name='robots'      content='noindex, nofollow'>
		<meta charset='utf-8'
	</head>
	
	<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
	
	<script>

		function confirma_excluir(){
			if(confirm('ATENÇÃO: CONFIRMA A EXCLUSÃO DESTE PEDIDO? Operação irreversível...')){
				return true;
			
			}else{
				return false;
			}
		}
		
	</script>

	<?php 

	$publicar_anuncio = false;
	$Talert = NULL;

	if(isset($_POST['id_transacao'])){ //Sempre que o MOIP postar uma atualização...
		
		$IDTRANSACAO  = $_POST['id_transacao'];
		$CODMOIP      = $_POST['cod_moip'];
		$STATUSMOIP   = $_POST['status_pagamento'];
		$VALORPAGO    = $_POST['valor'];
		$FORMAPAGMOIP = $_POST['forma_pagamento'];
		$TIPOPAGMOIP  = $_POST['tipo_pagamento'];
		
		$Tupdate = mysql_query("UPDATE transacoes SET CODMOIP='$CODMOIP',
																									STATUSMOIP=$STATUSMOIP,  
																									VALORPAGO='$VALORPAGO',
																									FORMAPAGMOIP=$FORMAPAGMOIP, 
																									TIPOPAGMOIP='$TIPOPAGMOIP', 
																									TULTALTERACAO='$datetime' WHERE IDTRANSACAO='$IDTRANSACAO' ") or print (mysql_error());
		if($Tupdate){
																		 
			if($STATUSMOIP == 1){ //Sempre que for AUTORIZADO um Pagamento via MOIP ...
			
				$publicar_anuncio = true;
				
			}
		}					
	}

	####### POST RECEBIDO VIA PAGSEGURO ######################################################################################################################

	if(isset($_POST['notificationType']) AND $_POST['notificationType'] == 'transaction'){

		$notificationCode = $_POST['notificationCode'];
		$email = "henrique.liduario@hotmail.com";
		$token = "2C530CCC0CC5433EACEE61667E71A902";
		
		$url = "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/$notificationCode?email=$email&token=$token";

		$curl = curl_init($url);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$http = curl_getinfo($curl);

		if($response == 'Unauthorized'){
			print_r($response);
			exit;
		}
		
		curl_close($curl);
		
		$response = simplexml_load_string($response);

		if(count($response -> error) > 0){
			print_r($response);
			exit;
		}
		
		$IDTRANSACAO = $response->items->item->id;
		$CODPAGSEG = $response->code;
		$STATUSPAGSEG = $response->status;
		$VALORPAGO = $response->items->item->amount;
		$FORMAPAGPAGSEG = $response->paymentMethod->type;
		$TIPOPAGPAGSEG = $response->paymentMethod->code;
		$VALORLIQUIDO = $response->netAmount;
		
		$CLIENTEselect = mysql_query("SELECT IDUSUARIO, IDANUNCIO, CODPRODUTO, PRODUTO, DESCRICAO FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO'") or print (mysql_error());
		$IDUSUARIO  = mysql_result($CLIENTEselect,0,"IDUSUARIO");
		$PRODUTO    = mysql_result($CLIENTEselect,0,"PRODUTO");
		$DESCRICAO  = mysql_result($CLIENTEselect,0,"DESCRICAO");
		$CODPRODUTO = mysql_result($CLIENTEselect,0,"CODPRODUTO");
		$IDANUNCIO  = mysql_result($CLIENTEselect,0,"IDANUNCIO");
		
		$ANselect = mysql_query("SELECT * FROM anuncios WHERE IDANUNCIO = '$IDANUNCIO'") or print(mysql_error());
		$ANDTEXPIRA = mysql_result($ANselect,0,"ANDTEXPIRACAO");
	
		switch($CODPRODUTO){
			case 'UP':      $VALOREXTRATO = 3.50;  $ANPRIORIDADE = 4; $ANDTEXPIRACAO = date('Y-m-d H:i:s', strtotime('+30 days', strtotime($datetime))); break;
			case 'CLASS':   $VALOREXTRATO = 12.50; $ANPRIORIDADE = 3; $ANDTEXPIRACAO = date('Y-m-d H:i:s', strtotime('+62 days', strtotime($datetime))); break;
			case 'TOP':     $VALOREXTRATO = 25.00; $ANPRIORIDADE = 2; $ANDTEXPIRACAO = date('Y-m-d H:i:s', strtotime('+8 days', strtotime($datetime))); break;
			case 'PREMIUM': $VALOREXTRATO = 37.50; $ANPRIORIDADE = 1; $ANDTEXPIRACAO = date('Y-m-d H:i:s', strtotime('+8 days', strtotime($datetime))); break;
		}
		
		switch($TIPOPAGPAGSEG){
			case '101': $TIPOPAG = 'Cartão de crédito Visa'; break;
			case '102': $TIPOPAG = 'Cartão de crédito MasterCard'; break;
			case '103': $TIPOPAG = 'Cartão de crédito American Express'; break;
			case '104': $TIPOPAG = 'Cartão de crédito Diners'; break;
			case '105': $TIPOPAG = 'Cartão de crédito Hipercard'; break;
			case '106': $TIPOPAG = 'Cartão de crédito Aura'; break;
			case '107': $TIPOPAG = 'Cartão de crédito Elo'; break;
			case '108': $TIPOPAG = 'Cartão de crédito PLENOCard'; break;
			case '109': $TIPOPAG = 'Cartão de crédito PersonalCard'; break;
			case '110': $TIPOPAG = 'Cartão de crédito JCB'; break;
			case '111': $TIPOPAG = 'Cartão de crédito Discover'; break;
			case '112': $TIPOPAG = 'Cartão de crédito BrasilCard'; break;
			case '113': $TIPOPAG = 'Cartão de crédito FORTBRASIL'; break;
			case '114': $TIPOPAG = 'Cartão de crédito CARDBAN'; break;
			case '115': $TIPOPAG = 'Cartão de crédito VALECARD'; break;
			case '116': $TIPOPAG = 'Cartão de crédito Cabal'; break;
			case '117': $TIPOPAG = 'Cartão de crédito Mais!'; break;
			case '118': $TIPOPAG = 'Cartão de crédito Avista'; break;
			case '119': $TIPOPAG = 'Cartão de crédito GRANDCARD'; break;
			case '120': $TIPOPAG = 'Cartão de crédito Sorocred'; break;
			case '201': $TIPOPAG = 'Boleto Bradesco'; break;
			case '202': $TIPOPAG = 'Boleto Santander'; break;
			case '301': $TIPOPAG = 'Débito online Bradesco'; break;
			case '302': $TIPOPAG = 'Débito online Itaú'; break;
			case '303': $TIPOPAG = 'Débito online Unibanco'; break;
			case '304': $TIPOPAG = 'Débito online Banco do Brasil'; break;
			case '305': $TIPOPAG = 'Débito online Banco Real'; break;
			case '306': $TIPOPAG = 'Débito online Banrisul'; break;
			case '307': $TIPOPAG = 'Débito online HSBC'; break;
			case '401': $TIPOPAG = 'Saldo PagSeguro'; break;
			case '501': $TIPOPAG = 'Oi Paggo'; break;
			case '701': $TIPOPAG = 'Depósito em conta - Banco do Brasil'; break;
			case '702': $TIPOPAG = 'Depósito em conta - HSBC'; break;
		}
					
		//Atualiza o Status no Banco...
		
		$Tupdate = mysql_query("UPDATE transacoes SET CODPAGSEG = '$CODPAGSEG', 
																									STATUSPAGSEG = $STATUSPAGSEG,
																									VALORPAGO = '$VALORPAGO',
																									FORMAPAGPAGSEG = $FORMAPAGPAGSEG, 
																									TIPOPAGPAGSEG = '$TIPOPAGPAGSEG', 
																									VALORLIQUIDO = '$VALORLIQUIDO',
																									TULTALTERACAO = '$datetime' WHERE IDTRANSACAO = '$IDTRANSACAO' ") or print (mysql_error());
		if($Tupdate){
																		 
			if($STATUSPAGSEG == 3){ //Sempre que for PAGO via PagSeguro ...
			  mysql_query("INSERT INTO extratos (IDUSUARIO,IDTRANSACAO,EXTTIPO,EXTDETALHES,EXTVALOR,EXTDATA,EXTCADBY) VALUES 
				('$IDUSUARIO','$IDTRANSACAO','C','$DESCRICAO','$VALOREXTRATO','$datetime','robot1')") or print (mysql_error());
				
			  $comunicar_pagamento = true;
				$tipo = 'PagamentoPagSeguro';
				$publicar_anuncio = true;
			}
			
			if($STATUSPAGSEG > 4){
				$comunicar_pagamento = true;
				$tipo = 'EstornoPagSeguro';
				$despublicar_anuncio = true;
			}
		}																				
	}
		
	if($publicar_anuncio == true){
	
		if($IDANUNCIO != NULL){
			
			//Publica automaticamente o anúncio conforme Plano comprado...
			
			if($CODPRODUTO == 'UP'){
				$ANupdate = mysql_query("UPDATE anuncios SET ANSTATUS = 1, ANDTSTATUS = '$datetime', ANUSRSTATUS = 'robot1', ANDTULTUP = '$datetime', ANPRIORIDADE = $ANPRIORIDADE, ANDTEXPIRACAO = '$ANDTEXPIRACAO' WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());
			
			}else{
				$ANupdate = mysql_query("UPDATE anuncios SET ANSTATUS = 1, ANDTSTATUS = '$datetime', ANUSRSTATUS = 'robot1', ANPLANO = '$CODPRODUTO', ANDTULTALTPLANO = '$datetime', ANPRIORIDADE = $ANPRIORIDADE, ANDTEXPIRACAO = '$ANDTEXPIRACAO' WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());					
				
				mysql_query("INSERT INTO extratos (IDUSUARIO,IDTRANSACAO,EXTTIPO,EXTDETALHES,EXTVALOR,EXTDATA,EXTCADBY) VALUES 
				('$IDUSUARIO','$IDTRANSACAO','D','Publicação do Anúncio $IDANUNCIO no Plano $CODPRODUTO','$VALOREXTRATO','$datetime','robot1')") or print (mysql_error());
			}
			
			if($ANupdate){
				$comunicar_atividade = true;
				$tipo = 'publicado';
			}
		}
	}	
	
	if($despublicar_anuncio == true){
	
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
			Acompanhe suas transações através do seu <a href='https://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
			
		}
		
		if($tipo == 'EstornoPagSeguro'){
			$MAASSUNTO = "Estorno de Pagamento via PagSeguro";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Fomos notificados de que um pagamento que você tinha realizado através do PagSeguro foi estornado.<br>
			Com isso seus anúncios, caso publicados, serão despublicados automaticamente.<br>
			Estamos a disposição para maiores esclarecimentos sobre nossos Termos de Uso.<br>
			Acompanhe suas transações através do seu <a href='https://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
			
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
			Acompanhe suas transações através do seu <a href='https://petslittle.com.br/transacoes.php'>Painel de Usuário</a> ou aplicativo Mobile.";
			
		}
		
		if($tipo == 'despublicado'){
			
			$MAASSUNTO  = "Anúncio despublicado";
			$MACONTEUDO = "Olá $MANOMEDESTINATARIO,<br><br>
			Seu anúncio <b>$IDANUNCIO</b> acaba de ser despublicado da Galeria <b>$CODPRODUTO</b>.<br>
			Isso ocorreu automaticamente devido a cancelamento do pagamento do respectivo crédito.<br>
			Qualquer dúvida entre em contato com nossa Central de Atendimento.";
			
		}
		
		include 'mailer-simples.php';
	
	}
	
	if(isset($_GET['action'])){
	
		$acao = $_GET['action'];
		
		if($acao == 'excluir'){
			$IDTRANSACAO = $_GET['tid'];
			
			$Tdelete = mysql_query("DELETE FROM transacoes WHERE IDTRANSACAO = '$IDTRANSACAO'") or print (mysql_error());
		
		}
		
		if($acao == 'success'){
			
			$IDTRANSACAO = $_GET['tid'];
			
			$Talert = 
			"<div class='alert alert-success alert-dismissable'>
				<a class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<strong>SUCESSO! Seu pedido <b>$IDTRANSACAO</b> foi concluído.</strong><br>
				Seu anúncio será publicado automaticamente assim que o pagamento for confirmado pela Instituição Financeira.<br>
				Você será notificado por e-mail sobre o andamento deste pedido, e também pode acompanhar o status de suas transações através do Seu Painel ou aplicativo Mobile.<br>
			</div>";
			
		}
	}
	
	
?>

	<body>
	  <div id='bg-desktop' style='background-image: url(assets/images/other_images/fundo_carrinho.jpg);'></div>
		<div id='bg-mobile'  style='background-image: url(assets/images/other_images/fundo_carrinhoM.jpg);'></div>
    <div id='outer-container'>
			<?php include 'header.php';?>
			
			<section id='main-content' class='clearfix'>
				<article id='transacoes' class='section-wrapper clearfix'>
					<div class='content-wrapper clearfix'>
            <div class='col-md-12 col-sm-12 pull-right'>
							<section class='feature-columns row clearfix'>
								<h1 class='section-title'><i class='fa fa-shopping-cart' aria-hidden='true'></i>&nbsp;Transações</h1>
								<h6>Este é um histórico das transações financeiras que você já realizou.</h6>
							  <p style='font-size:14px;'>Os anúncios são promovidos automaticamente assim que acusamos o pagamento.</p>
							
								<?php
								
								$Tselect = mysql_query("SELECT * FROM transacoes WHERE IDUSUARIO = '$ident_session' AND (STATUSPAGSEG > 0 OR STATUSMOIP > 0) ORDER BY TCADASTRO DESC") or print (mysql_error());
								$Ttotal  = mysql_num_rows($Tselect);
								
								if($Ttotal > 0){
									
									echo"
									<table class='table-action table table-responsive table-bordered table-hover' style='border-right: none; border-left:none;'>
										<thead>
											<tr>
												<th width='2%' ><div align='center'>           </div></th>
												<th width='4%' ><div align='center'>           </div></th>
												<th width='15%'><div align='center'>ID         </div></th>
												<th width='34%'><div>Produto                   </div></th>
												<th width='15%'><div align='center'>Valor      </div></th>
												<th width='15%'><div align='center'>Forma Pg.  </div></th>
												<th width='15%'><div align='center'>Data Status</div></th>
											</tr>
										</thead>
										<tbody style='background-color: transparent;'>";

									while($linha = mysql_fetch_array($Tselect)){
										$TID               = $linha["TID"];
										$IDCARTEIRAMOIP    = $linha["IDCARTEIRAMOIP"];
										$IDANUNCIO         = $linha["IDANUNCIO"];
										$IDTRANSACAO       = $linha["IDTRANSACAO"];
										$IDUSUARIO         = $linha["IDUSUARIO"];
										$PRODUTO           = $linha["PRODUTO"];
										$DESCRICAO         = $linha["DESCRICAO"];
										$VALORORIGINAL     = $linha["VALORORIGINAL"];
										$CODEPREVPAGSEG    = $linha["CODEPREVPAGSEG"];
										$CODPAGSEG	       = $linha["CODPAGSEG"];
										$STATUSPAGSEG      = $linha["STATUSPAGSEG"];
										$FORMAPAGPAGSEG    = $linha["FORMAPAGPAGSEG"];
										$TIPOPAGPAGSEG     = $linha["TIPOPAGPAGSEG"];
										$CODMOIP           = $linha["CODMOIP"];
										$STATUSMOIP        = $linha["STATUSMOIP"];
										$FORMAPAGMOIP      = $linha["FORMAPAGMOIP"];
										$TIPOPAGMOIP       = $linha["TIPOPAGMOIP"];
										$VALORPAGO         = $linha["VALORPAGO"];
										$TCADASTRO         = $linha["TCADASTRO"];
										$TCADBY            = $linha["TCADBY"];
										$TULTALTERACAO     = $linha["TULTALTERACAO"];
										$PAGADORNOME       = $linha["PAGADORNOME"];
										$PAGADOREMAIL      = $linha["PAGADOREMAIL"];
										$PAGADOREMAILMOIP  = $linha["PAGADOREMAILMOIP"];
										$PAGADORDDD        = $linha["PAGADORDDD"];
										$PAGADORTELEFONE   = $linha["PAGADORTELEFONE"];
										$PAGADORCEP        = $linha["PAGADORCEP"];
										$PAGADORLOGRADOURO = $linha["PAGADORLOGRADOURO"];
										$PAGADORNUMERO     = $linha["PAGADORNUMERO"];
										$PAGADORCOMPL      = $linha["PAGADORCOMPL"];
										$PAGADORBAIRRO     = $linha["PAGADORBAIRRO"];
										$PAGADORCIDADE     = $linha["PAGADORCIDADE"];
										$PAGADORESTADO     = $linha["PAGADORESTADO"];
										$PAGADORPAIS       = $linha["PAGADORPAIS"];
										
										$dtstatus = date("d/m/Y H:i", strtotime($TULTALTERACAO));
										
										$valor = 'R$ '.number_format($VALORORIGINAL,2,',','.');
										
										$situacao = "<img class='imgicones' src='assets/images/status/AZUL.png'    title='NÃO INICIADO\nTransação registrada, mas o pagamento ainda não foi iniciado.'/>";
										
										if($CODMOIP != NULL){
										
											switch($STATUSMOIP){
												case 0: $situacao = "<img class='imgicones' src='assets/images/status/AZUL.png'    title='NÃO INICIADO\nTransação registrada, mas o pagamento ainda não foi iniciado.'/>"; break;
												case 1: $situacao = "<img class='imgicones' src='assets/images/status/VERDE.png'   title='AUTORIZADO\nPagamento já foi realizado porém ainda não foi creditado na Carteira MoIP recebedora\n(devido ao floating da forma de pagamento)'/>"; break;
												case 2: $situacao = "<img class='imgicones' src='assets/images/status/AMARELO.png' title='INICIADO\nPagamento está sendo realizado ou janela do navegador foi fechada (pagamento abandonado)'/>"; break;
												case 3: $situacao = "<img class='imgicones' src='assets/images/status/BRANCO.png'  title='BOLETO EMITIDO\nBoleto foi impresso e ainda não foi pago'/>"; break;
												case 4: $situacao = "<img class='imgicones' src='assets/images/status/VERDE.png'   title='CONCLUÍDO\nPagamento já foi realizado e dinheiro já foi creditado na Carteira MoIP recebedora'/>"; break;
												case 5: $situacao = "<img class='imgicones' src='assets/images/status/PRETO.png'   title='CANCELADO\nPagamento foi cancelado pelo pagador, instituição de pagamento, MoIP ou recebedor antes de ser concluído'/>"; break;
												case 6: $situacao = "<img class='imgicones' src='assets/images/status/AMARELO.png' title='EM ANÁLISE\nPagamento foi realizado com cartão de crédito e autorizado, porém está em análise pela Equipe MoIP. Não existe garantia de que será concluído'/>"; break;
												case 7: $situacao = "<img class='imgicones' src='assets/images/status/ROXO.png'    title='ESTORNADO\nPagamento foi estornado pelo pagador, recebedor, instituição de pagamento ou MoIP'/>"; break;
											}
											
											if($FORMAPAGMOIP != NULL){
											
												switch($FORMAPAGMOIP){
													case 1: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
													case 2: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
													case 3: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
													case 4: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
													case 5: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
													case 7: $FORMAPAG = 'FP'; $FORMAPAGtitle = 'FP'; break;
												}
											
											}else{
												$FORMAPAGMOIP = NULL;
												$FORMAPAGtitle = NULL;
											}
											
											if($TIPOPAGMOIP != NULL){
												
												switch($TIPOPAGMOIP){
													case 'DebitoBancario': $TIPOPAG = 'Débito Bancário'; break;
													case 'FinanciamentoBancario': $TIPOPAG = 'Financiamento'; break;
													case 'BoletoBancario': $TIPOPAG = 'Boleto'; break;
													case 'CartaoDeCredito': $TIPOPAG = 'Cartão de Crédito'; break;
													case 'CartaoDeDebito': $TIPOPAG = 'Cartão de Débito'; break;
													case 'CarteiraMoIP': $TIPOPAG = 'Carteira MOIP'; break;
												}
											
											}else{
												$TIPOPAG = NULL;
											}
										}
										
										if($CODPAGSEG != NULL){
										
											switch($STATUSPAGSEG){
												case 0: $situacao = "<img class='imgicones' src='assets/images/status/AZUL.png'    title='NÃO INICIADO\nTransação registrada, mas o pagamento ainda não foi iniciado.'/>"; break;
												case 1: $situacao = "<img class='imgicones' src='assets/images/status/AMARELO.png' title='AGUARDANDO PAGAMENTO\nO comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento.'/>"; break;
												case 2: $situacao = "<img class='imgicones' src='assets/images/status/AMARELO.png' title='EM ANÁLISE\nO comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação.'/>"; break;
												case 3: $situacao = "<img class='imgicones' src='assets/images/status/VERDE.png'   title='PAGA\nA transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.'/>"; break;
												case 4: $situacao = "<img class='imgicones' src='assets/images/status/VERDE.png'   title='DISPONÍVEL\nA transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta.'/>"; break;
												case 5: $situacao = "<img class='imgicones' src='assets/images/status/BRANCO.png'  title='EM DISPUTA\nO comprador, dentro do prazo de liberação da transação, abriu uma disputa.'/>"; break;
												case 6: $situacao = "<img class='imgicones' src='assets/images/status/ROXO.png'    title='DEVOLVIDA\nO valor da transação foi devolvido para o comprador.'/>"; break;
												case 7: $situacao = "<img class='imgicones' src='assets/images/status/PRETO.png'   title='CANCELADA\nA transação foi cancelada sem ter sido finalizada.'/>"; break;
												case 8: $situacao = "<img class='imgicones' src='assets/images/status/ROXO.png'    title='DEBITADO\nO valor da transação foi devolvido para o comprador.'/>"; break;
												case 9: $situacao = "<img class='imgicones' src='assets/images/status/BRANCO.png'  title='RETENÇÃO TEMPORÁRIA\nO comprador contestou o pagamento junto à operadora do cartão de crédito ou abriu uma demanda judicial ou administrativa (Procon).'/>"; break;
											}
											
											if($FORMAPAGPAGSEG != NULL){
												switch($FORMAPAGPAGSEG){
													case 1: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador pagou pela transação com um cartão de crédito. Neste caso, o pagamento é processado imediatamente ou no máximo em algumas horas, dependendo da sua classificação de risco.'; break;
													case 2: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador optou por pagar com um boleto bancário. Ele terá que imprimir o boleto e pagá-lo na rede bancária. Este tipo de pagamento é confirmado em geral de um a dois dias após o pagamento do boleto. O prazo de vencimento do boleto é de 3 dias.'; break;
													case 3: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador optou por pagar com débito online de algum dos bancos com os quais o PagSeguro está integrado. O PagSeguro irá abrir uma nova janela com o Internet Banking do banco escolhido, onde o comprador irá efetuar o pagamento. Este tipo de pagamento é confirmado normalmente em algumas horas.'; break;
													case 4: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador possuía saldo suficiente na sua conta PagSeguro e pagou integralmente pela transação usando seu saldo.'; break;
													case 5: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador paga a transação através de seu celular Oi. A confirmação do pagamento acontece em até duas horas.'; break;
													case 7: $FORMAPAG = ''; $FORMAPAGtitle = 'O comprador optou por fazer um depósito na conta corrente do PagSeguro. Ele precisará ir até uma agência bancária, fazer o depósito, guardar o comprovante e retornar ao PagSeguro para informar os dados do pagamento. A transação será confirmada somente após a finalização deste processo, que pode levar de 2 a 13 dias úteis.'; break;
												}
											
											}else{
												$FORMAPAG = NULL;
												$FORMAPAGtitle = NULL;
											}
											
											if($TIPOPAGPAGSEG != NULL){
											
												switch($TIPOPAGPAGSEG){
													case '101': $TIPOPAG = 'Cartão de crédito Visa'; break;
													case '102': $TIPOPAG = 'Cartão de crédito MasterCard'; break;
													case '103': $TIPOPAG = 'Cartão de crédito American Express'; break;
													case '104': $TIPOPAG = 'Cartão de crédito Diners'; break;
													case '105': $TIPOPAG = 'Cartão de crédito Hipercard'; break;
													case '106': $TIPOPAG = 'Cartão de crédito Aura'; break;
													case '107': $TIPOPAG = 'Cartão de crédito Elo'; break;
													case '108': $TIPOPAG = 'Cartão de crédito PLENOCard'; break;
													case '109': $TIPOPAG = 'Cartão de crédito PersonalCard'; break;
													case '110': $TIPOPAG = 'Cartão de crédito JCB'; break;
													case '111': $TIPOPAG = 'Cartão de crédito Discover'; break;
													case '112': $TIPOPAG = 'Cartão de crédito BrasilCard'; break;
													case '113': $TIPOPAG = 'Cartão de crédito FORTBRASIL'; break;
													case '114': $TIPOPAG = 'Cartão de crédito CARDBAN'; break;
													case '115': $TIPOPAG = 'Cartão de crédito VALECARD'; break;
													case '116': $TIPOPAG = 'Cartão de crédito Cabal'; break;
													case '117': $TIPOPAG = 'Cartão de crédito Mais!'; break;
													case '118': $TIPOPAG = 'Cartão de crédito Avista'; break;
													case '119': $TIPOPAG = 'Cartão de crédito GRANDCARD'; break;
													case '120': $TIPOPAG = 'Cartão de crédito Sorocred'; break;
													case '201': $TIPOPAG = 'Boleto Bradesco'; break;
													case '202': $TIPOPAG = 'Boleto Santander'; break;
													case '301': $TIPOPAG = 'Débito online Bradesco'; break;
													case '302': $TIPOPAG = 'Débito online Itaú'; break;
													case '303': $TIPOPAG = 'Débito online Unibanco'; break;
													case '304': $TIPOPAG = 'Débito online Banco do Brasil'; break;
													case '305': $TIPOPAG = 'Débito online Banco Real'; break;
													case '306': $TIPOPAG = 'Débito online Banrisul'; break;
													case '307': $TIPOPAG = 'Débito online HSBC'; break;
													case '401': $TIPOPAG = 'Saldo PagSeguro'; break;
													case '501': $TIPOPAG = 'Oi Paggo'; break;
													case '701': $TIPOPAG = 'Depósito em conta - Banco do Brasil'; break;
													case '702': $TIPOPAG = 'Depósito em conta - HSBC'; break;
												}
												
											}else{
												$TIPOPAG = NULL;
											}
										}
										
										echo "
										
										<tr>
											<td><div align='center'>$situacao</div></td>
											<td>
												<div align='center'>";
												?>
												<a href='' onclick="populate_and_open_modal(event, 'modal-content-<?php echo $IDANUNCIO;?>', '', 'full-size');" title='Ver anúncio'><i class='fa fa-picture-o' aria-hidden='true'></i></a>
												<?php
												echo"
												</div>
											</td>
											<td><div align='center'>$IDTRANSACAO</div></td>
											<td><div>$PRODUTO<br><font style='font-size:10px;'>$DESCRICAO</font></div></td>
											<td><div align='center'>$valor</div></td>
											<td><div align='center'>$FORMAPAG $TIPOPAG</div></td>
											<td><div align='center'>$dtstatus</div></td>
										</tr>";
										
										$ANselect = mysql_query("SELECT * FROM anuncios WHERE IDANUNCIO = '$IDANUNCIO'") or print (mysql_error());
										$ANtotal  = mysql_num_rows($ANselect);
										
										echo "<div class='content-to-populate-in-modal' id='modal-content-$IDANUNCIO'>";
										
										if($ANtotal > 0){
											
											$ANID             = mysql_result($ANselect,0,'ANID');
											$IDANUNCIO        = mysql_result($ANselect,0,'IDANUNCIO');
											$ANCODANUNCIANTE  = mysql_result($ANselect,0,'ANCODANUNCIANTE');
											$CATCODIGO        = mysql_result($ANselect,0,'CATCODIGO');
											$GRUPOCODIGO      = mysql_result($ANselect,0,'GRUPOCODIGO');
											$ESPCODIGO        = mysql_result($ANselect,0,'ESPCODIGO');
											$SECODIGO         = mysql_result($ANselect,0,'SECODIGO');
											$ANCEP            = mysql_result($ANselect,0,'ANCEP');
											$ANCIDADE         = mysql_result($ANselect,0,'ANCIDADE');
											$ANUF             = mysql_result($ANselect,0,'ANUF');
											$ANQTDEDISPONIVEL = mysql_result($ANselect,0,'ANQTDEDISPONIVEL');
											$ANPESO           = mysql_result($ANselect,0,'ANPESO');
											$ANUMPESO         = mysql_result($ANselect,0,'ANUMPESO');
											$ANIDADE          = mysql_result($ANselect,0,'ANIDADE');
											$ANUMIDADE        = mysql_result($ANselect,0,'ANUMIDADE');
											$ANPRECOUNITARIO  = mysql_result($ANselect,0,'ANPRECOUNITARIO');
											$ANSEXO           = mysql_result($ANselect,0,'ANSEXO');
											$ANVACINADO       = mysql_result($ANselect,0,'ANVACINADO');
											$ANCASTRADO       = mysql_result($ANselect,0,'ANCASTRADO');
											$ANVERMIFUGADO    = mysql_result($ANselect,0,'ANVERMIFUGADO');
											$ANNEGINDIVIDUAL  = mysql_result($ANselect,0,'ANNEGINDIVIDUAL');
											$ANINFOCOMPL      = mysql_result($ANselect,0,'ANINFOCOMPL');
											$ANCADASTRO       = mysql_result($ANselect,0,'ANCADASTRO');
											$ANVISUALIZACOES  = mysql_result($ANselect,0,'ANVISUALIZACOES');
											$ANDTEXPIRACAO    = mysql_result($ANselect,0,'ANDTEXPIRACAO');
											$ANSTATUS         = mysql_result($ANselect,0,'ANSTATUS');
											$ANDTSTATUS       = mysql_result($ANselect,0,'ANDTSTATUS');
											$ANUSRSTATUS      = mysql_result($ANselect,0,'ANUSRSTATUS');
											$ANPLANO          = mysql_result($ANselect,0,'ANPLANO');
											
											if($ANQTDEDISPONIVEL == 0){ $disponibilidade = "<font color='red'>Indisponível</font>";}
											if($ANQTDEDISPONIVEL == 1){ $disponibilidade = "<font color='green'>Único disponível</font>";}
											if($ANQTDEDISPONIVEL >= 2){ $disponibilidade = "<font color='green'>$ANQTDEDISPONIVEL disponíveis</font>";}
											
											$CATselect    = mysql_query("SELECT * FROM categorias WHERE CATCODIGO = '$CATCODIGO'") or print (mysql_error());
											$CATDESCRICAO = mysql_result($CATselect,0,"CATDESCRICAO");
											$CATICONE     = mysql_result($CATselect,0,"CATICONE");
											
											$GRUPOselect    = mysql_query("SELECT * FROM grupos WHERE GRUPOCODIGO = '$GRUPOCODIGO'") or print (mysql_error());
											$GRUPODESCRICAO = mysql_result($GRUPOselect,0,"GRUPODESCRICAO");
											
											$ESPselect    = mysql_query("SELECT * FROM especies WHERE ESPCODIGO = '$ESPCODIGO'") or print (mysql_error());
											$ESPDESCRICAO = mysql_result($ESPselect,0,"ESPDESCRICAO");
											
											if($SECODIGO != NULL AND $SECODIGO != ''){
											
												$SEselect    = mysql_query("SELECT * FROM subespecies WHERE SECODIGO = '$SECODIGO'") or print (mysql_error());
												$SEDESCRICAO = mysql_result($SEselect,0,"SEDESCRICAO");
											
											}				
											
											switch($ANSEXO){
												case 'M':  $sexo = "<h6 title='Macho com sexagem'>Macho</h6>"; break;
												case 'F':  $sexo = "<h6 title='Fêmea com sexagem'>Fêmea</h6>"; break;
												case '+M': $sexo = "<h6 title='Grupo predominantemente Macho'>+ Machos</h6>"; break;
												case '+F': $sexo = "<h6 title='Grupo predominantemente Fêmea'>+ Fêmeas</h6>"; break;
												case 'PS': $sexo = "<h6 title='Sexo ainda não comprovado. Pendente sexagem'>Não comprovado</h6>"; break;
												case 'CS': $sexo = "<h6 title='Casal já formado. Não recomendamos separar casais, para garantir a felicidade de ambos.'>Casal formado</h6>"; break;
											}

											if($ANVACINADO == 1){ 
												$vacinado = "<i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;Vacinado";
										
											}else{
												$vacinado = "";
											}
											
											if($ANVERMIFUGADO == 1){ 
												$vermifugado = "<i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;Vermifugado";
											
											}else{
												$vermifugado = "";
											}
											
											if($ANCASTRADO == 1){ 
												$castrado = "<i class='fa fa-check-square-o' aria-hidden='true'></i>&nbsp;Castrado";
											
											}else{
												$castrado = "";
											}
											
											if($ANNEGINDIVIDUAL == 0){ 
												$negindividual = "<i class='fa fa-link' aria-hidden='true'></i>&nbsp;Não negocia individualmente";
											
											}else{
												$negindividual = "";
											}									
											
											$precounitario = number_format($ANPRECOUNITARIO,2,",",".");//Formatar o Valor.
											
											$ANPRECOTOTAL = $ANQTDEDISPONIVEL * $ANPRECOUNITARIO;
											$precototal   = number_format($ANPRECOTOTAL,2,",",".");//Formatar o Valor.
											
											$ANUNCIANTEselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$ANCODANUNCIANTE'") or print (mysql_error());
											$ANUNCIANTENOME     = mysql_result($ANUNCIANTEselect,0,"USERNOME");
											$ANUNCIANTETEL1     = mysql_result($ANUNCIANTEselect,0,"USERTEL1");
											$ANUNCIANTETEL2     = mysql_result($ANUNCIANTEselect,0,"USERTEL2");
											$ANUNCIANTEEMAIL    = mysql_result($ANUNCIANTEselect,0,"USERLOGIN");
											$ANUNCIANTEFOTO     = mysql_result($ANUNCIANTEselect,0,"USERFOTO");
											$ANUNCIANTECADASTRO = mysql_result($ANUNCIANTEselect,0,"USERCADASTRO");
											
											if($ANUNCIANTEFOTO == 1){
												$anunciante_iconeM = "<img class='img-responsive img-circle' style='width: 50px;  height: 50px;'  src='fotos/usuarios/$ANCODANUNCIANTE.jpg'/>";

											}else{
												$anunciante_iconeM = "<img class='img-responsive img-circle' style='width: 100px; height: 100px;' src='fotos/usuarios/SEMFOTO.png'/>";
											}
											
											$membersince = date("m/Y",strtotime($ANUNCIANTECADASTRO));

											$ANUNCIANTEREPUTACAO = 2;

											switch($ANUNCIANTEREPUTACAO){
												case 0: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 0'><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i></div>"; break;
												case 1: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 1'><i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i></div>"; break;
												case 2: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 2'><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i></div>"; break;
												case 3: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 3'><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i><i class='fa fa-star-o'></i></div>"; break;
												case 4: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 4'><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-o'></i></div>"; break;
												case 5: $estrelas = "<div title='REPUTAÇÃO do Anunciante: 5'><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i></div>"; break;
											}
											
											echo "
										
											<div class='row'>
												
												<div class='col-sm-6' align='left'>
													<h6>$IDANUNCIO</h6>	
												</div>
												
												<div class='col-sm-6' align='right'>
													<h5><font style='font-size:10px;'>R$</font><b>$precounitario</b> cada</h5>
													<h6>Preço total:   <font style='font-size:10px;'>R$</font><b>$precototal</b></h6>
												</div>
											</div>
											
											<p>$ANINFOCOMPL</p>
											
											<div id='unique-id-for-alt-image-slider' class='owl-carousel popup-alt-image-gallery'>";
											
												$FOTOSsubselect = mysql_query("SELECT * FROM fotos_anuncio WHERE IDANUNCIO = '$IDANUNCIO' ORDER BY FASEQUENCIA") or print (mysql_error());
												$FOTOSsubtotal  = mysql_num_rows($FOTOSsubselect);
												
												if($FOTOSsubtotal > 0){
												
													while($sublinha = mysql_fetch_array($FOTOSsubselect)){
														$FNOMEARQUIVO = $sublinha['FANOMEARQUIVO'];
														echo "<div class='item'><a href='fotos/anuncios/$FNOMEARQUIVO' data-lightbox='popup-alt-gallery'><img class='lazyOwl img-responsive' data-src='fotos/anuncios/$FNOMEARQUIVO' style='height: 300px; width: 300px;'></a></div>";
													}
												}else{
													echo "<div align='center'>Nenhuma imagem associada a este anúncio.</div>";
												}
												
												echo"								
											
											</div>
											<br>
											
											<div class='row'>
												<div class='col-sm-4 col-md-4' align='left'>
													<h6><img class='img-responsive' src='assets/images/icons/$CATICONE' width='40' height='40'/></h6>
													<h6>$ANBAIRRO - $ANCIDADE/$ANUF</h6>
													<h6>$GRUPODESCRICAO</h6>
													<h6>$ESPDESCRICAO</h6>
													$SEDESCRICAO
													$sexo
													<h6>$ANIDADE $ANUMIDADE - $ANPESO $ANUMPESO</h6>
													<p style='font-size:13px;'>$vacinado&nbsp;&nbsp;&nbsp;&nbsp;$castrado&nbsp;&nbsp;&nbsp;&nbsp;$vermifugado</p>
													<h6>$disponibilidade</h6>
													<p style='font-size:12px;'>$negindividual</p>
												</div>
													
												<div class='col-sm-4 col-md-4' align='center'>
													$anunciante_iconeM
													<h6>$ANUNCIANTENOME</h6>
													<h4>$estrelas</h4>
													<h6 style='font-size: 10px;'>Membro desde $membersince</h6>																
												</div>
											</div>";
										
										}else{
											echo "<div align='center' class='warning'>O anúncio <b>$IDANUNCIO</b> foi excluído!</div>";
										}
										
										echo "</div>";
				
									}
									
									echo "</tbody>
									</table>";
									
								}else{
									echo "<div class='info' align='center'>Nenhuma transação registrada.</div>";
								}
								
								$Tselect = mysql_query("SELECT * FROM transacoes WHERE TCADBY = '$ident_session' AND (STATUSPAGSEG = 0 OR STATUSMOIP = 0)") or print (mysql_error());
								$TTotalCarrinho = mysql_num_rows($Tselect);
								
								?>
								
								<div class='btn-group btn-group-sm'>
								  <a href='carrinho.php?action=history' class='btn btn-outline-inverse btn-sm'><i class='fa fa-shopping-cart' aria-hidden='true'></i>&nbsp;Carrinho&nbsp;<span class='badge'><?php echo $TTotalCarrinho;?></span></a>
									<a href='extrato.php' class='btn btn-outline-inverse btn-sm'><i class='fa fa-list' aria-hidden='true'></i>&nbsp;Extrato Detalhado</a>
								</div>
								
							</section>
            </div>
          </div>
        </article>
			</section>
			
			<?php 
								
			
			
			include 'footer.php'; 
			
			
			?>
			
		</div>		
	</body>
</html>