<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav class="navbar" style="background-color: rgba(153, 2, 2, 0.91);">
  <div class="container-fluid">
    <a class="navbar-brand" style="color: rgba(255, 250, 250, 0.91);">SMG | Gestão Administrativa</a>
     
    </form>
  </div>
</nav>
    </header>

    <main class="container p-4 ">

    <div class="row d-flex justify-content-center">
  <div class="col-sm-3 mb-3 mb-sm-0">
    <div class="card bg-success">
      <div class="card-body">
        <h5 class="card-title text-light">Novas solicitações</h5>
        <p class="card-text text-light">0</p>
      </div>
    </div>

  </div>
  <div class="col-sm-3">
    <div class="card bg-warning">
      <div class="card-body">
        <h5 class="card-title text-light">Em atendimento</h5>
        <p class="card-text text-light">0</p>
      </div>
    </div>
  </div>

  <div class="col-sm-3">
    <div class="card bg-dark">
      <div class="card-body">
        <h5 class="card-title text-light">Críticos/urgente</h5>
        <p class="card-text text-light">0</p>
      </div>
    </div>
  </div>
</div>

<div class="row d-flex justify-content-center m-5 p-5">
 <button type="button" class="btn col-sm-4 btn-outline-danger"> <i class="bi bi-list-ul"></i> Gerenciar todos os chamados</button>
<button type="button" class="btn col-sm-4 btn-outline-secondary"> <i class="bi bi-geo-alt"></i> Gerenciar localização</button>
</div>
 

    </main>
</body>
</html>