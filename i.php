<!DOCTYPE html>
<html lang='pt-Br'>
  <head>
	
    <meta charset='utf-8'>
    <title>BABADROOM</title>

    <meta name='description' content='Baby Store'>
    <meta name='keywords'    content='babadroom, baby store, bebe, bebê, chá de bebê, cha de bebe, bebês, roupa de bebê, coisas de bebê'>
    <meta name='author'      content='ELAINE CRISTINA DE OLIVEIRA LIDUARIO 06048814607'>
    <meta name='viewport'    content='width=device-width, initial-scale=1'>
    <meta name='theme-color' content='#ffffff'>		
   
    <link rel='apple-touch-icon'      sizes='180x180' href='apple-touch-icon.png'>
    <link rel='icon' type='image/png' sizes='32x32'   href='../img/favicon.png'>
   
    <link href='../css/vendor.min.css' media='screen' rel='stylesheet'/>
    <link href='../css/theme.min.css'  media='screen' rel='stylesheet'/>
		
		<link href='../components/fontawesome/css/fontawesome.min.css' rel='stylesheet'/>
		<link href='../components/fontawesome/css/all.min.css'         rel='stylesheet'/>
    <link href='../components/fontawesome/css/brands.min.css'      rel='stylesheet'/>
    <link href='../components/fontawesome/css/solid.min.css'       rel='stylesheet'/>
		<link href='../components/fontawesome/css/v4-shims.min.css'    rel='stylesheet'/>
		
  </head>
	
    <?php
		
		  include 'connect.php';
			
			$EXIBEPRD = FALSE;
			
			if(isset($_GET['p'])){
			
				$PRDCOD = $_GET['p'];
				
				//Dados do Produto...
			
				$PRDselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD = '$PRDCOD'");
				$PRDexiste = mysqli_num_rows($PRDselect);
				
				if($PRDexiste > 0){
					
					while($PRDcol = mysqli_fetch_array($PRDselect)){
						
						$GENCOD            = $PRDcol["GENCOD"];
						$SEGCOD            = $PRDcol["SEGCOD"];
						$DEPTCOD           = $PRDcol["DEPTCOD"];
						$CATCOD            = $PRDcol["CATCOD"];
						$GRPCOD            = $PRDcol["GRPCOD"];
						$BRDCOD            = $PRDcol["BRDCOD"];
						$COLORCOD          = $PRDcol["COLORCOD"];
						$SIZECOD           = $PRDcol["SIZECOD"];
						$PRDNAME           = $PRDcol["PRDNAME"];
						$PRDDESCRIPTION    = $PRDcol["PRDDESCRIPTION"];
						$PRDAPRESENTATION  = $PRDcol["PRDAPRESENTATION"];
						$PRDESPECIFICATION = $PRDcol["PRDESPECIFICATION"];
						$PRDPRICEBUY       = $PRDcol["PRDPRICEBUY"];
						$PRDIDEALMARGIM    = $PRDcol["PRDIDEALMARGIM"];
						$PRDTAXES          = $PRDcol["PRDTAXES"];
						$PRDPRICEOFICIAL   = $PRDcol["PRDPRICEOFICIAL"];
						$PRDINSALE         = $PRDcol["PRDINSALE"];
						$PRDPRICESALE      = $PRDcol["PRDPRICESALE"];
						$PRDCOUNTPICTURES  = $PRDcol["PRDCOUNTPICTURES"];
						
					}
					
					$PRECOOFICIAL = number_format($PRDPRICEOFICIAL,2,",",".");
									
					$PRECOSALE    = NULL;
					$ProductPrice = "<span class='h3 font-weight-normal text-accent mr-1'><small>R$</small>$PRECOOFICIAL</span>";
					
					if($PRDINSALE == 1){
						
						$PRECOSALE    = number_format($PRDPRICESALE,2,",",".");				
						$ProductPrice = 
						"<span class='h3 font-weight-normal text-accent mr-1'><small>R$</small>$PRECOSALE</span>
						 <del class='text-muted font-size-lg mr-3'><small>R$</small>$PRECOOFICIAL</del>
						 <span class='badge badge-danger badge-shadow align-middle mt-n2'>Promoção</span>";
						
					}           
					
					$EXIBEPRD = TRUE;				
					
				}			
			
			}
		
		?>		
		
		<style>
		
			body {
				background-image: url("textura.png");
				background-repeat: repeat-x;
			}
			
	  </style>
		
    <header class='box-shadow-sm'>
      
      <div class='navbar-sticky bg-light'>
        <div class='navbar navbar-expand-lg navbar-light'>
          <div class='container'>					
						
						<a class='navbar-brand d-none d-lg-block mr-3 flex-shrink-0' href='index.php' style='min-width: 7rem;'>
							<i class="fas fa-chevron-circle-left" style='width:32px; height:32px;'></i>&nbsp;&nbsp;
							<img width='142' src='../img/logo-horizontal.png' alt='BABADROOM'/>
						</a>
						
						<a class='navbar-brand d-lg-none mr-2' href='https://babadroom.store/index.php' style='min-width: 2.125rem;'>
							<i class="fas fa-chevron-circle-left" style='width:32px; height:32px;'></i>&nbsp;&nbsp;
							<img width='142' src='../img/logo-horizontal.png' alt='BABADROOM'/>
						</a>
            
          </div>
        </div>				
      </div>
			
    </header>
    
    <body>
    <div class="container">      
			
				<div class="bg-light box-shadow-lg rounded-lg px-4 py-3 mb-5">
				<?php 
			
			  if($EXIBEPRD){ ?>
				
					<div class="px-lg-3">
						<div class="row">
						 
							<div class="col-lg-7 pr-lg-0 pt-lg-4">
							
								<div class="cz-product-gallery">
									<div class="cz-preview order-sm-2">
									
									  <?php
										
										  if($PRDCOUNTPICTURES > 0){
												
												echo "										
												<div class='cz-preview-item active' id='first'>
													<img class='cz-image-zoom' src='../img/shop/catalog/$PRDCOD-1.png' data-zoom='../img/shop/catalog/$PRDCOD-1.png' alt='$PRDCOD'/>
													<div class='cz-image-zoom-pane'></div>
												</div>";
										
											}
											
											if($PRDCOUNTPICTURES > 1){
												
												echo "										
												<div class='cz-preview-item' id='second'>
													<img class='cz-image-zoom' src='../img/shop/catalog/$PRDCOD-2.png' data-zoom='../img/shop/catalog/$PRDCOD-2.png' alt='$PRDCOD'/>
													<div class='cz-image-zoom-pane'></div>
												</div>";
												
											}
											
											if($PRDCOUNTPICTURES > 2){
												
												echo "										
												<div class='cz-preview-item' id='third'>
													<img class='cz-image-zoom' src='../img/shop/catalog/$PRDCOD-3.png' data-zoom='../img/shop/catalog/$PRDCOD-3.png' alt='$PRDCOD'/>
													<div class='cz-image-zoom-pane'></div>
												</div>";
												
											}
											
											if($PRDCOUNTPICTURES > 3){
											
											  echo "										
												<div class='cz-preview-item' id='fourth'>
													<img class='cz-image-zoom' src='../img/shop/catalog/$PRDCOD-4.png' data-zoom='../img/shop/catalog/$PRDCOD-4.png' alt='$PRDCOD'/>
													<div class='cz-image-zoom-pane'></div>
												</div>";
												
											}
											
										?>
										
									</div>
									
									<div class="cz-thumblist order-sm-1">
									  
										<?php
										
									  if($PRDCOUNTPICTURES > 0){ echo "<a class='cz-thumblist-item active' href='#first' ><img src='../img/shop/catalog/$PRDCOD-1.png' alt='$PRDCOD'></a>"; }
										if($PRDCOUNTPICTURES > 1){ echo "<a class='cz-thumblist-item'        href='#second'><img src='../img/shop/catalog/$PRDCOD-2.png' alt='$PRDCOD'></a>"; }
										if($PRDCOUNTPICTURES > 2){ echo "<a class='cz-thumblist-item'        href='#third' ><img src='../img/shop/catalog/$PRDCOD-3.png' alt='$PRDCOD'></a>"; }
										if($PRDCOUNTPICTURES > 3){ echo "<a class='cz-thumblist-item'        href='#fourth'><img src='../img/shop/catalog/$PRDCOD-4.png' alt='$PRDCOD'></a>"; }
										
										?>
										<!--<a class='cz-thumblist-item video-item' href='https://www.youtube.com/watch?v=1vrXpMLLK14'>
											<div class='cz-thumblist-item-text'><i class='czi-video'></i>Video</div>
										</a>-->
									</div>
									
								</div>
							</div>					
							
							<div class="col-lg-5 pt-4 pt-lg-0 cz-image-zoom-pane">
								<div class="product-details ml-auto pb-3">
									<div class="d-flex justify-content-between align-items-center mb-2">
										<a href="#reviews" data-scroll>
											<div class="star-rating">
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star"></i>
												<i class="fas fa-star-half-alt"></i>
											</div>
											<span class="d-inline-block font-size-sm text-body align-middle mt-1 ml-1">(4.7/5.0) 376 avaliações</span>
										</a>
										<button class="btn-wishlist mr-0 mr-lg-n3" type="button" data-toggle="tooltip" title="Add to wishlist"><i class="fas fa-heart"></i></button>
									</div>
									<div class="mb-3">
									  <?php echo $ProductPrice;?>
									</div>
									<div class="font-size-sm mb-4"><?php echo $PRDNAME;?><br>
										<span class="text-heading font-weight-medium mr-1">Cor:</span><span class="text-muted"><?php echo $COLORCOD;?></span></div>
									
									<div class="position-relative mr-n4 mb-3">
										<div class="custom-control custom-option custom-control-inline mb-2">
											<input class="custom-control-input" type="radio" name="color" id="color1" checked>
											<label class="custom-option-label rounded-circle" for="color1"><span class="custom-option-color rounded-circle" style="background-image: url(img/shop/single/color-opt-1.png)"></span></label>
										</div>
										<!--<div class="custom-control custom-option custom-control-inline mb-2">
											<input class="custom-control-input" type="radio" name="color" id="color2">
											<label class="custom-option-label rounded-circle" for="color2"><span class="custom-option-color rounded-circle" style="background-image: url(img/shop/single/color-opt-2.png)"></span></label>
										</div>
										<div class="custom-control custom-option custom-control-inline mb-2">
											<input class="custom-control-input" type="radio" name="color" id="color3">
											<label class="custom-option-label rounded-circle" for="color3"><span class="custom-option-color rounded-circle" style="background-image: url(img/shop/single/color-opt-3.png)"></span></label>
										</div>-->
										<div class="product-badge product-available mt-n1"><i class="fas fa-fighter-jet"></i>&nbsp;FRETE GRÁTIS</div>
									</div>
									
									<form class='mb-grid-gutter' method='GET' action='cart_session.php'>
									
										<div class='form-group'>
										
											<div class='d-flex justify-content-between align-items-center pb-1'>
												<label class='font-weight-medium' for='product-size'>Tamanho:</label>
												<!--<a class='nav-link-style font-size-sm' href='#size-chart' data-toggle='modal'><i class='fas fa-info lead align-middle mr-1 mt-n1'></i>Guia de tamanhos</a>-->
											</div>
											
											<select class='custom-select' readonly id='product-size'>
												<option value=''><?php echo $SIZECOD;?></option>
											</select>
											
										</div>
										
										<div class='form-group d-flex align-items-center'>
										  <input type='hidden' name='p' value='<?php echo $PRDCOD;?>'/>
											<input type='number' name='q' min='1' max='10' class='custom-select mr-3' style='width: 10rem;' value='1'/>									
											<button class='btn btn-primary btn-shadow btn-block' type='submit'><i class='fas fa-shopping-bag font-size-lg mr-3'></i>Adicionar</button>
										</div>
										
									</form>
									
									<!-- Product panels-->
									<div class="accordion mb-4" id="productPanels">
										<div class="card">
											<div class="card-header">
												<h3 class="accordion-heading"><a href="#productInfo" role="button" data-toggle="collapse" aria-expanded="true" aria-controls="productInfo">
												<i class="fas fa-list-ul text-muted font-size-lg align-middle mt-n1 mr-2"></i>Especificação do Produto<span class="accordion-indicator"><i data-feather="chevron-up"></i></span></a></h3>
											</div>
											<div class="collapse show" id="productInfo" data-parent="#productPanels">
												<div class="card-body">
													<h6 class="font-size-sm mb-2"><?php echo $PRDESPECIFICATION;?></h6>                        
													<h6 class="font-size-sm mb-2">Referência</h6>
													<ul class="font-size-sm pl-4 mb-0">
														<li><?php echo $PRDCOD;?></li>
													</ul>
												</div>
											</div>
										</div>									
									</div>
									
									<!-- Sharing-->
									
									<a class="share-btn sb-twitter mr-2 my-2" href=""><i class="fab fa-whatsapp"></i>&nbsp;&nbsp;Compartilhar pelo WhatsApp</a>
									
								</div>
							</div>
						</div>
					</div>
						<?php 
			
					}else{
						
						echo "
						<div class='row align-items-center py-md-3'>
			
							<div class='col-lg-5 col-md-6 offset-lg-1 order-md-2'>
								<a href='https://www.instagram.com/babadroom/'><img class='d-block rounded-lg' src='img/catalogo-tablet.png' alt='CatalogoIG'></a>
							</div>
							
							<div class='col-lg-4 col-md-6 offset-lg-1 py-4 order-md-1'>
								<h2 class='h3 mb-4 pb-2'><img src='img/icone-list.png' width='36'/>OPS! Produto não especificado.</h2>         
								<p class='font-size-sm text-muted pb-2' align='justify'>Nosso catálogo é exibido apenas pelo <a href='https://www.instagram.com/babadroom/'>Instagram</a>.</p>								
							</div>
							
						</div>";
						
					}
					
					?>
					
				</div>
			
			
			
      <!-- Product description section 1-->
      <div class='row align-items-center py-md-3'>
			
			  <div class='col-lg-5 col-md-6 offset-lg-1 order-md-2'>
					<img class='d-block rounded-lg' src='img/algodao.jpg' alt='Image'>
					<br><br>
					<h6 class='font-size-base mb-3'><img src='img/icone-list.png' width='32'/>Algodão das melhores procedências.</h6>
					<h6 class='font-size-base mb-3'><img src='img/icone-list.png' width='32'/>Tecidos confortáveis e fofinhos.</h6>
					<h6 class='font-size-base mb-3'><img src='img/icone-list.png' width='32'/>Detalhes delicados e apaixonantes.</h6>	
				</div>
				
        <div class='col-lg-4 col-md-6 offset-lg-1 py-4 order-md-1'>
          <h2 class='h3 mb-4 pb-2'><img src='img/icone-list.png' width='36'/>Confecções de alta qualidade</h2>         
          <p class='font-size-sm text-muted pb-2' align='justify'>As coleções da BABADROOM são inspirações modernas. A cada estação buscamos apresentar uma novidade, seja aumentando nosso mix de produtos ou apostando em novas tendências, como cores mais arrojadas, além de delicadas roupinhas para coordenar com o enxoval do bebê. Convidamos você a viajar pelo universo de encantos da BABADROOM.</p>
          <h6 class='font-size-base mb-3'><img src='../img/icone-list.png' width='32'/>Instruções de Lavagem...</h6>
					
          <div class='tab-content text-muted font-size-sm'>
					  <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Lavar cores separadamente</div>
            <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;30° máquina de lavar suave</div>
            <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Usar somente sabão de coco ou neutro</div>
            <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Lavar à mão normal (30°)</div>
						<div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Passar ferro do lado avesso</div>
            <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Engomar a baixa temperatura</div>
            <div class='tab-pane fade show active' ><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;Não lavar a seco</div>
          </div>
					
        </div>
				
      </div>
			
			<?php include 'reviews.php'; ?>
    
    </div>  
	
		<?php include 'footer.php'; ?>
		
  </body>
</html>