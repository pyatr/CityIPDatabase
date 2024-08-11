const { default: axios } = require("axios");
const { getCookie, setCookie } = require("./cookies");

document.addEventListener("DOMContentLoaded", async () => {
    const isAuthorized = isUserAuthorized();

    if (isAuthorized) {
        const ipform = document.getElementById("ip-form");
        ipform.style.display = "";
    } else {
        const form = document.getElementById("login-form");
        form.style.display = "";

        await loadAuthorizationForm();
        await loadLoginForm();
    }
});

async function getLoginToken(formData) {
    const config = {
        headers: {
            "Content-Type": "application/json",
        }
    };

    let response;

    try {
        response = await axios.post('/api/login_check', formData, config);

        const token = response.data.token;

        if (token) {
            setCookie('token', token, 7);

            if (isUserAuthorized()) {
                window.alert('Авторизация успешна! Страница будет перезагружена');
                window.location.reload();
            } else {
                window.alert('Не получилось авторизоваться');
            }
        }
    }
    catch (ex) {
        response = ex.response;

        switch (response.status) {
            case 401:
                window.alert("Неправильный логин или пароль");
                break;
            default:
                window.alert(response);
                break;
        }
    }
}

async function loadLoginForm() {
    const form = document.getElementById("login-form");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);

        await getLoginToken(formData);
    });

    form.querySelector("input#switch-to-authorization").addEventListener("click", () => {
        document.getElementById("authorization-form").style.display = "";
        form.style.display = "none";
    });
}

async function loadAuthorizationForm() {
    const form = document.getElementById("authorization-form");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        let response;

        try {
            response = await axios.post('/api/register', formData);

            await getLoginToken(formData);
        }
        catch (ex) {
            response = ex.response;

            switch (response.status) {
                case 409:
                    window.alert(response.data.message);
                    break;
                default:
                    window.alert(response);
                    break;
            }
        }
    });

    form.querySelector("input#switch-to-login").addEventListener("click", () => {
        document.getElementById("login-form").style.display = "";
        form.style.display = "none";
    });
}

export function getUserToken() {
    return getCookie('token');
}

export function isUserAuthorized() {
    return getCookie('token') != ('' || null);
}