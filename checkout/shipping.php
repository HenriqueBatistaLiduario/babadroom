	<?php include 'header.php'; 
	
		if($LOGADO == 0){
			echo "<script>location.href='../index.php';</script>";
		}
	
	  //Gera o Pedido de Venda...
		
		if(isset($_POST['action'])){
			
			$action    = $_POST['action'];
			$subaction = $_POST['subaction'];
			
			if($action == 'NEWSO'){
				
				$CTMNOME      = $_POST['ctmnome'];
				$CTMTEL       = $_POST['ctmtel'];
				$SOZIPCODE    = $_POST['cep'];
				
				$SOADDRESS    = $_POST['endereco'];
				$SONUMBER     = $_POST['sonumber'];
				$SOCOMPLEMENT = $_POST['socomplement'];
				$SODISTRICT   = $_POST['bairro'];
				$SOCITY       = $_POST['cidade'];
				$SOSTATE      = $_POST['estado'];
				$SOCOUNTRY    = $_POST['pais'];
				
				$SOCOMMENTS   = $_POST['socomments'];
				
				$SOCODE = $_POST['socode'];
				
				if($subaction == 'insert'){   
					
					mysqli_query($con,"INSERT INTO sales_orders(SOCODE,CARTKEY,CTMIDENTIFICADOR,CTMNOME,SOTEL,SOZIPCODE,SOADDRESS,SONUMBER,SOCOMPLEMENT,SODISTRICT,SOCITY,SOSTATE,SOCOUNTRY,SOCOMMENTS,SOUSRSTATUS,SOCREATEDBY)
					VALUES ('$SOCODE','$cart_session','$USERSESSIONID','$CTMNOME','$CTMTEL','$SOZIPCODE','$SOADDRESS','$SONUMBER','$SOCOMPLEMENT','$SODISTRICT','$SOCITY','$SOSTATE','$SOCOUNTRY','$SOCOMMENTS','$USERSESSIONID','$USERSESSIONID')") or print(mysqli_error($con));
				
				}
				
				if($subaction == 'update'){
					
					mysqli_query($con,"UPDATE sales_orders SET CTMNOME = '$CTMNOME',
					                                             SOTEL = '$CTMTEL',
																							     SOZIPCODE = '$SOZIPCODE',
																							     SOADDRESS = '$SOADDRESS',
																							      SONUMBER = '$SONUMBER',
																					      SOCOMPLEMENT = '$SOCOMPLEMENT',
																						      SODISTRICT = '$SODISTRICT',
																								      SOCITY = '$SOCITY',
																								     SOSTATE = '$SOSTATE',
																							     SOCOUNTRY = '$SOCOUNTRY',
																						      SOCOMMENTS = '$SOCOMMENTS' WHERE SOCODE = '$SOCODE'") or print(mysqli_error($con));
					
				}
				
				if($subaction == 'APLICAR_CUPOM'){
				
					$CUPOMVALIDO = false;
					
					$CUPOM = $_POST['cupom'];
					
					//Consultar a tabela de descontos válidos, e se válido, fazer o Update na SO... Por enquanto não será implantado
					
					if(!$CUPOMVALIDO){
						
						echo "<script>alert('CUPOM INVÁLIDO!');</script>";					
						
					}
					
				}			
				
			}		
			
		}

    //Cálculo do FRETE utilizando API dos CORREIOS...
		
		include 'CalculaFrete.php';    		
		
		$origem  = '30575360';
		$destino = $SOZIPCODE;
		
		//Obtém dados dimensionais dos ítens no Carrinho...		
		
		$DIMENSIONSselect = mysqli_query($con,"SELECT PRDCOD,CARTQTDEITEM FROM carts WHERE CARTSESSION = '$cart_session'");
		
		$PESOTOTAL   = 0;
		$ALTURATOTAL = 0;
		
		while($DIMENSIONScol = mysqli_fetch_array($DIMENSIONSselect)){
			
			$PRDCOD = $DIMENSIONScol["PRDCOD"];
			$CARTQTDEITEM = $DIMENSIONScol["CARTQTDEITEM"];
			
			$PRDDselect = mysqli_query($con,"SELECT PRDUNITWEIGHT,PRDUNITHEIGHT,PRDUNITWIDTH,PRDUNITLENGTH FROM products WHERE PRDCOD = '$PRDCOD'");
			
			while($PRDDcol = mysqli_fetch_array($PRDDselect)){
			
				$PRDUNITWEIGHT = $PRDDcol["PRDUNITWEIGHT"]; //Peso Unitário do Ítem
				$PRDUNITHEIGHT = $PRDDcol["PRDUNITHEIGHT"]; //Altura Unitária do Ítem
				$PRDUNITWIDTH  = $PRDDcol["PRDUNITWIDTH"];  //Largura Unitária do Ítem
				$PRDUNITLENGTH = $PRDDcol["PRDUNITLENGTH"]; //Comprimento Unitário do Ítem
				
				//Peso multiplica Unitário X Quantidade
				
				$PESOTOTALITEM = $PRDUNITWEIGHT*$CARTQTDEITEM;
				
				//Altura multiplica Unitário X Quantidade, e se for inferior a 15 lança como 15, que é a dimensão mínima permitida pelos Correios.
				
				$ALTURATOTALITEM = $PRDUNITHEIGHT*$CARTQTDEITEM;
			
			}
			
			$PESOTOTAL += $PESOTOTALITEM;
			$ALTURATOTAL += $ALTURATOTALITEM;
			
		}
		
		if($ALTURATOTAL < 15){ $ALTURATOTAL = 15; }
		
		//Largura e Comprimento não multiplicar, considerar as informações do maior ítem adquirido.
		
		$PRDD2select = mysqli_query($con,"SELECT MAX(PRDUNITWIDTH) AS LARGURA, MAX(PRDUNITLENGTH) AS COMPRIMENTO FROM products WHERE PRDCOD IN(SELECT PRDCOD FROM carts WHERE CARTSESSION = '$cart_session')");
		
		while($PRDD2col = mysqli_fetch_array($PRDD2select)){
			
			$LARGURA = $PRDD2col["LARGURA"];
			$COMPRIMENTO = $PRDD2col["COMPRIMENTO"];
			
		}

		$peso        = $PESOTOTAL;
		$altura      = $ALTURATOTAL;
		$largura     = $LARGURA;
		$comprimento = $COMPRIMENTO;
		
		$SEDEX10 = false;

		$PACcalculo = calculaPAC( $origem, $destino, $peso, $altura, $largura, $comprimento, 0 );
		
		$PACVALOR = $PACcalculo['valor'];
		$PACPRAZO = $PACcalculo['prazo'];
		
		$SEDEXcalculo = calculaSEDEX( $origem, $destino, $peso, $altura, $largura, $comprimento, 0 );
		
		$SEDEXVALOR = $SEDEXcalculo['valor'];
		$SEDEXPRAZO = $SEDEXcalculo['prazo'];
		
		$SEDEX10calculo = calculaSEDEX10( $origem, $destino, $peso, $altura, $largura, $comprimento, 0 );
		
		$SEDEX10VALOR = $SEDEX10calculo['valor'];
		$SEDEX10PRAZO = $SEDEX10calculo['prazo']; 
		
		if($SEDEX10VALOR != NULL AND $SEDEX10PRAZO != NULL){
			
			$SEDEX10 = true;
			
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
		
		<div class='container pb-5 mb-2 mb-md-4'>
      <div class='row'>
        <section class='col-lg-8'>
          
					<!-- Steps-->
          <div class='steps steps-light pt-2 pb-3 mb-5'>
					
					  <a class='step-item active' href='../cart.php'>
							<div class='step-progress'><span class='step-count'>1</span></div>
							<div class='step-label'><i class='fas fa-shopping-bag'></i>&nbsp;Cesta</div>
						</a>
						
						<a class='step-item active' href='details.php?so=<?php echo $SOCODE;?>'>
							<div class='step-progress'><span class='step-count'>2</span></div>
							<div class='step-label'><i class='fas fa-user'></i>&nbsp;Destino</div>
						</a>
						
						<a class='step-item active current' href=''>
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

          <div class='d-sm-flex justify-content-between align-items-center bg-secondary p-4 rounded-lg mb-grid-gutter'>
						
						<div class='media align-items-center'>
						
							<div class='img-thumbnail rounded-circle position-relative' style='width: 6.375rem;'>
								<img class='rounded-circle' src='../img/shop/account/avatar.jpg' alt='<?php echo $USERSESSIONNOME;?>'/> 
							</div>
							
							<div class='media-body pl-3'>								  
								<h3 class='font-size-base mb-0'><?php echo "Destinatário: <b>$CTMNOME</b></h3>
								<span class='text-accent font-size-sm'>$SOADDRESS, $SONUMBER $SOCOMPLEMENT, $SODISTRICT<br>$SOCITY/$SOSTATE - $SOZIPCODE $SOCOUNTRY</span>
								<p class='font-size-xs text-muted mb-4'>$SOCOMMENTS</p>";?>
							</div>
							
						</div>
						
						<a class='btn btn-light btn-sm btn-shadow mt-3 mt-sm-0' href='details.php?so=<?php echo $SOCODE;?>'><i class='fas fa-edit mr-2'></i>&nbsp;&nbsp;Editar Endereço</a>
						
					</div>					
					
          <!-- Shipping methods table-->
          <h2 class='h6 pb-3 mb-2'>Escolha o método de envio</h2>
					
					<form id='metodo-envio' method='POST' action='payment.php'>
					
						<div class='table-responsive'>
						
							<table class='table table-hover font-size-sm border-bottom'>
							
								<thead>
									<tr>
										<th class='align-middle'></th>
										<th class='align-middle'>Método de Envio</th>
										<th class='align-middle'>Prazo estimado</th>
										<th class='align-middle'>Taxas adicionais</th>
									</tr>
								</thead>
								
								<tbody>
								
									<tr>
										<td>
											<div class='custom-control custom-radio mb-4'>
												<input class='custom-control-input' type='radio' id='pac' name='somethod' value='PAC' checked />
												<label class='custom-control-label' for='pac'></label>
											</div>
										</td>
										<td class='align-middle'><span class='text-dark font-weight-medium'>PAC</span><br><span class='text-muted'></span></td>
										<td class='align-middle'><?php echo "$PACPRAZO dias úteis";?></td>
										<td class='align-middle'><?php echo "R$ $PACVALOR";?></td>
									</tr>
									
									<tr>
									
										<td>
											<div class='custom-control custom-radio mb-4'>
												<input class='custom-control-input' type='radio' id='sedex' name='somethod' value='SEDEX' />
												<label class='custom-control-label' for='sedex'></label>
											</div>
										</td>
										<td class='align-middle'><span class='text-dark font-weight-medium'>SEDEX</span><br><span class='text-muted'></span></td>
										<td class='align-middle'><?php echo "$SEDEXPRAZO dias úteis";?></td>
										<td class='align-middle'><?php echo "R$ $SEDEXVALOR";?></td>
										
									</tr>
									
									<?php
									
									if($SEDEX10){
										
									  echo "
										<tr>
										
											<td>
												<div class='custom-control custom-radio mb-4'>
													<input class='custom-control-input' type='radio' id='sedex10' name='somethod' value='SEDEX10' />
													<label class='custom-control-label' for='sedex10'></label>
												</div>
											</td>
											<td class='align-middle'><span class='text-dark font-weight-medium'>SEDEX10</span><br><span class='text-muted'></span></td>
											<td class='align-middle'>$SEDEX10PRAZO dias úteis</td>
											<td class='align-middle'>R$ $SEDEX10VALOR</td>
											
										</tr>";
									
									}								
									
									if($SOCITY == 'Belo Horizonte' OR $SOCITY == 'BH' OR $SOCITY == 'BELO HORIZONTE'){
										
										echo "
										<tr>
																
											<td>
												<div class='custom-control custom-radio mb-4'>
													<input class='custom-control-input' type='radio' id='loggi' name='somethod' value='LOGGI'>
													<label class='custom-control-label' for='loggi'></label>
												</div>
											</td>
											
											<td class='align-middle'><span class='text-dark font-weight-medium'>LOGGI (BH)</span><br><span class='text-muted'>Motoboy credenciado</span></td>
											<td class='align-middle'>Em até 48h</td>
											<td class='align-middle'>R$15.00</td>
											
										</tr>
										
										<tr>
										
											<td>
												<div class='custom-control custom-radio mb-4'>
													<input class='custom-control-input' type='radio' id='pickup' name='somethod' value='CLRETIRA'>
													<label class='custom-control-label' for='pickup'></label>
												</div>
											</td>
											
											<td class='align-middle'><span class='text-dark font-weight-medium'>Retirar na loja BURITIS</span><br><span class='text-muted'>Dias úteis de 09:00 às 20:00</span></td>
											<td class='align-middle'>&mdash;</td>
											<td class='align-middle'>0,00 GRÁTIS!</td>
											
										</tr>";
										
									}
								
								?>									
									
								</tbody>
								
							</table>
							
						</div>
						
						<div class='d-sm-flex justify-content-between align-items-center bg-secondary p-4 rounded-lg mb-grid-gutter'>
						
							<div class='media align-items-center'>
							
								<div class='img-thumbnail position-relative' style='width: 6.375rem;'>
									<img src='../img/caixa-presente.png' alt='Caixa Presente'/> 
								</div>
								
								<div class='media-body pl-3'>								  
									<h3 class='font-size-base mb-0'>Embale sua encomenda em uma linda Caixa-Presente<br>
									<span class='text-accent font-size-sm'>A mamãe já recebe o presente prontinho,<br>sem nenhum trabalho adicional de sua parte.</span><br><br>
									<p class='font-size-xs text-muted mb-4'>Acrescenta R$ 15,90 ao valor final da compra</p>
								</div>
								
							</div>
							
							<input class='custom-control-input' type='checkbox' id='caixa-presente' name='caixa-presente'/>
						  <label class='custom-control-label' for='caixa-presente'>Quero!</label>
							
						</div>	
						
						<input type='hidden' name='pacvalor'     value='<?php echo $PACVALOR;?>'/>
						<input type='hidden' name='pacprazo'     value='<?php echo $PACPRAZO;?>'/>
						<input type='hidden' name='sedexvalor'   value='<?php echo $SEDEXVALOR;?>'/>
						<input type='hidden' name='sedexprazo'   value='<?php echo $SEDEXPRAZO;?>'/>
						<input type='hidden' name='sedex10valor' value='<?php echo $SEDEX10VALOR;?>'/>
						<input type='hidden' name='sedex10prazo' value='<?php echo $SEDEX10PRAZO;?>'/>
						
						<input type='hidden' name='so' value='<?php echo $SOCODE;?>'/>
						<input type='hidden' name='action' value='PAGAR'/>				
						
					</form>
					
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
								$ValorDesconto = "?";
								$ValorFrete    = "?";
								
								$SODISCOUNTselect = mysqli_query($con,"SELECT SODISCOUNT FROM sales_orders WHERE SOCODE = '$SOCODE'");
								
								while($SODcol = mysqli_fetch_array($SODISCOUNTselect)){
									
									$VALORDESCONTO = $SODcol["SODISCOUNT"];
									$ValorDesconto = number_format($VALORDESCONTO,2,",",".");
									
								}
								
								$VALORFRETE = 0;
								
								$VALORFINAL = ($SUBTOTAL - $VALORDESCONTO) + $VALORFRETE;
								
								$Subtotal   = number_format($SUBTOTAL,2,",",".");
								
								$ValorFinal = number_format($VALORFINAL,2,",",".");
							
							?>            
							
            </div>
						
            <ul class='list-unstyled font-size-sm pb-2 border-bottom'>
							<li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Subtotal:</span><span class='text-right'><small>R$</small><?php echo $Subtotal;?></span></li>
							<li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Desconto:</span><span class='text-right'><small>R$</small><?php echo "- $ValorDesconto";?></span></li>
              <li class='d-flex justify-content-between align-items-center'><span class='mr-2'>Frete:</span><span class='text-right'><small>R$</small><?php echo "+ $ValorFrete";?></span></li>              
            </ul>
						
            <h3 class='font-weight-normal text-center my-4'><small>R$</small><?php echo $ValorFinal;?></h3>
						
						<div class='accordion' id='order-options'>
							
              <div class='card'>
						
								<div class='card-header'>
									<h3 class='accordion-heading'>
										<a class='collapsed' href='#cupom-desconto' role='button' data-toggle='collapse' aria-expanded='true' aria-controls='shipping-estimates'>
											<i class='fas fa-ticket-alt'></i>&nbsp;&nbsp;Possui um Cupom?<span class='accordion-indicator'></span>
										</a>
									</h3>
								</div>
								
								<div class='collapse show' id='cupom-desconto' data-parent='#order-options'>
								
                  <div class='card-body'>
						
										<form class='needs-validation' method='POST' action='shipping.php'>
											<div class='form-group'>
												<input class='form-control' type='text' name='cupom' placeholder='Cupom de Desconto' required>
												<div class='invalid-feedback'>Informe um cupom válido!</div>
											</div>
											<input type='hidden' name='socode'    value='<?php echo $SOCODE;?>' />
											<input type='hidden' name='action'    value='NEWSO'/>
											<input type='hidden' name='subaction' value='APLICAR_DESCONTO'/>
											<button class='btn btn-outline-primary btn-block' type='submit'>Aplicar Desconto</button>
										</form>
										
									</div>
									
								</div>
										
							</div>
							
						</div>
						
						<a class='btn btn-primary btn-block' href='#' onclick="document.getElementById('metodo-envio').submit();"><span class='d-none d-sm-inline'>Seguir para Pagamento</span>
							<span class='d-inline d-sm-none'>Next</span><i class='fa fa-arrow-right mt-sm-0 ml-1'></i>
						</a>
						
          </div>
					
        </aside>
				
			</div>
		</div>