<html>  
    <head>  
        <title>Calcular Frete</title>  
		
		<script src="jquery.min.js"></script>  
		<script src="bootstrap.min.js"></script>
		<link rel="stylesheet" href="bootstrap.min.css" />
		
    </head>  
    <body>  
        <div class="container">
          <br />
      <h3 align="center">CONSULTA FRETE CORREIOS</h3>
      <br />
      <br />
			<div class="panel panel-default">
  				<div class="panel-heading">Informe os dados para caluclar o FRETE</div>
				
				<form action="calculando-frete.php" method="get">
  				<div class="row panel-body" align="left">
				<div class="col-md-12">
				
				<div class="col-md-3">
					<label>SERVIÇO</label>
  					<select class="form-control" name="servico">
						<option value="SEDEX">SEDEX</option>
						<option value="PAC">PAC</option>
					</select>
  				</div>
				
				<div class="col-md-3">
					<label>CEP ORIGEM</label>
  					<input type="text" class="form-control" name="origem" id="cep-origem" />  				
  				</div>
				
				<div class="form-group col-md-3">
					<label>CEP DESTINO</label>
  					<input type="text" class="form-control" name="destino" id="cep-destino" />  				
  				</div>
				
				<div class="form-group col-md-3">
					<label>PESO EM KG</label>
  					<input type="text" class="form-control" name="peso" id="peso" />  				
  				</div>
				
				<div class="form-group col-md-4">
					<label>ALTURA</label>
  					<input type="text" class="form-control" name="altura" id="peso" />  				
  				</div>
				
				<div class="form-group col-md-4">
					<label>LARGURA</label>
  					<input type="text" class="form-control" name="largura" id="peso" />  				
  				</div>
				
				<div class="form-group col-md-4">
					<label>COMPRIMENTO</label>
  					<input type="text" class="form-control" name="comprimento" id="peso" />  				
  				</div>
				
				<div class="form-group">
				<button class="btn btn-success col-md-offset-3 col-md-6">Calcular Frete</button>
				</div>
				</div>
				</form>
  			</div>
  		</div>
    </body>  
</html>