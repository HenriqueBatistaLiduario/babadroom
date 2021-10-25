<?php include 'head.php'; ?>

	<script type="text/javascript">
		function confirma_atualizacao(){
			if(confirm('ATENÇÃO! CONFIRMA A ATUALIZAÇÃO DA MATRIZ DE CONHECIMENTO?\n\nUma nova atualização só poderá ser realizada daqui a X meses, portanto, certifique de ter revisto todos os dados informados.\n\nClique em Ok para continuar...'))
				return true;
			else
				return false;
		}
	</script>
	
	<body>
	<div class='container-fluid'>
	
<?php 

  $MCRECURSO = $_POST['idrecurso'];
  
	$Rselect = mysql_query("SELECT * FROM recursos WHERE RCPFCNPJ = '$MCRECURSO'") or print (mysql_error());
	$Rexiste = mysql_num_rows($Rselect);
	
	if($Rexiste > 0){
		$RNOME = mysql_result($Rselect,0,"RNOME");
		
		echo"
		<div class='panel panel-default'>
								
			<div class='panel-heading'>
				<span class='subpageName'>Matriz de Conhecimento de <b>$RNOME ($MCRECURSO)</b></span>
				<ul class='pull-right list-inline'>
					<li><a href='form_cadastro_recursos.php' title='Fechar'><i class='fa fa-times'></i></a></li>
				</ul>
			</div>
			
			<div class='panel-body'>";
		
				$USERselect = mysql_query("SELECT * FROM usuarios WHERE USERIDENTIFICACAO = '$MCRECURSO'") or print(mysql_error());
				$USERFOTO = mysql_result($USERselect,0,"USERFOTO");

				if ($USERFOTO == 1){
					$userfoto = "<img class='foto_perfilP' src='../$DOMINIO/imagens/Fotos/$MCRECURSO.jpg'/>";

				}else{
					$userfoto = "<img class='foto_perfilP' src='../imagens/Fotos/SEMFOTO.png'/>";
				}
				
				$acao = NULL;
				
				if(isset($_POST['acao'])){
					$acao = $_POST['acao'];
				}
				
				if(isset($_GET['acao'])){
					$acao = $_GET['acao'];
				}
					
				if($acao == 'SALVAR'){
					
					$MCdelete = mysql_query("DELETE FROM mconhecimento WHERE MCRECURSO = '$MCRECURSO'") or print (mysql_error());
					
					if($MCdelete){
						$proximo=0;
						$proximo2=0;
						
						$modulos = mysql_query("SELECT DISTINCT * FROM modulos WHERE MSTATUS = 1 ORDER BY MCODIGO")or print (mysql_error());

						while($linha = mysql_fetch_array($modulos)){ // Pega os módulos e insere no Banco... Matriz dinâmica.
							$MSEGMENTO = $linha["MSEGMENTO"];
							$MCODIGO   = $linha["MCODIGO"];
							
							$MCNIVEL   = $_POST['nivel'][$proximo];$proximo++;
							$MCTEMPEXP = $_POST['exp'][$proximo2];$proximo2++;

							$MCinsert = mysql_query("INSERT INTO mconhecimento(MCRECURSO,MCSEGMENTO,MCCODMODULO,MCNIVEL,MCTEMPEXP,MCCADASTRO,MCCADBY) VALUES 
							('$MCRECURSO', '$MSEGMENTO', '$MCODIGO', $MCNIVEL, $MCTEMPEXP, '$datetime', '$ident_session')") or print (mysql_error());
						}
						
						if($MCinsert){
							
							if(isset($_POST['estudando'])){
								foreach($_POST['estudando'] as $MCCODMODULO){
									$UPDATE = mysql_query("UPDATE mconhecimento SET MCESTUDANDO = 1, MCESTUDADESDE= '$datetime' WHERE MCCODMODULO = '$MCCODMODULO' AND MCRECURSO = '$MCRECURSO'");
								}
							}
							
							$Rupdate = mysql_query("UPDATE recursos SET RSTATUS = 3, RDTSTATUS = '$datetime', RUSRSTATUS = '$ident_session' WHERE RCPFCNPJ = '$MCRECURSO'") or print (mysql_error());
							
							if($Rupdate){
								echo "<div align='center' class='success'>Matriz de Conhecimento atualizada com sucesso.<br>Aguarde avaliação do Gestor.</div>";
								exit;
							}
						 
						}else{
							echo "<div align='center' class='error'>Matriz de Conhecimento não atualizada. Entre em contato com o Gestor.</div>";
						}
					}			
				}

				if($acao == 'ATUALIZAR'){		
					echo "<fieldset><legend class='subpageName'>&nbsp;&nbsp;Atualizar Matriz de Conhecimento&nbsp;&nbsp;</legend>";
					
					$Mselect = mysql_query("SELECT DISTINCT * FROM modulos WHERE MSTATUS = 1 ORDER BY MCODIGO");
					$Mtotal = mysql_num_rows($Mselect);
					
					if($Mtotal > 0){
						echo"
						<form name='cadastromc' method='POST' action='form_cadastro_mconhecimento.php' onsubmit='return confirma_atualizacao();'>
							<table align='center' width='100%' class='table table-responsive table-condensed table-striped table-action'>
								<thead>
									<tr>
										<th width='10%'><div align='center'>Segmento              </div></th>
										<th width='40%'><div align='center'>Módulo                </div></th>	  
										<th width='15%'><div align='center'>Nível de Conhecimento </div></th>
										<th width='15%'><div align='center'>Experiência (em meses)</div></th>
										<th width='20%'><div align='center'>Estudando?            </div></th>
									</tr>
								</thead>
								<tbody>";

							while ($linha = mysql_fetch_array($Mselect)){
								$MCODIGO    = $linha["MCODIGO"];
								$MSEGMENTO  = $linha["MSEGMENTO"];
								$MDESCRICAO = $linha["MDESCRICAO"];
								
								$MCselect = mysql_query("SELECT * FROM mconhecimento WHERE MCCODMODULO = '$MCODIGO' AND MCRECURSO = '$MCRECURSO' ORDER BY MCCODMODULO");
								$total = mysql_num_rows($MCselect);
								
								if($total > 0){
									$MCNIVEL     = mysql_result($MCselect,0,"MCNIVEL");
									$MCTEMPEXP   = mysql_result($MCselect,0,"MCTEMPEXP");
									$MCESTUDANDO = mysql_result($MCselect,0,"MCESTUDANDO");
									
									if($MCESTUDANDO == 1){
										
										$MCESTUDADESDE = mysql_result($MCselect,0,"MCESTUDADESDE");
										$mcestudadesde = date('d/m/Y',strtotime($MCESTUDADESDE));
										
										$estudando = "<input class='inputcheck' name='estudando[]' type='checkbox' checked value='$MCODIGO'/> desde $MCESTUDADESDE";
									
									}else{
										$estudando = "<input class='inputcheck' name='estudando[]' type='checkbox' value='$MCODIGO'/>";
									}
								}
							
								echo "
								<tr>
									<td><div align='center' name='segmento'>$MSEGMENTO</div></td>
									<td><div align='left'   name='modulo'  >&nbsp;&nbsp;$MCODIGO | $MDESCRICAO</div></td>							
									<td>
										<div align='center'>
											<select name='nivel[]' class='form-control' required >";
											if($total >0){ echo "<option value=$MCNIVEL>$MCNIVEL</option>";} else {echo "<option value=''>Selecione...</option>";}
											
											echo"
												<option value=0>0</option>
												<option value=1>1</option>
												<option value=2>2</option>
												<option value=3>3</option>
												<option value=4>4</option>
												<option value=5>5+</option>
											</select>
										</div>
									<td><div align='center'>
									<input class='form-control' name='exp[]' type='number' min='0' max='840'"; if ($total >0){echo " value='$MCTEMPEXP'";} echo "required />
									</div>
									</td>
									<td><div align='center'>";if($total > 0){echo $estudando;}else{ echo "<input class='inputcheck' name='estudando[]' type='checkbox' value='$MCODIGO'/>";}echo "</div></td>
								</tr>";
							}
							
							echo"
							</table>
							<input name='idrecurso' type='hidden' value='$MCRECURSO'>
							<input name='acao'      type='hidden' value='SALVAR'><br>
							<div align='right'><input class='but but-azul' type='submit' value='      Salvar      '/></div>
						</form>";
						
					}else{
						
						echo "<div class='info'>Nenhum Módulo cadastrado. Cadastre os Módulos primeiro.</div>";
					}
					
					echo"
							
					</fieldset>";
					exit;
				}
		
				$MCselect = mysql_query("SELECT * FROM mconhecimento WHERE MCRECURSO = '$MCRECURSO' ORDER BY MCCODMODULO");
				$MCtotal  = mysql_num_rows($MCselect);

				if ($MCtotal > 0){
					echo "<ul class='list-inline pull-right'>";
					
						if(($perfil_session == 'ADM' OR $perfil_session == 'GP') AND $method = 'POST'){
							echo"
							<li>
								<form name='analisar' method='POST' action='analisar_perfil.php'>
									<input name='usernome'   type='hidden' value='$RNOME'/>
									<input name='userident'  type='hidden' value='$MCRECURSO'/>
									<button type='submit' class='btn btn-info btn-sm'>Avaliar Recurso</button>
								</form>
							</li>";
						}
						
						echo"
						<li>		
							<form name='atualizar' method='POST' action='form_cadastro_mconhecimento.php'>
							  <input name='dominio'   type='hidden' value='$dominio_session'/>
								<input name='idrecurso' type='hidden' value='$MCRECURSO'/>
								<input name='acao'      type='hidden' value='ATUALIZAR'/>
								<button type='submit' class='btn btn-info btn-sm'>Atualizar Matriz</button>
							</form>
						</li>
					</ul>
									
					<table class='table table-condensed table-bordered table-action table-striped' align='center'>
						<thead>
							<tr>
								<th width='2%' ><div align='center'>$userfoto             </div></th>
								<th width='10%'><div align='center'>Segmento              </div></th>
								<th width='23%'><div>Módulo                               </div></th>	  
								<th width='14%'><div align='center'>Nível de Conhecimento </div></th>
								<th width='14%'><div align='center'>Experiência (em meses)</div></th>
								<th width='8%'><div align='center'>Estudando?             </div></th>
								<th width='15%'><div align='center'>Última atualização    </div></th>
								<th width='15%'><div align='center'>Última avaliação      </div></th>
							</tr>
						</thead>
						<tbody>";
							
						while ($linha = mysql_fetch_array($MCselect)){
							$MCSEGMENTO    = $linha["MCSEGMENTO"];
							$MCMODULO      = $linha["MCCODMODULO"];
							$MCNIVEL       = $linha["MCNIVEL"];
							$MCTEMPEXP     = $linha["MCTEMPEXP"];
							$MCESTUDANDO   = $linha["MCESTUDANDO"];
							$MCESTUDADESDE = $linha["MCESTUDADESDE"];
							$MCCADASTRO    = $linha["MCCADASTRO"];
							
							$estudadesde = date("d/m/Y H:i",strtotime($MCESTUDADESDE));
							$dtultatual  = date("d/m/Y H:i",strtotime($MCCADASTRO));
							
							$MCDTULTAVAL = $linha['MCDTULTAVAL'];
							
							if($MCDTULTAVAL != NULL){
								$dtultaval = date("d/m/Y H:i",strtotime($MCDTULTAVAL));
							
							}else{
								$dtultaval = 'NUNCA AVALIADO';
							}
							
							$MCAPTO  = $linha["MCAPTO"];
							
							if($MCESTUDANDO == 1){
								$mcestudando = "SIM desde $estudadesde";
							
							}else{
								$mcestudando = "NÃO";
							}
							
							switch($MCAPTO){
								case 0: $resultado = "<img src='../imagens/status/VERMELHO.png' title='Resultado da última avaliação: INAPTO'/>"; break;
								case 1: $resultado = "<img src='../imagens/status/VERDE.png'    title='Resultado da última avaliação: APTO'/>";  break;
							}

							$Mselect = mysql_query("select MDESCRICAO from modulos WHERE MCODIGO = '$MCMODULO' ORDER BY MCODIGO") or print (mysql_error());
							$MDESCRICAO = mysql_result($Mselect,0,"MDESCRICAO");

							echo "
							<tr>
								<td><div align='center'>$resultado</div></td>
								<td><div align='center'>$MCSEGMENTO</div></td>
								<td><div>$MCMODULO | $MDESCRICAO</div></td>
								<td><div align='center'><b>$MCNIVEL</b></div></td>
								<td><div align='center'>$MCTEMPEXP</div></td>
								<td><div align='center'>$mcestudando</div></td>
								<td><div align='center'>$dtultatual</div></td>
								<td><div align='center'>$dtultaval</div></td>
							</tr>";
						}
					
						echo "
						</tbody>
					</table>";
				}
				
				else{
					echo "<div align='center' class='info'>Este profissional ainda não preencheu a Matriz de Conhecimento.</div>
					<div align='right'>
						<form name='atualizar' method='POST' action='form_cadastro_mconhecimento.php'>
							<input name='idrecurso' type='hidden' value='$MCRECURSO'/>
							<input name='acao'      type='hidden' value='ATUALIZAR'/>
							<button type='submit' class='btn btn-info btn-sm'><i class='fa fa-edit'></i>&nbsp;&nbsp;Preencher agora</button>
						</form>
					</div>";
					
				}
			
			}else{
				echo "<div align='center' class='warning'>Link inválido ou recurso inexistente.</div>";
			}
				?>
		</div>
	</div>
</div>
</body>
</html>