  4<!DOCTYPE html>
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
		
		  include '../connect.php';
			
			$EXIBEPRD = FALSE;
			
			if(isset($_GET['p'])){
			
				$PRDCOD   = $_GET['p'];
				
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
						$PRDPRICESALE1     = $PRDcol["PRDPRICESALE1"];
						$PRDPRICESALE2     = $PRDcol["PRDPRICESALE2"];
						
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
					
						<a class='navbar-brand d-none d-lg-block mr-3 flex-shrink-0' href='#' style='min-width: 7rem;'><img width='142' src='../img/logo-horizontal.png' alt='BABADROOM'/></a>
						<a class='navbar-brand d-lg-none mr-2' href='#' style='min-width: 2.125rem;'><img width='142' src='../img/logo-horizontal.png' alt='BABADROOM'/></a>
            
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
										<div class="cz-preview-item active" id="first">
											<img class="cz-image-zoom" src="../img/shop/catalog/<?php echo "$PRDCOD-01.PNG";?>" data-zoom="../img/shop/catalog/<?php echo "$PRDCOD-01.PNG";?>" alt="<?php echo $PRDCOD;?>"/>
											<div class="cz-image-zoom-pane"></div>
										</div>
										
										<div class="cz-preview-item" id="second">
											<img class="cz-image-zoom" src="../img/shop/catalog/<?php echo "$PRDCOD-02.PNG";?>" data-zoom="../img/shop/catalog/<?php echo "$PRDCOD-02.PNG";?>" alt="<?php echo $PRDCOD;?>"/>
											<div class="cz-image-zoom-pane"></div>
										</div>
										
										<div class="cz-preview-item" id="third">
											<img class="cz-image-zoom" src="../img/shop/catalog/<?php echo "$PRDCOD-03.PNG";?>" data-zoom="../img/shop/catalog/<?php echo "$PRDCOD-03.PNG";?>" alt="<?php echo $PRDCOD;?>"/>
											<div class="cz-image-zoom-pane"></div>
										</div>
										
										<div class="cz-preview-item" id="fourth">
											<img class="cz-image-zoom" src="../img/shop/catalog/<?php echo "$PRDCOD-04.PNG";?>" data-zoom="../img/shop/catalog/<?php echo "$PRDCOD-04.PNG";?>" alt="<?php echo $PRDCOD;?>"/>
											<div class="cz-image-zoom-pane"></div>
										</div>
										
									</div>
									
									<div class="cz-thumblist order-sm-1">
										<a class="cz-thumblist-item active" href="#first" ><img src="../img/shop/catalog/<?php echo "$PRDCOD-01.PNG";?>" alt="<?php echo $PRDCOD;?>"></a>
										<a class="cz-thumblist-item"        href="#second"><img src="../img/shop/catalog/<?php echo "$PRDCOD-02.PNG";?>" alt="<?php echo $PRDCOD;?>"></a>
										<a class="cz-thumblist-item"        href="#third" ><img src="../img/shop/catalog/<?php echo "$PRDCOD-03.PNG";?>" alt="<?php echo $PRDCOD;?>"></a>
										<a class="cz-thumblist-item"        href="#fourth"><img src="../img/shop/catalog/<?php echo "$PRDCOD-04.PNG";?>" alt="<?php echo $PRDCOD;?>"></a>
										<!--<a class="cz-thumblist-item video-item" href="https://www.youtube.com/watch?v=1vrXpMLLK14">
											<div class="cz-thumblist-item-text"><i class="czi-video"></i>Video</div>
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
									<div class="mb-3"><span class="h3 font-weight-normal text-accent mr-1"><small>R$</small><?php echo $PRDPRICESALE2; ?></span>
										<del class="text-muted font-size-lg mr-3"><small>R$</small><?php echo $PRDPRICESALE1; ?></del><span class="badge badge-danger badge-shadow align-middle mt-n2">Promoção</span>
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
									
									<form class='mb-grid-gutter' method='GET' action=''>
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
											<select class='custom-select mr-3' readonly style='width: 5rem;'>
												<option value='1'>1</option>
											</select>
											<input type='hidden' name='action' value='addtocart'/>
											<input type='hidden' name='prdid'  value='<?php echo $PRDID;?>'/>
											<button class='btn btn-primary btn-shadow btn-block' type='submit'><i class='fas fa-shopping-bag font-size-lg mr-2'></i>Comprar</button>
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
								<a href='https://www.instagram.com/babadroom/'><img class='d-block rounded-lg' src='../img/catalogo-tablet.png' alt='CatalogoIG'></a>
							</div>
							
							<div class='col-lg-4 col-md-6 offset-lg-1 py-4 order-md-1'>
								<h2 class='h3 mb-4 pb-2'><img src='../img/icone-list.png' width='36'/>OPS! Produto não especificado.</h2>         
								<p class='font-size-sm text-muted pb-2' align='justify'>Nosso catálogo é exibido apenas pelo <a href='https://www.instagram.com/babadroom/'>Instagram</a>.</p>								
							</div>
							
						</div>";
						
					}
					
					?>
					
				</div>
			
			
			
      <!-- Product description section 1-->
      <div class='row align-items-center py-md-3'>
			
			  <div class='col-lg-5 col-md-6 offset-lg-1 order-md-2'>
					<img class='d-block rounded-lg' src='../img/algodao.jpg' alt='Image'>
					<br><br>
					<h6 class='font-size-base mb-3'><img src='../img/icone-list.png' width='32'/>Algodão das melhores procedências.</h6>
					<h6 class='font-size-base mb-3'><img src='../img/icone-list.png' width='32'/>Tecidos confortáveis e fofinhos.</h6>
					<h6 class='font-size-base mb-3'><img src='../img/icone-list.png' width='32'/>Detalhes delicados e apaixonantes.</h6>	
				</div>
				
        <div class='col-lg-4 col-md-6 offset-lg-1 py-4 order-md-1'>
          <h2 class='h3 mb-4 pb-2'><img src='../img/icone-list.png' width='36'/>Confecções de alta qualidade</h2>         
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
    
	<!-- Blog + Instagram info cards-->
	<section class='container-fluid px-0'>
		<div class='row no-gutters'>
			
			<div class='col-md-6'>
				<a class='card border-0 rounded-0 text-decoration-none py-md-4 bg-faded-primary' href='https://www.instagram.com/babadroom/' target='_blank'>					
					<div class='card-body text-center'>
						<img class='img-responsive rounded-lg' src='../img/home/instagram.png' width='93' height='95'/><br><br>
						<p class='text-muted font-size-sm'>Siga @babadroom</p>
					</div>						
				</a>
			</div>
			
			<div class='col-md-6'>
				<a class='card border-0 rounded-0 text-decoration-none py-md-4 bg-faded-accent' href='https://www.facebook.com/babadroom.store' target='_blank'>
					<div class='card-body text-center'>
					  <img class='img-responsive rounded-lg' src='../img/home/facebook.png' width='93' height='95'/><br><br>
						<p class='text-muted font-size-sm'>Tendências e Novidades da Moda Bebê no mundo</p>						
					</div>
				</a>
			</div>
			
		</div>
	</section>

	<footer class='bg-dark pt-5'>
			
      <div class='pt-5 bg-darker'>
        <div class='container'>
          <div class='row pb-3'>
            <div class='col-md-3 col-sm-6 mb-4'>
              <div class='media'><i class='fas fa-fighter-jet text-primary' style='font-size: 2.25rem;'></i>
                <div class='media-body pl-3'>
                  <h6 class='font-size-base text-light mb-1'>Entrega rápida e gratuita*</h6>
                  <p class='mb-0 font-size-ms text-light opacity-50'>* Entrega grátis para todo Brasil nas compras acima de R$ 200,00.</p>
                </div>
              </div>
            </div>
						
            <div class='col-md-3 col-sm-6 mb-4'>
              <div class='media'><i class='fas fa-hand-holding-usd text-primary' style='font-size: 2.25rem;'></i>
                <div class='media-body pl-3'>
                  <h6 class='font-size-base text-light mb-1'>Satisfação garantida</h6>
                  <p class='mb-0 font-size-ms text-light opacity-50'>Fique satisfeita ou reembolsamos-lhe em até 7 dias após a compra.</p>
                </div>
              </div>
            </div>
						
            <div class='col-md-3 col-sm-6 mb-4'>
              <div class='media'><i class='fas fa-comments text-primary' style='font-size: 2.25rem;'></i>
                <div class='media-body pl-3'>
                  <h6 class='font-size-base text-light mb-1'>Atendimento personalizado</h6>
                  <p class='mb-0 font-size-ms text-light opacity-50'>Consultores de vendas online a sua inteira disposição, via WhatsApp.</p>
                </div>
              </div>
            </div>
						
            <div class='col-md-3 col-sm-6 mb-4'>
              <div class='media'><i class='fas fa-lock text-primary' style='font-size: 2.25rem; color:green;'></i>
                <div class='media-body pl-3'>
                  <h6 class='font-size-base text-light mb-1'>Credibilidade e Segurança</h6>
                  <p class='mb-0 font-size-ms text-light opacity-50'>Pode confiar! Você está em um ambiente 100% seguro.</p>
                </div>
              </div>
            </div>
          </div>
          <hr class='hr-light pb-4 mb-3'>
          <div class='row pb-2'>
					
            <div class='col-md-5 col-sm-5 col-lg-5 text-center text-md-left mb-4'>
              <div class='text-nowrap mb-4'><a class='d-inline-block align-middle mt-n1 mr-3' href='#'><img class='d-block' width='120' src='../img/logo-no-escuro.png' alt='BABADROOM'/></a>
                <div class='btn-group dropdown disable-autohide'>
                  <button class='btn btn-outline-light border-light btn-sm dropdown-toggle px-2' type='button' data-toggle='dropdown'>
										<img class='mr-2' width='20' src='../img/flags/br.png' alt='Português'/>Brasil / R$
                  </button>
                  
                </div>
              </div>
							
							<div class='pb-4 font-size-xs text-light opacity-50 text-center text-md-left'>Vestindo bebês com elegância.</div>
              
							<div class='widget widget-links widget-light'>
                <ul class='widget-list d-flex flex-wrap justify-content-center justify-content-md-start'>
                  <li class='widget-list-item mr-4'><a class='widget-list-link' href='../WhatsApp'>Atendimento & Suporte</a></li>
                  <li class='widget-list-item mr-4'><a class='widget-list-link' href='#'>Privacidade</a></li>
                  <li class='widget-list-item mr-4'><a class='widget-list-link' href='#'>Termos de Uso</a></li>
                </ul>
              </div>
							
            </div>						
						
						<div class='col-md-2 col-sm-2 col-lg-2 text-center'>
							<img class='d-inline-block' width='150' src='../img/logo-footer.png' widthalt='Logomarca'/>
            </div>						
						
            <div class='col-md-5 col-sm-5 col-lg-5 text-center text-md-right mb-4'>
							<img class='d-inline-block' width='300' height='161' src='../img/home/pagseguro.png' widthalt='Payment methods'/>
            </div>
						
          </div>
          <div class='pb-4 font-size-xs text-light opacity-50 text-center'>BABADROOM© 2019. Todos os direitos reservados.<br>Plataforma criada por <a class='text-light' href='https://imperialize.me/' target='_blank'>IMPERIALIZE Tecnologia</a></div>
        </div>
      </div>
    </footer>
		
    <!-- Back To Top Button-->
		<a class='btn-scroll-top' href='#top' data-scroll>
			<span class='btn-scroll-top-tooltip text-muted font-size-sm mr-2'>Top</span>
			<i class='btn-scroll-top-icon fas fa-arrow-up'></i>
		</a>
		
    <!-- JavaScript libraries, plugins and custom scripts-->
		<script src='../js/jquery-3.4.1.min.js'></script>
    <script src='../js/vendor.min.js'></script>
    <script src='../js/theme.min.js'></script>
		<script src='../components/fontawesome/js/all.min.js'></script>
		<script src='../components/fontawesome/js/fontawesome.min.js'></script>
		<script src='../components/fontawesome/js/solid.min.js'></script>
		<script src='../components/fontawesome/js/regular.min.js'></script>
		<script src='../components/fontawesome/js/brands.min.js'></script>
		<script src='../components/fontawesome/js/v4-shims.min.js'></script>
		
  </body>
</html>