// footer.js

function createFooter() {
    const footer = document.createElement('footer');
    footer.className = 'container mt-5';
    
    const currentYear = new Date().getFullYear();
    footer.innerHTML = `
        <hr>
        <p class="text-center">
            © ${currentYear} Plant-a-base. All rights reserved.
        </p>
    `;
    
    document.body.appendChild(footer);
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', createFooter);
