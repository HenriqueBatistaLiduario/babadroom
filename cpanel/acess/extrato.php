<?php include 'head.php'; ?>

<!DOCTYPE html>
<html>
  <head>
	  <title>Extrato</title>
		<meta name='viewport'    content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author'      content=''>
    <meta name='robots'      content='noindex, nofollow'>
		<meta charset='utf-8'
	</head>
	
	<body>

		<div id='bg-desktop' style='background-image: url(assets/images/other_images/fundo_carrinho.jpg);'></div>
		<div id='bg-mobile'  style='background-image: url(assets/images/other_images/fundo_carrinhoM.jpg);'></div>
    <div id='outer-container'>
			<?php include 'header.php';?>
			
			<section id='main-content' class='clearfix'>
			
				<article id='perfil' class='section-wrapper clearfix' data-custom-background-img='assets/images/other_images/bg3.jpg'>
					<div class='content-wrapper clearfix'>
            <div class='col-md-12 col-sm-12 pull-right'>
							<section class='feature-columns row clearfix'>
								<h1 class='section-title'><i class='fa fa-list' aria-hidden='true'></i>&nbsp;Extrato de Movimentações</h1>
								<h6>Acompanhe seu saldo de créditos através do seu extrato de movimentação no iLyptus...</h6>
								
								<?php
								
								$EXTselect = mysql_query("SELECT * FROM extratos WHERE IDUSUARIO = '$ident_session' ORDER BY EXTDATA") or print (mysql_error());
								$EXTtotal  = mysql_num_rows($EXTselect);
								
								if($EXTtotal > 0){
									echo"
									<table class='table-action table table-responsive table-bordered table-hover' style='border-right: none; border-left:none;'>
										<thead>
											<tr>
												
												<th width='10%'><div align='center'>ID         </div></th>
												<th width='20%'><div align='center'>Data Status</div></th>
												<th width='50%'><div>Detalhes                  </div></th>
												<th width='10%'><div align='center'>Tipo       </div></th>
												<th width='10%'><div align='center'>Valor      </div></th>
											</tr>
										</thead>
										<tbody style='background-color: transparent;'>";

									while ($linha = mysql_fetch_array($EXTselect)){
										$EXTID       = $linha["EXTID"];
										$IDANUNCIO   = $linha["IDANUNCIO"];
										$EXTTIPO     = $linha["EXTTIPO"];
										$EXTDETALHES = $linha["EXTDETALHES"];
										$EXTVALOR    = $linha["EXTVALOR"];
										$EXTDATA     = $linha["EXTDATA"];
										$EXTCADBY    = $linha["EXTCADBY"];
										
										$dataextrato = date("d/m/Y H:i", strtotime($EXTDATA));

										switch($EXTTIPO){					
											case 'C': $tipo = "<font color='green'>Crédito</font>"; break;									
											case 'D': $tipo = "<font color='red'>Débito</font>"; break;
										}
										
										$valorop = number_format($EXTVALOR,2,",",".");//Formatar o Valor.
										
										echo "
										
										<tr>
												
												<td><div align='center'><b>$EXTID</b></div></td>
												<td><div align='center'>$dataextrato </div></td>
												<td><div>$EXTDETALHES                </div></td>
												<td><div align='center'>$tipo        </div></td>
												<td><div align='center'>$valorop     </div></td>
											</tr>";
									}
									
									echo "</tbody>
									</table>";
									
								}else{
									echo "<div class='info' align='center'>Nenhuma movimentação registrada em seu Extrato.</div>";
								}
								
								$Cselect = mysql_query("SELECT SUM(EXTVALOR) AS CREDITOS FROM extratos WHERE IDUSUARIO = '$ident_session' AND EXTTIPO = 'C'") or print(mysql_error());
								$Dselect = mysql_query("SELECT SUM(EXTVALOR) AS DEBITOS FROM extratos WHERE IDUSUARIO = '$ident_session' AND EXTTIPO = 'D'") or print(mysql_error());
								
								$CREDITOS = mysql_result($Cselect,0,"CREDITOS");
								$DEBITOS  = mysql_result($Dselect,0,"DEBITOS");
								
								$SALDO = $CREDITOS - $DEBITOS;
								$saldoatual = number_format($SALDO,2,",",".");//Formatar o Valor.
								
								echo "<div align='right'><h2>Saldo: + <font style='font-size:12px;'>R$</font> $saldoatual</h2>"
								
								
								?>
								
								<div class='btn-group btn-group-sm'>
									<a href='transacoes.php'   class='btn btn-outline-inverse btn-sm'><i class='fa fa-shopping-cart' aria-hidden='true'></i>&nbsp;Voltar para Transações</a>
								</div>
									
							</section>
            </div>
          </div>
        </article>
			</section>
			
     <?php include 'footer.php';?>
     
		</div>
	</body>
</html>