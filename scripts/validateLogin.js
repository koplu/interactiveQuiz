const errorBox = document.getElementById('login-error');
errorBox.style.color = "red";
const params = new URLSearchParams(window.location.search);

if (params.get('error') === 'wrongLogin') {
    errorBox.textContent = "Error: Wrong login!";
}

document.querySelector(".login-form").addEventListener("submit", function(e) {

    const uid = document.getElementById("uid").value.trim();
    const password = document.getElementById("pwd").value.trim();

    errorBox.textContent = "";

    if (!uid || !password) {
        e.preventDefault();
        errorBox.textContent = "Error: Fill all fields!";
    }
});