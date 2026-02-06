<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTOR - chamados </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav class="navbar" style="background-color: rgba(153, 2, 2, 0.91);">
            <div class="container-fluid">
            <div class="row  w-100">
                <div class="col  col-sm-6"><a class="navbar-brand" style="color: rgba(255, 250, 250, 0.91);">SMG | Gestão Administrativa</a></div>
                
                <div class="col  col-sm-6 d-flex justify-content-end">
                      
                        <a class="nav-link text-light" href="#">Chamados</a>
                    
                        <a class="nav-link disabled" aria-disabled="true">Link</a>
                    
                        <button> <a href="api/logout.php" class="btn bg-light" type="submit">Sair</button></a>
                
                </div>
            </div>    
            </div>    
            
              

            
        </nav>
    </header>

    <main class=" container p-4">
        <div>
        <h2 title-dark> Todos os chamados</h2>
        <button type="button" class="btn btn-outline-primary">Todos</button>
<button type="button" class="btn btn-outline-danger">Aberto</button>
<button type="button" class="btn btn-outline-warning">Em execucão</button>
<button type="button" class="btn btn-outline-success">Concluídos</button>


        <div>
           <table class="table m-4">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Solicitante</th>
      <th scope="col">Local/tipo</th>
      <th scope="col">Prioridade</th>
     <th scope="col">Técnico</th>
    <th scope="col">Status</th>
    <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">#1</th>
      <td>Maria Solicitante</td>
      <td>Bloco adinistrativo - Recepção</td>
      <td> <i class="bi bi-circle-fill "></i> ALTA</td>
      <td>João Técnico</td>
      <td><span class= " bg-dark rounded text-light px-2">Fechado</span></td>
      <td> <button class = "bg-primary rounded text-light"> <i class="bi bi-eye-fill"></i>Gerenciar</td> </button>
    </tr>
  </tbody>
</table>
        
        </div>

    </main>

</body>

</html>