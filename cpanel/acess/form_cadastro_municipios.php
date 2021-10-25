<?php include 'head.php';?>
<!DOCTYPE HTML>
<html>
<title>Municípios</title>
<head><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body>
<div class='container-fluid'>
<div class='panel panel-default'>

		<div class='panel-heading'>
			<span class='subpageName'>Municípios</span>

			<ul class='pull-right list-inline'>
				<li>
					<ul class='pull-right list-inline'>
						<li><a href='#' title='Ocultar' onclick="recolher_painel('painel_clientes')"><i class='fa fa-arrows-v' aria-hidden='true'></i></a></li>
					</ul>
				</li>
			</ul>
		</div>

		<div class='panel-body' id='painel_clientes'>

<?php
	
		$Cselect = mysql_query("SELECT * FROM cidades ORDER BY CSIGLAESTADO") or print(mysql_error());
		$Ctotal  = mysql_num_rows($Cselect);
	
	  if($Ctotal > 0){
	    echo"
			
			<table id='DataTables' class='display table table-responsive table-condensed table-bordered table-action table-striped'>	
				<thead>			
					<tr>
						<th width='2%' ><div align='center'>      </div>     </th>
						<th width='4%' ><div align='center'>      </div>     </th>
						<th width='20%'><div>Nome Estado</div> </th>
						<th width='10%'><div align='center'>Cod. Cidade</div></th>
						<th width='44%'><div>Nome Cidade</div> </th>
						<th width='10%'><div align='center'>CEP</div></th>
						<th width='10%'><div align='center'>Cod. Região</div>       </th>
						<th width='3%' data-orderable='false'>
							
						</th>
					</tr>
				</thead>
			<tbody>";
			
			while ($linha = mysql_fetch_array($Cselect)){
			  $CID          = $linha['CID'];         
				$CCODESTADO   = $linha['CCODESTADO'];    
				$CSIGLAESTADO = $linha['CSIGLAESTADO'];  
				$CNOMEESTADO  = $linha['CNOMEESTADO'];   
				$CCODCIDADE   = $linha['CCODCIDADE'];    
				$CNOMECIDADE  = $linha['CNOMECIDADE'];   
				$CCEP         = $linha['CCEP'];          
				$CCODREGIAO   = $linha['CCODREGIAO'];    
					
				echo"
					<tr>
					  <td><div align='center'>$CID</div></td>
						<td><div align='center'>$CCODESTADO</div></td>
						<td><div>$CNOMEESTADO</div></td>
						<td><div align='center'><b>$CCODCIDADE</b></div></td>
						<td><div >$CNOMECIDADE</div></td>
						<td><div align='center'>$CCEP</div></td>
						<td><div align='center'>$CCODREGIAO</div></td>
						<td></td>
					</tr>";
			}
			
			echo "</tbody></table>";
		
		}else{
			echo "<div align='center' class='info'>Nenhum município cadastrado.<br>Você pode importar a maioria dos municípios cadastrados no IBGE, através da rotina de importação.</div>
		   
		  <div align='center'>
				<form name='importar' method='POST' action='importar_municipios.php' onsubmit='return confirma_importacao();'>
					<input name='acao' type='hidden' value='IMPORTAR'/>
					<button type='submit' class='btn btn-info btn-lg'><i class='fa fa-magnet' aria-hidden='true'></i>&nbsp;&nbsp;Processar importação</button>			
				</form>			
			</div>";
		
		}
		?>
	</div>
</div>
</div>
</body>
</html>