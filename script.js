<<<<<<< HEAD
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
=======
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
>>>>>>> ee59040cc70d5b4e2460ecdb82f21e941850dc09
// console.log(loginLink);