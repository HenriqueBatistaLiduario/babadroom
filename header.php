<!DOCTYPE html>
<html lang='pt-Br'>
  <head>
	
    <meta charset='utf-8'>
    <title>BABADROOM</title>
   
    <meta name='description' content='Baby Store'>
    <meta name='keywords'    content='Babadroom, baby, bebe, bebê, chá de bebê, cha de bebe, bebês, roupa de bebê, coisas de bebê'>
    <meta name='author'      content='IMPERIALIZE'>
    <meta name='viewport'    content='width=device-width, initial-scale=1'>
    <meta name='theme-color' content='#ffffff'>		
   
    <link rel='apple-touch-icon'      sizes='180x180' href='apple-touch-icon.png'>
    <link rel='icon' type='image/png' sizes='32x32'   href='img/favicon.png'>
    
    <link rel='mask-icon' href='safari-pinned-tab.svg' color='#fe6a6a'/>    
   
    <link href='css/vendor.min.css' media='screen' rel='stylesheet'/>
    <link href='css/theme.min.css'  media='screen' rel='stylesheet'/>
		
		<link href='components/fontawesome/css/fontawesome.min.css' rel='stylesheet'>
		<link href='components/fontawesome/css/all.min.css'         rel='stylesheet'>
    <link href='components/fontawesome/css/brands.min.css'      rel='stylesheet'>
    <link href='components/fontawesome/css/solid.min.css'       rel='stylesheet'>
		<link href='components/fontawesome/css/v4-shims.min.css'    rel='stylesheet'>
		
  </head>
	
	<style>

		.btn-buy-flutuante {
			position: fixed;
			top: 82%;
			left: 1%;
			padding: 10px;
			z-index: 1000;
		}

	</style>
	
  <body>
	
	  <div>
			<a href='wa'>
				<img class='btn-buy-flutuante img-responsive' src='https://imperialize.me/buttons/help-wa.png' width='90'/>
			</a>
		</div>	
	
	  <?php 
		
			include 'connect.php'; 
		  include 'timezone.php';
		
		?>
	 
    <noscript>
      
    </noscript>
		
		<script>
		
			function consistencia_prs(){
				var valido = false;
				
				with(document.prs){
					
					if(userprspcodigo.value.length < 13){
						userprspcodigo.focus;
						alert('ATENÇÃO: CÓDIGO DE VALIDAÇÃO INVÁLIDO!\n\nEste código possui no mínimo 13 caracteres.');	
											
					}else if(usersenha.value.length < 8){
						usersenha.focus;
						alert('ATENÇÃO: A senha deve conter no mínimo 8 caracteres.');				
					
					}else if(usersenha.value != usersenha2.value){
						usersenha2.focus;
						alert('ATENÇÃO: As senhas digitadas são diferentes. Confirme corretamente a senha.');
					
					}else{
						var valido = true;
					}			
					
				}
				
				return valido;
			}	
			
			function submit_cadastro(){
				if(confirm('DECLARAÇÃO\n\nAo confirmar a Alteração de Senha de sua conta você declara estar de acordo com os Termos de Uso e com a Política de Privacidade da PetsLittle.\n\nClique em OK para confirmar...'))
					return true;
				else
					return false;
			}
			
			function desabilitar(){ 
				return false; 
			} 
			
			document.oncontextmenu=desabilitar;
			
		</script>
		
		<?php
		
		  $LOGADO = false;
			$FirstNameSession = "Visitante";
			$MSGlogado        = "Fazer Login";
			$IconUserColor    = "red";
			$ModalLogin       = "href='#signin-modal' data-toggle='modal'";	
      $HREF             = "login.php";			
			
			$USERSESSIONID      = NULL;
			$USERSESSIONNOME    = NULL;
			$USERSESSIONPERFIL  = NULL;
			$USERSESSIONEMAIL   = NULL;
			$USERSESSIONTEL     = NULL;	
			
			session_start();
			
			if(isset($_SESSION['idsession'])){
				
				$id_session    = $_SESSION['idsession'];
				$ident_session = $_SESSION['ident_session'];			
				
				$USERSESSIONselect = mysqli_query($con,"SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$ident_session'");
				
				while($USERSESSIONrow = mysqli_fetch_assoc($USERSESSIONselect)){
				
					$USERSESSIONID     = $USERSESSIONrow["USERIDENTIFICACAO"];
					$USERSESSIONNOME   = $USERSESSIONrow["USERNOME"];
					$USERSESSIONPERFIL = $USERSESSIONrow["USERPERFIL"];					
					$USERSESSIONEMAIL  = $USERSESSIONrow["USERLOGIN"];
          $USERSESSIONTEL    = $USERSESSIONrow["USERTEL1"];
					
					$FirstNameSession = explode(" ", $USERSESSIONNOME);
				  $FirstNameSession = $FirstNameSession[0];
					
					$AVATAR = "<img class='rounded-circle' src='img/shop/account/avatar.jpg' alt='$USERSESSIONNOME'>";
				
				}			
				
				$LOGADO = true;			
				
				$MSGlogado = "Bem-vindo!";
				$IconUserColor = "green";
				$ModalLogin = NULL;			
				$HREF   = "#";
				
			}
			
		?>   
	 
    <!--<div class='modal fade' id='signin-modal' tabindex='-1' role='dialog'>
      <div class='modal-dialog modal-dialog-centered' role='document'>
        <div class='modal-content'>
				
          <div class='modal-header'>
            
						<ul class='nav nav-tabs card-header-tabs' role='tablist'>
              <li class='nav-item'>
								<a class='nav-link active' href='#signin-tab' data-toggle='tab' role='tab' aria-selected='true'><i class='fas fa-sign-in-alt'></i>&nbsp;&nbsp;Já sou cadastrado</a>
							</li>
              <li class='nav-item'>
								<a class='nav-link' href='#signup-tab' data-toggle='tab' role='tab' aria-selected='false'><i class='fas fa-user-plus'></i>&nbsp;&nbsp;Não possuo cadastro</a>
							</li>
            </ul>
						
            <button class='close' type='button' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
						
          </div>
					
          <div class='modal-body tab-content py-4'>
            
						<form name='prs' class='needs-validation tab-pane fade show active' method='POST' autocomplete='off' id='signin-tab' action='header.php'>
						
              <div class='form-group'>
                <label for='si-email'>Email</label>
                <input class='form-control' type='email' id='si-email' name='email' placeholder='Ex: gabrielreis@hotmail.com' required />
                <div class='invalid-feedback'>Entre com um email válido!</div>
              </div>
							
              <div class='form-group'>
                <label for='si-password'>Senha</label>
                <div class='password-toggle'>
                  <input class='form-control' type='password' id='si-password' name='senha' required />
									<div class='invalid-feedback'>Digite sua senha pessoal!</div>
                  <label class='password-toggle-btn'>
                    <input class='custom-control-input' type='checkbox' /><i class='far fa-eye password-toggle-indicator'></i><span class='sr-only'>Mostrar</span>
                  </label>
                </div>
              </div>
							
              <div class='form-group d-flex flex-wrap justify-content-between'>
                <div class='custom-control custom-checkbox mb-2'>
                  <input class='custom-control-input' type='checkbox' id='si-remember' />
                  <label class='custom-control-label' for='si-remember'>Lembrar-me neste dispositivo</label>
                </div>
								
								<a class='font-size-sm' href='account-password-recovery.php'>Esqueci a senha</a>
								
              </div>
							<input type='hidden' name='action' value='LOGIN'/>
              <button class='btn btn-primary btn-block btn-shadow' type='submit'>Entrar&nbsp;&nbsp;<i class='fas fa-sign-in-alt'></i></button>
							
            </form>
						
            <form class='needs-validation tab-pane fade' method='POST' autocomplete='off' id='signup-tab' action='header.php'>
						
              <div class='form-group'>
                <label for='su-name'>Nome Completo</label>
                <input class='form-control' type='text' id='su-name' name='ctmnome' placeholder='Ex: Gabriel Reis' maxlength='100' required />
                <div class='invalid-feedback'>Informe seu nome completo!</div>
              </div>
							
              <div class='form-group'>
                <label for='su-email'>Email</label>
                <input class='form-control' type='email' id='su-email' name='ctmemail' placeholder='Ex: gabrielreis@hotmail.com' maxlength='100' required />
                <div class='invalid-feedback'>Entre com um email válido!</div>
              </div>
							
							<div class='form-group'>
                <label for='su-tel'>Celular</label>
                <input class='form-control' type='tel' id='su-tel' name='ctmtel1' placeholder='Ex: 31996687628' maxlength='11' required />
                <div class='invalid-feedback'>Informe seu telefone!</div>
              </div>
							
							<div class='form-group'>
                <label for='su-cep'>CEP</label>
                <input class='form-control' type='tel' id='su-cep' name='ctmcep' placeholder='Ex: 30575360' />
                <div class='invalid-feedback'>Informe seu CEP!</div>
              </div>
							
              <div class='form-group'>
                <label for='su-password'>Senha</label>
                <div class='password-toggle'>
                  <input class='form-control' type='password' id='su-password' name='usersenha1' maxlength='11' required />
                  <label class='password-toggle-btn'>
                    <input class='custom-control-input' type='checkbox' /><i class='far fa-eye password-toggle-indicator'></i><span class='sr-only'>Mostrar</span>
                  </label>
                </div>
              </div>
							
              <div class='form-group'>
                <label for='su-password-confirm'>Confirme Senha</label>
                <div class='password-toggle'>
                  <input class='form-control' type='password' id='su-password-confirm' name='usersenha2' maxlength='11' required />
                  <label class='password-toggle-btn'>
                    <input class='custom-control-input' type='checkbox' /><i class='far fa-eye password-toggle-indicator'></i><span class='sr-only'>Mostrar</span>
                  </label>
                </div>
              </div>
							
							<input type='hidden' name='ctmidentificador' value='<php echo uniqid();?>'/>
							<input type='hidden' name='action' value='CADASTRAR'/>
							
              <button class='btn btn-primary btn-block btn-shadow' type='submit'>Confirmar</button>
							
            </form>
						
          </div>
        </div>
      </div>
    </div>		    
		
    <!-- Navbar 3 Level (Light)-->
    <header class='box-shadow-sm'>      
      
      <div class='navbar-sticky bg-light'>
        <div class='navbar navbar-expand-lg navbar-light'>
          <div class='container'>
					
						<a class='navbar-brand d-none d-lg-block mr-3 flex-shrink-0' href='#' style='min-width: 7rem;'><img width='142' src='img/logo-horizontal.png' alt='BABADROOM'/></a>
						<a class='navbar-brand d-lg-none mr-2' href='#' style='min-width: 2.125rem;'><img width='142' src='img/logo-horizontal.png' alt='BABADROOM'/></a>
            
						<!--<div class='input-group-overlay d-none d-lg-flex mx-4'>
              <input class='form-control appended-form-control' type='text' placeholder='Encontrar na loja...'>
              <div class='input-group-append-overlay'><span class='input-group-text'><i class='fas fa-search'></i></span></div>
            </div>-->
						
            <div class='navbar-toolbar d-flex flex-shrink-0 align-items-center'>
              <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarCollapse3'><span class='navbar-toggler-icon'></span></button>
							
							<a class='navbar-tool navbar-stuck-toggler ml-1 mr-2' href='#'>
                <div class='navbar-tool-icon-box'><i class='navbar-tool-icon fas fa-bars'></i></div>               
							</a>							
							
							<?php 
								
							echo "
							<a class='navbar-tool ml-1 ml-lg-0 mr-n1 mr-lg-2' href='$HREF'>
								<div class='navbar-tool-icon-box bg-secondary' data-toggle='tooltip' data-placement='left' title='$USERSESSIONNOME\n$USERSESSIONEMAIL\n$USERSESSIONTEL'><i class='navbar-tool-icon fas fa-user' style='color: $IconUserColor;'></i></div>
								<div class='navbar-tool-text ml-n2'><small>Olá <b>$FirstNameSession</b></small>$MSGlogado</div>
							</a>"; 
							
							$TotalItensCesta = NULL;
							$ValorTotalCesta = NULL;
							$SiglaMoeda      = NULL;
							$MSGcesta        = "Sua cesta está vazia.";
							$CESTAtotal      = 0;
							
							//Buscar os ítens da Cesta em sessão, criada ao adicionar o primeiro ítem...
							if(isset($_SESSION['bbdcartkey'])){
								
								$cart_session = $_SESSION['bbdcartkey'];							
								
								$CESTAselect = mysqli_query($con,"SELECT CARTID FROM carts WHERE CARTSESSION = '$cart_session'") or print(mysqli_error());
								$CESTAtotal  = mysqli_num_rows($CESTAselect);								
								
								if($CESTAtotal > 0){ 								
									
									$SUMcesta = mysqli_query($con,"SELECT SUM(CARTQTDEITEM) AS QTDETOTAL, SUM(CARTQTDEITEM*CARTVALORITEM) AS VLTOTALCESTA FROM carts WHERE CARTSESSION = '$cart_session'") or print(mysqli_error());
									
									while($PRICErow = mysqli_fetch_assoc($SUMcesta)){
										$TotalItensCesta = "<span class='navbar-tool-label'>".$PRICErow["QTDETOTAL"]."</span>"; 
										$ValorTotalCesta = $PRICErow["VLTOTALCESTA"];
									}								
									
									$MSGcesta = "Ítens em sua cesta";
									
								}
							
							}
							
							echo "
							
              <div class='navbar-tool dropdown ml-3'>
								<a class='navbar-tool-icon-box bg-secondary dropdown-toggle' href='cart.php'>$TotalItensCesta<i class='navbar-tool-icon fas fa-shopping-bag fas-navbar'></i></a>
								<a class='navbar-tool-text' href='cart.php'><small>$MSGcesta</small>$SiglaMoeda $ValorTotalCesta</a>";
										
									//LOOP cesta...
									
									if($CESTAtotal > 0){
										
										$CARTHEADERselect = mysqli_query($con,"SELECT * FROM carts WHERE CARTSESSION = '$cart_session'");
									
										echo "						
										<div class='dropdown-menu dropdown-menu-right' style='width: 20rem;'>
											<div class='widget widget-cart px-3 pt-2 pb-3'>
												<div style='height: 15rem;' data-simplebar data-simplebar-auto-hide='false'>";
												
												while($ITEMcesta = mysqli_fetch_assoc($CARTHEADERselect)){

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
															
															$FotoPrincipal = "<img width='64' src='img/ProdutoSemFoto.png' alt='Produto Sem Foto'/>";
															
														}else{
														
															//Foto Principal
														
															$PXPselect = mysqli_query($con,"SELECT PXPNOMEARQUIVO FROM productxpictures WHERE PRDCOD = '$PRDCOD' ORDER BY PXPSEQUENCIA LIMIT 0,1");
															$PXPexiste = mysqli_num_rows($PXPselect);
															
															if($PXPexiste > 0){
																
																while($PXPcol = mysqli_fetch_array($PXPselect)){
																	$PXPNOMEARQUIVO = $PXPcol["PXPNOMEARQUIVO"];
																}
																
																$FotoPrincipal = "<img width='64' src='img/shop/catalog/$PXPNOMEARQUIVO' alt='Foto principal' />";
																
															}
															
														}						
														
													}
													
													echo 	"											  
									
													
													
													<div class='widget-cart-item py-2 border-bottom'>
														
														<div class='media align-items-center'><a class='d-block mr-2' href='#'>$FotoPrincipal</a>
															<div class='media-body'>
																<h6 class='widget-product-title'><a href='#'>$PRDNAME</a></h6>
																<div class='widget-product-meta'><span class='text-accent mr-2'><small>R$</small>$ValorItem</span><span class='text-muted'>x $CARTQTDEITEM</span></div>
															</div>
														</div>
													</div>";
												
												}//Fim do loop
												
												echo "
											</div>
											
											<div class='d-flex flex-wrap justify-content-between align-items-center py-3'>
												<div class='font-size-sm mr-2 py-2'><span class='text-muted'>Subtotal:</span><span class='text-accent font-size-base ml-1'><small>R$</small>$ValorTotalCesta</span></div>
												<a class='btn btn-outline-secondary btn-sm' href='cart.php'><i class='fas fa-expand-arrows-alt'></i>&nbsp;&nbsp;Expandir</a>
											</div>
											
											<a class='btn btn-primary btn-sm btn-block' href='checkout'><i class='fas fa-cash-register' mr-2 font-size-base align-middle'></i>&nbsp;&nbsp;Finalizar Compra</a>
											
										</div>
									</div>";						
								
									}
							
								echo "
								
              </div>";
							
							?>
							
            </div>
          </div>
        </div>
				
        <div class='navbar navbar-expand-lg navbar-light navbar-stuck-menu mt-n2 pt-0 pb-2'>
          <div class='container'>
            <div class='collapse navbar-collapse' id='navbarCollapse3'>
              
							<!-- Search-->
              <div class='input-group-overlay d-lg-none my-3'>
                <div class='input-group-prepend-overlay'><span class='input-group-text'><i class='fas fa-search'></i></span></div>
                <input class='form-control prepended-form-control' type='text' placeholder='Localizar produto...'>
              </div>
							
              <!-- Primary menu-->
              <ul class='navbar-nav'>
                <li class='nav-item active'><a class='nav-link' href='index.php'>Home</a></li>
								<li class='nav-item'><a class='nav-link' href='about.php'>Quem somos</a></li>								
                
								<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>Navegar</a>
								
                  <div class='dropdown-menu p-0'>
                    <div class='d-flex flex-wrap flex-md-nowrap px-2'>
										
											<div class='py-4 px-3' style='width: 15rem;'>
                        <div class='widget widget-links'>
                          <h6 class='font-size-base mb-3'>Segmentos</h6>
                          <ul class='widget-list'>                            
													  <li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=SEG&seg=SEGSHOP001'>Moda Bebê</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=SEG&seg=SEGSHOP002'>Moda Gestante</a></li>														
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=SEG&seg=SEGSHOP003'>Moda Mamãe & Bebê</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=SEG&seg=SEGSHOP004'>Moda Papai & Bebê</a></li>													
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=SEG&seg=SEGSHOP005'>Moda Infantil</a></li>
                          </ul>
                        </div>
                      </div>
                      
											<div class='py-4 px-3' style='width: 15rem;'>
                        <div class='widget widget-links'>
                          <h6 class='font-size-base mb-3'>Gêneros</h6>
                          <ul class='widget-list'>
                            <li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=GEN&gen=M'>Menino</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=GEN&gen=F'>Menina</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=GEN&gen=N'>Neutro</a></li>
                          </ul>
                        </div>
                      </div>
											
											<div class='py-4 px-3' style='width: 15rem;'>
                        <div class='widget widget-links'>
                          <h6 class='font-size-base mb-3'>Categorias</h6>
                          <ul class='widget-list'>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=CAT&cat=PM'>Prematuro</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=CAT&cat=RN'>Recém-nascido</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=CAT&cat=36'>3 a 6 meses</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=CAT&cat=712'>7 a 12 meses</a></li>
														<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=CAT&cat=1324'>1 a 2 anos</a></li>													
                          </ul>
                        </div>
                      </div>							
											
											<div class='py-4 px-3' style='width: 15rem;'>
                        <div class='widget widget-links'>
                          <h6 class='font-size-base mb-3'>Departamentos</h6>
														<ul class='widget-list'>
													
															<?php 

															$GRPDEPTselect = mysqli_query($con,"SELECT GRPCOD,GRPNAME,GRPPATHIMG FROM groups WHERE GRPAPPLY = 'DEPT' AND GRPSTATUS = 1") or print (mysqli_error());
															$GRPDEPTtotal  = mysqli_num_rows($GRPDEPTselect);
															
															if($GRPDEPTtotal > 0){
																
																while($GRPDEPTrow = mysqli_fetch_array($GRPDEPTselect)){
																	
																	$GRPDEPTCOD     = $GRPDEPTrow["GRPCOD"];
																	$GRPDEPTNAME    = $GRPDEPTrow["GRPNAME"];
																	$GRPDEPTPATHIMG = $GRPDEPTrow["GRPPATHIMG"];
																	
																	echo "<li class='widget-list-item pb-1'><a class='widget-list-link' href='index.php?get=DEPT&dept=$GRPDEPTCOD'>$GRPDEPTNAME</a></li>";																									
																
																}
																
															}

															?>
															
															<li class='widget-list-item pb-1'><a class='widget-list-link' href='shop-departments.php'>... Ver tudo</a></li>
														
														</ul>
												</div>
											</div>                    
											
                      <div class='py-4 pr-3' style='width: 15rem;' data-toggle='tooltip' data-placement='left' title='Lindo Produto em Liquidação. Clique para ver detalhes...'><a class='d-block' href='shop-single.php?prd=010203'><img src='img/shop/promo-menu.jpg' alt='Promo banner'/></a></div>
											
                    </div>
                  </div>
									
                </li>
							  
								<li class='nav-item'><a class='nav-link' href='wa'>Atendimento</a></li>			
								<!--<li class='nav-item dropdown'>
									<a class='nav-link dropdown-toggle' href='wa' data-toggle='dropdown'>Atendimento</a>
									<ul class='dropdown-menu'>
										<li><a class='dropdown-item' href='help-topics.html'>Dúvidas Frequentes</a></li>
										<li><a class='dropdown-item' href='contact.html'>Contate-nos</a></li>
										<li><a class='dropdown-item' href='help-submit-request.html'>Avaliar-nos</a></li>
									</ul>
								</li>-->
								
								<?php 
								
									if($LOGADO){
										
										echo "									
										<li class='nav-item dropdown'>
											<a class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>Conta</a>
											<ul class='dropdown-menu'>												
												<li><a class='dropdown-item' href='order-tracking.php'>Seus Pedidos</a></li>							
												<li><a class='dropdown-item' href='logout.php?id_session=$id_session'>LogOut</a></li>                   
											</ul>
										</li>";
									
									}
								
								?>
								
              </ul>
            </div>
          </div>
        </div>
      </div>
    </header>