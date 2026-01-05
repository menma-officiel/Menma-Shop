document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const userInput = document.querySelector('#username');
    const passInput = document.querySelector('#password');

    // 1. Toggle visibilité mot de passe
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.textContent = 'Afficher';
    toggle.className = 'toggle-pass'; // Utilise ton CSS pour le style
    
    if (passInput.parentNode) {
        passInput.parentNode.appendChild(toggle);
    }

    toggle.addEventListener('click', function () {
        const isPass = passInput.type === 'password';
        passInput.type = isPass ? 'text' : 'password';
        toggle.textContent = isPass ? 'Masquer' : 'Afficher';
    });

    // 2. Gestion de la soumission
    form.addEventListener('submit', function (e) {
        let ok = true;

        // Reset styles
        userInput.style.borderColor = '#d1d5db';
        passInput.style.borderColor = '#d1d5db';

        // Validation ultra-simple : juste vérifier que ce n'est pas vide
        if (userInput.value.trim() === '') {
            ok = false;
            userInput.style.borderColor = '#e74c3c';
        }
        if (passInput.value === '') {
            ok = false;
            passInput.style.borderColor = '#e74c3c';
        }

        if (!ok) {
            e.preventDefault();
            return false;
        }

        // 3. Empêcher la double soumission (Très important sur Render/Docker)
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            // On attend un petit délai avant de désactiver pour être sûr que le POST part
            setTimeout(() => {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner"></span> Connexion...';
            }, 50);
        }
    });
});
