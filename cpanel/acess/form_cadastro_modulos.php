<?php include "head.php"; ?>

<script type="text/javascript">
   
	function confirma_ativar(){
		if(confirm('ATENÇÃO: CONFIRMA A ATIVAÇÃO DESTE PRODUTO? Projetos em andamento não serão afetados.\n\nClique em OK para continuar...'))
		return true;
		else
		return false;
	}
	
	function confirma_inativar(){
		if(confirm('ATENÇÃO: CONFIRMA A INATIVAÇÃO DESTE PRODUTO? Projetos em andamento não serão afetados.\n\nClique em OK para continuar...'))
		return true;
		else
		return false;
	}
	
	function confirma_edicao(){
		if(confirm('ATENÇÃO: CONFIRMA A ALTERAÇÃO? Operação irreversível. Projetos em andamento não serão afetados.\n\nClique em OK para continuar...'))
			return true;
		else
			return false;
	}
	
	$(document).ready(function(){			
		  $("#mhrspadrao").maskMoney({showSymbol:false, decimal:":", thousands:"", allowZero:true});
			$("#mvalorhora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});
			$("#mcustohora").maskMoney({showSymbol:false, decimal:".", thousands:"", allowZero:true});			
		});
	
</script>
<body>
<div class='container-fluid'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<span class='subpageName'>Produtos</span>

			<ul class='pull-right list-inline'>
				<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
			</ul>
		</div>

		<div class='panel-body'>

<?php 

	if (isset($_POST['acao'])){
		$acao = $_POST['acao'];
		
		if($acao == 'INCLUIR'){
			?>
			<fieldset><legend class='subpageName'>Incluir Produto</legend>

				<div align='right'>
					<form name='fechar' method='POST' action='form_cadastro_modulos.php'>
						<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
					</form>
				</div>
				
				<form class='form-horizontal' name='incluir' method='POST' action='form_cadastro_modulos.php'>
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Código:</label>
							<div class='col-sm-2'><input class='form-control' name='mcodigo' type='text' id='mcodigo' onkeyup="maiuscula(this)" maxlength="10" required /></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Segmento:</label>
						<div class='col-sm-6'>
							<select name='segmento' class='form-control' required >
								<option value='999' title='Aplicável a todos os Segmentos do Portfólio'>INDIFERENTE</option>								
								<?php
								
								$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGSTATUS = 1 ORDER BY SEGCODIGO") or print(mysql_error());
								$SEGtotal = mysql_num_rows($SEGselect);
								
								if($SEGtotal > 0){
									while($SEGlinha = mysql_fetch_array($SEGselect)){
										$SEGCODIGO = $SEGlinha["SEGCODIGO"];
										$SEGDESCRICAO = $SEGlinha["SEGDESCRICAO"];
										
										echo "<option value='$SEGCODIGO'>$SEGDESCRICAO ($SEGCODIGO)</option>";										
										
									}
									
								}else{
									echo "<option value=''>Nenhum segmento ativo.</option>";
								}
								
								?>
							</select>
						</div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Descrição:</label>
						<div class='col-sm-6'><input class='form-control' name="mdescricao" type="text" id="mdescricao" maxlength="100" onkeyup="maiuscula(this)" required /></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Duração padrão(h):</label>
						<div class='col-sm-2'><input class='form-control' name='mhrspadrao' type='text' id='mhrspadrao' value='00:00' maxlength='6'/></div>
					</div>
					
					<div class='form-group'>
						<label class='col-sm-6 control-label'>Valor/Hora:</label>
						<div class='col-sm-2'><input class='form-control' name='mvalorhora' id='mvalorhora' type='text' maxlength='10' value='0.00' required /></div>
					
						<label class='col-sm-2 control-label'>Custo/Hora:</label>
						<div class='col-sm-2'><input class='form-control' name='mcustohora' id='mcustohora' type='text' maxlength='10' value='0.00' required /></div>
					</div>
					
					<input name='acao' type='hidden' value='SALVAR'/>
					<div align='right'><input class='but but-azul' type='submit' value='    Salvar    '/></div>
				</form>
			</fieldset><br>
	    <?php
		}
		
		if($acao == 'SALVAR'){
			$MCODIGO    = $_POST['mcodigo'];
			$MSEGMENTO  = $_POST['segmento'];
			$MDESCRICAO = $_POST['mdescricao'];
			$MHRSPADRAO = $_POST['mhrspadrao'];
			$MVALORHORA = $_POST['mvalorhora'];
			$MCUSTOHORA = $_POST['mcustohora'];
			
			$quebraHora = explode(":",$MHRSPADRAO); 
			$minutos    = $quebraHora[0];
			$minutos    = $minutos*60;
			$MDURACAO   = $minutos+$quebraHora[1];

			$busca = mysql_query("SELECT COUNT(*) FROM modulos WHERE MCODIGO = '$MCODIGO'");
			$total = mysql_result($busca,0,"COUNT(*)");

			if ($total != 0){
				echo "<div align='center' class='warning'>Código <b>$MCODIGO</b> já cadastrado!</div>";  

			}else{
				$insert = mysql_query("INSERT INTO modulos (IDDOMINIO,IDADMINISTRADOR,MCODIGO,MSEGMENTO,MDESCRICAO,MHRSPADRAO,MVALORHORA,MCUSTOHORA,MSTATUS,MCADASTRO,MCADBY)								   
				VALUES('$dominio_session','$adm_session','$MCODIGO','$MSEGMENTO','$MDESCRICAO',$MDURACAO,'$MVALORHORA','$MCUSTOHORA',1,'$datetime','$ident_session')") or print (mysql_error());

				if($insert) {
					echo "<div id='alert' align='center' class='success'>Produto <b>$MCODIGO</b> cadastrado com sucesso!</div>";
				
				}else{
					echo "<div align='center' class='error'>Erro no cadastro de Produto! Entre em contato com o Administrador do sistema.</div>";  
				}
	    }
		}
		
		if($acao == 'EDITAR'){
			$MCODIGO = $_POST['mcodigo'];
			
			$MSelectEdit = mysql_query("SELECT * FROM modulos WHERE MCODIGO = '$MCODIGO'") or print(mysql_error());
			$MSEGMENTO  = mysql_result($MSelectEdit,0,"MSEGMENTO");
			$MDESCRICAO = mysql_result($MSelectEdit,0,"MDESCRICAO");
			$MHRSPADRAO = mysql_result($MSelectEdit,0,"MHRSPADRAO");
			$MVALORHORA = mysql_result($MSelectEdit,0,"MVALORHORA");
			$MCUSTOHORA = mysql_result($MSelectEdit,0,"MCUSTOHORA");
			
			$MHRSPADRAOminutos = $MHRSPADRAO%60;
			$MHRSPADRAOhoras   =($MHRSPADRAO-$MHRSPADRAOminutos)/60;

			if($MHRSPADRAOminutos >= -9 AND  $MHRSPADRAOminutos <= 9){
				$MHRSPADRAOminutosabs  = abs($MHRSPADRAOminutos);
				$MHRSPADRAOminutos     = "0$MHRSPADRAOminutosabs";

			}else{
			  $MHRSPADRAOminutos = abs($MHRSPADRAOminutos);
			}
			
			if($MSEGMENTO != '999'){
				$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGCODIGO = '$MCODIGO'") or print(mysql_error());
			  $SEGDESCRICAO = mysql_result($SEGselect,0,"SEGDESCRICAO");
				
			}else{
				$SEGDESCRICAO = 'INDIFERENTE';
			}
			
			
			
			?>
			  <fieldset><legend class='subpageName'>Editar Produto</legend>

					<div align='right'>
						<form name='fechar' method='POST' action='form_cadastro_modulos.php'>
							<input class='inputsrc' type='image' src='../imagens/close.png' title='Fechar'/>
						</form>
					</div>
						
					<form class='form-horizontal' name='editar' method='POST' action="form_cadastro_modulos.php" onsubmit='return confirma_edicao();'>
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Código:</label>
							<div class='col-sm-2'><input class='form-control' name='mcodigo' type='text' id='mcodigo' onkeyup="maiuscula(this)" maxlength="10" required value='<?php echo $MCODIGO;?>' readonly /></div>
						</div>

						<div class='form-group'>
							<label class='col-sm-6 control-label'>Segmento:</label>
							<div class='col-sm-6'>
								<select name='segmento' class='form-control' required >
									<option value="<?php echo $MSEGMENTO;?>"><?php echo $SEGDESCRICAO;?></option>
									<?php
								
										$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGSTATUS = 1 ORDER BY SEGCODIGO") or print(mysql_error());
										$SEGtotal = mysql_num_rows($SEGselect);
										
										if($SEGtotal > 0){
											while($SEGlinha = mysql_fetch_array($SEGselect)){
												$SEGCODIGO = $SEGlinha["SEGCODIGO"];
												$SEGDESCRICAO = $SEGlinha["SEGDESCRICAO"];
												
												echo "<option value='$SEGCODIGO'>$SEGDESCRICAO ($SEGCODIGO)</option>";										
												
											}
											
										}else{
											echo "<option value=''>Nenhum segmento ativo.</option>";
										}
								
									?>
									
									<option value='999' title='Aplicável a todos os Segmentos do Portfólio'>INDIFERENTE</option>											
								</select>
							</div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Descrição:</label>
							<div class='col-sm-4'><input class='form-control' name="mdescricao" type="text" id="modulo" maxlength="100" onkeyup="maiuscula(this)" required value='<?php echo $MDESCRICAO;?>' /></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Duração padrão(h):</label>
							<div class='col-sm-2'><input class='form-control' name='mhrspadrao' type='text' id='mhrspadrao' value='<?php echo "$MHRSPADRAOhoras:$MHRSPADRAOminutos";?>' maxlength='6'/></div>
						</div>
						
						<div class='form-group'>
							<label class='col-sm-6 control-label'>Valor/Hora:</label>
							<div class='col-sm-2'><input class='form-control' name='mvalorhora' id='mvalorhora' type='text' maxlength='10' value='<?php echo $MVALORHORA;?>' required /></div>
						
							<label class='col-sm-2 control-label'>Custo/Hora:</label>
							<div class='col-sm-2'><input class='form-control' name='mcustohora' id='mcustohora' type='text' maxlength='10' value='<?php echo $MCUSTOHORA;?>' required /></div>
						</div>
					
						
						<input name='acao' type='hidden' value='CONFIRMAR_EDICAO'/>
						<div align='right'><input class='but but-azul' type='submit' value='    Salvar    '/></div>
					</form>
				</fieldset><br>
			
			<?php
		}
		
		if($acao == 'CONFIRMAR_EDICAO'){
			$MCODIGO    = $_POST['mcodigo'];
			$MSEGMENTO  = $_POST['segmento'];
			$MDESCRICAO = $_POST['mdescricao'];
			$MHRSPADRAO = $_POST['mhrspadrao'];
			$MVALORHORA = $_POST['mvalorhora'];
			$MCUSTOHORA = $_POST['mcustohora'];
			
			$quebraHora = explode(":",$MHRSPADRAO); 
			$minutos    = $quebraHora[0];
			$minutos    = $minutos*60;
			$MDURACAO   = $minutos+$quebraHora[1];
			
			$Mupdate = mysql_query("UPDATE modulos SET MSEGMENTO = '$MSEGMENTO', 
			                                          MDESCRICAO = '$MDESCRICAO', 
																								MHRSPADRAO = $MDURACAO,
                                                MVALORHORA = '$MVALORHORA',
																								MCUSTOHORA = '$MCUSTOHORA' WHERE MCODIGO = '$MCODIGO'") or print (mysql_error());
			
			if($Mupdate){
				echo "<div id='alert' align='center' class='success'>Módulo <b>$MCODIGO</b> alterado com sucesso.</div>";
			}
		}
		
		if($acao == 'ATIVAR'){
			$MCODIGO = $_POST['mcodigo'];
			$Mupdate = mysql_query("UPDATE modulos SET MSTATUS=1,MDTSTATUS='$datetime',MUSRSTATUS='$ident_session' WHERE MCODIGO='$MCODIGO' ") or print(mysql_error());

			if($Mupdate){
			  echo "<div id='alert' align='center' class='success'>Produto <b>$MCODIGO</b> ativado com sucesso.</div>";
			}
		}

		if($acao == 'INATIVAR'){
			$MCODIGO = $_POST['mcodigo'];
			$Mupdate = mysql_query("UPDATE modulos SET MSTATUS=0,MDTSTATUS='$datetime',MUSRSTATUS='$ident_session' WHERE MCODIGO='$MCODIGO' ") or print(mysql_error());

			if($Mupdate){
			  echo "<div id='alert' align='center' class='success'>Produto <b>$MCODIGO</b> desativado com sucesso.</div>";
			}
		}			
	}//Fim das ações.
			
		$Mselect = mysql_query("SELECT * FROM modulos ORDER BY MDESCRICAO");
		$Mtotal = mysql_num_rows($Mselect);

		if($Mtotal != 0){
			echo"
			
			<table id='DataTables' class='display table table-responsive table-condensed table-action table-striped'>
			<thead>
				<tr>
					<th width='2%' ><div align='center'>               </div></th>
					<th width='10%'><div align='center'>Código         </div></th>
					<th width='35%'><div>Descrição      </div></th>
					<th width='20%'><div align='center'>Segmento       </div></th>
					<th width='10%'><div align='center'>Duração Default</div></th>
					<th width='10%'><div align='center'>Custo/Hora(R$)</div></th>
					<th width='10%'><div align='center'>Valor/Hora(R$)</div></th>
					<th width='3%' data-orderable='false'>
						<div align='center'>
							<form name='add' method='POST' action='form_cadastro_modulos.php'>
								<input name='acao'      type='hidden' value='INCLUIR'/>
								<input class='inputsrc' type='image'  src='../imagens/add3.png' title='Incluir'/>
							</form>
						</div>					
					</th>
				</tr>
			</thead>
			<tbody>";
			
			while ($linha = mysql_fetch_array($Mselect)){
				$mid        = $linha['MID'];
				$MCODIGO    = $linha['MCODIGO'];
				$MSEGMENTO  = $linha['MSEGMENTO'];
				$MDESCRICAO = $linha['MDESCRICAO'];
				$MHRSPADRAO = $linha['MHRSPADRAO'];
				$MCUSTOHORA = $linha['MCUSTOHORA'];
				$MVALORHORA = $linha['MVALORHORA'];
				$MSTATUS    = $linha['MSTATUS'];
				$MDTSTATUS  = $linha['MDTSTATUS'];
				$MUSRSTATUS = $linha['MUSRSTATUS'];
				
				$MHRSPADRAOminutos = $MHRSPADRAO%60;
				$MHRSPADRAOhoras   =($MHRSPADRAO-$MHRSPADRAOminutos)/60;

				if ($MHRSPADRAOminutos >= -9 AND $MHRSPADRAOminutos <= 9){
						$MHRSPADRAOminutosabs  = abs($MHRSPADRAOminutos);
						$MHRSPADRAOminutos     =   "0$MHRSPADRAOminutosabs";

				}else{
					$MHRSPADRAOminutos = abs($MHRSPADRAOminutos);
				}
				
				$mvalorhora = number_format($MVALORHORA,2,",",".");
			  $mcustohora = number_format($MCUSTOHORA,2,",",".");
				
				if($MSEGMENTO != '999'){
					$SEGselect = mysql_query("SELECT * FROM segmentos WHERE SEGCODIGO = '$MSEGMENTO'") or print(mysql_error());
					$SEGDESCRICAO = mysql_result($SEGselect,0,"SEGDESCRICAO");
					
				}else{
					$SEGDESCRICAO = 'INDIFERENTE';
				}
				
				switch($MSTATUS){
					case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='INATIVO'/>";break;
					case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='ATIVO'/>"; break;
				}
				
				echo "
				<tr>
					<td data-order='$MSTATUS'><div align='center'>$situacao</div></td>
					<td><div align='center'><b>$MCODIGO</b></div></td>
					<td><div>$MDESCRICAO</div></td>
					<td><div align='center'>$SEGDESCRICAO ($MSEGMENTO)</div></td>
					<td><div align='center'>$MHRSPADRAOhoras:$MHRSPADRAOminutos h</div></td>
					<td><div align='center'>$mcustohora</div></td>
					<td><div align='center'>$mvalorhora</div></td>
					<td>
						<div id='cssmenu' align='center'>
								<ul>
									<li class='has-sub'><a href='#'><span></span></a>
										<ul>
											<li>
												<form name='addrotina' method='POST' action='form_cadastro_rotinasXmodulo.php'>
													<input name='mcodigo'   type='hidden' value='$MCODIGO'/>
													<input class='inputsrc' type='image'  src='../imagens/rotinas.png' title='Módulos/MacroProcessos'/>
												</form>
											</li>
											<li>
												<form name='problemas' method='POST' action='form_cadastro_problemas.php'>
													<input name='mcodigo'   type='hidden' value='$MCODIGO'/>
													<input name='origem'    type='hidden' value='MODULO'/>
													<input class='inputsrc' type='image'  src='../imagens/bug.png' title='Problemas neste Nível'/>
												</form>
											</li>
											<li>
												<form name='editar' method='POST' action='form_cadastro_modulos.php'>
													<input name='mcodigo'    type='hidden' value='$MCODIGO'/>
													<input name='acao'       type='hidden' value='EDITAR'/>
													<input class='inputsrc'  type='image'  src='../imagens/edit.png' title='Editar'/></input>
												</form>
											</li>";
					
											if($MSTATUS == 1){//BLOQUEAR
												echo "
												<li>
													<form name='bloquear' method='POST' action='form_cadastro_modulos.php' onsubmit='return confirma_inativar()'>
														<input name='mcodigo'   type='hidden' value='$MCODIGO'/>
														<input name='acao'      type='hidden' value='INATIVAR'/>
														<input class='inputsrc' type='image'  src='../imagens/icone_omitido.png' title=' Inativar '/>
													</form>
												</li>";
											}
					
											if($MSTATUS == 0){//DESBLOQUEAR
												echo "
												<li>
													<form name='desbloquear' method='POST' action='form_cadastro_modulos.php' onsubmit='return confirma_ativar()'>
														<input name='mcodigo'   type='hidden' value='$MCODIGO'/>
														<input name='acao'      type='hidden' value='ATIVAR'/>
														<input class='inputsrc' type='image'  src='../imagens/icone_omitido.png' title=' Ativar '/>
													</form>
												</li>";
											}
										
										echo "
									</ul>
								</li>
							</ul>
						</div>
					</td>
				</tr>";
			}
			
			echo "</tbody>
			</table>";
		
		}else{
			echo "<div align='center' class='info'>Nenhum produto cadastrado!</div>
			<div align='center'>
				<form name='add' method='POST' action='form_cadastro_modulos.php'>
					<input name='acao'      type='hidden' value='INCLUIR'/>
					<input class='inputsrc' type='image'  src='../imagens/add3.png' title='Incluir'/>
				</form>
			</div>";
		}
		?>
	</div>
</div>
</body>
</html>