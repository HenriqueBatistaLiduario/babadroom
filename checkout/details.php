  <?php 
	
		include 'header.php'; 
		
		if($LOGADO == 0){
			
			echo "<script>location.href='https://babadroom.com/checkout';</script>";
			
		}
	
	?>
	
	  <script src='../js/jquery-3.4.1.min.js'></script>
	  <script src='../js/jquery.mask.min.js'></script>
		<script src='../js/jquery-viacep.min.js'></script>		
		
		<script>
		
			jQuery( document ).ready( function ( $ ) { 
					$('#cep').mask('00000-000');
			} );
			
    </script>
    
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
					
					  <a class='step-item active' href='../cart.php'>
							<div class='step-progress'><span class='step-count'>1</span></div>
							<div class='step-label'><i class='fas fa-shopping-bag'></i>&nbsp;Cesta</div>
						</a>
						
						<a class='step-item active current' href='details.php'>
							<div class='step-progress'><span class='step-count'>2</span></div>
							<div class='step-label'><i class='fas fa-user'></i>&nbsp;Destino</div>
						</a>
						
						<a class='step-item' href=''>
							<div class='step-progress'><span class='step-count'>3</span></div>
							<div class='step-label'><i class='fas fa-shipping-fast'></i>&nbsp;Remessa</div>
						</a>
						
						<a class='step-item' href=''>
							<div class='step-progress'><span class='step-count'>4</span></div>
							<div class='step-label'><i class='fas fa-money-check-alt'></i>&nbsp;Pagamento</div>
						</a>
						
						<a class='step-item' href=''>
							<div class='step-progress'><span class='step-count'>5</span></div>
							<div class='step-label'><i class='fas fa-check'></i>&nbsp;Confirmação</div>
						</a>
						
					</div>
					
          <?php
					  
						$CTMNOME       = $USERSESSIONNOME;
						$SOTEL         = $USERSESSIONTEL;
					  $SOZIPCODE     = NULL; 						
						$SOADDRESS     = NULL;
						$SONUMBER      = NULL;
						$SOCOMPLEMENT  = NULL;
						$SODISTRICT    = NULL;
						$SOCITY        = NULL;
						$SOSTATE       = NULL;
						$SOCOUNTRY     = 'Brasil';
						$SOCOMMENTS    = NULL;
						$SODTDELIVERYP = NULL;
						
						$SOCODE     = uniqid();
						$SUBACTION  = 'insert';
					
					  if(isset($_GET['so'])){						
							
							$SOCODE    = $_GET['so'];
							$SUBACTION = 'update';
							
							$SOselect = mysqli_query($con,"SELECT * FROM sales_orders WHERE SOCODE='$SOCODE'");
							
							while($SOcol = mysqli_fetch_array($SOselect)){
								
								$CTMNOME       = $SOcol["CTMNOME"];
								$SOTEL         = $SOcol["SOTEL"];
								$SOZIPCODE     = $SOcol["SOZIPCODE"];								
								$SOADDRESS     = $SOcol["SOADDRESS"];
								$SONUMBER      = $SOcol["SONUMBER"];
								$SOCOMPLEMENT  = $SOcol["SOCOMPLEMENT"];
								$SODISTRICT    = $SOcol["SODISTRICT"];
								$SOCITY        = $SOcol["SOCITY"];
								$SOSTATE       = $SOcol["SOSTATE"];
								$SOCOUNTRY     = $SOcol["SOCOUNTRY"];
								$SOCOMMENTS    = $SOcol["SOCOMMENTS"];
								$SODTDELIVERYP = $SOcol["SODTDELIVERYP"];						
								
							}					
							
						}

            echo "					
					
						<div class='d-sm-flex justify-content-between align-items-center bg-secondary p-4 rounded-lg mb-grid-gutter'>
						
							<div class='media align-items-center'>
							
								<div class='img-thumbnail rounded-circle position-relative' style='width: 6.375rem;'>
									<span class='badge badge-warning' data-toggle='tooltip' title='Reward points'>384</span>
									<img class='rounded-circle' src='../img/shop/account/avatar.jpg' alt='$USERSESSIONNOME'/> 
								</div>
								
								<div class='media-body pl-3'>								  
									<h3 class='font-size-base mb-0'>$USERSESSIONNOME</h3><span class='text-accent font-size-sm'>$USERSESSIONEMAIL</span>
								</div>
								
							</div>
							
							<a class='btn btn-light btn-sm btn-shadow mt-3 mt-sm-0' href='account-profile.html'><i class='fas fa-edit mr-2'></i>&nbsp;&nbsp;Editar perfil</a>
							
						</div>
					
						<h2 class='h6 pt-1 pb-3 mb-3 border-bottom'>Dados do Destinatário</h2>
						
						<form name='nwesaleorder' class='class='needs-validation' method='POST' action='shipping.php' data-viacep>
							<div class='row'>
							
								<div class='col-sm-12'>
									<div class='form-group'>
										<label for='checkout-fn'>Nome do Destinatário</label>
										<input class='form-control' type='text' id='checkout-fn' name='ctmnome' value='$CTMNOME'>
									</div>
								</div>
								
							</div>
					
							<div class='row'>
							
								<div class='col-sm-6'>
									<div class='form-group'>
										<label for='checkout-email'>E-mail</label>
										<input class='form-control' type='email' id='checkout-email' name='ctmemail' value='$USERSESSIONEMAIL' readonly>
									</div>
								</div>
								
								<div class='col-sm-6'>
									<div class='form-group'>
										<label for='checkout-phone'>Celular do Destinatário</label>
										<input class='form-control' type='tel' id='checkout-phone' name='ctmtel' value='$SOTEL' required >
									</div>
								</div>
								
							</div>					
							
              <h6 class='mb-3 py-3 border-bottom'>Endereço de Entrega</h6>
              <div class='row'>
					
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for='cep'>CEP</label>
										<input class='form-control' type='text' id='cep' name='cep' value='$SOZIPCODE' placeholder='00000-000' maxlenght='9' required data-viacep-cep>
									</div>
								</div>		

                <div class='col-sm-9'>
									<div class='form-group'>
										<label for='endereco'>Endereço</label>
										<input class='form-control' type='text' id='endereco' name='endereco' value='$SOADDRESS' required data-viacep-endereco>
									</div>
								</div>								
								
							</div>
					
							<div class='row'>								
								
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for='sonumber'>Número</label>
										<input class='form-control' type='text' id='sonumber' name='sonumber' value='$SONUMBER' required >
									</div>
								</div>
								
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for='socomplement'>Complemento</label>
										<input class='form-control' type='text' id='socomplement' name='socomplement' value='$SOCOMPLEMENT' >
									</div>
								</div>
								
								<div class='col-sm-6'>
									<div class='form-group'>
										<label for='bairro'>Bairro</label>
										<input class='form-control' type='text' id='bairro' name='bairro' value='$SODISTRICT' required data-viacep-bairro>
									</div>
								</div>

							</div>
							
							<div class='row'>	
							  <div class='col-sm-6'>
									<div class='form-group'>
										<label for='cidade'>Cidade</label>
										<input class='form-control' type='text' id='cidade' name='cidade' value='$SOCITY' required data-viacep-cidade>
									</div>
								</div>  
								
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for='estado'>Estado</label>
										<input class='form-control' type='text' id='estado' name='estado' value='$SOSTATE' maxlenght='2' required data-viacep-estado>
									</div>
								</div>  
								
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for='pais'>País</label>
										<input class='form-control' type='text' id='pais' name='pais' value='$SOCOUNTRY' required >
									</div>
								</div>					
								
							</div>						
					
							<div class='form-group mb-4'>
							
								<label class='mb-3' for='order-comments'><span class='badge badge-info font-size-xs mr-2'>Nota</span></label>
								<textarea class='form-control' rows='6' id='order-comments' name='socomments' placeholder='Observações adicionais...'>$SOCOMMENTS</textarea>
								<hr></hr><br>
								<input type='hidden' name='action'    value='NEWSO'/>
								<input type='hidden' name='subaction' value='$SUBACTION'/>
								<input type='hidden' name='socode'    value='$SOCODE'/>
								
								<button class='btn btn-primary btn-block' type='submit'>Avançar&nbsp;<i class='fas fa-arrow-right mt-sm-0 ml-1'></i></button>
								
							</div>
							
						</form>";
						
						?>
					
        </section>
				
        <!-- Sidebar-->
        <aside class='col-lg-4 pt-4 pt-lg-0'>
				
          <div class='cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto'>
            <div class='widget mb-3'>
						
              <h2 class='widget-title text-center'>Resumo da Compra</h2>
							<p class='font-size-xs text-muted mb-4' align='center'><?php echo $SOCODE;?></p>
							
							<?php						
							
				        $SUBTOTAL = 0;
							
								$CARTDETAILSselect = mysqli_query($con,"SELECT * FROM carts WHERE CARTSESSION = '$cart_session'");
								$CARTDETAILStotal  = mysqli_num_rows($CARTDETAILSselect);
								
								if($CARTDETAILStotal > 0){
								
									while($CARTDETAILScol = mysqli_fetch_array($CARTDETAILSselect)){
										
										$CARTID        = $CARTDETAILScol["CARTID"];
										$PRDCOD        = $CARTDETAILScol["PRDCOD"];
										$CARTQTDEITEM  = $CARTDETAILScol["CARTQTDEITEM"];
										$CARTVALORITEM = $CARTDETAILScol["CARTVALORITEM"];

										$VALORTOTALITEM = $CARTQTDEITEM * $CARTVALORITEM;

										$ValorItem = number_format($CARTVALORITEM,2,",",".");	
										$ValorTotalItem = number_format($VALORTOTALITEM,2,",",".");

										$ITMDETAILSCESTAselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD='$PRDCOD' ORDER BY PRDCOD");
										
										$ITMselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD='$PRDCOD'");
										
										while($ITMDETAILSCESTAcol = mysqli_fetch_array($ITMDETAILSCESTAselect)){
											
											$GENCOD            = $ITMDETAILSCESTAcol["GENCOD"];
											$SEGCOD            = $ITMDETAILSCESTAcol["SEGCOD"];
											$DEPTCOD           = $ITMDETAILSCESTAcol["DEPTCOD"];
											$CATCOD            = $ITMDETAILSCESTAcol["CATCOD"];
											$GRPCOD            = $ITMDETAILSCESTAcol["GRPCOD"];
											$BRDCOD            = $ITMDETAILSCESTAcol["BRDCOD"];
											$COLORCOD          = $ITMDETAILSCESTAcol["COLORCOD"];
											$SIZECOD           = $ITMDETAILSCESTAcol["SIZECOD"];
											$PRDNAME           = $ITMDETAILSCESTAcol["PRDNAME"];
											$PRDDESCRIPTION    = $ITMDETAILSCESTAcol["PRDDESCRIPTION"];
											$PRDAPRESENTATION  = $ITMDETAILSCESTAcol["PRDAPRESENTATION"];
											$PRDESPECIFICATION = $ITMDETAILSCESTAcol["PRDESPECIFICATION"];										
											$PRDCOUNTPICTURES  = $ITMDETAILSCESTAcol["PRDCOUNTPICTURES"];									
											
											if($PRDCOUNTPICTURES == 0){
												
												$FotoPrincipal = "<img width='64' src='../img/ProdutoSemFoto.png' alt='Produto Sem Foto'/>";
												
											}else{
											
												//Foto Principal
											
												$PXPDETAILSselect = mysqli_query($con,"SELECT PXPNOMEARQUIVO FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA LIMIT 0,1");
												$PXPDETAILSexiste = mysqli_num_rows($PXPDETAILSselect);
												
												if($PXPDETAILSexiste > 0){
													
													while($PXPDETAILScol = mysqli_fetch_array($PXPDETAILSselect)){
														$PXPDETAILSNOMEARQUIVO = $PXPDETAILScol["PXPNOMEARQUIVO"];
													}
													
													$FotoPrincipal = "<img width='64' src='../img/shop/catalog/$PXPDETAILSNOMEARQUIVO' alt='Foto principal' />";
													
												}
												
											}						
											
										}
										
										echo 
										"<div class='media align-items-center pb-2 border-bottom'><a class='d-block mr-2' href='#'>$FotoPrincipal</a>
											<div class='media-body'>
												<h6 class='widget-product-title'><a href='#'>$PRDNAME</a></h6>
												<div class='widget-product-meta'><span class='text-accent mr-2'><small>R$</small>$ValorItem</span><span class='text-muted'>x $CARTQTDEITEM</span></div>
											</div>
										</div>";		

                    $SUBTOTAL += $VALORTOTALITEM;									
										
									}			

								}else{
									
									echo "<div align='center' class='alert alert-info'>SEU CARRINHO ESTÁ VAZIO!</div>";

								}	

                //Demonstrativo de Valores							 	
								
								$VALORDESCONTO = 0;
								$VALORFRETE = 0;
								
								$VALORFINAL = ($SUBTOTAL - $VALORDESCONTO) + $VALORFRETE;
								
								$Subtotal      = number_format($SUBTOTAL,2,",",".");
								$ValorDesconto = number_format($VALORDESCONTO,2,",",".");
								$ValorFrete    = number_format($VALORFRETE,2,",",".");
								$ValorFinal    = number_format($VALORFINAL,2,",",".");
							
							?>            
							
            </div>
						
            <ul class='list-unstyled font-size-sm pb-2 border-bottom'>
							<li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Subtotal:</span><span class='text-right'><small>R$</small><?php echo $Subtotal;?></span></li>
							<li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Desconto:</span><span class='text-right'><small>R$</small><?php echo "- $ValorDesconto";?></span></li>
              <li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Frete:</span><span class='text-right'><small>R$</small><?php echo "+ $ValorFrete";?></span></li>              
            </ul>
						
            <h3 class='font-weight-normal text-center my-4'><small>R$</small><?php echo $ValorFinal;?></h3>
						
          </div>
					
        </aside>
				
      </div>			
      
    </div>
		  
	  <?php include 'footer.php'; ?>