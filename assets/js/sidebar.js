const sidebar = document.querySelector(".sidebar");
const burgerButton = document.querySelector(".burger");

burgerButton.addEventListener("click", () => {
    sidebar.classList.toggle("maximized");
});

const navLinks = document.querySelectorAll(".nav-list li a");

navLinks.forEach(link => {
    if (link.href === window.location.href) {
        link.parentNode.classList.add("active");
    }
});
