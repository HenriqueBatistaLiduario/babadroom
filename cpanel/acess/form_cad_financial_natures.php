<?php include 'head.php';?>

	<script type="text/javascript">
	
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTA NATUREZA FINANCEIRA? Lançamentos Financeiros desta Natureza não serão afetados.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTA NATUREZA FINANCEIRA?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

	</script> 
	
<body>
	<div class='container-fluid'>
	
		<div class='panel panel-default'>
			<div class='panel-heading'>
				<span class='subpageName'><i>Naturezas Financeiras (financial natures)</i></span>

				<ul class='pull-right list-inline'>
					<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>

			<div class='panel-body'>
	
			<?php 
		
				if (isset($_POST['acao'])){
					$acao = $_POST['acao'];
					
					if ($acao == 'INATIVAR'){
						
						$FNATCOD = $_POST['fnatcod'];
						$FNATupdate = mysqli_query($con,"UPDATE financial_natures SET FNATSTATUS = 0, FNATDTSTATUS = '$datetime', FNATUSRSTATUS = '$ident_session' WHERE FNATCOD = '$FNATCOD'") or print(mysqli_error());
						
						if($FNATupdate){
							echo "<div align='center' class='success'><i>Financial Nature</i> <b>$FNATCOD</b> desativada.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Financial Nature</i> <b>$FNATCOD</b> não pode ser desativada.</div>";
						}
						
					}
					
					if ($acao == 'ATIVAR'){
						
						$FNATCOD = $_POST['fnatcod'];
						$FNATupdate = mysqli_query($con,"UPDATE financial_natures SET FNATSTATUS=1,FNATDTSTATUS='$datetime',FNATUSRSTATUS='$ident_session' WHERE FNATCOD = '$FNATCOD'") or print(mysqli_error());
						
						if($FNATupdate){
							echo "<div id='alert' align='center' class='success'><i>Financial Nature</i> <b>$FNATCOD</b> ativada com sucesso.</div>";
							
						}else{
							echo "<div align='center' class='error'><i>Financial Nature</i> <b>$FNATCOD</b> não pode ser ativada.</div>";
						}
						
					}
					
					if($acao == 'EDITAR'){
						
						$FNATCOD = $_POST['fnatcod'];
						
						$FNATEDITselect = mysqli_query($con,"SELECT * FROM financial_natures WHERE FNATCOD = '$FNATCOD'") or print(mysqli_error());
						
						while($FNATEDITrow = mysqli_fetch_assoc($FNATEDITselect)){
							
							$FNATNAME      = $FNATEDITrow["FNATNAME"];
							$FNATDESC      = $FNATEDITrow["FNATDESC"];
							$FNATAPLICAVEL = $FNATEDITrow["FNATAPLICAVEL"];
							
						}
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar <i>Financial Nature</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_financial_natures.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='editar_NaturezaFinanceira' method='POST' action='form_cad_financial_natures.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='fnatcod' type='text' id='fnatcod' value='<?php echo $FNATCOD;?>' readonly /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='fnataplicavel' required >
											<option value='<?php echo $FNATAPLICAVEL;?>'><?php echo $FNATAPLICAVEL;?></option>
											<option value='RECEITAS'>RECEITAS</option>
											<option value='DESPESAS'>DESPESAS</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input class='form-control' name='fnatname' type='text' id='fnatname' value='<?php echo $FNATNAME;?>' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input class='form-control' name='fnatdesc' type='text' id='fnatdesc' value='<?php echo $FNATDESC;?>' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
					}
					
					if ($acao == 'CONFIRMAR_EDICAO'){
						
						$FNATCOD       = $_POST['fnatcod'];
						$FNATNAME      = $_POST['fnatname'];
						$FNATDESC      = $_POST['fnatdesc'];
						$FNATAPLICAVEL = $_POST['fnataplicavel'];
						
						$FNATverifica  = mysqli_query($con,"SELECT * FROM financial_natures WHERE FNATNAME = '$FNATNAME' AND FNATCOD NOT IN('$FNATCOD')") or print (mysqli_error());
						$FNATregistros = mysqli_num_rows($FNATverifica);
						
						if($FNATregistros > 0){
							echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe Financial Nature com este nome.</div>";
						
						}else{
							
							$FNATupdate = mysqli_query($con,"UPDATE financial_natures SET FNATAPLICAVEL = '$FNATAPLICAVEL', 
																																			           FNATNAME = '$FNATNAME', 
																																			           FNATDESC = '$FNATDESC', 
																																			         ULTALTERON = '$datetime',
																																			         ULTALTERBY = '$ident_session' WHERE FNATCOD='$FNATCOD'") or print (mysqli_error());					
							if($FNATupdate){
								echo "<div id='alert' align='center' class='success'><i>Financial Nature</i> <b>$FNATCOD</b> alterada com sucesso.</div>";

							}else{
								
								echo "<div align='center' class='error'><i>Financial Nature</i> <b>$FNATCOD</b> não pode ser editada.</div>";
								
							}
							
						}
						
					}
					
					if ($acao == 'ADD'){
						
						?>
						
						<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir <i>Financial Nature</i>&nbsp;&nbsp;</legend>
							<div align='right'>
								<form name='fechar' method='POST' action='form_cad_financial_natures.php'>
									<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
								</form>
							</div>
							
							<form class='form-horizontal' name='NaturezaFinanceira' method='POST' action='form_cad_financial_natures.php'>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Código:</label>
									<div class='col-sm-2'><input class='form-control' name='fnatcod' type='text' id='fnatcod' onkeyup='maiuscula(this);' maxlength='10' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Aplicável a:</label>
									<div class='col-sm-6'>
										<select class='form-control' name='fnataplicavel' required >
											<option value='RECEITAS'>RECEITAS</option>
											<option value='DESPESAS'>DESPESAS</option>
											<option value='GENERICO'>GENÉRICO</option>
										</select>
									</div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Name:</label>
									<div class='col-sm-6'><input class='form-control' name='fnatname' type='text' id='fnatname' maxlength='50' required /></div>
								</div>
								
								<div class='form-group'>
									<label class='col-sm-6 control-label'>Descrição:</label>
									<div class='col-sm-6'><input title='Descrição característica da Natureza Financeira, para fins administrativos' class='form-control' name='fnatdesc' type='text' id='fnatdesc' maxlength='100' required /></div>
								</div>
								
								<input name='acao' type='hidden' value='SALVAR'/></input>
								<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
								
							</form>
						</fieldset><br>
						
						<?php
						
					}
					
					if ($acao == 'SALVAR'){
						
						$FNATCOD       = $_POST['fnatcod'];
						$FNATNAME      = $_POST['fnatname'];
						$FNATDESC      = $_POST['fnatdesc'];
						$FNATAPLICAVEL = $_POST['fnataplicavel'];
						
						$FNATverifica = mysqli_query($con,"SELECT * FROM financial_natures WHERE FNATCOD = '$FNATCOD' OR FNATNAME = '$FNATNAME'") or print (mysqli_error());
						$FNATregistros= mysqli_num_rows($FNATverifica);
						
						if($FNATregistros > 0){
							echo "<div align='center' class='warning'>CADASTRO NÃO CONFIRMADO: Código ou Descrição já utilizados.</div>";
						
						}else{
							
							$FNATinsert = mysqli_query($con,"INSERT INTO financial_natures (FNATCOD,FNATDESC,FNATAPLICAVEL,FNATNAME,FNATUSRSTATUS,FNATCADBY)
							VALUES ('$FNATCOD','$FNATDESC','$FNATAPLICAVEL','$FNATNAME','$ident_session','$ident_session')") or print (mysqli_error());
						
							if($FNATinsert){
								echo "<div id='alert' align='center' class='success'><i>Financial Nature</i> <b>$FNATCOD</b> inserida com sucesso.</div>";
							
							}else{
								echo "<div align='center' class='error'>Erro na inserção da <i>Financial Nature</i> <b>$FNATCOD</b><br>Entre em contato com o Administrador deste domínio.</div>";
							}
							
						}
					}
				}//Fim de ações

				$FNATselect = mysqli_query($con,"SELECT * FROM financial_natures ORDER BY FNATCOD");
				$FNATtotal = mysqli_num_rows($FNATselect);

				if($FNATtotal > 0){
					echo "
						<table width='100%' id='DataTables' class='display table-responsive table-action table-condensed table-striped'>
						<thead>
							<tr>
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='15%'><div align='center'>Código       </div></th>
								<th width='30%'><div>Name         </div></th>
								<th width='30%'><div>Descrição                   </div></th>
								<th width='20%'><div align='center'>Aplicável a  </div></th>
								<th width='3%' data-orderable='false'>
									<div align='center'>
										<form name='novo' method='POST' action='form_cad_financial_natures.php'>
											<input name='acao'  type='hidden' value='ADD'/>
											<button type='submit' class='btn btn-default btn-sm' title='Incluir'><i class='fa fa-plus'></i></button>
										</form>
									</div>
								</th>
							</tr>
						</thead>
					<tbody>";
							
					while($FNATrow = mysqli_fetch_array($FNATselect)){						
						$FNATID        = $FNATrow["FNATID"];
						$FNATAPLICAVEL = $FNATrow["FNATAPLICAVEL"];
						$FNATCOD       = $FNATrow["FNATCOD"];
						$FNATNAME      = $FNATrow["FNATNAME"];
						$FNATDESC      = $FNATrow["FNATDESC"];
						$FNATSTATUS    = $FNATrow["FNATSTATUS"];
						$FNATDTSTATUS  = $FNATrow["FNATDTSTATUS"];
						$FNATUSRSTATUS = $FNATrow["FNATUSRSTATUS"];
						
						switch($FNATSTATUS){
							case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='Inativo'/>"; break;
							case 1: $situacao = "<img src='../images/status/VERDE.png'    title='Ativo'/>"; break;
						}
						
						echo "
							<tr>
								<td><div align='center'>$situacao</div></td>
								<td><div align='center'><b>$FNATCOD</b></div></td>
								<td><div>$FNATNAME</div></td>
								<td><div><small>$FNATDESC</small></div></td>
								<td><div align='center'>$FNATAPLICAVEL</div></td>
								<td>
									<div id='cssmenu' align='center'>
										<ul>
											<li class='has-sub'><a href='#'><span></span></a>
												<ul>
													<li>
														<form name='editar' method='POST' action='form_cad_financial_natures.php'>
															<input name='fnatcod' type='hidden' value='$FNATCOD'/>
															<input name='acao'     type='hidden' value='EDITAR'/>
															<button class='btn btn-default btn-xs' title='EDITAR'><i class='fa fa-pencil'></i></button>
														</form>
													</li>";
									
													if($FNATSTATUS == 1){
														
														echo"
														<li>
															<form name='inativar' method='POST' action='form_cad_financial_natures.php' onsubmit='return confirma_inativar();'>
																<input name='fnatcod' type='hidden' value='$FNATCOD'/>
																<input name='acao'    type='hidden' value='INATIVAR'/>
																<button class='btn btn-default btn-xs' title='INATIVAR'><i class='fa fa-ban' aria-hidden='true'></i></button>
															</form>
														</li>";
													
													}
													
													if($FNATSTATUS == 0){
														
														echo"
														<li>
															<form name='ativar' method='POST' action='form_cad_financial_natures.php' onsubmit='return confirma_ativar();'>
																<input name='fnatcod' type='hidden' value='$FNATCOD'/>
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
					
					 echo "<div align='center' class='info'>Nenhuma Natureza Financeira cadastrada!</div>
					 <div align='center'>
						 <form name='novo' method='POST' action='form_cad_financial_natures.php'>
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