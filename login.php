<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: rgba(153, 2, 2, 0.91);
            --bg-soft: #f4f7f6;
        }

        body {
            background-color: var(--bg-soft);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, sans-serif;
            margin: 0;
        }

        .login-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 95%;
            border: none;
            display: flex;
        }

        .brand-section {
            background-color: var(--primary-color);
            color: white;
            padding: 50px;
            flex: 1.1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            text-align: center;
        }

        .brand-section::after {
            content: "";
            position: absolute;
            right: -1px;
            top: 0;
            height: 100%;
            width: 50px;
            background: white;
            clip-path: ellipse(100% 100% at 100% 50%);
        }

        .form-section {
            flex: 1.2;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            color: #333;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 40px;
            position: relative;
            display: inline-block;
        }

        .login-title::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 10px;
        }

        .input-group {
            background-color: #f8f9fa;
            border-radius: 50px;
            margin-bottom: 20px;
            padding: 5px 15px;
            border: 1px solid #eee;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #adb5bd;
        }

        .form-control {
            background: transparent;
            border: none;
            padding: 12px 10px;
            box-shadow: none !important;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px;
            padding: 12px 40px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            filter: brightness(1.2);
            transform: translateY(-2px);
            color: white;
        }

        .forgot-password {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-card { flex-direction: column; }
            .brand-section::after { display: none; }
            .brand-section { padding: 50px 20px; }
        }
    </style>
</head>
<body>

    <div class="login-card shadow-lg">
        <div class="brand-section">
            <i class="bi bi-shield-lock" style="font-size: 4rem;"></i>
            <h1 class="fw-bold mt-3" style="letter-spacing: 2px; margin-bottom: 10px;">SGM</h1>
            <p class="mb-0 opacity-100 fw-light" style="font-size: 1.1rem; line-height: 1.4;">
                Sistema de Gestão <br> de Manutenção
            </p>
        </div>

        <div class="form-section">
            <h2 class="login-title">Login</h2>
            
            <form id="formLogin">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" class="form-control" placeholder="E-mail" required>
                </div>

                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="senha" class="form-control" placeholder="Senha" required>
                </div>

                <div id="mensagem" class="alert alert-danger d-none text-center py-2" style="border-radius: 25px; font-size: 0.9rem;"></div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="#" class="forgot-password"></a>
                    <button type="submit" id="btnEntrar" class="btn btn-login shadow-sm">ENTRAR</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.getElementById('formLogin').addEventListener('submit', async function(e) {
        e.preventDefault(); 

        const btn = document.getElementById('btnEntrar');
        const msg = document.getElementById('mensagem');
        
        msg.classList.add('d-none');
        msg.innerText = '';

        btn.disabled = true;
        btn.innerText = "CARREGANDO...";

        const emailInput = document.getElementById('email').value.trim();
        const senhaInput = document.getElementById('senha').value;

        try {
            const resposta = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: emailInput,
                    senha: senhaInput
                })
            });

            if (!resposta.ok) {
                throw new Error("Erro de rede");
            }

            const result = await resposta.json();

            if (result.success === true) {
                
                // CORREÇÃO DA SINTAXE: Blocos de condições devidamente encadeados e fechados
                if (result.perfil === 'admin' || result.perfil === 'gestor') {
                    window.location.href = 'gestor_dashboard.php';
                } else if (result.perfil === 'solicitante') {
                    window.location.href = 'solicitante_dashboard.php';
                } else {
                    window.location.href = 'tecnico_dashboard.php';
                }

            } else {
                msg.innerText = result.message || "Erro ao realizar login.";
                msg.classList.remove('d-none');
                
                btn.disabled = false;
                btn.innerText = "ENTRAR";
            }

        } catch (erro) {
            console.error("Erro:", erro);
            msg.innerText = "Falha ao processar requisição.";
            msg.classList.remove('d-none');
            
            btn.disabled = false;
            btn.innerText = "ENTRAR";
        }
    });
    </script>
</body>
</html>