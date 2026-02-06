<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOLICITANTE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav class="navbar" style="background-color: rgba(153, 2, 2, 0.91);">
  <div class="container-fluid">
    <a class="navbar-brand" style="color: rgba(255, 250, 250, 0.91);">SMG | Painel do solicitante</a>
      <button> <a href="api/logout.php" class="btn bg-light" type="submit">Sair</button></a>
    </form>
  </div>
</nav>
    </header>

    <main class=" container p-4">
        <div>
        <h2 title-dark> Minha fila de tarefas</h2>
        <button class="btn text-light" style="background-color:rgba(153, 2, 2, 0.91);" type="submit"> <i class="bi bi-plus-lg"></i>Nova solicitação</button>
        </div>

        <div>
           <table class="table m-4">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Foto</th>
      <th scope="col">Local</th>
      <th scope="col">Descrição</th>
     <th scope="col">Data</th>
    <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">#1</th>
      <td>...</td>
      <td>Bloco adinistrativo - Recepção</td>
      <td>vazando água na lâmpada</td>
      <td>06/02/2026</td>
      <td>FECHADO</td>
    </tr>
  </tbody>
</table>
        
        </div>

    </main>

</body>

</html>