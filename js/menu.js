// menu.js

function createMenu() {
    const menuHTML = `
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="fas fa-leaf me-2"></i>Plant-a-base</a>
                <div class="navbar-nav">
                    <a class="nav-link" href="index.html">Home</a>
                    <a class="nav-link" href="database.html">Database</a>
                    <a class="nav-link" href="template.html">Template</a>
                    <a class="nav-link" href="contact.html">Contact</a>
                </div>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search plants..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </nav>
    `;

    // Create a temporary container
    const temp = document.createElement('div');
    temp.innerHTML = menuHTML;

    // Get the menu element
    const menu = temp.firstElementChild;

    // Find the placeholder element and replace it with the menu
    const placeholder = document.getElementById('menu-placeholder');
    if (placeholder) {
        placeholder.replaceWith(menu);
    } else {
        console.error('Menu placeholder not found');
    }

    // Set the active class for the current page
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = menu.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
            link.setAttribute('aria-current', 'page');
        }
    });
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', createMenu);
