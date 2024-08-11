const { default: axios } = require("axios");
const { getCookie } = require("./cookies");

document.addEventListener("DOMContentLoaded", async () => {
    const isAuthorized = await isUserAuthorized();
    console.log(isAuthorized);
    await loadAuthorizationForm();
    await loadLoginForm();
    if (!isAuthorized) {
    } else {

    }
});

async function loadLoginForm() {
    const form = document.getElementById("login-form");
    form.style.display = "";

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const config = {
            headers: {
                "Content-Type": "application/json",
            }
        };
        const response = await axios.post('/api/login_check', formData, config);
        console.log(response);
    });
}

async function loadAuthorizationForm() {
    const form = document.getElementById("authorization-form");
    form.style.display = "";

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const response = await axios.post('/api/register', formData);
        console.log(response);
    });
}

export async function isUserAuthorized() {
    return getCookie('token') != ('' || null);
}