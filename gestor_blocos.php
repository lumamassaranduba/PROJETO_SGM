<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SGM - Gestão de Blocos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="bg-light" style="font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">

<nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color: #990202;">
    <div class="container py-1">
        <a href="gestor_dashboard.php" class="btn btn-link text-light text-decoration-none me-2">
            <i class="bi bi-arrow-left-circle-fill fs-4"></i>
        </a>
        <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
        
        <div class="navbar-nav ms-auto gap-2">
            <a class="nav-link px-3 rounded-pill text-light bg-white bg-opacity-10" href="gestor_chamados.php">Chamados</a>
            <a class="nav-link px-3 text-light" href="gestor_dashboard.php">Home</a>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0 p-3">BLOCOS</h2>
        <a href="gestor_adicionar_blocos.php" class="btn text-white rounded-pill px-4 fw-bold shadow-sm m-3" style="background-color: #990202;">
            <i class="bi bi-plus-lg me-1"></i> Adicionar blocos</a>
    </div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden p-4">
        <div class="table-responsive">
            <table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">BLOCO</th>
      <th scope="col">GERENCIAR</th>
      <th scope="col">DELETAR</th>
    </tr>
  </thead>
  <tbody class="table-group-divider">
    <tr>
      <th scope="row">1</th>
      <td>Administrativo</td>
      <td><a href="./gestor_atualizar_blocos.php"><button class="btn btn-sm px-3 rounded-pill bg-warning text-white shadow-sm"
       style="font-size: 12px; font-weight: 600;"> <i class="bi bi-upload"></i> ATUALIZAR</button></a></td>
   <td><a href="#"><button class="btn btn-sm px-3 rounded-pill text-white shadow-sm"
       style="background-color: #990202; font-size: 12px; font-weight: 600;" onclick="alert('Deletado!')"
> <i class="bi bi-trash3"></i> DELETAR</button></a></td>
    </tr>
 </tr>
     
  </tbody>
</table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
