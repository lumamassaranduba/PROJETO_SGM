<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Busca os dados dinâmicos do usuário
$query = "SELECT nome, email, perfil, foto, data_criacao FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();

    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} else {
    die("Erro no banco de dados: " . $conn->error);
}

// CORRIGIDO: Cores oficiais sólidas (sem degradê) e aspas corrigidas
$cor_vinho_sido  = "#990202";
$cor_ouro        = "#ffc107";

$data_entrada = "Não informada";
if (!empty($usuario['data_criacao'])) {
    $data_entrada = date('d/m/Y', strtotime($usuario['data_criacao']));
}

// Verifica se existe foto real cadastrada no banco, senão cai na foto genérica
$tem_foto_real = !empty($usuario['foto']) && file_exists($usuario['foto']);
$foto_perfil = $tem_foto_real ? $usuario['foto'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }
        
        /* NAVBAR COR SÓLIDA VINHO POR INTEIRO */
        .navbar-custom { 
            background-color: <?= $cor_vinho_sido ?> !important; 
            border-bottom: 4px solid <?= $cor_ouro ?> !important; 
            height: 65px; 
        }
        
        /* CARD DO PERFIL SGM */
        .profile-card { 
            background: white; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            border-top: 5px solid <?= $cor_ouro ?>; 
        }
        
        /* Círculo de Upload Ajustado para renderizar perfeitamente */
        .avatar-upload { position: relative; max-width: 130px; margin: 0 auto 20px; }
        
        .avatar-preview { 
            width: 130px; 
            height: 130px; 
            border-radius: 50%; 
            border: 4px solid white; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
            object-fit: cover; 
            display: block; 
            background: <?= $cor_vinho_sido ?>;
        }
        
        .avatar-edit { 
            position: absolute; 
            right: 5px; 
            bottom: 5px; 
            background: <?= $cor_ouro ?>; 
            width: 36px; 
            height: 36px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            cursor: pointer; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); 
            transition: 0.2s; 
        }
        .avatar-edit:hover { background: #e0a800; transform: scale(1.05); }
        
        /* BADGES LATERAIS ALINHADAS COM O VINHO SGM */
        .info-badge { 
            background-color: #fff8f8; 
            border-left: 4px solid <?= $cor_vinho_sido ?>; 
            border-radius: 8px; 
            padding: 12px; 
        }

        /* BOTÃO SALVAR REESTILIZADO COM A COR CORPORATIVA SÓLIDA */
        .btn-salvar-sgm {
            background-color: <?= $cor_vinho_sido ?> !important;
            border: none !important;
            color: white !important;
            transition: 0.2s;
        }
        .btn-salvar-sgm:hover {
            opacity: 0.9;
            color: <?= $cor_ouro ?> !important;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-custom navbar-dark shadow-sm px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="javascript:history.back()" class="btn btn-link text-white p-0 me-3">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                </a>
                <span class="navbar-brand fw-bold mb-0 text-white">
                    <i class="bi bi-person-gear text-warning me-2"></i>Meu Perfil SGM
                </span>
            </div>
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">Sair</button>
        </div>
    </nav>
</header>

<main class="container my-5" style="max-width: 700px;">
    
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Alterações gravadas com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($_GET['erro']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="profile-card p-4 p-md-5">
        <form action="api/atualizar_perfil.php" method="POST" enctype="multipart/form-data">
            
            <div class="text-center">
                <div class="avatar-upload">
                    <img id="imagePreview" src="<?= htmlspecialchars($foto_perfil) ?>" class="avatar-preview" alt="Foto de Perfil">
                    <label for="fotoInput" class="avatar-edit">
                        <i class="bi bi-camera-fill text-dark"></i>
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" style="display: none;">
                </div>
                <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($usuario['nome']) ?></h4>
                <span class="badge text-uppercase mb-4 px-3 py-2 bg-dark-subtle text-dark-emphasis rounded-pill fw-bold" style="letter-spacing: 0.5px; font-size: 0.75rem;"><?= $usuario['perfil'] ?></span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <div class="info-badge">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Membro desde</small>
                        <span class="fw-semibold text-dark"><i class="bi bi-calendar3 me-2 text-danger"></i><?= $data_entrada ?></span>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="info-badge">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">ID do Usuário</small>
                        <span class="fw-semibold text-dark"><i class="bi bi-hash me-1 text-danger"></i>#<?= $user_id ?></span>
                    </div>
                </div>
            </div>

            <hr class="text-muted mb-4">

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-semibold text-dark">Nova Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="password" name="nova_senha" class="form-control" placeholder="Deixe em branco para manter">
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label fw-semibold text-dark">Confirmar Nova Senha</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a nova senha">
                    </div>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-salvar-sgm btn-lg rounded-pill fw-bold fs-6 shadow-sm">
                    <i class="bi bi-check2-circle me-2"></i>Gravar Alterações
                </button>
            </div>

        </form>
    </div>
</main>

<div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-body text-center p-4">
                <div class="text-warning mb-3"><i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i></div>
                <h5 class="fw-bold text-dark mb-2">Confirmar Saída</h5>
                <p class="text-muted small mb-4">Tem a certeza que deseja encerrar a sua sessão atual no SGM?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold text-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="api/logout.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: <?= $cor_vinho_sido ?>; border: none;">Sim, Sair</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Pré-visualização instantânea da foto selecionada antes de enviar
document.getElementById('fotoInput').addEventListener('change', function(event) {
    const output = document.getElementById('imagePreview');
    if(event.target.files[0]) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    }
});
</script>
</body>
</html>