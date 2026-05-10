// Portal Kemenag Nganjuk - Frontend JavaScript

// Import Alpine.js from CDN fallback (if not using vite)
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine.js
Alpine.start();

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    // Any additional DOM-ready scripts can go here
});

// Back to top button (backup implementation)
const backToTopBtn = document.getElementById('back-to-top');
if (backToTopBtn) {
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
            backToTopBtn.classList.remove('opacity-100', 'visible');
        }
    });

    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Lazy loading for images
if ('loading' in HTMLImageElement.prototype) {
    // Native lazy loading supported
    document.querySelectorAll('img[loading="lazy"]').forEach(function(img) {
        img.src = img.dataset.src;
    });
} else {
    // Fallback for browsers that don't support lazy loading
    // You could use a library like lazysizes here
    console.log('Native lazy loading not supported');
}
