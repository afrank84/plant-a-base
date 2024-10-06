function createMenu() {
    const menuHTML = `
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#"><i class="fas fa-leaf me-2"></i>Plant-a-base</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="database.html">Database</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="template.html">Template</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.html">Contact</a>
                        </li>
                    </ul>
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search plants..." aria-label="Search">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
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

        // Add hover effect to menu items
        link.addEventListener('mouseover', () => {
            link.classList.add('text-success');
        });
        link.addEventListener('mouseout', () => {
            link.classList.remove('text-success');
        });
    });
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', createMenu);
