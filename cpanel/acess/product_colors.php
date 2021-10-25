<?php include 'head.php';?>

	<script type="text/javascript">
		
		function confirma_inativar(){
			if(confirm('ATENÇÃO: DESASSOCIAR ESTA COR DESTE PRODUTO? \n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: REASSOCIAR ESTA COR A ESTE PRODUTO?\n\nClique em OK para continuar...'))
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
					<span class='subpageName'><i class='fa fa-paint-brush' aria-hidden='true'></i>&nbsp;&nbsp;Cores associadas ao Produto <b>$PRDNAME ($PRDCOD)</b></span>

					<ul class='pull-right list-inline'>
						<li><a href='form_cad_products.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
				</div>

				<div class='panel-body'>";
			
					if (isset($_POST['acao'])){
						$acao = $_POST['acao'];
						
						if ($acao == 'INATIVAR'){
							
							$PXCID = $_POST['pxcid'];
							$PXCupdate = mysqli_query($con,"UPDATE productxcolors SET PXCSTATUS = 0, PXCDTSTATUS = '$datetime', PXCUSRSTATUS = '$ident_session' WHERE PXCID = $PXCID") or print(mysqli_error());
							
							if($PXCupdate){
								echo "<div id='alert' align='center' class='success'>Cor desassociada com sucesso.</div>";
								
							}else{
								echo "<div align='center' class='error'>Cor não foi desassociada.</div>";
							}
							
						}
						
						if ($acao == 'ATIVAR'){
							
							$PXCID = $_POST['pxcid'];
							$PXCupdate = mysqli_query($con,"UPDATE productxcolors SET PXCSTATUS = 1, PXCDTSTATUS = '$datetime', PXCUSRSTATUS = '$ident_session' WHERE PXCID = $PXCID") or print(mysqli_error());
							
							if($PXCupdate){
								echo "<div id='alert' align='center' class='success'>Cor associada com sucesso.</div>";
								
							}else{
								echo "<div align='center' class='error'>Cor não foi associada.</div>";
							}
							
						}					
						
						if ($acao == 'ASSOCIAR'){
							
							if(!isset($_POST['colorcod'])){
								echo "<div align='center' class='alert alert-warning'>Nenhuma cor selecionada!</div>";
								
							}else{
							
								foreach($_POST['colorcod'] AS $COLORCOD){
								
									$PXCverifica = mysqli_query($con,"SELECT PXCID FROM productxcolors WHERE PRDCOD = '$PRDCOD' AND COLORCOD = '$COLORCOD'");
									$PXCexiste = mysqli_num_rows($PXCverifica);
									
									if($PXCexiste > 0){ //Tratamento para repetição do Browser...
										
									}else{

										$PXCinsert = mysqli_query($con,"INSERT INTO productxcolors (PRDCOD,COLORCOD,PXCUSRSTATUS,PXCCADBY) VALUES ('$PRDCOD','$COLORCOD','$ident_session','$ident_session')") or print (mysqli_error());

									}						
								}							
						
								if($PXCinsert){
									
									echo "<div id='alert' align='center' class='success'>Cores associadas com sucesso.</div>";
								
								}

							}				
								
						}
						
					}//Fim de ações

					$PXCselect = mysqli_query($con,"SELECT * FROM productxcolors WHERE PRDCOD = '$PRDCOD'");
					$PXCtotal  = mysqli_num_rows($PXCselect);

					if($PXCtotal > 0){
						echo "
						<table width='100%' id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
							<thead>							
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='5%'><div align='center'>            </div></th>
								<th width='20%'><div align='center'>Código     </div></th>
								<th width='20%'><div align='center'>Name       </div></th>
								<th width='30%'><div>Descrição                 </div></th>
								<th width='20%'><div align='center'>Aplicável a</div></th>
								<th width='3%' data-orderable='false'> 								 </th>						
							</thead>
							<tbody>";
								
							while($PXCrow = mysqli_fetch_array($PXCselect)){
														
								$PXCID        = $PXCrow["PXCID"];
								$PXCSTATUS    = $PXCrow["PXCSTATUS"];
								$PXCDTSTATUS  = $PXCrow["PXCDTSTATUS"];
								$PXCUSRSTATUS = $PXCrow["PXCUSRSTATUS"];
								
								$dtstatus = date("d/m/Y H:i", strtotime($PXCDTSTATUS));
								
								$COLORCOD     = $PXCrow["COLORCOD"];
								
								$COLORselect  = mysqli_query($con,"SELECT * FROM colors WHERE COLORCOD = '$COLORCOD'");
								
								while($COLORrow = mysqli_fetch_array($COLORselect)){
								
									$COLORAPLICAVEL = $COLORrow["COLORAPLICAVEL"];
									$COLORNAME      = $COLORrow["COLORNAME"];
									$COLORDESC      = $COLORrow["COLORDESC"];							
									
								}	
									
								switch($PXCSTATUS){
									case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='INATIVO. Desassociada em $dtstatus por $PXCUSRSTATUS'/>"; break;
									case 1: $situacao = "<img src='../images/status/VERDE.png'    title='ATIVO. Associada em $dtstatus por $PXCUSRSTATUS'/>"; break;
								}
									
								echo "
								<tr>
									<td><div align='center'>$situacao</div></td>
									<td style='background-color:$COLORCOD;'><div align='center'></div></td>
									<td><div align='center'><b>$COLORCOD</b></div></td>
									<td><div align='center'>$COLORNAME</div></td>
									<td><div>$COLORDESC</div></td>
									<td><div align='center'>$COLORAPLICAVEL</div></td>
									<td>
										<div id='cssmenu' align='center'>
											<ul>
												<li class='has-sub'><a href='#'><span></span></a>
													<ul>";
										
														if($PXCSTATUS == 1){
															
															echo"
															<li>
																<form name='inativar' method='POST' action='product_colors' onsubmit='return confirma_inativar();'>
																	<input name='pxcid'  type='hidden' value='$PXCID'/>
																	<input name='prdcod' type='hidden' value='$PRDCOD'/>
																	<input name='acao'   type='hidden' value='INATIVAR'/>
																	<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
																</form>
															</li>";
														
														}
														
														if($PXCSTATUS == 0){
															
															echo"
															<li>
																<form name='ativar' method='POST' action='product_colors' onsubmit='return confirma_ativar();'>
																	<input name='pxcid'  type='hidden' value='$PXCID'/>
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
						
						echo "<div align='center' class='info'>Nenhuma cor associada a este produto!</div>
						<div align='center'>
							<a href='#associar' class='btn btn-info btn-sm' data-toggle='modal'><i class='fa fa-plus'></i>&nbsp;&nbsp;Associar cor</a>
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
							<h4 class='modal-title'>Associar Cor ao Produto <b><?php echo $PRDCOD;?></b></h4>
						</div>
												
						<form class='form-horizontal' id='colors' name='colors' method='POST' action='product_colors.php'>
							<div class='modal-body'>
							
								<?php
												
									$NEWCOLORselect = mysqli_query($con,"SELECT * FROM colors WHERE COLORSTATUS = 1 AND COLORCOD NOT IN (SELECT COLORCOD FROM productxcolors WHERE PRDCOD = '$PRDCOD')");
									$NEWCOLORtotal  = mysqli_num_rows($NEWCOLORselect);
									
									if($NEWCOLORtotal > 0){
										echo "
										<table class='table table-condensed table-action table-striped'>
											<tbody>";
										
											while($NEWCOLORrow = mysqli_fetch_array($NEWCOLORselect)){
												
												$NEWCOLORCOD  = $NEWCOLORrow["COLORCOD"];
												$NEWCOLORNAME = $NEWCOLORrow["COLORNAME"];
												$NEWCOLORDESC = $NEWCOLORrow["COLORDESC"];	
												
												echo "
												<tr>
													<td width='10%'><div align='center'><input class='inputcheck checar' name='colorcod[]' type='checkbox' value='$NEWCOLORCOD' /></div></td>
													<td width='10%' style='background-color: $NEWCOLORCOD;'></td>
													<td width='10%'><div align='center'><b>$NEWCOLORCOD</b></div></td>
													<td width='35%'><div align='center'>$NEWCOLORNAME</div></td>
													<td width='35%'><div><small>$NEWCOLORDESC</small></div></td>
												</tr>";
												
											}
											
											echo "</tbody>
										</table>";
									
									}else{
										echo "<div align='center' class='info'>Nenhuma cor disponível para associação.</div>";
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