<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Login Profissional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh; margin: 0;">

    <div class="container shadow-lg rounded-4 overflow-hidden bg-white" style="max-width: 900px;">
        <div class="row g-0">
            
            <div class="col-md-6 text-light d-none d-md-flex flex-column align-items-center justify-content-center text-white p-5" style="background-color: rgba(153, 2, 2, 0.91);">
                <i class="bi bi-shield-lock-fill"  style="font-size: 3rem;"></i>
                <h2 class="fw-bold">SGM</h2>
                <p class="text-center opacity-75">Sistema de Gestão de Manutenção.</p>
            </div>

            <div class="col-md-6 p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-dark">LOGIN</h2>
                </div>

                <form id="formLogin">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">E-MAIL</label>
                        <input type="email" id="email" class="form-control border-0 bg-light py-2" placeholder="email" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold text-secondary">SENHA</label>
                    
                        </div>
                        <input type="password" id="senha" class="form-control border-0 bg-light py-2" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn w-100 text-light py-2 fw-bold shadow-sm rounded-3" style="background-color: rgba(153, 2, 2, 0.91);">
                        ENTRAR
                    </button>

                    <div id="mensagem" class="mt-3 text-center text-danger small"></div>

                
                </form>
            </div>

        </div>
    </div>

    <script src="assets/js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>