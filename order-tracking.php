  <?php include 'header.php'; 
		
			$SOselect = mysqli_query($con,"SELECT * FROM sales_orders WHERE CTMIDENTIFICADOR = '$ident_session' AND SOSTATUS > 0 ORDER BY SOCREATEDON DESC");
			$SOtotal  = mysqli_num_rows($SOselect);
			
			echo "
			<body>
				<div class='bg-dark py-4'>
					<div class='container d-lg-flex justify-content-between py-2 py-lg-3'>        
						<div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
							<h2 class='h3 text-light mb-0'>Seus Pedidos ($SOtotal)</h2>
						</div>
					</div>
				</div>";
				
			if($SOtotal > 0){
				
				while($SOcol = mysqli_fetch_array($SOselect)){
					
					$SOCODE          = $SOcol["SOCODE"];
					$CARTKEY         = $SOcol["CARTKEY"];
					$SOMETHOD        = $SOcol["SOMETHOD"];
					$SODEADLINE      = $SOcol["SODEADLINE"];
					$SODTDELIVERYP   = $SOcol["SODTDELIVERYP"];
					$SOCOMMENTS      = $SOcol["SOCOMMENTS"];
					$SOSTATUS        = $SOcol["SOSTATUS"]; //1-AGUARDANDO CONF. PAGAMENTO 2-PAGAMENTO CONFIRMADO 3-PEDIDO FATURADO 4-NOTA FISCAL EMITIDA 5-PREPARANDO O ENVIO 6-PEDIDO POSTADO 7-ENTREGA CONFIRMADA 90-PEDIDO CANCELADO
					$SODTSTATUS      = $SOcol["SODTSTATUS"];
					$SOAMOUNT        = $SOcol["SOAMOUNT"];
          $SOVALUESHIPPING = $SOcol["SOVALUESHIPPING"];					
					$SOGIFTBOX       = $SOcol["SOGIFTBOX"];
					
					$dtstatus   = date("d/m/Y H:i",strtotime($SODTSTATUS));
					$dtprevista = date("d/m/Y",strtotime($SODTDELIVERYP));
					
					$completed1 = NULL; $active1 = NULL;
					$completed2 = NULL; $active2 = NULL;
					$completed3 = NULL; $active3 = NULL;				
					
					switch($SOSTATUS){
						
						case 0:  $situacao = "CARRINHO ABANDONADO<br><small>desde $dtstatus</small>"; break;
						case 1:  $situacao = "AGUARDANDO CONF. PAGAMENTO<br><small>desde $dtstatus</small>"; break;
						case 2:  $situacao = "PAGAMENTO CONFIRMADO<br><small>em $dtstatus</small>"; break;
						case 3:  $situacao = "PEDIDO FATURADO<br><small>em $dtstatus</small>";       $completed1 = 'completed'; $active2 = 'active'; break;
						case 4:  $situacao = "NOTA FISCAL EMITIDA<br><small>em $dtstatus</small>";   $completed1 = 'completed'; $active2 = 'active'; break;
						case 5:  $situacao = "PREPARANDO O ENVIO<br><small>desde $dtstatus</small>"; $completed1 = 'completed'; $active2 = 'active'; break;
						case 6:  $situacao = "PEDIDO POSTADO<br><small>em $dtstatus</small>";        $completed1 = 'completed'; $completed2 = 'completed'; $active3 = 'active'; break;
						case 7:  $situacao = "ENTREGA CONFIRMADA<br><small>em $dtstatus</small>";    $completed1 = 'completed'; $completed2 = 'completed'; $completed3 = 'completed'; break;
						case 90: $situacao = "PEDIDO CANCELADO<br><small>em $dtstatus</small>"; break;						
						
					}				
					
					echo "
					
					<div class='container py-5 mb-2 mb-md-3'>
						<p class='h4 mb-0'>Pedido <span class='h4 font-weight-normal'>$SOCODE</span></p><br>
						
						<div class='row mb-4'>
							<div class='col-sm-4 mb-2'>
								<div class='bg-secondary p-4 text-center rounded-lg'><span class='font-weight-medium text-dark mr-2'>Postagem via:</span>$SOMETHOD</div>
							</div>
							<div class='col-sm-4 mb-2'>
								<div class='bg-secondary p-4 text-center rounded-lg'><span class='font-weight-medium text-dark mr-2'>Status:</span>$situacao</div>
							</div>
							<div class='col-sm-4 mb-2'>
								<div class='bg-secondary p-4 text-center rounded-lg'><span class='font-weight-medium text-dark mr-2'>Previsão de Entrega:</span>Até $dtprevista</div>
							</div>
						</div>
						
						<div class='card border-0 box-shadow-lg'>
							<div class='card-body pb-2'>
								<ul class='nav nav-tabs media-tabs nav-justified'>
								
									<li class='nav-item'>
										<div class='nav-link $completed1 $active1'>
											<div class='media align-items-center'>
												<div class='media-tab-media mr-4'><i class='czi-bag'></i></div>
												<div class='media-body'>
													<div class='media-tab-subtitle text-muted font-size-xs mb-1'>1</div>
													<h6 class='media-tab-title text-nowrap mb-0'>FATURADO</h6>
												</div>
											</div>
										</div>
									</li>
									
									<li class='nav-item'>
										<div class='nav-link $completed2 $active2'>
											<div class='media align-items-center'>
												<div class='media-tab-media mr-4'><i class='czi-star'></i></div>
												<div class='media-body'>
													<div class='media-tab-subtitle text-muted font-size-xs mb-1'>2</div>
													<h6 class='media-tab-title text-nowrap mb-0'>POSTADO</h6>
												</div>
											</div>
										</div>
									</li>
									
									<li class='nav-item'>
										<div class='nav-link $completed3 $active3'>
											<div class='media align-items-center'>
												<div class='media-tab-media mr-4'><i class='czi-package'></i></div>
												<div class='media-body'>
													<div class='media-tab-subtitle text-muted font-size-xs mb-1'>3</div>
													<h6 class='media-tab-title text-nowrap mb-0'>ENTREGUE</h6>
												</div>
											</div>
										</div>
									</li>
									
								</ul>
							</div>
						</div>
						
						<div class='d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-4'>
							<div class='custom-control custom-checkbox mt-2 mr-3'>
								<input class='custom-control-input' type='checkbox' id='notify-me' checked readonly>
								<label class='custom-control-label' for='notify-me'>Notifique-me por e-mail sobre quaisquer alterações no status deste pedido.</label>
							</div><a class='btn btn-primary btn-sm mt-2' href='#order-details$SOCODE' data-toggle='modal'>Ver detalhes deste Pedido</a>
						</div>
						<br>
						<hr></hr>
					</div>
					
					<div class='modal fade' id='order-details$SOCODE'>
						<div class='modal-dialog modal-lg modal-dialog-scrollable'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h5 class='modal-title'>Pedido $SOCODE</h5>
									<button class='close' type='button' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								</div>
								
								<div class='modal-body pb-0'>";
								
								  $SOCARTselect = mysqli_query($con,"SELECT * FROM carts WHERE CARTSESSION = '$CARTKEY'");
									
									$CARTSUBTOTAL = 0;
									
									while($ITEMcesta = mysqli_fetch_assoc($SOCARTselect)){

										$CARTID        = $ITEMcesta["CARTID"];
										$PRDCOD        = $ITEMcesta["PRDCOD"];
										$CARTQTDEITEM  = $ITEMcesta["CARTQTDEITEM"];
										$CARTVALORITEM = $ITEMcesta["CARTVALORITEM"];
										
										$VALORTOTALITEM = $CARTQTDEITEM * $CARTVALORITEM;
										
										$ValorItem = number_format($CARTVALORITEM,2,",",".");	
										$ValorTotalItem = number_format($VALORTOTALITEM,2,",",".");
										
										$ITMCESTAselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD='$PRDCOD' ORDER BY PRDCOD");
										
										while($ITMCESTAcol = mysqli_fetch_array($ITMCESTAselect)){
								
											$GENCOD            = $ITMCESTAcol["GENCOD"];
											$SEGCOD            = $ITMCESTAcol["SEGCOD"];
											$DEPTCOD           = $ITMCESTAcol["DEPTCOD"];
											$CATCOD            = $ITMCESTAcol["CATCOD"];
											$GRPCOD            = $ITMCESTAcol["GRPCOD"];
											$BRDCOD            = $ITMCESTAcol["BRDCOD"];
											$COLORCOD          = $ITMCESTAcol["COLORCOD"];
											$SIZECOD           = $ITMCESTAcol["SIZECOD"];
											$PRDNAME           = $ITMCESTAcol["PRDNAME"];
											$PRDDESCRIPTION    = $ITMCESTAcol["PRDDESCRIPTION"];
											$PRDAPRESENTATION  = $ITMCESTAcol["PRDAPRESENTATION"];
											$PRDESPECIFICATION = $ITMCESTAcol["PRDESPECIFICATION"];										
											$PRDCOUNTPICTURES  = $ITMCESTAcol["PRDCOUNTPICTURES"];									
											
											if($PRDCOUNTPICTURES == 0){
												
												$FotoPrincipal = "<img class='img-fluid' src='img/ProdutoSemFoto.png' alt='Produto Sem Foto'/>";
												
											}else{
											
												//Foto Principal
											
												$PXPselect = mysqli_query($con,"SELECT PXPNOMEARQUIVO FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA LIMIT 0,1");
												$PXPexiste = mysqli_num_rows($PXPselect);
												
												if($PXPexiste > 0){
													
													while($PXPcol = mysqli_fetch_array($PXPselect)){
														$PXPNOMEARQUIVO = $PXPcol["PXPNOMEARQUIVO"];
													}
													
													$FotoPrincipal = "<img class='img-fluid' src='img/shop/catalog/$PXPNOMEARQUIVO' alt='Foto principal' />";
													
												}
												
											}						
											
										}
										
										echo 	"												
										<div class='d-sm-flex justify-content-between mb-4 pb-3 pb-sm-2 border-bottom'>
											<div class='media d-block d-sm-flex text-center text-sm-left'><a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 10rem;'>$FotoPrincipal</a>
												<div class='media-body pt-2'>
													<h3 class='product-title font-size-base mb-2'><a href='#'>$PRDNAME</a></h3>
													<div class='font-size-sm'><span class='text-muted mr-2'>Tamanho:</span>$SIZECOD</div>
													<div class='font-size-sm'><span class='text-muted mr-2'>Cor:</span>$COLORCOD</div>
													<div class='font-size-sm'><span class='text-muted mr-2'>Vl.Unitário:</span><small>R$</small>$ValorItem</div>
												</div>
											</div>
											<div class='pt-2 pl-sm-3 mx-auto mx-sm-0 text-center'>
												<div class='text-muted mb-2'>Quantidade:</div>$CARTQTDEITEM
											</div>
											<div class='pt-2 pl-sm-3 mx-auto mx-sm-0 text-center'>
												<div class='text-muted mb-2'>Subtotal</div><small>R$</small>$ValorTotalItem
											</div>
										</div>";
										
										$CARTSUBTOTAL += $VALORTOTALITEM;
									
									}//Fim do loop
									
									$VALORADICIONAL = 0;
									
									if($SOGIFTBOX == 1){
										
										echo 	"												
										<div class='d-sm-flex justify-content-between mb-4 pb-3 pb-sm-2 border-bottom'>
											<div class='media d-block d-sm-flex text-center text-sm-left'><a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 10rem;'><img class='img-fluid' src='img/caixa-presente.png'/></a>
												<div class='media-body pt-2'>
													<h3 class='product-title font-size-base mb-2'><a href='#'>Caixa Presente Especial</a></h3>
												</div>
											</div>
											<div class='pt-2 pl-sm-3 mx-auto mx-sm-0 text-center'>
												<div class='text-muted mb-2'>Quantidade:</div>1
											</div>
											<div class='pt-2 pl-sm-3 mx-auto mx-sm-0 text-center'>
												<div class='text-muted mb-2'>Subtotal</div><small>R$</small>15,90
											</div>
										</div>";						

                    $VALORADICIONAL = '15.90';										
										
									}
									
									$SubTotal       = number_format($CARTSUBTOTAL,2,",",".");	
									$ValorFrete     = number_format($SOVALUESHIPPING,2,",",".");			
                  $ValorAdicional = number_format($VALORADICIONAL,2,",",".");		
                  $ValorFinal     = number_format($SOAMOUNT,2,",",".");									
									
									echo "
									<div class='modal-footer flex-wrap justify-content-between bg-secondary font-size-md'>
										<div class='px-2 py-1'><span class='text-muted'>Subtotal:&nbsp;</span><span><small>R$</small>$SubTotal</span></div>
										<div class='px-2 py-1'><span class='text-muted'>Frete:&nbsp;</span><span><small>R$</small>$ValorFrete</span></div>
										<div class='px-2 py-1'><span class='text-muted'>Adicionais:&nbsp;</span><span><small>R$</small>$ValorAdicional</span></div>
										<div class='px-2 py-1'><span class='text-muted'>Total:&nbsp;</span><span class='font-size-lg'><small>R$</small><strong>$ValorFinal</strong></span></div>
									</div>
								</div>
							</div>
						</div>
					</div>";
					
				}		
				
			}else{
				
				echo "<div class='alert alert-info'>Você ainda não fez nenhum Pedido!</div>";
				
			}
		
      include 'footer.php'; ?>    
   
  </body>
</html>