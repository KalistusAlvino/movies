class UIManager {
    static getElementOrThrow(selector) {
        const element = document.querySelector(selector);
        if (!element) {
            throw new Error(`Element not found: ${selector}`);
        }
        return element;
    }

    static showError(message, duration = 5000) {
        const errorElement = document.getElementById('errorMessage');
        const errorTextElement = document.getElementById('errorMessageText');

        if (errorElement && errorTextElement) {
            errorTextElement.textContent = message;
            errorElement.classList.remove('hidden');
            setTimeout(() => {
                errorElement.classList.add('hidden');
            }, duration);
        } else {
            alert(message);
        }
    }

    static showSuccess(message, duration = 3000) {
        const successElement = document.getElementById('successMessage');
        const successTextElement = document.getElementById('successMessageText');

        if (successElement && successTextElement) {
            successTextElement.textContent = message;
            successElement.classList.remove('hidden');
            setTimeout(() => {
                successElement.classList.add('hidden');
            }, duration);
        } else {
            alert(message);
        }
    }

    static disableButton(btn) {
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
        }
    }

    static enableButton(btn) {
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    }

    static animateCardRemoval(card, callback) {
        if (!card) return;

        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.8)';

        setTimeout(() => {
            card.remove();
            if (callback) callback();
        }, 300);
    }
}
