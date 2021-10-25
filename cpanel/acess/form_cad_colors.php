<?php include 'head.php';?>

	<script type="text/javascript">
	
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTA COR? Products com essa cor associada não serão afetados. Caso queira fazer isso, desassocie a cor de cada produto.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTA COR?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

	</script> 
	
<body>
	<div class='container-fluid'>
	
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'><i>Colors</i></span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
	
			<?php 
		
				if (isset($_POST['acao'])){
					$acao = $_POST['acao'];
					
					if ($acao == 'INATIVAR'){
						
						$COLORCOD = $_POST['colorcod'];
						$COLORupdate = mysqli_query($con,"UPDATE colors SET COLORSTATUS = 0, COLORDTSTATUS = '$datetime', COLORUSRSTATUS = '$ident_session' WHERE COLORCOD = '$COLORCOD'") or print(mysqli_error());
						
						if($COLORupdate){
							echo "<div align='center' class='success'><i>Color</i> <b>$COLORCOD</b> desativada.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Color</i> <b>$COLORCOD</b> não pode ser desativada.</div>";
						}
						
					}
					
					if ($acao == 'ATIVAR'){
						$COLORCOD = $_POST['colorcod'];
						$COLORupdate = mysqli_query($con,"UPDATE colors SET COLORSTATUS=1,COLORDTSTATUS='$datetime',COLORUSRSTATUS='$ident_session' WHERE COLORCOD = '$COLORCOD'") or print(mysqli_error());
						
						if($COLORupdate){
							echo "<div id='alert' align='center' class='success'><i>Color</i> <b>$COLORCOD</b> ativada com sucesso.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Color</i> <b>$COLORCOD</b> não pode ser ativada.</div>";
						}
					}
					
					if($acao == 'EDITAR'){
						
						$COLORCOD = $_POST['colorcod'];
						
						$COLOREDITselect = mysqli_query($con,"SELECT * FROM colors WHERE COLORCOD = '$COLORCOD'") or print(mysqli_error());
						
						while($COLOREDITrow = mysqli_fetch_assoc($COLOREDITselect)){
							
							$COLORNAME      = $COLOREDITrow["COLORNAME"];
							$COLORDESC      = $COLOREDITrow["COLORDESC"];
							$COLORAPLICAVEL = $COLOREDITrow["COLORAPLICAVEL"];
							
						}
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar <i>Color</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_colors.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='editar_cor' method='POST' action='form_cad_colors.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='colorcod' type='text' id='colorcod' value='<?php echo $COLORCOD;?>' readonly /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='coloraplicavel' required >
											<option value='<?php echo $COLORAPLICAVEL;?>'><?php echo $COLORAPLICAVEL;?></option>
											<option value='TECIDOS'>Tecidos</option>
											<option value='ACESSORIOS'>Acessórios</option>
											<option value='BRINQUEDOS'>Brinquedos</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input class='form-control' name='colorname' type='text' id='colorname' value='<?php echo $COLORNAME;?>' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input class='form-control' name='colordesc' type='text' id='colordesc' value='<?php echo $COLORDESC;?>' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
					}
					
					if ($acao == 'CONFIRMAR_EDICAO'){
						
						$COLORCOD       = $_POST['colorcod'];
						$COLORNAME      = $_POST['colorname'];
						$COLORDESC      = $_POST['colordesc'];
						$COLORAPLICAVEL = $_POST['coloraplicavel'];
						
						$COLORverifica  = mysqli_query($con,"SELECT * FROM colors WHERE COLORNAME = '$COLORNAME' AND COLORCOD NOT IN('$COLORCOD')") or print (mysqli_error());
						$COLORregistros = mysqli_num_rows($COLORverifica);
						
						if($COLORregistros > 0){
							echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe Color com este nome.</div>";
						
						}else{
							
							$COLORupdate = mysqli_query($con,"UPDATE colors SET COLORAPLICAVEL = '$COLORAPLICAVEL', 
																																			 COLORNAME = '$COLORNAME', 
																																			 COLORDESC = '$COLORDESC', 
																																			ULTALTERON = '$datetime',
																																			ULTALTERBY = '$ident_session' WHERE COLORCOD='$COLORCOD'") or print (mysqli_error());					
							if($COLORupdate){
								echo "<div id='alert' align='center' class='success'><i>Color</i> <b>$COLORCOD</b> alterada com sucesso.</div>";

							}else{
								
								echo "<div align='center' class='error'><i>Color</i> <b>$COLORCOD</b> não pode ser editada.</div>";
								
							}
							
						}
						
					}
					
					if ($acao == 'ADD'){
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir <i>Color</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_colors.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='cor' method='POST' action='form_cad_colors.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='colorcod' type='text' id='colorcod' onkeyup='maiuscula(this);' maxlength='10' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='coloraplicavel' required >
											<option value='TECIDOS'>Tecidos</option>
											<option value='ACESSORIOS'>Acessórios</option>
											<option value='BRINQUEDOS'>Brinquedos</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input title='Nome que irá aparecer para a seleção do cliente no site' class='form-control' name='colorname' type='text' id='colorname' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input title='Descrição característica da cor, para fins administrativos' class='form-control' name='colordesc' type='text' id='colordesc' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='SALVAR'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
						
					}
					
					if ($acao == 'SALVAR'){
						
						$COLORCOD       = $_POST['colorcod'];
						$COLORNAME      = $_POST['colorname'];
						$COLORDESC      = $_POST['colordesc'];
						$COLORAPLICAVEL = $_POST['coloraplicavel'];
						
						$COLORverifica = mysqli_query($con,"SELECT * FROM colors WHERE COLORCOD = '$COLORCOD' OR COLORNAME = '$COLORNAME'") or print (mysqli_error());
						$COLORregistros= mysqli_num_rows($COLORverifica);
						
						if($COLORregistros > 0){
							echo "<div align='center' class='warning'>CADASTRO NÃO CONFIRMADO: Código ou Descrição já utilizados.</div>";
						
						}else{
							
							$COLORinsert = mysqli_query($con,"INSERT INTO colors (COLORCOD,COLORDESC,COLORAPLICAVEL,COLORNAME,COLORSTATUS,COLORUSRSTATUS,COLORCADBY)
							VALUES ('$COLORCOD','$COLORDESC','$COLORAPLICAVEL','$COLORNAME',0,'$ident_session','$ident_session')") or print (mysqli_error());
						
							if($COLORinsert){
								echo "<div id='alert' align='center' class='success'><i>Color</i> <b>$COLORCOD</b> inserida com sucesso.</div>";
							
							}else{
								echo "<div align='center' class='error'>Erro na inserção da <i>Color</i> <b>$COLORCOD</b><br>Entre em contato com o Administrador deste domínio.</div>";
							}
							
						}
					}
				}//Fim de ações

				$COLORselect = mysqli_query($con,"SELECT * FROM colors ORDER BY COLORNAME");
				$COLORtotal = mysqli_num_rows($COLORselect);

				if($COLORtotal > 0){
					echo "
						<table width='100%' id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
						<thead>
							<tr>
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='5%'><div align='center'>              </div></th>
								<th width='20%'><div align='center'>Código       </div></th>
								<th width='20%'><div align='center'>Name         </div></th>
								<th width='30%'><div>Descrição                   </div></th>
								<th width='20%'><div align='center'>Aplicável a  </div></th>
								<th width='3%' data-orderable='false'>
									<div align='center'>
										<form name='novo' method='POST' action='form_cad_colors.php'>
											<input name='acao'  type='hidden' value='ADD'/>
											<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
										</form>
									</div>
								</th>
							</tr>
						</thead>
					<tbody>";
							
					while($COLORrow = mysqli_fetch_array($COLORselect)){						
						$COLORID        = $COLORrow["COLORID"];
						$COLORAPLICAVEL = $COLORrow["COLORAPLICAVEL"];
						$COLORCOD       = $COLORrow["COLORCOD"];
						$COLORNAME      = $COLORrow["COLORNAME"];
						$COLORDESC      = $COLORrow["COLORDESC"];
						$COLORSTATUS    = $COLORrow["COLORSTATUS"];
						$COLORDTSTATUS  = $COLORrow["COLORDTSTATUS"];
						$COLORUSRSTATUS = $COLORrow["COLORUSRSTATUS"];
						
						switch($COLORSTATUS){
							case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='Inativo'/>"; break;
							case 1: $situacao = "<img src='../images/status/VERDE.png'    title='Ativo'/>"; break;
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
												<ul>
													<li>
														<form name='editar' method='POST' action='form_cad_colors.php'>
															<input name='colorcod' type='hidden' value='$COLORCOD'/>
															<input name='acao'     type='hidden' value='EDITAR'/>
															<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
														</form>
													</li>";
									
													if($COLORSTATUS == 1){
														
														echo"
														<li>
															<form name='inativar' method='POST' action='form_cad_colors.php' onsubmit='return confirma_inativar();'>
																<input name='colorcod' type='hidden' value='$COLORCOD'/>
																<input name='acao'     type='hidden' value='INATIVAR'/>
																<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
															</form>
														</li>";
													
													}
													
													if($COLORSTATUS == 0){
														
														echo"
														<li>
															<form name='ativar' method='POST' action='form_cad_colors.php' onsubmit='return confirma_ativar();'>
																<input name='colorcod' type='hidden' value='$COLORCOD'/>
																<input name='acao'     type='hidden' value='ATIVAR'/>
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
					
					 echo "<div align='center' class='info'>Nenhuma cor cadastrada!</div>
					 <div align='center'>
						 <form name='novo' method='POST' action='form_cad_colors.php'>
							<input name='acao' type='hidden' value='ADD'/>
							<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
						</form>
					</div>";
					
				} 
				
				?>
				
			</div>
		</div>
	</div>
</body>
</html>