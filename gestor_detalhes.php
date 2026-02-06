<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECNICO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <main>
       <div class="btn-group" role="group" aria-label="Basic outlined example">
  <button type="button" class="btn btn-outline-secondary">Voltar</button>
       </div>

       <div class="container border d-flex justify-content-center">

       <section>
           <div class="card text-center">
  <div class="card-header">
    Dados da solicitação
  </div>
  <div class="card-body">
    <h5 class="card-title">Special title treatment</h5>
    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
  <div class="card-footer text-body-secondary">
    2 days ago
  </div>
</div>
       </section>

       <section class=" border">
        <form id="formdetalhes">
            <div class="mb-3">
                <label>Técnico Responsável</label>
               <input type="text" id="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Prioridade</label>
                <select class="form-select form-select-sm" aria-label="Small select example">
  <option selected>Selecionar</option>
  <option value="1">Alta</option>
  <option value="2">Média</option>
  <option value="3">Baixa</option>
</select>
            </div>

            <div class="mb-3">
                <label>Data prevista</label>
               <input type="date" id="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Confirmar Atribuição</button>
            <div id="mensagem" class="mt-3 text-center text-danger small"></div>
        </form>

       </section>

       </div>


    </main>

</body>

</html>