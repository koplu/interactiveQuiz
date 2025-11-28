const errorBox = document.getElementById('signup-error');
errorBox.style.color = "red";
const params = new URLSearchParams(window.location.search);

if (params.get('error') === 'uidtaken') {
    errorBox.textContent = "Error: Username is taken!";
}

document.querySelector(".login-form").addEventListener("submit", function(e) {

    const name = document.getElementById("name").value.trim();
    const surname = document.getElementById("surname").value.trim();
    const uid = document.getElementById("uid").value.trim();
    const password = document.getElementById("pwd").value.trim();
    const confirm  = document.getElementById("pwdrepeat").value.trim();

    errorBox.textContent = "";

    if (!name || !surname || !uid || !password || !confirm) {
        e.preventDefault();
        errorBox.textContent = "Error: Fill all fields!";
    }

    else if (password !== confirm) {
        e.preventDefault();
        errorBox.textContent = "Error: Passwords don't match!";
    }
});