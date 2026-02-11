document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const userInput = document.querySelector('#username');
    const passInput = document.querySelector('#password');

    // 1. Toggle visibilité mot de passe
    // 1. Toggle visibilité mot de passe
    // Créer un conteneur relatif pour l'input mot de passe s'il n'existe pas déjà
    if (passInput.parentNode) {
        const wrapper = document.createElement('div');
        wrapper.className = 'password-container';
        passInput.parentNode.insertBefore(wrapper, passInput);
        wrapper.appendChild(passInput);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.textContent = 'AFFICHER';
        toggle.className = 'toggle-pass';
        wrapper.appendChild(toggle);

        toggle.addEventListener('click', function () {
            const isPass = passInput.type === 'password';
            passInput.type = isPass ? 'text' : 'password';
            toggle.textContent = isPass ? 'MASQUER' : 'AFFICHER';
        });
    }

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
