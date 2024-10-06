// footer.js
function createFooter() {
    const footer = document.createElement('footer');
    footer.className = 'container mt-5';
    
    const currentYear = new Date().getFullYear();
    footer.innerHTML = `
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p>© ${currentYear} Plant-a-base. All rights reserved.</p>
            </div>
            <div class="col-md-6">
                <ul class="list-inline text-md-end mb-0">
                    <li class="list-inline-item">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    `;
    
    document.body.appendChild(footer);
}

// Call the function when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', createFooter);
