<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" rel="stylesheet"
        crossorigin="anonymous" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.10/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

    <link href="https://cdn.www.gov.co/v3/assets/cdn.min.css" rel="stylesheet">
    
    <title>Document</title>
</head>

<body>
   <br><br>
   <div class="gov-co-advance">
      <div class="progress">
         <div class="progress-bar" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="navs-link-advance">
         <a class="nav-link-advance advance" value="1"> <span>1</span> Inicio </a>
         <a class="nav-link-advance advance" value="2"> <span>2</span> Hago mi solicitud </a>
         <a class="nav-link-advance advance" value="3"> <span>3</span> Procesan mi solicitud </a>
         <a class="nav-link-advance advance" value="4"> <span>4</span> Respuesta </a>
      </div>
   </div>


   <div class="card gov-co-tramite-card">
      <div class="card-header text-center">Radicar trámite</div>
      <div class="card-body">
         <p class="card-text">Inicie el trámite de legalización de los documentos de educación superior.</p>
      </div>
   </div>
   
   <!-- card en estado seleccionado se debe agregar la clase active -->
   <div class="card gov-co-tramite-card active">
      <div class="card-header text-center">Radicar trámite</div>
      <div class="card-body">
         <p class="card-text">Inicie el trámite de legalización de los documentos de educación superior.</p>
      </div>
   </div>
   
   <!-- card en estado Inhabilitado se debe agregar la clase disabled -->
   <div class="card gov-co-tramite-card disabled">
      <div class="card-header text-center">Radicar trámite</div>
      <div class="card-body">
         <p class="card-text">Inicie el trámite de legalización de los documentos de educación superior.</p>
      </div>
   </div>

   <!-- Unico archivo -->
<div class="form-group">
   <label for="input-file-simple-label">
      Archivo(s) soporte de vinculación: <br />
      <span>Tipo de archivo permitido .PDF hasta de 2MB</span>
   </label>
   <div class="custom-file file-govco">
      <input type="file" name="inputTypeExample" class="custom-file-input input-file-govco" onchange= id="input-file-simple"/>
      <label class="custom-file-label label-file-govco" ondrop="utils.dropHandler(event, 'input-file-simple')" ondragover="utils.dragOverHandler(event, 'input-file-simple')" for="input-file-simple">Arrastre aquí su(s) archivo(s) o haga click para añadir.</label>
   </div>
</div>

<!-- Multiples archivo -->
<div class="form-group">
   <label for="input-file-simple-label">
      Archivo(s) soporte de vinculación: <br />
      <span>Tipo de archivo permitido .PDF hasta de 2MB</span>
   </label>
   <div class="custom-file file-govco">
      <input type="file" name="inputTypeExample" class="custom-file-input input-file-govco" id="input-file-simple" multiple/>
      <label class="custom-file-label label-file-govco" ondrop="utils.dropHandler(event, 'input-file-simple')" ondragover="utils.dragOverHandler(event, 'input-file-simple')" for="input-file-simple">Arrastre aquí su(s) archivo(s) o haga click para añadir.</label>
   </div>
</div>
 
  






</body>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.10/dist/js/bootstrap-select.min.js" type="text/javascript" ></script>

<script script src="https://cdn.www.gov.co/v3/assets/js/utils.js"></script>
<script>
  
const configDataFileSimple = {
   label: 'Arrastre aquí su(s) archivo(s) o haga click para añadir.',
   id: 'input-file-simple',
};
utils.initInputFile(configDataFileSimple);


const configDataFileMultiple = {
   label: 'Arrastre aquí su(s) archivo(s) o haga click para añadir.',
   id: 'input-file-multiple',
};

utils.initInputFile(configDataFileMultiple);
</script>

</html>
