// OLAY.az Design - Main JavaScript

// Hero Slider
const initSlider = () => {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    let currentSlide = 0;
    let slideInterval;

    const showSlide = (index) => {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        currentSlide = index;
        if (currentSlide >= slides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = slides.length - 1;

        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    };

    const nextSlide = () => {
        showSlide(currentSlide + 1);
    };

    const prevSlide = () => {
        showSlide(currentSlide - 1);
    };

    const startAutoSlide = () => {
        slideInterval = setInterval(nextSlide, 5000);
    };

    const stopAutoSlide = () => {
        clearInterval(slideInterval);
    };

    // Arrow navigation
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoSlide();
            startAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoSlide();
            startAutoSlide();
        });
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoSlide();
            startAutoSlide();
        });
    });

    // Pause on hover
    const slider = document.querySelector('.hero-slider');
    if (slider) {
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
    }

    // Start auto slide
    startAutoSlide();

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            stopAutoSlide();
            startAutoSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            stopAutoSlide();
            startAutoSlide();
        }
    });
};

// Initialize slider when DOM is loaded
if (document.querySelector('.hero-slider')) {
    initSlider();
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Mobile Menu functionality
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const mobileNav = document.getElementById('mobileNav');
const mobileMenuClose = document.getElementById('mobileMenuClose');

if (mobileMenuToggle && mobileNav) {
    // Open mobile menu - backup event listener (inline onclick is primary)
    mobileMenuToggle.addEventListener('click', function() {
        mobileNav.classList.add('active');
        mobileMenuToggle.classList.add('active');
        document.body.style.overflow = 'hidden';
        if (mobileMenuClose) {
            mobileMenuClose.style.display = 'flex';
        }
    });

    // Close mobile menu
    const closeMobileMenu = function() {
        mobileNav.classList.remove('active');
        mobileMenuToggle.classList.remove('active');
        document.body.style.position = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        if (mobileMenuClose) {
            mobileMenuClose.style.display = 'none';
        }
    };

    if (mobileMenuClose) {
        mobileMenuClose.addEventListener('click', closeMobileMenu);
    }

    // Close on link click
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });

    // Close on background click
    mobileNav.addEventListener('click', function(e) {
        if (e.target === mobileNav) {
            closeMobileMenu();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
            closeMobileMenu();
        }
    });
}

// Search Modal functionality
const searchBtn = document.querySelector('.search-btn');
const searchModal = document.getElementById('searchModal');
const searchModalClose = document.getElementById('searchModalClose');
const searchInput = document.getElementById('searchInput');
const searchForm = document.getElementById('searchForm');

if (searchBtn && searchModal) {
    // Open search modal
    searchBtn.addEventListener('click', function() {
        searchModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            searchInput.focus();
        }, 300);
    });

    // Close search modal
    if (searchModalClose) {
        searchModalClose.addEventListener('click', function() {
            searchModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Close on background click
    searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
            searchModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchModal.classList.contains('active')) {
            searchModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Handle search form submission
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (!query) {
                e.preventDefault();
                return false;
            }
            // Form will submit normally to the action URL
        });
    }
}

// Add hover effects for news cards
document.querySelectorAll('.news-card, .featured-news, .sidebar-news').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.cursor = 'pointer';
    });

    card.addEventListener('click', function(e) {
        if (!e.target.closest('a')) {
            console.log('News card clicked');
        }
    });
});

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Active navigation link - server already sets this via blade template
// No need for JavaScript to manage this

console.log('OLAY.az Design loaded successfully');
