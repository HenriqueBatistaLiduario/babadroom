<?php include 'head.php';?>

	<script type="text/javascript">
	
		function confirma_inativar(){
			if(confirm('ATENÇÃO: INATIVAR ESTE DEPARTAMENTO? Chamados com esta depegoria não serão afetados.\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}

		function confirma_ativar(){
			if(confirm('ATENÇÃO: ATIVAR ESTE DEPARTAMENTO?\n\nClique em OK para continuar...'))
			return true;
			else
			return false;
		}
		
	</script> 
	
	<body>
		<div class='container-fluid'>
		
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<span class='subpageName'>Departamentos (departments)</span>

					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
				</div>

				<div class='panel-body'>
		<?php 
			
			if (isset($_POST['acao'])){
				$acao = $_POST['acao'];
				
				if ($acao == 'INATIVAR'){
					
					$DEPTCOD = $_POST['deptcod'];
					$DEPTupdate = mysqli_query($con,"UPDATE departments SET DEPTSTATUS = 0, DEPTDTSTATUS = '$datetime', DEPTUSRSTATUS = '$ident_session' WHERE DEPTCOD = '$DEPTCOD'") or print(mysqli_error());
					
					if($DEPTupdate){
						echo "<div align='center' class='success'><i>Department</i> <b>$DEPTCOD</b> desativada.</div>";
						
					}else{
						echo "<div align='center' class='error'><i>Department</i> <b>$DEPTCOD</b> não pode ser desativada.</div>";
					}
					
				}
				
				if ($acao == 'ATIVAR'){
					$DEPTCOD = $_POST['deptcod'];
					$DEPTupdate = mysqli_query($con,"UPDATE departments SET DEPTSTATUS=1,DEPTDTSTATUS='$datetime',DEPTUSRSTATUS='$ident_session' WHERE DEPTCOD = '$DEPTCOD'") or print(mysqli_error());
					
					if($DEPTupdate){
						echo "<div id='alert' align='center' class='success'><i>Department</i> <b>$DEPTCOD</b> ativada com sucesso.</div>";
						
					}else{
						echo "<div align='center' class='error'><i>Department</i> <b>$DEPTCOD</b> não pode ser ativada.</div>";
					}
				}
				
				if($acao == 'EDITAR'){
					
					$DEPTCOD = $_POST['deptcod'];
					
					$DEPTEDITselect = mysqli_query($con,"SELECT * FROM departments WHERE DEPTCOD = '$DEPTCOD'") or print(mysqli_error());
					
					while($DEPTEDITrow = mysqli_fetch_assoc($DEPTEDITselect)){
						
						$DEPTNAME = $DEPTEDITrow["DEPTNAME"];
						
					}
					
					?>
					
					<fieldset><legend class='subpageName'>&nbsp;&nbsp;Editar <i>Department</i>&nbsp;&nbsp;</legend>
						<div align='right'>
							<form name='fechar' method='POST' action='form_cadastro_departments.php'>
								<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
							</form>
						</div>
						
						<form class='form-horizontal' name='editar_depegoria' method='POST' action='form_cadastro_departments.php'>
							
							<div class='form-group'>
								<label class='col-sm-6 control-label'>Código:</label>
								<div class='col-sm-2'><input class='form-control' name='deptcod' type='text' id='deptcod' onkeyup="maiuscula(this)" value='<?php echo $DEPTCOD;?>' maxlength="10" readonly /></div>
							</div>
							
							<div class='form-group'>
								<label class='col-sm-6 control-label'>Título:</label>
								<div class='col-sm-6'><input class='form-control' name='deptname' type='text' id='deptname' value='<?php echo $DEPTNAME;?>' maxlength="50" required /></div>
							</div>
							
							<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/></input>
							<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
							
						</form>
					</fieldset><br>
					
					<?php
				}
				
				if ($acao == 'CONFIRMAR_EDICAO'){
					
					$DEPTCOD       = $_POST['deptcod'];
					$DEPTNAME      = $_POST['deptname'];
					
					$DEPTverifica  = mysqli_query($con,"SELECT * FROM departments WHERE DEPTNAME = '$DEPTNAME' AND DEPTCOD NOT IN('$DEPTCOD')") or print (mysqli_error());
					$DEPTregistros = mysqli_num_rows($DEPTverifica);
					
					if($DEPTregistros > 0){
						echo "<div align='center' class='warning'>ALTERAÇÃO NÃO REALIZADA: Já existe Department com este nome.</div>";
					
					}else{
						
						$DEPTupdate = mysqli_query($con,"UPDATE departments SET DEPTNAME = '$DEPTNAME' WHERE DEPTCOD='$DEPTCOD'") or print (mysqli_error());		
						
						if($DEPTupdate){
							echo "<div id='alert' align='center' class='success'><i>Department</i> <b>$DEPTCOD</b> alterada com sucesso.</div>";

						}else{
							
							echo "<div align='center' class='error'><i>Department</i> <b>$DEPTCOD</b> não pode ser editada.</div>";
							
						}
						
					}
					
				}
				
				if ($acao == 'ADD'){
					
					?>
					
					<fieldset><legend class='subpageName'>&nbsp;&nbsp;Incluir <i>Department</i>&nbsp;&nbsp;</legend>
						<div align='right'>
							<form name='fechar' method='POST' action='form_cadastro_departments.php'>
								<input class='inputsrc' type='image' src='../images/close.png' title='Fechar'/>
							</form>
						</div>
						
						<form class='form-horizontal' name='depegoria' method='POST' action='form_cadastro_departments.php'>
							
							<div class='form-group'>
								<label class='col-sm-6 control-label'>Código:</label>
								<div class='col-sm-2'><input class='form-control' name='deptcod' type='text' id='deptcod' onkeyup="maiuscula(this)" maxlength="10" required /></div>
							</div>
						
							<div class='form-group'>
								<label class='col-sm-6 control-label'>Título:</label>
								<div class='col-sm-6'><input class='form-control' name='deptname' type='text' id='deptname' maxlength="50" required /></div>
							</div>
							
							<input name='acao' type='hidden' value='SALVAR'/></input>
							<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
							
						</form>
					</fieldset><br>
					
					<?php
					
				}
				
				if ($acao == 'SALVAR'){
					
					$DEPTCOD  = $_POST['deptcod'];
					$DEPTNAME = $_POST['deptname'];
					
					$DEPTverifica = mysqli_query($con,"SELECT * FROM departments WHERE DEPTCOD = '$DEPTCOD' OR DEPTNAME = '$DEPTNAME'") or print (mysqli_error());
					$DEPTregistros= mysqli_num_rows($DEPTverifica);
					
					if($DEPTregistros > 0){
						echo "<div align='center' class='warning'>CADASTRO NÃO CONFIRMADO: Código ou Descrição já utilizados.</div>";
					
					}else{
						$DEPTinsert = mysqli_query($con,"INSERT INTO departments (DEPTCOD,DEPTNAME,DEPTSTATUS,DEPTDTSTATUS,DEPTUSRSTATUS,DEPTCADASTRO,DEPTCADBY)
						VALUES ('$DEPTCOD','$DEPTNAME',1,'$datetime','$ident_session','$datetime','$ident_session')") or print (mysqli_error());
					
						if($DEPTinsert){
							echo "<div id='alert' align='center' class='success'><i>Department</i> <b>$DEPTCOD</b> inserida com sucesso.</div>";
						
						}else{
							echo "<div align='center' class='error'>Erro na inserção da <i>Department</i> <b>$DEPTCOD</b><br>Entre em contato com o Administrador deste domínio.</div>";
						}
					}
				}
			}//Fim de ações

					$DEPTselect = mysqli_query($con,"SELECT * FROM departments ORDER BY DEPTNAME");
					$DEPTtotal = mysqli_num_rows($DEPTselect);

					if($DEPTtotal > 0){
						echo "
						<table width='100%' id='DataTables' class='display table table-action table-condensed table-striped'>
							<thead>								
								<th width='2%' data-orderable='false'><div align='center' ></div></th>
								<th width='30%'><div align='center'>Código       </div></th>
								<th width='65%'><div>Descrição    </div></th>
								<th width='3%' data-orderable='false'>
									<div align='center'>
										<form name='novo' method='POST' action='form_cadastro_departments.php'>
											<input name='acao'  type='hidden' value='ADD'/>
											<input name='editar' class='inputsrc' type='image' img src='../images/add3.png' title='Incluir <i>Department</i>'/>
										</form>
									</div>
								</th>								
							</thead>
						<tbody>";
								
						while($DEPTcol = mysqli_fetch_array($DEPTselect)){
							
							$DEPTID        = $DEPTcol["DEPTID"];						
							$DEPTCOD       = $DEPTcol["DEPTCOD"];
							$DEPTNAME      = $DEPTcol["DEPTNAME"];						
							$DEPTSTATUS    = $DEPTcol["DEPTSTATUS"];
							$DEPTDTSTATUS  = $DEPTcol["DEPTDTSTATUS"];
							$DEPTUSRSTATUS = $DEPTcol["DEPTUSRSTATUS"];
							
							switch($DEPTSTATUS){
								case 0: $situacao = "<img src='../images/status/VERMELHO.png' title='Inativo'/>"; break;
								case 1: $situacao = "<img src='../images/status/VERDE.png'    title='Ativo'/>"; break;
							}
							
							echo "
								<tr>
									<td><div align='center'>$situacao</div></td>
									<td><div align='center'><b>$DEPTCOD</b></div></td>
									<td><div>$DEPTNAME</div></td>
									<td>
										<div id='cssmenu' align='center'>
											<ul>
												<li class='has-sub'><a href='#'><span></span></a>
													<ul>
														<li>
															<form name='editar' method='POST' action='form_cadastro_departments.php'>
																<input name='deptcod' type='hidden' value='$DEPTCOD'/>
																<input name='acao'      type='hidden' value='EDITAR'/>
																<input class='inputsrc' name='editar' type='image'  src='../images/edit.png' title='Editar'/>
															</form>
														</li>";
										
														if($DEPTSTATUS == 1){
															echo"
															<li>
																<form name='inativar' method='POST' action='form_cadastro_departments.php'>
																	<input name='deptcod' type='hidden' value='$DEPTCOD'/>
																	<input name='acao'      type='hidden' value='INATIVAR'/>
																	<input class='inputsrc' name='excluir' type='image' onclick='return confirma_inativar();' src='../images/ativar.png' title='Inativar'/></input>
																</form>
															</li>";
														
														}else{
															echo"
															<li>
																<form name='ativar' method='POST' action='form_cadastro_departments.php'>
																	<input name='deptcod' type='hidden' value='$DEPTCOD'/>
																	<input name='acao'      type='hidden' value='ATIVAR'/>
																	<input class='inputsrc' name='excluir' type='image' onclick='return confirma_ativar();' src='../images/ativar.png' title='Ativar'/></input>
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
						 echo "<div align='center' class='info'>Nenhum Departamento cadastrado!</div>
						 <div align='center'>
							 <form name='novo' method='POST' action='form_cadastro_departments.php'>
								<input name='acao' type='hidden' value='ADD'/>
								<input name='editar' class='inputsrc' type='image' img src='../images/add3.png' title='Incluir <i>Department</i>'/>
							</form>
						</div>";
					} 
					
					?>
					
				</div>
			</div>
		</div>
	</body>
</html>