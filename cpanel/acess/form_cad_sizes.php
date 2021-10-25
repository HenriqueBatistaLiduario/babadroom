<?php include 'head.php';?>

	<script type="text/javascript">
	
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTE TAMANHO DE MEDIDA? Products com essa cor associada não serão afetados. Caso queira fazer isso, desassocie a cor de cada produto.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTE TAMANHO DE MEDIDA?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

	</script> 
	
<body>
	<div class='container-fluid'>
	
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'><i>Sizes</i></span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
	
			<?php 
		
				if (isset($_POST['acao'])){
					$acao = $_POST['acao'];
					
					if ($acao == 'INATIVAR'){
						
						$SIZECOD = $_POST['sizecod'];
						$SIZEupdate = mysqli_query($con,"UPDATE sizes SET SIZESTATUS = 0, SIZEDTSTATUS = '$datetime', SIZEUSRSTATUS = '$ident_session' WHERE SIZECOD = '$SIZECOD'") or print(mysqli_error());
						
						if($SIZEupdate){
							echo "<div align='center' class='success'><i>Size</i> <b>$SIZECOD</b> desativada.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Size</i> <b>$SIZECOD</b> não pode ser desativada.</div>";
						}
						
					}
					
					if ($acao == 'ATIVAR'){
						$SIZECOD = $_POST['sizecod'];
						$SIZEupdate = mysqli_query($con,"UPDATE sizes SET SIZESTATUS=1,SIZEDTSTATUS='$datetime',SIZEUSRSTATUS='$ident_session' WHERE SIZECOD = '$SIZECOD'") or print(mysqli_error());
						
						if($SIZEupdate){
							echo "<div id='alert' align='center' class='success'><i>Size</i> <b>$SIZECOD</b> ativada com sucesso.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Size</i> <b>$SIZECOD</b> não pode ser ativada.</div>";
						}
					}
					
					if($acao == 'EDITAR'){
						
						$SIZECOD = $_POST['sizecod'];
						
						$SIZEEDITselect = mysqli_query($con,"SELECT * FROM sizes WHERE SIZECOD = '$SIZECOD'") or print(mysqli_error());
						
						while($SIZEEDITrow = mysqli_fetch_assoc($SIZEEDITselect)){
							
							$SIZENAME      = $SIZEEDITrow["SIZENAME"];
							$SIZEDESC      = $SIZEEDITrow["SIZEDESC"];
							$SIZEAPLICAVEL = $SIZEEDITrow["SIZEAPLICAVEL"];
							
						}
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar <i>Size</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_sizes.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='editar_cor' method='POST' action='form_cad_sizes.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='sizecod' type='text' id='sizecod' value='<?php echo $SIZECOD;?>' readonly /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='sizeaplicavel' required >
											<option value='<?php echo $SIZEAPLICAVEL;?>'><?php echo $SIZEAPLICAVEL;?></option>
											<option value='TECIDOS'>Tecidos</option>
											<option value='ACESSORIOS'>Acessórios</option>
											<option value='BRINQUEDOS'>Brinquedos</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input class='form-control' name='sizename' type='text' id='sizename' value='<?php echo $SIZENAME;?>' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input class='form-control' name='sizedesc' type='text' id='sizedesc' value='<?php echo $SIZEDESC;?>' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
					}
					
					if ($acao == 'CONFIRMAR_EDICAO'){
						
						$SIZECOD       = $_POST['sizecod'];
						$SIZENAME      = $_POST['sizename'];
						$SIZEDESC      = $_POST['sizedesc'];
						$SIZEAPLICAVEL = $_POST['sizeaplicavel'];
						
						$SIZEverifica  = mysqli_query($con,"SELECT * FROM sizes WHERE SIZENAME = '$SIZENAME' AND SIZECOD NOT IN('$SIZECOD')") or print (mysqli_error());
						$SIZEregistros = mysqli_num_rows($SIZEverifica);
						
						if($SIZEregistros > 0){
							echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe Size com este nome.</div>";
						
						}else{
							
							$SIZEupdate = mysqli_query($con,"UPDATE sizes SET SIZEAPLICAVEL = '$SIZEAPLICAVEL', 
																																		 SIZENAME = '$SIZENAME', 
																																		 SIZEDESC = '$SIZEDESC', 
																																	 ULTALTERON = '$datetime',
																																	 ULTALTERBY = '$ident_session' WHERE SIZECOD='$SIZECOD'") or print (mysqli_error());					
							if($SIZEupdate){
								echo "<div id='alert' align='center' class='success'><i>Size</i> <b>$SIZECOD</b> alterada com sucesso.</div>";

							}else{
								
								echo "<div align='center' class='error'><i>Size</i> <b>$SIZECOD</b> não pode ser editada.</div>";
								
							}
							
						}
						
					}
					
					if ($acao == 'ADD'){
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir <i>Size</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_sizes.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='cor' method='POST' action='form_cad_sizes.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='sizecod' type='text' id='sizecod' onkeyup='maiuscula(this);' maxlength='10' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='sizeaplicavel' required >
											<option value='TECIDOS'>Tecidos</option>
											<option value='ACESSORIOS'>Acessórios</option>
											<option value='BRINQUEDOS'>Brinquedos</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input title='Nome que irá aparecer para a seleção do cliente no site' class='form-control' name='sizename' type='text' id='sizename' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input title='Descrição característica da cor, para fins administrativos' class='form-control' name='sizedesc' type='text' id='sizedesc' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='SALVAR'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
						
					}
					
					if ($acao == 'SALVAR'){
						
						$SIZECOD       = $_POST['sizecod'];
						$SIZENAME      = $_POST['sizename'];
						$SIZEDESC      = $_POST['sizedesc'];
						$SIZEAPLICAVEL = $_POST['sizeaplicavel'];
						
						$SIZEverifica = mysqli_query($con,"SELECT * FROM sizes WHERE SIZECOD = '$SIZECOD' OR SIZENAME = '$SIZENAME'") or print (mysqli_error());
						$SIZEregistros= mysqli_num_rows($SIZEverifica);
						
						if($SIZEregistros > 0){
							echo "<div align='center' class='warning'>CADASTRO NÃO CONFIRMADO: Código ou Descrição já utilizados.</div>";
						
						}else{
							
							$SIZEinsert = mysqli_query($con,"INSERT INTO sizes (SIZECOD,SIZEDESC,SIZEAPLICAVEL,SIZENAME,SIZESTATUS,SIZEUSRSTATUS,SIZECADBY)
							VALUES ('$SIZECOD','$SIZEDESC','$SIZEAPLICAVEL','$SIZENAME',0,'$ident_session','$ident_session')") or print (mysqli_error());
						
							if($SIZEinsert){
								echo "<div id='alert' align='center' class='success'><i>Size</i> <b>$SIZECOD</b> inserida com sucesso.</div>";
							
							}else{
								echo "<div align='center' class='error'>Erro na inserção da <i>Size</i> <b>$SIZECOD</b><br>Entre em contato com o Administrador deste domínio.</div>";
							}
							
						}
					}
				}//Fim de ações

				$SIZEselect = mysqli_query($con,"SELECT * FROM sizes ORDER BY SIZENAME");
				$SIZEtotal = mysqli_num_rows($SIZEselect);

				if($SIZEtotal > 0){
					echo "
						<table width='100%' id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
						<thead>
							<tr>
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='20%'><div align='center'>Código       </div></th>
								<th width='20%'><div align='center'>Name         </div></th>
								<th width='35%'><div>Descrição                   </div></th>
								<th width='20%'><div align='center'>Aplicável a  </div></th>
								<th width='3%' data-orderable='false'>
									<div align='center'>
										<form name='novo' method='POST' action='form_cad_sizes.php'>
											<input name='acao' type='hidden' value='ADD'/>
											<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
										</form>
									</div>
								</th>
							</tr>
						</thead>
					<tbody>";
							
					while($SIZErow = mysqli_fetch_array($SIZEselect)){						
						$SIZEID        = $SIZErow["SIZEID"];
						$SIZEAPLICAVEL = $SIZErow["SIZEAPLICAVEL"];
						$SIZECOD       = $SIZErow["SIZECOD"];
						$SIZENAME      = $SIZErow["SIZENAME"];
						$SIZEDESC      = $SIZErow["SIZEDESC"];
						$SIZESTATUS    = $SIZErow["SIZESTATUS"];
						$SIZEDTSTATUS  = $SIZErow["SIZEDTSTATUS"];
						$SIZEUSRSTATUS = $SIZErow["SIZEUSRSTATUS"];
						
						switch($SIZESTATUS){
							case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='Inativo'/>"; break;
							case 1: $situacao = "<img src='../images/status/VERDE.png'    title='Ativo'/>"; break;
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
												<ul>
													<li>
														<form name='editar' method='POST' action='form_cad_sizes.php'>
															<input name='sizecod' type='hidden' value='$SIZECOD'/>
															<input name='acao'    type='hidden' value='EDITAR'/>
															<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
														</form>
													</li>";
									
													if($SIZESTATUS == 1){
														
														echo"
														<li>
															<form name='inativar' method='POST' action='form_cad_sizes.php' onsubmit='return confirma_inativar();'>
																<input name='sizecod' type='hidden' value='$SIZECOD'/>
																<input name='acao'    type='hidden' value='INATIVAR'/>
																<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
															</form>
														</li>";
													
													}
													
													if($SIZESTATUS == 0){
														
														echo"
														<li>
															<form name='ativar' method='POST' action='form_cad_sizes.php' onsubmit='return confirma_ativar();'>
																<input name='sizecod' type='hidden' value='$SIZECOD'/>
																<input name='acao'    type='hidden' value='ATIVAR'/>
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
					
					 echo "<div align='center' class='info'>Nenhum tamanho de medida cadastrado!</div>
					 <div align='center'>
						 <form name='novo' method='POST' action='form_cad_sizes.php'>
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