document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const msg = document.getElementById('mensagem');

    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, senha: senha })
        });

        const result = await response.json();

        if (result.success) {
            // REDIRECIONAMENTO CORRIGIDO POR PERFIL
            if (result.perfil === 'gestor') {
                window.location.href = 'gestor_dashboard.php';
            } else if (result.perfil === 'tecnico') {
                window.location.href = 'tecnico_dashboard.php';
            } else {
                window.location.href = 'solicitante_dashboard.php';
            }
        } else {
            msg.innerText = result.message;
            msg.classList.add('text-danger');
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
        if(msg) msg.innerText = "Erro ao conectar com o servidor.";
    }
});