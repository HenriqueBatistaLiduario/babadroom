  <?php include 'header.php'; 
	  
		$action = NULL;
		
		//Recupera os ítens do carrinho em sessão...
		
		if(isset($_SESSION['bbdcartkey'])){
    
			$CARTSESSION = $_SESSION['bbdcartkey'];
			$EXIBEITENS  = TRUE;
			$MSGTOP      = "";
		
		}else{
			
			$EXIBEITENS  = FALSE;
			$MSGTOP      = "<div align='center' class='alert alert-info'>SEU CARRINHO ESTÁ VAZIO!</div>";
			
		}
		
		$CEPDESTINO   = NULL;
		$PRAZOENTREGA = NULL;
		
		if(isset($_GET['a'])){
			
			$action = $_GET['a'];
			
		}
		
		if(isset($_POST['action'])){
			
			$action = $_POST['action']; 
			
			if($action = 'ATUALIZAR_CESTA'){
			
				$NEWQTDEselect = mysqli_query($con,"SELECT CARTID, PRDCOD, CARTQTDEITEM, CARTVALORITEM FROM carts WHERE CARTSESSION = '$CARTSESSION' ORDER BY PRDCOD");
				
				while($NEWQTDEcol = mysqli_fetch_array($NEWQTDEselect)){
					
					$PRDCOD = $NEWQTDEcol["PRDCOD"];
					
					$NEWQTDE = $_POST["$PRDCOD"];
					
					if($NEWQTDE == 0){ //Se zerou, remove o ítem da sacola...
						
						mysqli_query($con,"DELETE FROM carts WHERE CARTSESSION = '$CARTSESSION' AND PRDCOD='$PRDCOD'");						
						
					}else{ //Atualiza a quantidade...
						
						mysqli_query($con,"UPDATE carts SET CARTQTDEITEM=$NEWQTDE WHERE CARTSESSION = '$CARTSESSION' AND PRDCOD='$PRDCOD'");	
					}
					
				}		
				
			}
			
		}
	
	?>
	
    <!-- Page Title (Shop)-->
    <div class='page-title-overlap bg-dark pt-4'>
      <div class='container d-lg-flex justify-content-between py-2 py-lg-3'>
        
        <div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
          <h1 class='h3 text-light mb-0'>Sua cesta</h1>
        </div>
      </div>
    </div>
		
    <!-- Page Content-->
    <div class='container pb-5 mb-2 mb-md-4'>
      <div class='row'>
        <!-- List of items-->
        <section class='col-lg-8'>
          
					<div class='d-flex justify-content-between align-items-center pt-3 pb-2 pb-sm-5 mt-1'>
            <h2 class='h6 text-light mb-0'>Ítens</h2>
						<a class='btn btn-outline-primary btn-sm pl-2' href='index.php'><i class='fas fa-arrow-left mr-2'></i>Continuar comprando</a>
          </div>
					
					<?php
					  
						echo $MSGTOP;
						$SUBTOTAL = 0;
						
						if($EXIBEITENS){
					
							$CARTselect = mysqli_query($con,"SELECT CARTID, PRDCOD, CARTQTDEITEM, CARTVALORITEM FROM carts WHERE CARTSESSION = '$CARTSESSION' ORDER BY PRDCOD");
							$CARTtotal  = mysqli_num_rows($CARTselect);
							
							if($CARTtotal > 0){
							  echo "<form name='cesta' class='form' method='POST' action='cart.php'>";
								
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
											
											$FotoPrincipal = "<img src='img/ProdutoSemFoto.png' alt='Produto Sem Foto'/>";
											
										}else{
										
											//Foto Principal
										
											$PXPselect = mysqli_query($con,"SELECT PXPNOMEARQUIVO FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA LIMIT 0,1");
											$PXPexiste = mysqli_num_rows($PXPselect);
											
											if($PXPexiste > 0){
												
												while($PXPcol = mysqli_fetch_array($PXPselect)){
													$PXPNOMEARQUIVO = $PXPcol["PXPNOMEARQUIVO"];
												}
												
												$FotoPrincipal = "<img src='img/shop/catalog/$PXPNOMEARQUIVO' alt='Foto principal' />";
												
											}
											
										}	

										$SUBTOTAL += $VALORTOTALITEM;									
										
									}
									
									echo 
									"<div class='d-sm-flex justify-content-between my-4 pb-3 border-bottom'>							
										<div class='media d-block d-sm-flex text-center text-sm-left'>
											<a class='d-inline-block mx-auto mr-sm-4' href='#' style='width: 10rem;'>$FotoPrincipal</a>
											<div class='media-body pt-2'>
												<h3 class='product-title font-size-base mb-2'>$PRDNAME</h3>
												<div class='font-size-sm'><span class='text-muted mr-2'>Tamanho:</span>$SIZECOD</div>
												<div class='font-size-sm'><span class='text-muted mr-2'>Cor:</span>$COLORCOD</div>
												<div class='font-size-lg text-accent pt-2'><small>R$</small>$ValorTotalItem</div>
											</div>
										</div>
										
										<div class='pt-2 pt-sm-0 pl-sm-3 mx-auto mx-sm-0 text-center text-sm-left' style='max-width: 9rem;'>
										
											<div class='form-group mb-0'>
												<label class='font-weight-medium' for='quantity1'>Quantidade</label>
												<input class='form-control' type='number' name='$PRDCOD' value='$CARTQTDEITEM' min='0'/>
											</div>
											
										</div>										
									</div>";								     
									
								}			
								
								echo "
								<input type='hidden' name='action' value='ATUALIZAR_CESTA'/>
								<button class='btn btn-outline-accent btn-block' type='submit'><i class='fas fa-sync-alt font-size-base mr-2'></i>Atualizar cesta</button>
								</form>";

              }else{
								
                echo "<div align='center' class='alert alert-info'>SEU CARRINHO ESTÁ VAZIO!</div>";

              }								

						}
						
						?>
					
        </section>
				
        <!-- Sidebar-->
        <aside class='col-lg-4 pt-4 pt-lg-0'>
          <div class='cz-sidebar-static rounded-lg box-shadow-lg ml-lg-auto'>
					
            <div class='text-center mb-4 pb-3 border-bottom'>
              <h2 class='h6 mb-3 pb-1'>Subtotal</h2>
              <h3 class='font-weight-normal'><small>R$</small><?php echo $SUBTOTAL; ?></h3>
            </div>
						
            <div class='accordion' id='order-options'>
							
              <div class='card'>
							
                <!--<div class='card-header'>
                  <h3 class='accordion-heading'>
										<a class='collapsed' href='#shipping-estimates' role='button' data-toggle='collapse' aria-expanded='true' aria-controls='shipping-estimates'>
											<i class='far fa-calendar-alt'></i>&nbsp;&nbsp;Previsão de Entrega<span class='accordion-indicator'></span>
										</a>
									</h3>
                </div>
								
                <div class='collapse show' id='shipping-estimates' data-parent='#order-options'>
								
                  <div class='card-body'>
									
                    <form class='needs-validation' method='POST' action='cart.php'>
											
                      <div class='form-group'>
                        <input class='form-control' name='cep' type='text' placeholder='Informe seu CEP...' required />
                        <div class='invalid-feedback'>Informe um CEP válido!</div>
                      </div>
											<input type='hidden' name='action' value='CALCULAR_ENTREGA' />
                      <button class='btn btn-outline-primary btn-block' type='submit'>Calcular</button>
											
                    </form>
										
                  </div>
									
                </div>-->
								
								<?php 								
									
									echo "<a class='btn btn-primary btn-shadow btn-block' href='checkout'><i class='far fa-arrow-alt-circle-right'></i>&nbsp;&nbsp;Finalizar Compra</a>";									
									
								?>
								
              </div>
							
            </div>					
						
          </div>

				</aside>
				
      </div>
			
    </div>

    <!-- Footer-->
    <?php include 'footer.php'; ?>