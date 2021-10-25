<?php include 'head.php';?>

	<script type="text/javascript">
		
		function confirma_inativar(){
			if(confirm('ATENÇÃO: DESASSOCIAR ESTA MEDIDA DESTE PRODUTO? \n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: REASSOCIAR ESTA MEDIDA A ESTE PRODUTO?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

	</script> 
	
	<body>
		<div class='container-fluid'>
	
			<?php 
			
			$PRDCOD = $_POST['prdcod'];
			
			$PRDselect = mysqli_query($con,"SELECT * FROM products WHERE PRDCOD = '$PRDCOD'");
			
			while($PRDrow = mysqli_fetch_assoc($PRDselect)){					
				
				$PRDNAME        = $PRDrow["PRDNAME"];
				$PRDDESCRIPTION = $PRDrow["PRDDESCRIPTION"];		
						
			}
			
			echo "

			<div class='panel panel-default'>
				<div class='panel-heading'>
					<span class='subpageName'><i class='fa fa-bar-chart' aria-hidden='true'></i>&nbsp;&nbsp;Tamanhos associados ao Produto <b>$PRDNAME ($PRDCOD)</b></span>

					<ul class='pull-right list-inline'>
						<li><a href='form_cad_products.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
				</div>

				<div class='panel-body'>";
			
					if (isset($_POST['acao'])){
						$acao = $_POST['acao'];
						
						if ($acao == 'INATIVAR'){
							
							$PXSID = $_POST['pxsid'];
							$PXSupdate = mysqli_query($con,"UPDATE productxsizes SET PXSSTATUS = 0, PXSDTSTATUS = '$datetime', PXSUSRSTATUS = '$ident_session' WHERE PXSID = $PXSID") or print(mysqli_error());
							
							if($PXSupdate){
								echo "<div id='alert' align='center' class='success'>Tamanho desassociada com sucesso.</div>";
								
							}else{
								echo "<div align='center' class='error'>Tamanho não foi desassociada.</div>";
							}
							
						}
						
						if ($acao == 'ATIVAR'){
							
							$PXSID = $_POST['pxsid'];
							$PXSupdate = mysqli_query($con,"UPDATE productxsizes SET PXSSTATUS = 1, PXSDTSTATUS = '$datetime', PXSUSRSTATUS = '$ident_session' WHERE PXSID = $PXSID") or print(mysqli_error());
							
							if($PXSupdate){
								echo "<div id='alert' align='center' class='success'>Tamanho associado com sucesso.</div>";
								
							}else{
								echo "<div align='center' class='error'>Tamanho não foi associada.</div>";
							}
							
						}					
						
						if ($acao == 'ASSOCIAR'){
							
							if(!isset($_POST['sizecod'])){
								echo "<div align='center' class='alert alert-warning'>Nenhum tamanho selecionado!</div>";
								
							}else{
							
								foreach($_POST['sizecod'] AS $SIZECOD){
								
									$PXSverifica = mysqli_query($con,"SELECT PXSID FROM productxsizes WHERE PRDCOD = '$PRDCOD' AND SIZECOD = '$SIZECOD'");
									$PXSexiste = mysqli_num_rows($PXSverifica);
									
									if($PXSexiste > 0){ //Tratamento para repetição do Browser...
										
									}else{

										$PXSinsert = mysqli_query($con,"INSERT INTO productxsizes (PRDCOD,SIZECOD,PXSUSRSTATUS,PXSCADBY) VALUES ('$PRDCOD','$SIZECOD','$ident_session','$ident_session')") or print (mysqli_error());

									}						
								}							
						
								if($PXSinsert){
									
									echo "<div id='alert' align='center' class='success'>Tamanhos associados com sucesso.</div>";
								
								}

							}				
								
						}
						
					}//Fim de ações

					$PXSselect = mysqli_query($con,"SELECT * FROM productxsizes WHERE PRDCOD = '$PRDCOD'");
					$PXStotal  = mysqli_num_rows($PXSselect);

					if($PXStotal > 0){
						echo "
						<table width='100%' id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
							<thead>							
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='20%'><div align='center'>Código     </div></th>
								<th width='20%'><div align='center'>Name       </div></th>
								<th width='35%'><div>Descrição                 </div></th>
								<th width='20%'><div align='center'>Aplicável a</div></th>
								<th width='3%' data-orderable='false'> 							 </th>						
							</thead>
							<tbody>";
								
							while($PXSrow = mysqli_fetch_array($PXSselect)){
														
								$PXSID        = $PXSrow["PXSID"];
								$PXSSTATUS    = $PXSrow["PXSSTATUS"];
								$PXSDTSTATUS  = $PXSrow["PXSDTSTATUS"];
								$PXSUSRSTATUS = $PXSrow["PXSUSRSTATUS"];
								
								$dtstatus = date("d/m/Y H:i", strtotime($PXSDTSTATUS));
								
								$SIZECOD = $PXSrow["SIZECOD"];
								
								$SIZEselect = mysqli_query($con,"SELECT * FROM sizes WHERE SIZECOD = '$SIZECOD'");
								
								while($SIZErow = mysqli_fetch_array($SIZEselect)){
								
									$SIZEAPLICAVEL = $SIZErow["SIZEAPLICAVEL"];
									$SIZENAME      = $SIZErow["SIZENAME"];
									$SIZEDESC      = $SIZErow["SIZEDESC"];							
									
								}	
									
								switch($PXSSTATUS){
									case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO. Desassociada em $dtstatus por $PXSUSRSTATUS'/>"; break;
									case 1: $situacao = "<img src='../images/status/VERDE.png'    title='ATIVO. Associada em $dtstatus por $PXSUSRSTATUS'/>"; break;
								}
									
								echo "
								<tr>
									<td><div align='center'>$situacao</div></td>
									<td><div align='center'><b>$SIZECOD</b></div></td>
									<td><div align='center'>$SIZENAME</div></td>
									<td><div>$SIZEDESC</div></td>
									<td><div align='center'>$SIZEAPLICAVEL</div></td>
									<td>
										<div id='cssmenu' align='center'>
											<ul>
												<li class='has-sub'><a href='#'><span></span></a>
													<ul>";
										
														if($PXSSTATUS == 1){
															
															echo"
															<li>
																<form name='inativar' method='POST' action='product_sizes.php' onsubmit='return confirma_inativar();'>
																	<input name='pxsid'  type='hidden' value='$PXSID'/>
																	<input name='prdcod' type='hidden' value='$PRDCOD'/>
																	<input name='acao'   type='hidden' value='INATIVAR'/>
																	<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
																</form>
															</li>";
														
														}
														
														if($PXSSTATUS == 0){
															
															echo"
															<li>
																<form name='ativar' method='POST' action='product_sizes.php' onsubmit='return confirma_ativar();'>
																	<input name='pxsid'  type='hidden' value='$PXSID'/>
																	<input name='prdcod' type='hidden' value='$PRDCOD'/>
																	<input name='acao'   type='hidden' value='ATIVAR'/>
																	<button class='btn btn-default btn-xs' title='ATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
																</form>
															</li>";
															
														}
														
														echo"
													</ul>
												</li>
											</ul>
										</div>
									</td>
								</tr>";				
						
							}
						
						echo "<tbody>
						</table>";
					
					}else{
						
						echo "<div align='center' class='info'>Nenhum tamanho associado a este produto!</div>
						<div align='center'>
							<a href='#associar' class='btn btn-info btn-sm' data-toggle='modal'><i class='fa fa-plus'></i>&nbsp;&nbsp;Associar Tamanho</a>
						</div>";
						
					} 
					
					?>
				
				</div>
			</div>
			
			<div id='associar' class='modal fade' role='dialog'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
							<h4 class='modal-title'>Associar Size ao Produto <b><?php echo $PRDCOD;?></b></h4>
						</div>
												
						<form class='form-horizontal' id='sizes' name='sizes' method='POST' action='product_sizes.php'>
							<div class='modal-body'>
							
								<?php
												
									$NEWSIZEselect = mysqli_query($con,"SELECT * FROM sizes WHERE SIZESTATUS = 1 AND SIZECOD NOT IN (SELECT SIZECOD FROM productxsizes WHERE PRDCOD = '$PRDCOD')");
									$NEWSIZEtotal  = mysqli_num_rows($NEWSIZEselect);
									
									if($NEWSIZEtotal > 0){
										echo "
										<table class='table table-condensed table-action table-striped'>
											<tbody>";
										
											while($NEWSIZErow = mysqli_fetch_array($NEWSIZEselect)){
												
												$NEWSIZECOD  = $NEWSIZErow["SIZECOD"];
												$NEWSIZENAME = $NEWSIZErow["SIZENAME"];
												$NEWSIZEDESC = $NEWSIZErow["SIZEDESC"];	
												
												echo "
												<tr>
													<td width='10%'><div align='center'><input class='inputcheck checar' name='sizecod[]' type='checkbox' value='$NEWSIZECOD' /></div></td>
													<td width='10%' style='background-size: $NEWSIZECOD;'></td>
													<td width='10%'><div align='center'><b>$NEWSIZECOD</b></div></td>
													<td width='35%'><div align='center'>$NEWSIZENAME</div></td>
													<td width='35%'><div><small>$NEWSIZEDESC</small></div></td>
												</tr>";
												
											}
											
											echo "</tbody>
										</table>";
									
									}else{
										echo "<div align='center' class='info'>Nenhum tamanho disponível para associação.</div>";
									}
									
								?>								
								
							</div>
							
							<div class='modal-footer'>
								<div align='right'><button type='submit' class='btn btn-info btn-sm'>Confirmar</button></div>
							</div>
							
							<input name='prdcod' type='hidden' value='<?php echo $PRDCOD;?>'/>
							<input name='acao'   type='hidden' value='ASSOCIAR'/>	
						</form>
					</div>
				</div>
			</div>
	
		</div>
	</body>
</html>