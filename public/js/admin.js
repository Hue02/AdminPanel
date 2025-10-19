document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleSidebarMobile = document.getElementById("toggleSidebarMobile");

    toggleSidebarMobile.addEventListener("click", function () {
        sidebar.classList.toggle("open");

        // Hide the toggle button when sidebar is open
        toggleSidebarMobile.style.display = sidebar.classList.contains("open") ? "none" : "block";
    });

    // Close sidebar when clicking outside
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !toggleSidebarMobile.contains(event.target)) {
            sidebar.classList.remove("open");
            toggleSidebarMobile.style.display = "block"; // Show button again
        }
    });
});
