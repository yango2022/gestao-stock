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

document.addEventListener("DOMContentLoaded", function () {
    const html = document.documentElement;

    // Carregar tema salvo
    const savedTheme = localStorage.getItem("theme");
    html.setAttribute("data-theme", savedTheme ?? "auto");

    // Botões
    const btnLight = document.getElementById("theme-light");
    const btnDark = document.getElementById("theme-dark");
    const btnAuto = document.getElementById("theme-auto");

    const toggler = document.getElementById("theme-toggler");
    const toggleBtn = toggler.querySelector(".toggle-btn");

    // Abrir / fechar menu
    toggleBtn.addEventListener("click", () => {
        toggler.classList.toggle("active");
    });

    // Aplicar temas
    btnLight.addEventListener("click", () => {
        html.setAttribute("data-theme", "light");
        localStorage.setItem("theme", "light");
        toggler.classList.remove("active");
    });

    btnDark.addEventListener("click", () => {
        html.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
        toggler.classList.remove("active");
    });

    btnAuto.addEventListener("click", () => {
        html.setAttribute("data-theme", "auto");
        localStorage.setItem("theme", "auto");
        toggler.classList.remove("active");
    });
});

// Alternar tema claro/escuro
document.querySelector("#themeBtn").addEventListener("click", () => {
    document.body.classList.toggle("dark-theme");

    // salvar preferência
    const isDark = document.body.classList.contains("dark-theme");
    localStorage.setItem("theme", isDark ? "dark" : "light");
});

// Carregar tema salvo
window.addEventListener("DOMContentLoaded", () => {
    const saved = localStorage.getItem("theme");
    if (saved === "dark") document.body.classList.add("dark-theme");
});

// mostrar/esconder botão
document.querySelector("#toggleVisibility").addEventListener("click", () => {
    document.querySelector(".theme-button").classList.toggle("theme-toggle-hidden");
});
