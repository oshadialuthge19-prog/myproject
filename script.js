
const logregBox = document.querySelector('.login-reg-box');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');

registerLink.onclick = (e) => {
    e.preventDefault(); // Prevents page jump
    logregBox.classList.add('active');
};

loginLink.onclick = (e) => {
    e.preventDefault(); // Prevents page jump
    logregBox.classList.remove('active');
};
// console.log(registerLink);