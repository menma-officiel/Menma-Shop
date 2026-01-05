// Script pour la page admin/login.php
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const userInput = document.querySelector('#username');
    const passInput = document.querySelector('#password');

    // Ajouter toggle visibilité mot de passe
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.textContent = 'Afficher';
    toggle.style.marginTop = '8px';
    toggle.className = 'toggle-pass';

    passInput.parentNode.appendChild(toggle);

    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        if (passInput.type === 'password') {
            passInput.type = 'text';
            toggle.textContent = 'Masquer';
        } else {
            passInput.type = 'password';
            toggle.textContent = 'Afficher';
        }
    });

    // Validation simple côté client et prévention double soumission
    form.addEventListener('submit', function (e) {
        let ok = true;
        // Reset styles
        userInput.classList.remove('input-error');
        passInput.classList.remove('input-error');

        if (!userInput.value || userInput.value.trim().length < 1) {
            ok = false;
            userInput.classList.add('input-error');
        }
        if (!passInput.value || passInput.value.length < 6) {
            ok = false;
            passInput.classList.add('input-error');
        }

        if (!ok) {
            e.preventDefault();
            // Focus sur le premier champ invalide
            const firstErr = document.querySelector('.input-error');
            if (firstErr) firstErr.focus();
            return false;
        }

        // Désactiver le bouton pour éviter double envoi
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Connexion...';
        }
    });
});