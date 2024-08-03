document.addEventListener("DOMContentLoaded", () => {
    const ipform = document.getElementById("ip-form");

    ipform.addEventListener("submit", (event) => {
        event.preventDefault();
        const formData = new FormData(event.target);
        console.log(formData);
    });
});