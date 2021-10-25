
  <?php 
	
		include 'header.php'; 
		
		$SOMETHOD  = NULL;
		$SOGIFTBOX = 0;
		$SOVALUEGB = 0;
		
		if(isset($_POST['action'])){
			
			$action = $_POST['action'];
			
			if($action == 'PAGAR'){
				
				$SOCODE       = $_POST['so'];
				$SOMETHOD     = $_POST['somethod'];
				$PACVALOR     = $_POST['pacvalor'];    
				$SEDEXVALOR   = $_POST['sedexvalor'];  
				$SEDEX10VALOR = $_POST['sedex10valor'];
				$PACPRAZO     = $_POST['pacprazo'];    
				$SEDEXPRAZO   = $_POST['sedexprazo'];  
				$SEDEX10PRAZO = $_POST['sedex10prazo'];				
				
				if(isset($_POST['caixa-presente'])){
					
					$SOGIFTBOX = 1;
					$SOVALUEGB = '15.90';
					
				}
				
				switch($SOMETHOD){
					
					case 'PAC':      $SOVALUESHIPPING = $PACVALOR;     $SODEADLINE = $PACPRAZO;     break;
					case 'SEDEX':    $SOVALUESHIPPING = $SEDEXVALOR;   $SODEADLINE = $SEDEXPRAZO;   break;
					case 'SEDEX10':  $SOVALUESHIPPING = $SEDEX10VALOR; $SODEADLINE = $SEDEX10PRAZO; break;
					case 'LOGGI':    $SOVALUESHIPPING = "15"; $SODEADLINE = 2; break;
					case 'CLRETIRA': $SOVALUESHIPPING = "0";  $SODEADLINE = 0; break;			
					
				}
	
				$DIASAMAIS = $PACPRAZO%7 +2;
				$PRAZO = $PACPRAZO + $DIASAMAIS;				
				$DTPREVISTA = date("Y-m-d", strtotime("+$PRAZO days"));
				
				$SODISCOUNT = 0;
				
				$SUBTOTALcart = mysqli_query($con,"SELECT SUM(CARTQTDEITEM*CARTVALORITEM) AS SUBTOTAL FROM carts WHERE CARTSESSION = '$cart_session'");
				
				while($STcol = mysqli_fetch_array($SUBTOTALcart)){
					
					$SUBTOTAL = $STcol["SUBTOTAL"];
					
				}
        
				//Valor Final da Transação
        
				$SOAMOUNT = ($SUBTOTAL + $SOVALUESHIPPING	+ $SOVALUEGB) - $SODISCOUNT;				
				
				
				$SOupdate = mysqli_query($con,"UPDATE sales_orders SET SOMETHOD = '$SOMETHOD',
				                                                      SOGIFTBOX = '$SOGIFTBOX',
																												SOVALUESHIPPING = '$SOVALUESHIPPING',
																														 SODEADLINE = $SODEADLINE,
																													SODTDELIVERYP = '$DTPREVISTA',
																														 SODISCOUNT = '$SODISCOUNT',
																														   SOAMOUNT = '$SOAMOUNT' WHERE SOCODE = '$SOCODE'");																															
				if($SOupdate){					
					
					//2. Gera o Transaction no PagSeguro...		
						
					$IDTRANSACAO = uniqid();
			
					$TDESCRICAO = "Ref. Pedido $SOCODE feito na Babadroom Store em $agora";					

					$SOselect = mysqli_query($con,"SELECT * FROM sales_orders WHERE SOCODE = '$SOCODE'") or print (mysqli_error($con));
					
					while($SOcol = mysqli_fetch_array($SOselect)){
						
						$PAGADORNOME   = $SOcol["CTMNOME"];
						$PAGADOREMAIL  = $USERSESSIONEMAIL;
						$PAGADORDDD    = substr($SOcol["SOTEL"],0,2);
						$PAGADORTEL    = substr($SOcol["SOTEL"],2,9);
						$PAGADORCEP    = $SOcol["SOZIPCODE"];
						$PAGADORLOG    = $SOcol["SOADDRESS"];
						$PAGADORNUMERO = $SOcol["SONUMBER"];
						$PAGADORCOMPL  = $SOcol["SOCOMPLEMENT"];
						$PAGADORBAIRRO = $SOcol["SODISTRICT"];
						$PAGADORCIDADE = $SOcol["SOCITY"];
						$PAGADORESTADO = $SOcol["SOSTATE"];
						
						$SOAMOUNT = $SOcol["SOAMOUNT"];					
						
					}
						
					$VALORFINAL = number_format($SOAMOUNT, 2, '.', '');						        

					####### Obtém o code para criar um transaction no PagSeguro ##################################################################
					
					$data['token']    = '6d9d14dc-a0d5-4b4b-9c12-1015ff329cce8e9fd4db4b3ea3aa67b0c5906acfee27b69f-977e-44a0-a669-61a1c3c34845';
					$data['email']    = 'pagseguro@babadroom.com';
					$data['currency'] = 'BRL';								
					
					$data['itemId1']          = "$IDTRANSACAO";
					$data['itemQuantity1']    = "1";
					$data['itemDescription1'] = "$TDESCRICAO";
					$data['itemAmount1']      = "$VALORFINAL";
					
					$data['shippingType'] = "1";			
					/*$data['shippingAddressRequired'] = "false";*/
					
					$data['senderName']       = "$PAGADORNOME";
					$data['senderEmail']      = "$PAGADOREMAIL";	
					$data['senderAreaCode']   = "$PAGADORDDD";
					$data['senderPhone']      = "$PAGADORTEL";					
					
					$data['shippingAddressPostalCode'] = "$PAGADORCEP";						
					$data['shippingAddressStreet']     = "$PAGADORLOG";
					$data['shippingAddressNumber']     = "$PAGADORNUMERO";
					$data['shippingAddressComplement'] = "$PAGADORCOMPL";
					$data['shippingAddressDistrict']   = "$PAGADORBAIRRO";
					$data['shippingAddressCity']       = "$PAGADORCIDADE";
					$data['shippingAddressState']      = "$PAGADORESTADO";
					$data['shippingAddressCountry']    = "BRA";

					$url = 'https://ws.pagseguro.uol.com.br/v2/checkout/';

					$data = http_build_query($data);

					$curl = curl_init($url);

					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

					$xml = curl_exec($curl);

					if($xml == 'Unauthorized'){
						
						$return = 'Não Autorizado';
						echo $return;
						exit;
						
					}

					curl_close($curl);

					$xml = simplexml_load_string($xml);

					if(count($xml -> error) > 0){
						
						$return = 'Dados Inválidos '.$xml ->error-> message;
						echo $return;
						exit;
						
					}

					$CODEPREVPAGSEG = $xml->code;					
					
					####### Cria a transação ###########################################################################################################################
					
					$Tinsert = mysqli_query($con,"INSERT INTO transacoes(IDTRANSACAO,IDUSUARIO,SOCODE,TDESCRICAO,CODEPREVPAGSEG,VALORORIGINAL,PAGADORNOME,PAGADOREMAIL,TIDSESSAO,TCADASTRO,TCADBY) 
					VALUES ('$IDTRANSACAO','$ident_session','$SOCODE','$TDESCRICAO','$CODEPREVPAGSEG','$VALORFINAL','$PAGADORNOME','$PAGADOREMAIL','$id_session','$datetime','$ident_session')") or print "<div class='error'><b>FALHA NO PROCESSAMENTO DO PEDIDO.<br>ERRO DESCONHECIDO: </b>".(mysqli_error($con))."</div>";

					/*if($Tinsert){						
						echo "<script>location.href='https://pagseguro.uol.com.br/v2/checkout/payment.html?code=$CODEPREVPAGSEG';</script>";
					}	*/		
					
				}
				
			}		
			
		}
	
	?>
 
	<!-- Page Title (Shop)-->
	<div class='page-title-overlap bg-dark pt-4'>
		<div class='container d-lg-flex justify-content-between py-2 py-lg-3'>
			
			<div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
				<h1 class='h3 text-light mb-0'>Checkout</h1>
			</div>
			
		</div>
	</div>
		
    <!-- Page Content-->
	<div class='container pb-5 mb-2 mb-md-4'>
		<div class='row'>
			<section class='col-lg-8'>
				
				<!-- Steps-->
				<div class='steps steps-light pt-2 pb-3 mb-5'>
				
					<a class='step-item active' href=''>
						<div class='step-progress'><span class='step-count'>1</span></div>
						<div class='step-label'><i class='fas fa-shopping-bag'></i>&nbsp;Cesta</div>
					</a>
					
					<a class='step-item active' href=''>
						<div class='step-progress'><span class='step-count'>2</span></div>
						<div class='step-label'><i class='fas fa-user'></i>&nbsp;Destino</div>
					</a>
					
					<a class='step-item active' href=''>
						<div class='step-progress'><span class='step-count'>3</span></div>
						<div class='step-label'><i class='fas fa-shipping-fast'></i>&nbsp;Remessa</div>
					</a>
					
					<a class='step-item active current' href=''>
						<div class='step-progress'><span class='step-count'>4</span></div>
						<div class='step-label'><i class='fas fa-money-check-alt'></i>&nbsp;Pagamento</div>
					</a>
					
					<a class='step-item' href=''>
						<div class='step-progress'><span class='step-count'>5</span></div>
						<div class='step-label'><i class='fas fa-check'></i>&nbsp;Confirmação</div>
					</a>
					
				</div>		
          
				<div class='d-flex justify-content-between align-items-center pt-3 pb-2 pb-sm-5 mt-1'>
					<h2 class='h6 text-light mb-0'>Conclusão do seu Pedido <b><?php echo $SOCODE;?></b></h2>
				</div>
					
				<?php

				$CARTselect = mysqli_query($con,"SELECT CARTID, PRDCOD, CARTQTDEITEM, CARTVALORITEM FROM carts WHERE CARTSESSION = '$cart_session' ORDER BY PRDCOD");							

				while($CARTcol = mysqli_fetch_array($CARTselect)){
					
					$CARTID        = $CARTcol["CARTID"];
					$PRDCOD        = $CARTcol["PRDCOD"];
					$CARTQTDEITEM  = $CARTcol["CARTQTDEITEM"];
					$CARTVALORITEM = $CARTcol["CARTVALORITEM"];
					
					$VALORTOTALITEM = $CARTQTDEITEM * $CARTVALORITEM;
						
					$ValorTotalItem = number_format($VALORTOTALITEM,2,",",".");
					
					$ITMselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD='$PRDCOD' ORDER BY PRDCOD");
					
					while($ITMcol = mysqli_fetch_array($ITMselect)){

						$GENCOD            = $ITMcol["GENCOD"];
						$SEGCOD            = $ITMcol["SEGCOD"];
						$DEPTCOD           = $ITMcol["DEPTCOD"];
						$CATCOD            = $ITMcol["CATCOD"];
						$GRPCOD            = $ITMcol["GRPCOD"];
						$BRDCOD            = $ITMcol["BRDCOD"];
						$COLORCOD          = $ITMcol["COLORCOD"];
						$SIZECOD           = $ITMcol["SIZECOD"];
						$PRDNAME           = $ITMcol["PRDNAME"];
						$PRDDESCRIPTION    = $ITMcol["PRDDESCRIPTION"];
						$PRDAPRESENTATION  = $ITMcol["PRDAPRESENTATION"];
						$PRDESPECIFICATION = $ITMcol["PRDESPECIFICATION"];										
						$PRDCOUNTPICTURES  = $ITMcol["PRDCOUNTPICTURES"];									
						
						if($PRDCOUNTPICTURES == 0){
							
							$FotoPrincipal = "<img class='img-fluid' src='../img/ProdutoSemFoto.png' alt='Produto Sem Foto'/>";
							
						}else{
						
							//Foto Principal
						
							$PXPselect = mysqli_query($con,"SELECT PXPNOMEARQUIVO FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA LIMIT 0,1");
							$PXPexiste = mysqli_num_rows($PXPselect);
							
							if($PXPexiste > 0){
								
								while($PXPcol = mysqli_fetch_array($PXPselect)){
									$PXPNOMEARQUIVO = $PXPcol["PXPNOMEARQUIVO"];
								}
								
								$FotoPrincipal = "<img class='img-fluid' src='../img/shop/catalog/$PXPNOMEARQUIVO' alt='Foto principal' />";
								
							}
							
						}	

						//$SUBTOTAL += $VALORTOTALITEM;									
						
					}
					
					echo "					
					<div class='d-sm-flex justify-content-between my-4 pb-3 border-bottom'>							
						<div class='media d-block d-sm-flex text-center text-sm-left'>
							<a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 7rem;'>$FotoPrincipal</a>
							<div class='media-body pt-2'>
								<h3 class='product-title font-size-base mb-2'>$PRDNAME</h3>
								<div class='font-size-sm'><span class='text-muted mr-2'>Tamanho: </span>$SIZECOD</div>
								<div class='font-size-sm'><span class='text-muted mr-2'>Cor: </span>$COLORCOD</div>
								<div class='font-size-sm'><span class='text-muted mr-2'>Quantidade: </span>$CARTQTDEITEM</div>
							</div>
						</div>									
						<div class='pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left' style='max-width: 9rem;'>									
							<div class='form-group mb-0'>
								<div class='font-size-lg text-accent pt-2'><small>R$</small>$ValorTotalItem</div>
							</div>										
						</div>										
					</div>";								     
					
				}

				if($SOGIFTBOX == 1){
					
					echo 
					"<div class='d-sm-flex justify-content-between my-4 pb-3 border-bottom'>							
						<div class='media d-block d-sm-flex text-center text-sm-left'>
							<a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 7rem;'><img class='img-fluid' src='../img/caixa-presente.png'/></a>
							<div class='media-body pt-2'>
								<h3 class='product-title font-size-base mb-2'>Caixa Presente Especial</h3>	
                <div class='font-size-sm'><span class='text-muted mr-2'>A mamãe vai se surpreender.</div>								
							</div>
						</div>									
						<div class='pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left' style='max-width: 9rem;'>									
							<div class='form-group mb-0'>
								<div class='font-size-lg text-accent pt-2'>+ <small>R$</small>15,90</div>
							</div>										
						</div>										
					</div>";
					
				}	

				echo 
				"<div class='d-sm-flex justify-content-between my-4 pb-3 border-bottom'>							
					<div class='media d-block d-sm-flex text-center text-sm-left'>
						<a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 7rem;'><img class='img-fluid' src='../img/frete-vetor.png'/></a>
						<div class='media-body pt-2'>
							<h3 class='product-title font-size-base mb-2'>Frete na Modalidade <b>$SOMETHOD</b></h3>
              <div class='font-size-sm'><span class='text-muted mr-2'>Vai chegar rapidinho!</div>							
						</div>
					</div>								
					<div class='pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left' style='max-width: 9rem;'>								
						<div class='form-group mb-0'>
							<div class='font-size-lg text-accent pt-2'>+ <small>R$</small>$SOVALUESHIPPING</div>
						</div>									
					</div>										
				</div>";								

				?>
					
			</section>
				
			<!-- Sidebar-->
			<aside class='col-lg-4 pt-4 pt-lg-0'>
			
				<div class='cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto'>
				
					<div class='text-center mb-4 pb-3 border-bottom'>
						<h2 class='h6 mb-3 pb-1'>Total da Compra</h2>
						<h3 class='font-weight-normal'><small>R$</small><?php echo $SOAMOUNT; ?></h3>
					</div>
					
					<div class='accordion' id='order-options'>
						
						<div class='card'>
							
							<a class='btn btn-primary btn-block' href='https://pagseguro.uol.com.br/v2/checkout/payment.html?code=<?php echo $CODEPREVPAGSEG;?>'><span class='d-none d-sm-inline'>Pagar com PagSeguro</span>
								<span class='d-inline d-sm-none'>Next</span><i class='fa fa-arrow-right mt-sm-0 ml-1'></i>
							</a>
							
						</div>
						
					</div>					
					
				</div>

			</aside>
				
		</div>
			
  </div>
		
	<?php include 'footer.php'; ?>
    
  </body>
</html>