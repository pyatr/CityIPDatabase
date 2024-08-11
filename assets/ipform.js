import axios from "axios";
import { getUserToken, isUserAuthorized } from "./authorization";

document.addEventListener("DOMContentLoaded", () => {
    const ipform = document.getElementById("ip-form");
    const ipInput = ipform.querySelector("input");
    const generateRandomButton = document.getElementById("generate-random");
    const responseDisplay = document.getElementById("response-display");

    ipInput.addEventListener("input", filterIPInput);
    generateRandomButton.addEventListener("click", generateRandomIP);

    function generateRandomIP() {
        ipInput.value = Math.floor(Math.random() * 255).toString() + '.' +
            Math.floor(Math.random() * 255).toString() + '.' +
            Math.floor(Math.random() * 255).toString() + '.' +
            Math.floor(Math.random() * 255).toString();
    }

    function filterIPInput(event) {
        const characters = event.target.value.split('');
        const cleanCharacters = [];
        let charactersSinceLastDot = 0;
        let dotsCount = 0;

        characters.forEach(char => {
            const charAsNumber = Number.parseInt(char);

            if (!isNaN(charAsNumber) && charactersSinceLastDot < 3) {
                cleanCharacters.push(char);
            }

            const addingDot = (char == '.' && charactersSinceLastDot > 0);
            const lastDotWasLongTimeAgo = charactersSinceLastDot >= 3;
            const needsMoreDots = dotsCount < 3;

            if ((addingDot || lastDotWasLongTimeAgo) && needsMoreDots) {
                cleanCharacters.push('.');
                dotsCount++;
                charactersSinceLastDot = 0;
            } else {
                charactersSinceLastDot++;
            }
        });

        event.target.value = cleanCharacters.join('');
    }

    ipform.addEventListener("submit", async (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        const ip = formData.get('ip');
        const addressParts = ip.split('.');
        const errors = [];

        addressParts.forEach(part => {
            if (Number.parseInt(part) > 255) {
                errors.push(part);
            }
        });

        if (addressParts.length != 4) {
            window.alert('Неправильный формат адреса. Нужно ровно 4 части.');

            return;
        }

        if (errors.length == 1) {
            window.alert(`Неправильная часть адреса: ${errors.join(', ')}`);

            return;
        } else if (errors.length > 1) {
            window.alert(`Неправильные части адреса: ${errors.join(', ')}`);

            return;
        }

        if (!isUserAuthorized()) {
            window.alert('Пожалуйста, зарегистрируйтесь или войдите в свой аккаунт');

            return;
        }

        let response;

        try {
            response = await axios.get(`/api/get_ip_location?ip=${ip}`, {
                headers: {
                    Authorization: `Bearer ${getUserToken()}`
                }
            });

            if (!response.status.toString().startsWith('4') && !response.status.toString().startsWith('5')) {
                const city = response.data;

                responseDisplay.textContent = city;
            }
        }
        catch (ex) {
            response = ex.response;
            responseDisplay.textContent = '';

            switch (response.status) {
                case 429:
                    responseDisplay.textContent = "Слишком много запросов";
                    break;
                default:
                    responseDisplay.textContent = response.statusText;
                    break;
            }
        }
        finally {
            responseDisplay.style.display = '';
        }
    });
});