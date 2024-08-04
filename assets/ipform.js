import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
    const ipform = document.getElementById("ip-form");
    const ipInput = ipform.querySelector('input');

    ipInput.addEventListener("input", filterIPInput);

    function filterIPInput(event) {
        const characters = event.target.value.split('');
        const cleanCharacters = [];
        let charactersSinceLastDot = 0;
        let dotsCount = 0;

        characters.forEach((char, i) => {
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

        const response = await axios.get(`/get_ip_location?ip=${ip}`);

        if (!response.status.toString().startsWith('4') && !response.status.toString().startsWith('5')) {
            const city = response.data;

            console.log(city);
        }
    });
});