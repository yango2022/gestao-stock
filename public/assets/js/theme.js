document.addEventListener("DOMContentLoaded", function () {
    const html = document.documentElement;

    // Carregar tema salvo
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme) {
        html.setAttribute("data-theme", savedTheme);
    } else {
        html.setAttribute("data-theme", "auto");
    }

    // Botões
    const btnLight = document.getElementById("theme-light");
    const btnDark = document.getElementById("theme-dark");
    const btnAuto = document.getElementById("theme-auto");

    btnLight?.addEventListener("click", () => {
        html.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "light");
    });

    btnDark?.addEventListener("click", () => {
        html.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
    });

    btnAuto?.addEventListener("click", () => {
        html.setAttribute("data-theme", "auto");
        localStorage.setItem("theme", "auto");
    });
});