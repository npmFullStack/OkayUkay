document.addEventListener("DOMContentLoaded", () => {
    // PASSWORD FUNCTIONALITY
    const passwordInput = document.getElementById("password");
    const passwordToggle = document.querySelector(".password-toggle");

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener("click", () => {
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordInput.setAttribute("type", type);

            const icon = document.querySelector(".password-toggle i");
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });
    }

    // FILTER ICOM & CATEGORY FUNCTION
    const filterIcon = document.querySelector(".filter-icon");
    const categoryMenu = document.getElementById("category-menu");

    if (filterIcon && categoryMenu) {
        // Toggle menu when filter icon is clicked
        filterIcon.addEventListener("click", e => {
            e.stopPropagation(); // Prevent this click from triggering the document click handler
            categoryMenu.classList.toggle("show");
            filterIcon.classList.toggle("active");
        });

        // Close menu when clicking outside
        document.addEventListener("click", e => {
            if (
                !e.target.closest(".search-bar-content") &&
                !e.target.closest(".category-menu")
            ) {
                categoryMenu.classList.remove("show");
                filterIcon.classList.remove("active");
            }
        });

        // Category selection functionality
        const categoryItems = document.querySelectorAll(".category-item");
        categoryItems.forEach(item => {
            item.addEventListener("click", () => {
                // 1. Remove 'active' class from all categories
                categoryItems.forEach(cat => cat.classList.remove("active"));

                // 2. Add 'active' class to clicked category
                item.classList.add("active");

                // 3. Get the selected category name
                const selectedCategory = item.textContent;

                // 4. You can now use this to filter products
                console.log("Selected category:", selectedCategory);

                // Here you would typically:
                // - Make an AJAX call to get filtered products, OR
                // - Filter existing products on the page
                // - Update the product display

                // 5. Optional: Close the menu after selection
                // categoryMenu.classList.remove("show");
                // filterIcon.classList.remove("active");
            });
        });
    }
});
