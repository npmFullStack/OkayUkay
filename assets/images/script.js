document.addEventListener("DOMContentLoaded", () => {
    const burger = document.querySelector(".burger");
    const mobileMenu = document.querySelector(".mobile-menu");

    burger.addEventListener("click", () => {
        mobileMenu.classList.toggle("active");
        const icon = burger.querySelector("i");
        if (mobileMenu.classList.contains("active")) {
            icon.classList.remove("fa-bars");
            icon.classList.add("fa-times");
        } else {
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
        }
    });

    const mobileLinks = document.querySelectorAll(".mobile-nav-list a");
    mobileLinks.forEach(link => {
        link.addEventListener("click", () => {
            mobileMenu.classList.remove("active");
            const icon = burger.querySelector("i");
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
        });
    });

    const passwordInput = document.getElementById("password");
    const passwordToggle = document.querySelector(".password-toggle");

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener("click", () => {
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordInput.setAttribute("type", type);
            const icon = passwordToggle.querySelector("i");
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });
    }

    // ✅ Support multiple dropdowns
    const toggles = document.querySelectorAll(".dropdown-toggle");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", e => {
            e.stopPropagation(); // Prevent outside click from closing it immediately
            const menu = toggle.nextElementSibling;

            // Close other dropdowns
            document.querySelectorAll(".dropdown-menu").forEach(m => {
                if (m !== menu) m.style.display = "none";
            });

            // Toggle current
            menu.style.display =
                menu.style.display === "block" ? "none" : "block";
        });
    });

    // Close all dropdowns when clicking outside
    document.addEventListener("click", e => {
        if (!e.target.closest(".card-head")) {
            document.querySelectorAll(".dropdown-menu").forEach(menu => {
                menu.style.display = "none";
            });
        }
    });

    // Category dropdown functionality
    const categoryBtn = document.querySelector(".category-btn");
    const categoryMenu = document.querySelector(".category-menu");

    if (categoryBtn && categoryMenu) {
        categoryBtn.addEventListener("click", e => {
            e.stopPropagation();
            categoryMenu.classList.toggle("show-category");
        });

        // Close when clicking outside
        document.addEventListener("click", e => {
            if (!e.target.closest(".category-dropdown")) {
                categoryMenu.classList.remove("show-category");
            }
        });

        // Filter functionality
        const filterOptions = document.querySelectorAll(".filter-option");
        filterOptions.forEach(option => {
            option.addEventListener("click", e => {
                e.preventDefault();
                const filter = option.getAttribute("data-filter");
                filterTasks(filter);
                categoryMenu.classList.remove("show-category");
            });
        });
    }

    // Search functionality
const searchInput = document.getElementById("search");
if (searchInput) {
    searchInput.addEventListener("input", (e) => {
        const searchTerm = e.target.value.toLowerCase();
        filterTasksBySearch(searchTerm);
    });
}

function filterTasksBySearch(searchTerm) {
    const cards = document.querySelectorAll(".card");
    
    cards.forEach(card => {
        const taskName = card.querySelector("h4").textContent.toLowerCase();
        const dueDate = card.querySelector(".due-date").textContent.toLowerCase();
        
        if (taskName.includes(searchTerm) || dueDate.includes(searchTerm)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

// Update the existing filterTasks function to work with search
function filterTasks(filter) {
    const cards = document.querySelectorAll(".card");
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : "";
    
    cards.forEach(card => {
        const statusElement = card.querySelector(".upcoming, .overdue, .done");
        let status = "";
        
        if (statusElement) {
            status = statusElement.textContent.toLowerCase();
        }
        
        const taskName = card.querySelector("h4").textContent.toLowerCase();
        const dueDate = card.querySelector(".due-date").textContent.toLowerCase();
        
        const matchesSearch = searchTerm === "" || 
                             taskName.includes(searchTerm) || 
                             dueDate.includes(searchTerm);
        
        const matchesFilter = filter === "all" || 
                             (filter === "done" && card.querySelector(".done")) ||
                             (filter === "upcoming" && card.querySelector(".upcoming")) ||
                             (filter === "overdue" && card.querySelector(".overdue"));
        
        if (matchesSearch && matchesFilter) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}
});
