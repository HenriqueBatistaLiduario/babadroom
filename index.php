  <?php include 'header.php';
	  
		$DEPTCOD = NULL;
		$CATCOD  = NULL;
		$SALECOD = NULL;
		
		$pageTitle = "Exibindo todos os ítens da loja";
		$SQLselect = "SELECT * FROM products WHERE PRDSTATUS = 2";
		$ORDERBY   = "PRDCADASTRO DESC";
	  
		if(isset($_GET['get'])){
			
			$GET = $_GET['get'];
			
			switch($GET){
				
				case "SEG":  
					$SEGCOD   = $_GET['seg']; 
					$pageTitle = "Exibindo ítens do <b>SEGMENTO $SEGCOD</b>"; 
					$SQLselect = "$SQLselect AND SEGCOD = '$SEGCOD' ORDER BY $ORDERBY";			
				
				break;
				
				case "GEN":  
					$GENCOD   = $_GET['gen']; 
					$pageTitle = "Exibindo ítens do <b>GÊNERO $GENCOD</b>"; 
					$SQLselect = "$SQLselect AND GENCOD IN('$GENCOD','N') ORDER BY $ORDERBY";			
				
				break;
				
					case "CAT" :  
					$CATCOD    = $_GET['cat'];  
					$pageTitle = "Exibindo ítens da <b>CATEGORIA $CATCOD</b>"; 
					$SQLselect = "$SQLselect AND CATCOD = '$CATCOD' ORDER BY $ORDERBY";
					
				break;
				
				case "DEPT":  
					$DEPTCOD   = $_GET['dept']; 
					$pageTitle = "Exibindo ítens do <b>DEPARTAMENTO $DEPTCOD</b>"; 
					$SQLselect = "$SQLselect AND DEPTCOD LIKE '$DEPTCOD%' ORDER BY $ORDERBY";			
				
				break;
			
				case "SALE" : 
					$SALECOD = $_GET['sale'];                       
					$pageTitle = "Exibindo somente ítens em LIQUIDAÇÃO"; 
					$SQLselect = "$SQLselect AND PRDSALEOFF = 1 ORDER BY $ORDERBY";		
					
				break;
					
			}
			
		}
		
		$GRIDselect = mysqli_query($con,$SQLselect);
		
	?>
	
    <!-- Page Title (Shop)-->
    <div class='page-title-overlap pt-4'>
      <div class='container-fluid d-lg-flex justify-content-between py-2 py-lg-3'>
        <div class='order-lg-2 mb-3 mb-lg-0 pt-lg-2'>
          <nav aria-label='breadcrumb'>
            <ol class='breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-star'>
              <li class='breadcrumb-item text-nowrap active' aria-current='page' style='color: #91D8F7';><?php echo $pageTitle; ?></li>
            </ol>
          </nav>
        </div>
        <!--<div class='order-lg-1 pr-lg-4 text-center text-lg-left'>
          <h1 class='h3 text-light mb-0'>Shop</h1>
        </div>-->
      </div>
    </div>
		
    <!-- Page Content-->
    <div class='container pb-5 mb-2 mb-md-4'>
      <div class='row'>        
        
				<?php include 'aside.php'; //Filtro vertical na barra lateral esquerda. ?>				
				
        <!-- Content  -->
        <section class="col-lg-12 col-sm-12">
          <!-- Toolbar-->
          <div class='d-flex justify-content-center justify-content-sm-between align-items-center pt-lg-2 pb-4 pb-sm-5 mt-n2 mt-lg-0'>
            
						<!--<div class='d-flex flex-wrap'>
              <div class='form-inline flex-nowrap mr-3 mr-sm-6 pb-3'>							  
                <label class='opacity-75 text-nowrap mr-2 d-none d-sm-block' for='sorting'>Ordenar por</label>
                <select class='form-control custom-select' id='sorting'>
                  <option>Popularidade</option>
                  <option>Menor Preço</option>
                  <option>Maior Preço</option>
                  <option>Classificação Média</option>
                  <option>Ordem alfabética A->Z</option>
                  <option>Ordem alfabética Z->A</option>
                </select>
								<span class='font-size-sm opacity-75 text-nowrap ml-2 d-none d-md-block'>em <b>287</b> produtos</span>
              </div>
            </div>-->
						
            <!--<div class="d-flex pb-3">
							<a class="nav-link-style nav-link-light mr-3" href="#"><i class="fas fa-arrow-left"></i></a>
							<span class="font-size-md text-light">1 / 5</span>
							<a class="nav-link-style nav-link-light ml-3" href="#"><i class="fas fa-arrow-right"></i></a>
						</div>
						
            <div class="d-none d-sm-flex pb-3">
						
							<a class="btn btn-icon nav-link-style bg-light text-dark disabled opacity-100 mr-2" href="#"><i class="czi-view-grid"></i></a>
							<a class="btn btn-icon nav-link-style nav-link-light" href="shop-list-ls.html"><i class="czi-view-list"></i></a>
							
						</div>-->
						
          </div>
          <!-- Products grid-->
          <div class="row mx-n2">
					
					  <?php 
						
							$PRDtotal  = mysqli_num_rows($GRIDselect);
							
							if($PRDtotal > 0){
								
								while($PRDcol = mysqli_fetch_array($GRIDselect)){
									
									$PRDID              = $PRDcol["PRDID"];					
									$GENCOD             = $PRDcol["GENCOD"];
									$SEGCOD             = $PRDcol["SEGCOD"];
									$DEPTCOD            = $PRDcol["DEPTCOD"];
									$CATCOD             = $PRDcol["CATCOD"];
									$GRPCOD             = $PRDcol["GRPCOD"];
									$BRDCOD             = $PRDcol["BRDCOD"];
									$COLORCOD           = $PRDcol["COLORCOD"];
									$SIZECOD            = $PRDcol["SIZECOD"];
									$PRDCOD             = $PRDcol["PRDCOD"];
									$PRDNAME            = $PRDcol["PRDNAME"];
									$PRDDESCRIPTION     = $PRDcol["PRDDESCRIPTION"];
									$PRDAPRESENTATION   = $PRDcol["PRDAPRESENTATION"];
									$PRDESPECIFICATION  = $PRDcol["PRDESPECIFICATION"];
									$PRDPRICEBUY        = $PRDcol["PRDPRICEBUY"];
									$PRDIDEALMARGIM     = $PRDcol["PRDIDEALMARGIM"];
									$PRDPRICEOFICIAL    = $PRDcol["PRDPRICEOFICIAL"];
									$PRDINSALE          = $PRDcol["PRDINSALE"];
									$PRDPRICESALE       = $PRDcol["PRDPRICESALE"];
									$PRDCOUNTPICTURES   = $PRDcol["PRDCOUNTPICTURES"];	
									
									$PRECOOFICIAL = number_format($PRDPRICEOFICIAL,2,",",".");
									
									$PRECOSALE    = NULL;
									$SpanSale     = NULL;
									$ProductPrice = "<span class='text-accent'><small>R$</small>$PRECOOFICIAL</span>";
									
									if($PRDINSALE == 1){
										
										$SpanSale     = "<span class='badge badge-danger badge-shadow'>PROMOÇÃO</span>";
										$PRECOSALE    = number_format($PRDPRICESALE,2,",",".");				
										$ProductPrice = "<span class='text-accent'><small>R$</small>$PRECOSALE&nbsp;&nbsp;</span><del class='font-size-sm text-muted'>$PRECOOFICIAL</del>";
										
									}                  									
									
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
									
									echo "
									
									<div class='col-md-4 col-sm-6 px-2 mb-4'>
										<div class='card product-card'>$SpanSale
											<button class='btn-wishlist btn-sm' type='button' data-toggle='tooltip' data-placement='left' title='Adicionar a sua Wishlist'><i class='fas fa-heart'></i></button>
											<a class='card-img-top d-block overflow-hidden' href='i.php?p=$PRDCOD'>$FotoPrincipal</a>
											
											<div class='card-body py-2'>
											
												<a class='product-meta d-block font-size-xs pb-1' href='#'>$PRDCOD</a>
												
												<h3 class='product-title font-size-sm'><a href='i.php?p=$PRDCOD'>$PRDNAME</a></h3>
												
												<div class='d-flex justify-content-between'>
												
													<div class='product-price'>
														$ProductPrice
													</div>
													
													<div class='star-rating'>
														<i class='fas fa-star'></i>
														<i class='fas fa-star'></i>
														<i class='fas fa-star'></i>
														<i class='fas fa-star'></i>
														<i class='fas fa-star-half-alt'></i>
													</div>
													
												</div>
												
											</div>
											
										</div>
										<hr class='d-sm-none'>
									</div>";									
									
								}							
								
							}else{
								
								echo "<div class='alert alert-info' align='center'><strong>AGUARDE!</strong> Em breve teremos muitas novidades neste Setor.</div>";
								
							}
							
						?>					
            
          </div>
					
          <!-- Banner--
          <div class='py-sm-2'>
            <div class='d-sm-flex justify-content-between align-items-center bg-secondary overflow-hidden mb-4 rounded-lg'>
              <div class='py-4 my-2 my-md-0 py-md-5 px-4 ml-md-3 text-center text-sm-left'>
                <h4 class='font-size-lg font-weight-light mb-2'>Converse All Star</h4>
                <h3 class='mb-4'>Make Your Day Comfortable</h3><a class='btn btn-primary btn-shadow btn-sm' href='#'>Shop Now</a>
              </div><img class='d-block ml-auto' src='img/shop/catalog/banner.jpg' alt='Shop Converse'>
            </div>
          </div>-->
        
					
          <hr class="my-3">
					
          <!-- Pagination--
          <nav class='d-flex justify-content-between pt-2' aria-label='Page navigation'>
            <ul class='pagination'>
              <li class='page-item'><a class='page-link' href='#'><i class='fas fa-arrow-left mr-2'></i>Prev</a></li>
            </ul>
            <ul class='pagination'>
              <li class='page-item d-sm-none'><span class='page-link page-link-static'>1 / 5</span></li>
              <li class='page-item active d-sm-block aria-current='page'><a class='page-link' href='#'>1</a></li>
              <li class='page-item d-none d-sm-block'><span class='page-link'>2<span class='sr-only'>(current)</span></span></li>
              <li class='page-item d-none d-sm-block'><a class='page-link' href='#'>3</a></li>
              <li class='page-item d-none d-sm-block'><a class='page-link' href='#'>4</a></li>
              <li class='page-item d-none d-sm-block'><a class='page-link' href='#'>5</a></li>
            </ul>
            <ul class='pagination'>
              <li class='page-item'><a class='page-link' href='#' aria-label='Next'>Next<i class='fas fa-arrow-right ml-2'></i></a></li>
            </ul>
          </nav>-->
					
        </section>
      </div>
    </div>
		
    <!-- Toast: Added to Cart-->
    <div class='toast-container toast-bottom-center'>
      <div class='toast mb-3' id='cart-toast' data-delay='5000' role='alert' aria-live='assertive' aria-atomic='true'>
        <div class='toast-header bg-success text-white'><i class='czi-check-circle mr-2'></i>
          <h6 class='font-size-sm text-white mb-0 mr-auto'>Adicionado a cesta!</h6>
          <button class='close text-white ml-2 mb-1' type='button' data-dismiss='toast' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        </div>
        <div class='toast-body'>Adicionamos este ítem a sua cesta.</div>
      </div>
    </div>
		
		<section class='container pt-md-3 pb-4 pb-md-5 mb-lg-2'>
			
			<div class='row'>
			
				<div class='col-lg-4 col-md-6 mb-2 py-3'>
					<div class='widget'>
						<h3 class='widget-title'>Mais vendidos</h3>
						
						<div class='media align-items-center pb-2 border-bottom'>
							<a class='d-block mr-2' href='i.php?p=ST37AMARELO'><img width='64' src='img/shop/catalog/ST37AMARELO-1.png' alt='ST37AMARELO'/></a>
							<div class='media-body'>
								<h6 class='widget-product-title'><a href='i.php?p=ST37AMARELO'>Saída Maternidade Tricotada Renda Tule</a></h6>
								<div class='widget-product-meta'><span class='text-accent mr-2'>R$399.<small>00</small></span></div>
							</div>
						</div>
						
						<div class='media align-items-center py-2 border-bottom'>
							<a class='d-block mr-2' href='i.php?p=ST38CINZA'><img width='64' src='img/shop/catalog/ST38CINZA-1.png' alt='ST38CINZA'/></a>
							<div class='media-body'>
								<h6 class='widget-product-title'><a href='shop-single.html'>Saída Maternidade Tricotada Dream Ovelhinha</a></h6>
								<div class='widget-product-meta'><span class='text-accent mr-2'>$449.<small>00</small></span></div>
							</div>
						</div>
						
					</div>
				</div>
				
				<div class='col-lg-4 col-md-6 mb-2 py-3'>
				
					<div class='widget'>
						<h3 class='widget-title'>Novidades & Lançamentos</h3>
						<div class='media align-items-center pb-2 border-bottom'>
							<a class='d-block mr-2' href='i.php?p=ST07BRANCA'><img width='64' src='img/shop/catalog/ST07BRANCA-1.png' alt='ST07BRANCA'/></a>
							<div class='media-body'>
								<h6 class='widget-product-title'><a href='i.php?p=ST07BRANCA'>Saída Maternidade Tricotada Flores</a></h6>
								<div class='widget-product-meta'><span class='text-accent mr-2'>R$399.<small>00</small></span></div>
							</div>
						</div>
						
						<div class='media align-items-center py-2 border-bottom'>
							<a class='d-block mr-2' href='i.php?p=ST23AZUL'><img width='64' src='img/shop/catalog/ST23AZUL-1.png' alt='ST23AZUL'/></a>
							<div class='media-body'>
								<h6 class='widget-product-title'><a href='i.php?p=ST23AZUL'>Saída Maternidade Tricotada George</a></h6>
								<div class='widget-product-meta'><span class='text-accent mr-2'>R$399.<small>00</small></span></div>
							</div>
						</div>						
						
						<!--<p class='mb-0'>...</p><a class='font-size-sm' href='shop.php?get=LANC'>Ver mais<i class='fas fa-arrow-right font-size-xs ml-1'></i></a>-->
						
					</div>
					
				</div>
				
				<div class='col-md-4 col-sm-6 d-none d-lg-block'><a class='d-block' href='index.php?get=DEPT&dept=DEPT006'><img class='d-block rounded-lg' src='img/home/v-banner.jpg' alt='Promo banner'></a></div>
			</div>
		</section>
		
    <!-- Footer-->
    <?php include 'footer.php';?>