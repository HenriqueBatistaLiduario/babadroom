<?php include 'head.php';?>

	<script>

		function consistencia(){
			
			if(
			(document.getElementById('PRMSGA0002').value != 'C'  && document.getElementById('PRMSGA0002').value != 'D') ||
			(document.getElementById('PRMSGA0003').value != 'SA' && document.getElementById('PRMSGA0003').value != 'T') || 
			(document.getElementById('PRMSGA0004').value != 'SS' && document.getElementById('PRMSGA0004').value != 'T') || 
			(document.getElementById('PRMSGA0005').value != 'SA' && document.getElementById('PRMSGA0005').value != 'T') || 
			(document.getElementById('PRMSGA0006').value != 'SS' && document.getElementById('PRMSGA0006').value != 'T') ||
			
			(parseInt(document.getElementById('PRMSGA0007').value) < 0) || 
			(parseInt(document.getElementById('PRMSGA0007').value) > 3) || 
			(parseInt(document.getElementById('PRMSGA0008').value) < 0) || 
			(parseInt(document.getElementById('PRMSGA0008').value) > 3) || 
			(parseInt(document.getElementById('PRMSGA0009').value) < 0) || 
			(parseInt(document.getElementById('PRMSGA0009').value) > 5) || 
			
			(parseInt(document.getElementById('PRMSGA0010').value) < 0) || 
			(parseInt(document.getElementById('PRMSGA0010').value) > 4) ||
			(parseInt(document.getElementById('PRMSGA0013').value) < 1) ||
			(parseInt(document.getElementById('PRMSGA0013').value) > 40) ||
			
			(parseInt(document.getElementById('PRMSGA0014').value) < 3) ||
			(parseInt(document.getElementById('PRMSGA0015').value) < 3) ||
			(parseInt(document.getElementById('PRMSGA0016').value) < 3) ||
			(parseInt(document.getElementById('PRMSGA0017').value) < 3) ||
			(parseInt(document.getElementById('PRMSGA0018').value) < 3) ||
			(parseInt(document.getElementById('PRMSGA0019').value) < 3) ||
			
			
			){
			alert ('Conteúdo inválido encontrado em parâmetro');
			
				return false;

			}else{
				return true
			}
		}

	</script>
	
	<body>
		<div class='container-fluid'>

			<?php 

				if(isset($_POST['acao'])){
					$acao = $_POST['acao'];
					
					if($acao == 'SALVAR'){
						
						$PRMGLB0001  = $_POST['PRMGLB0001'];
						$PRMGLB0002  = $_POST['PRMGLB0002'];
						$PRMGLB0003  = $_POST['PRMGLB0003'];
						$PRMGLB0004  = $_POST['PRMGLB0004'];
						$PRMGLB0005  = $_POST['PRMGLB0005'];
						$PRMGLB0006  = $_POST['PRMGLB0006'];
						$PRMGLB0007  = $_POST['PRMGLB0007'];
						$PRMGLB0008  = $_POST['PRMGLB0008'];
						$PRMGLB0009  = $_POST['PRMGLB0009'];
						
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0001'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0002', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0002'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0003', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0003'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0004', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0004'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0005', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0005'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0006', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0006'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0007', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0007'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0008', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0008'") or print (mysql_error());
						$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGLB0009', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGLB0009'") or print (mysql_error());
						
						if($apl_session == 'SGSD'){
							
							$PRMSGA0001  = $_POST['PRMSGA0001'];
							$PRMSGA0002  = $_POST['PRMSGA0002'];
							$PRMSGA0003  = $_POST['PRMSGA0003'];
							$PRMSGA0004  = $_POST['PRMSGA0004'];
							$PRMSGA0005  = $_POST['PRMSGA0005'];
							$PRMSGA0006  = $_POST['PRMSGA0006'];
							$PRMSGA0007  = $_POST['PRMSGA0007'];
							$PRMSGA0008  = $_POST['PRMSGA0008'];
							$PRMSGA0009  = $_POST['PRMSGA0009'];
							$PRMSGA0010  = $_POST['PRMSGA0010'];
							$PRMSGA0011  = $_POST['PRMSGA0011'];
							$PRMSGA0012  = $_POST['PRMSGA0012'];
							$PRMSGA0013  = $_POST['PRMSGA0013'];
							$PRMSGA0014  = $_POST['PRMSGA0014'];
							$PRMSGA0015  = $_POST['PRMSGA0015'];
							$PRMSGA0016  = $_POST['PRMSGA0016'];
							$PRMSGA0017  = $_POST['PRMSGA0017'];
							$PRMSGA0018  = $_POST['PRMSGA0018'];
							$PRMSGA0019  = $_POST['PRMSGA0019'];
							$PRMSGA0020  = $_POST['PRMSGA0020'];
							$PRMSGA0021  = $_POST['PRMSGA0021'];
							
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0001'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0002', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0002'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0003', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0003'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0004', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0004'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0005', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0005'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0006', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0006'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0007', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0007'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0008', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0008'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0009', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0009'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0010', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0010'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0011', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0011'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0012', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0012'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0013', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0013'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0014', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0014'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0015', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0015'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0016', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0016'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0017', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0017'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0018', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0018'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0019', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0019'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0020', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0020'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGA0021', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGA0021'") or print (mysql_error());
						
						}
						
						if($apl_session == 'GOCF'){
							$PRMGOCF001  = $_POST['PRMGOCF001'];
							
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMGOCF001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMGOCF001'") or print (mysql_error());
							
						}
						
						if($apl_session == 'SGVR'){
						
							$PRMSGVR001  = $_POST['PRMSGVR001'];
							$PRMSGVR002  = $_POST['PRMSGVR002'];
							$PRMSGVR003  = $_POST['PRMSGVR003'];
							
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGVR001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGVR001'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGVR001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGVR002'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSGVR001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSGVR003'") or print (mysql_error());
						
						}
						
						if($apl_session == 'SRPB'){
						
							$PRMSRPB001  = $_POST['PRMSRPB001'];
							$PRMSRPB002  = $_POST['PRMSRPB002'];
							$PRMSRPB003  = $_POST['PRMSRPB003'];
							
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSRPB001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSRPB001'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSRPB001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSRPB002'") or print (mysql_error());
							$PARAMupdate = mysql_query("UPDATE parametros SET	PARAMCONTEUDO = '$PRMSRPB001', ULTALTERON = '$datetime', ULTALTERBY = '$ident_session' WHERE PARAMCODIGO = 'PRMSRPB003'") or print (mysql_error());
						
						}
						
						if($PARAMupdate){
							echo "<div align='center' id='alert' class='success'>Parâmetros da aplicação <b>$apl_session</b> alterados com sucesso.</div>";
						}
						
					}
				}

			?>
			
			<div class='panel panel-default'>

				<div class='panel-heading'>
					<span class='subpageName'>Parâmetros da Aplicação <b><?php echo $apl_session;?></b></span>

					<ul class='pull-right list-inline'>
						<li><a href='home.php' title='Fechar'><i class='fa fa-times'></i></a></li>
					</ul>
					
				</div>

				<div class='panel-body'>

					<?php 

					$PARAMselect = mysql_query("SELECT * FROM parametros WHERE PARAMAPLICACAO = '$apl_session' OR PARAMGLOBAL = 1 OR PARAMAPLICACAO IN ('GLB','COM') ORDER BY PARAMAPLICACAO,PARAMCODIGO") or print (mysql_error());
					$PARAMtotal = mysql_num_rows($PARAMselect);

					if($PARAMtotal > 0){

						echo "<table width='100%' class='table-responsive table-action table-bordered table-condensed table-striped'>
							<thead>
								<tr>
									<th width='2%'></th>
									<th width='10%'>Código</th>
									<th width='73%'>Descrição</th>
									<th width='15%'>Conteúdo</th>			
								</tr>	
							</thead>
							<tbody>
							<form class='form-horizontal' name='parametros' method='POST' action='parametros.php' onsubmit='return consistencia()'>
						";
						
						if($perfil_session != 'ADM'){
							$readonly = 'readonly';
							$botao = '';
							
						}else{
							$readonly = NULL;
							$botao = "<div align='right'><input class='but but-azul' type='submit' value='      Salvar alterações      '/></div>";
						}
						
						$ReadOnly = array('PRMCOM0001','PRMCOM0002','PRMCOM0003','PRMCOM0004','PRMGLB0009');
						
						while($linha = mysql_fetch_array($PARAMselect)){
							$PARAMID          = $linha["PARAMID"];
							$PARAMAPLICACAO   = $linha["PARAMAPLICACAO"];
							$PARAMCODIGO      = $linha["PARAMCODIGO"];
							$PARAMDESCRICAO   = $linha["PARAMDESCRICAO"];
							$PARAMOPCOES      = $linha["PARAMOPCOES"];
							$PARAMCONTEUDO    = $linha["PARAMCONTEUDO"];
							$PARAMSITUACAO    = $linha["PARAMSITUACAO"];
							$PARAMDTSITUACAO  = $linha["PARAMDTSITUACAO"];
							$PARAMUSRSITUACAO = $linha["PARAMUSRSITUACAO"];
							$ULTALTERON       = $linha["ULTALTERON"];
							$ULTALTERBY       = $linha["ULTALTERBY"];
							
							$type = 'text';
							
							if($PARAMCODIGO == 'PRMGLB0003'){
								
								$type = 'password';
							
							}
							
							if($PARAMCODIGO == 'PRMSGA0001' OR 
								 $PARAMCODIGO == 'PRMSGA0007' OR 
								 $PARAMCODIGO == 'PRMSGA0008' OR 
								 $PARAMCODIGO == 'PRMSGA0009' OR 
								 $PARAMCODIGO == 'PRMGLB0004' OR
								 $PARAMCODIGO == 'PRMGLB0005' OR
								 $PARAMCODIGO == 'PRMGLB0008' OR
								 $PARAMCODIGO == 'PRMCOM0001' OR
								 $PARAMCODIGO == 'PRMCOM0002' OR
								 $PARAMCODIGO == 'PRMGOCF001' OR
								 $PARAMCODIGO == 'PRMSRPB001' OR
								 $PARAMCODIGO == 'PRMSRPB003') {
								
								 $type = 'number';
							}
							
							if($PARAMCODIGO == 'PRMSGA0020' OR 
								 $PARAMCODIGO == 'PRMSGA0021'){
									 
								 $type = 'time';
							}
							
							if($PARAMCODIGO == 'PRMGLB0006'){
								$TDbgcolor = $PRMGLB0006;
							
							}else if($PARAMCODIGO == 'PRMGLB0007'){
								$TDbgcolor = $PRMGLB0007;
							
							}else{
								$TDbgcolor = NULL;
							}
							
							$NumberMinimo = 0;
							$NumberMaximo = 99999;
							
							switch($PARAMCODIGO){
								case 'PRMGLB0008': $NumberMinimo = 12; $NumberMaximo = 16; break;
								case 'PRMSGA0001': $NumberMinimo = 1;  $NumberMaximo = 365; break;
								case 'PRMSGA0007': $NumberMinimo = 0;  $NumberMaximo = 3; break;
								case 'PRMSGA0008': $NumberMinimo = 0;  $NumberMaximo = 3; break;
								case 'PRMSGA0009': $NumberMinimo = 0;  $NumberMaximo = 5; break;
								case 'PRMGOCF001': $NumberMinimo = 0;  $NumberMaximo = 1; break;
								case 'PRMSRPB001': $NumberMinimo = 0;  $NumberMaximo = 30; break;
								case 'PRMSRPB003': $NumberMinimo = 0;  $NumberMaximo = 180; break;
							}
							
							if(in_array("$PARAMCODIGO",$ReadOnly)){
								
								switch($PARAMCODIGO){
									case 'PRMCOM0001': $PARAMCONTEUDO = $PRMCOM0001; break;
									case 'PRMCOM0002': $PARAMCONTEUDO = $PRMCOM0002; break;
									case 'PRMCOM0003': $PARAMCONTEUDO = $PRMCOM0003; break;
									case 'PRMCOM0004': $PARAMCONTEUDO = $PRMCOM0004; break;
								}			
								
								$readonly = 'readonly';
							
							}else{
								$readonly = NULL;
							}
							
							switch($PARAMSITUACAO){
								case 0: $situacao = "<img src='../imagens/status/VERMELHO.png' title='Inativo'/>"; break;
								case 1: $situacao = "<img src='../imagens/status/VERDE.png'    title='Ativo'/>"; break;
							}
							
							$campo = "<input class='form-control' type='$type' min='$NumberMinimo' max='$NumberMaximo' name='$PARAMCODIGO' id='$PARAMCODIGO' value='$PARAMCONTEUDO' $readonly />";
							
							if($PARAMCODIGO == 'PRMSGA0010'){
								$campo = "
								<select class='form-control' name='PRMSGA0010'>
									<option value='MODPRDCLICAT' title='1ºMODULO 2ºPRODUTO 3ºCLIENTE 4ºCATEGORIA'>MODPRDCLICAT</option> 
									<option value='PRDCLICATMOD' title='1ºPRODUTO 2ºCLIENTE 3ºCATEGORIA 4ºMODULO'>PRDCLICATMOD</option>
									<option value='CLICATMODPRD' title='1ºCLIENTE 2ºCATEGORIA 3ºMODULO 4ºPRODUTO'>CLICATMODPRD</option>
									<option value='CATMODPRDCLI' title='1ºCATEGORIA 2ºMODULO 3ºPRODUTO 4ºCLIENTE'>CATMODPRDCLI</option>
								</select>";
							}
							
							if($PARAMCODIGO == 'PRMSGA0012' OR $PARAMCODIGO == 'PRMSGA0013' OR $PARAMCODIGO == 'PRMSGA0014' ){
								$campo = "
								<select class='form-control' name='$PARAMCODIGO'>
									<option value='MOSTRAR'>Mostrar</option> 
									<option value='OCULTAR'>Ocultar</option>
								</select>";
							}
							
							if($PARAMCODIGO == 'PRMSGA0015' OR $PARAMCODIGO == 'PRMSGA0016' OR $PARAMCODIGO == 'PRMSGA0017' OR $PARAMCODIGO == 'PRMSGA0018' OR $PARAMCODIGO == 'PRMSGA0019'){
								$campo = "<input class='form-control' type='text' name='$PARAMCODIGO' id='$PARAMCODIGO' value='$PARAMCONTEUDO' maxlenght='30' required />";						
							}
							
							if($PARAMCODIGO == 'PRMSRPB002' OR $PARAMCODIGO == 'PRMSGVR001' OR $PARAMCODIGO == 'PRMSGVR002' OR $PARAMCODIGO == 'PRMSGVR003'){ //Binários Default NÃO
								$campo = "
								<select class='form-control' name='$PARAMCODIGO'>								  
									<option value=0>Não</option> 
									<option value=1>Sim</option>									
								</select>";
								
							}
							
							echo "
								<tr>
									<td>$situacao</td>
									<td><b>$PARAMCODIGO</b></td>
									<td><b>$PARAMDESCRICAO</b><br>$PARAMOPCOES</td>
									<td bgcolor='$TDbgcolor'><div align='center'>$campo</div></td>
								</tr>";
						}
							
						echo "</tbody>
							</table>
							<br>
							<input name='acao' type='hidden' value='SALVAR'/>
							$botao
						</form>";

					}

					?>
				</div>
			</div>
		</div>
	</body>
</html>