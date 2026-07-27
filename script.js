const logregBox = document.querySelector('.login-reg-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

if (registerLink) {
    registerLink.onclick = (e) => {
        e.preventDefault();
        logregBox.classList.add('active');
    };
}

if (loginLink) {
    loginLink.onclick = (e) => {
        e.preventDefault();
        logregBox.classList.remove('active');
    };
}