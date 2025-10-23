// ==================== Hero Slider ====================
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slide');
        this.dots = document.querySelectorAll('.slider-dots .dot');
        this.prevBtn = document.querySelector('.slider-btn.prev');
        this.nextBtn = document.querySelector('.slider-btn.next');
        this.currentSlide = 0;
        this.autoPlayInterval = null;

        if (!this.prevBtn || !this.nextBtn || this.slides.length === 0) return;
        this.init();
    }

    init() {
        // Event listeners
        this.prevBtn.addEventListener('click', () => this.prevSlide());
        this.nextBtn.addEventListener('click', () => this.nextSlide());

        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Auto play
        this.startAutoPlay();

        // Pause on hover
        const heroSlider = document.querySelector('.hero-slider');
        heroSlider.addEventListener('mouseenter', () => this.stopAutoPlay());
        heroSlider.addEventListener('mouseleave', () => this.startAutoPlay());

        // Touch support
        this.addTouchSupport();
    }

    goToSlide(index) {
        // Remove active class from all slides and dots
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.dots.forEach(dot => dot.classList.remove('active'));

        // Add active class to current slide and dot
        this.currentSlide = index;
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');
    }

    nextSlide() {
        const next = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(next);
    }

    prevSlide() {
        const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prev);
    }

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }

    addTouchSupport() {
        const slider = document.querySelector('.hero-slider');
        let touchStartX = 0;
        let touchEndX = 0;

        slider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        slider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        });

        const handleSwipe = () => {
            if (touchEndX < touchStartX - 50) {
                this.nextSlide();
            }
            if (touchEndX > touchStartX + 50) {
                this.prevSlide();
            }
        };

        this.handleSwipe = handleSwipe;
    }
}

// ==================== Trending Carousel ====================
class TrendingCarousel {
    constructor() {
        this.track = document.querySelector('.trending-track');
        this.cards = Array.from(document.querySelectorAll('.trending-card'));
        this.prevBtn = document.querySelector('.prev-trending');
        this.nextBtn = document.querySelector('.next-trending');
        this.currentPage = 0;
        this.isTransitioning = false;

        if (!this.track || this.cards.length === 0) return;
        this.init();
    }

    getCardsPerPage() {
        const width = window.innerWidth;
        if (width <= 640) return 2; // Mobile: 2 cards
        if (width <= 768) return 2; // Tablet: 2 cards
        return 3; // Desktop: 3 cards
    }

    getTotalPages() {
        const cardsPerPage = this.getCardsPerPage();
        return Math.ceil(this.cards.length / cardsPerPage);
    }

    init() {
        // Button event listeners
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.scrollPrev());
        }
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.scrollNext());
        }

        // Listen for transition end
        this.track.addEventListener('transitionend', () => {
            this.isTransitioning = false;
        });

        // Auto scroll
        this.startAutoScroll();

        // Pause on hover
        this.track.addEventListener('mouseenter', () => this.stopAutoScroll());
        this.track.addEventListener('mouseleave', () => this.startAutoScroll());

        // Touch support
        this.addTouchSupport();

        // Update on resize
        window.addEventListener('resize', () => {
            this.currentPage = 0; // Reset to first page on resize
            this.updatePosition(false);
        });

        // Initial position
        this.updatePosition(false);
    }

    getPageWidth() {
        const carousel = document.querySelector('.trending-carousel');
        return carousel ? carousel.offsetWidth : 0;
    }

    scrollNext() {
        if (this.isTransitioning) return;
        this.isTransitioning = true;

        const totalPages = this.getTotalPages();
        this.currentPage = (this.currentPage + 1) % totalPages;
        this.updatePosition(true);
    }

    scrollPrev() {
        if (this.isTransitioning) return;
        this.isTransitioning = true;

        const totalPages = this.getTotalPages();
        this.currentPage = (this.currentPage - 1 + totalPages) % totalPages;
        this.updatePosition(true);
    }

    updatePosition(animated = true) {
        const pageWidth = this.getPageWidth();
        const gap = 24;
        const offset = -(this.currentPage * (pageWidth + gap));

        if (animated) {
            this.track.style.transition = 'transform 0.5s ease';
        } else {
            this.track.style.transition = 'none';
        }

        this.track.style.transform = `translateX(${offset}px)`;

        if (!animated) {
            this.isTransitioning = false;
        }
    }

    startAutoScroll() {
        this.autoScrollInterval = setInterval(() => {
            this.scrollNext();
        }, 4000);
    }

    stopAutoScroll() {
        if (this.autoScrollInterval) {
            clearInterval(this.autoScrollInterval);
        }
    }

    addTouchSupport() {
        let touchStartX = 0;
        let touchEndX = 0;

        this.track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50) {
                this.scrollNext();
            }
            if (touchEndX > touchStartX + 50) {
                this.scrollPrev();
            }
        });
    }
}

// ==================== Breaking News Ticker ====================
class NewsTicker {
    constructor() {
        this.tickerContent = document.querySelector('.ticker-content');
        if (!this.tickerContent) return;
        this.init();
    }

    init() {
        const parent = this.tickerContent.parentElement;

        // Remove any existing clones
        const existingClones = parent.querySelectorAll('.ticker-content');
        if (existingClones.length > 1) {
            // Keep only the first one, remove others
            for (let i = 1; i < existingClones.length; i++) {
                existingClones[i].remove();
            }
        }

        // No cloning - keep only one ticker-content block
    }
}

// ==================== Scroll Animations ====================
class ScrollAnimations {
    constructor() {
        this.init();
    }

    init() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Animate news cards
        document.querySelectorAll('.news-card, .trending-card, .sidebar-widget').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    }
}

// ==================== Header Scroll Effect ====================
class HeaderScroll {
    constructor() {
        this.header = document.querySelector('.header');
        this.lastScroll = 0;
        this.init();
    }

    init() {
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            // Only change box-shadow on scroll, keep background from CSS
            if (currentScroll > 100) {
                this.header.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.1)';
            } else {
                this.header.style.boxShadow = '0 4px 16px rgba(0, 0, 0, 0.08)';
            }

            this.lastScroll = currentScroll;
        });

        this.header.style.transition = 'all 0.3s ease';
    }
}

// ==================== Search Functionality ====================
class SearchModal {
    constructor() {
        this.searchBtn = document.querySelector('.search-btn');
        if (!this.searchBtn) return;
        this.init();
    }

    init() {
        this.searchBtn.addEventListener('click', () => {
            this.openSearch();
        });
    }

    openSearch() {
        // Create search modal
        const modal = document.createElement('div');
        modal.className = 'search-modal';
        modal.innerHTML = `
            <div class="search-modal-content">
                <div class="search-modal-header">
                    <h2 class="search-modal-title">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Xəbər axtarışı
                    </h2>
                    <button class="close-search">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="search-input-wrapper">
                    <svg class="search-input-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" placeholder="Hansı xəbəri axtarırsınız?" class="search-input" autofocus>
                    <button class="search-submit-btn" type="button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        Tap
                    </button>
                </div>

                <div class="search-popular">
                    <div class="search-popular-title">🔥 Populyar axtarışlar:</div>
                    <div class="search-tags">
                        <div class="search-tags-loading">Yüklənir...</div>
                    </div>
                </div>

                <div class="search-results">
                    <div class="search-hint">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                        </svg>
                        <span>Axtarmağa başlamaq üçün yazmağa başlayın...</span>
                    </div>
                </div>
            </div>
        `;

        // Add styles
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 10000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 80px 20px;
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
        `;

        const content = modal.querySelector('.search-modal-content');
        const isDark = document.body.classList.contains('dark-theme');
        content.style.cssText = `
            width: 100%;
            max-width: 720px;
            background: ${isDark
                ? 'rgba(10, 10, 27, 0.95)'
                : 'linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95))'};
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid ${isDark ? 'rgba(99, 102, 241, 0.3)' : 'rgba(58, 145, 255, 0.2)'};
            border-radius: 28px;
            padding: 40px;
            animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 20px 60px rgba(0, 0, 0, ${isDark ? '0.7' : '0.15'}),
                        0 0 100px ${isDark ? 'rgba(99, 102, 241, 0.3)' : 'rgba(58, 145, 255, 0.2)'},
                        inset 0 1px 0 ${isDark ? 'rgba(99, 102, 241, 0.2)' : 'rgba(255, 255, 255, 1)'};
        `;

        document.body.appendChild(modal);

        // Get elements
        const searchInput = modal.querySelector('.search-input');
        const searchTagsContainer = modal.querySelector('.search-tags');
        const searchSubmitBtn = modal.querySelector('.search-submit-btn');

        // Load popular searches from API
        fetch('/api/popular-searches')
            .then(response => response.json())
            .then(queries => {
                // Clear loading message
                searchTagsContainer.innerHTML = '';

                if (queries.length > 0) {
                    // Create button for each popular query
                    queries.forEach(query => {
                        const button = document.createElement('button');
                        button.className = 'search-tag';
                        button.textContent = query;
                        button.addEventListener('click', () => {
                            window.location.href = `/search?q=${encodeURIComponent(query)}`;
                        });
                        searchTagsContainer.appendChild(button);
                    });
                } else {
                    // If no popular searches, show default message
                    searchTagsContainer.innerHTML = '<div class="search-tags-empty">Məlumat yoxdur</div>';
                }
            })
            .catch(error => {
                console.error('Error loading popular searches:', error);
                searchTagsContainer.innerHTML = '<div class="search-tags-empty">Yüklənmə xətası</div>';
            });

        // Close on click outside or ESC
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeSearch(modal);
            }
        });

        modal.querySelector('.close-search').addEventListener('click', () => {
            this.closeSearch(modal);
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSearch(modal);
            }
        });

        // Search submit button click
        searchSubmitBtn.addEventListener('click', () => {
            const query = searchInput.value.trim();
            if (query.length >= 1) {
                window.location.href = `/search?q=${encodeURIComponent(query)}`;
            }
        });

        // Enter key to search - redirect to search page
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                if (query.length >= 1) {
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // Focus input
        searchInput.focus();
    }

    closeSearch(modal) {
        modal.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

// ==================== Theme Toggle ====================
class ThemeToggle {
    constructor() {
        this.themeBtn = document.querySelector('.theme-toggle-btn');
        this.body = document.body;

        if (!this.themeBtn) return;
        this.init();
    }

    init() {
        // Проверяем сохраненную тему (по умолчанию всегда светлая)
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme === 'dark') {
            this.setTheme('dark');
        } else {
            this.setTheme('light');
        }

        // Обработчик клика
        this.themeBtn.addEventListener('click', () => {
            this.toggleTheme();
        });
    }

    setTheme(theme) {
        if (theme === 'dark') {
            this.body.classList.remove('light-theme');
            this.body.classList.add('dark-theme');
        } else {
            this.body.classList.remove('dark-theme');
            this.body.classList.add('light-theme');
        }

        // Переключаем логотип
        const logoImages = document.querySelectorAll('.logo-img');
        logoImages.forEach(img => {
            if (theme === 'dark') {
                img.src = '/images/logoag1-dark.svg';
            } else {
                img.src = '/images/newslogo3.svg';
            }
        });

        // Сохраняем выбор
        localStorage.setItem('theme', theme);

        // Добавляем анимацию
        this.themeBtn.style.transform = 'rotate(360deg) scale(1.1)';
        setTimeout(() => {
            this.themeBtn.style.transform = '';
        }, 300);
    }

    toggleTheme() {
        const currentTheme = this.body.classList.contains('dark-theme') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);

        // Показываем уведомление
        this.showNotification(newTheme === 'dark' ? 'Qaranlıq tema aktivdir' : 'İşıqlı tema aktivdir');
    }

    showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-weight: 600;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 2000);
    }
}

// ==================== Bookmark Functionality ====================
class BookmarkSystem {
    constructor() {
        this.bookmarkBtns = document.querySelectorAll('.bookmark-btn');
        this.bookmarks = JSON.parse(localStorage.getItem('bookmarks')) || [];
        this.init();
    }

    init() {
        this.bookmarkBtns.forEach((btn, index) => {
            // Check if already bookmarked
            if (this.bookmarks.includes(index)) {
                btn.style.color = 'var(--accent)';
            }

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleBookmark(btn, index);
            });
        });
    }

    toggleBookmark(btn, index) {
        if (this.bookmarks.includes(index)) {
            // Remove bookmark
            this.bookmarks = this.bookmarks.filter(item => item !== index);
            btn.style.color = 'var(--text-secondary)';
            this.showNotification('Əlfəcin silindi');
        } else {
            // Add bookmark
            this.bookmarks.push(index);
            btn.style.color = 'var(--accent)';
            this.showNotification('Əlfəcinlərə əlavə edildi');
        }

        localStorage.setItem('bookmarks', JSON.stringify(this.bookmarks));

        // Animation
        btn.style.transform = 'scale(1.3)';
        setTimeout(() => {
            btn.style.transform = 'scale(1)';
        }, 200);
    }

    showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-weight: 600;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 2000);
    }
}

// ==================== Main Featured Slider ====================
class MainFeaturedSlider {
    constructor() {
        this.track = document.querySelector('.main-featured-track');
        this.cards = document.querySelectorAll('.main-featured-card');
        this.prevBtn = document.querySelector('.prev-main-featured');
        this.nextBtn = document.querySelector('.next-main-featured');
        this.currentIndex = 0;
        this.autoPlayInterval = null;

        if (!this.track || !this.prevBtn || !this.nextBtn) return;
        this.init();
    }

    init() {
        this.prevBtn.addEventListener('click', () => this.scrollPrev());
        this.nextBtn.addEventListener('click', () => this.scrollNext());

        // Auto play
        this.startAutoPlay();

        // Pause on hover
        const slider = document.querySelector('.main-featured-slider-wrapper');
        if (slider) {
            slider.addEventListener('mouseenter', () => this.stopAutoPlay());
            slider.addEventListener('mouseleave', () => this.startAutoPlay());
        }

        // Touch support
        this.addTouchSupport();
    }

    scrollNext() {
        this.currentIndex = (this.currentIndex + 1) % this.cards.length;
        this.updatePosition();
    }

    scrollPrev() {
        this.currentIndex = (this.currentIndex - 1 + this.cards.length) % this.cards.length;
        this.updatePosition();
    }

    updatePosition() {
        const slider = document.querySelector('.main-featured-slider');
        const sliderWidth = slider ? slider.offsetWidth : 0;
        const offset = -this.currentIndex * sliderWidth;
        this.track.style.transform = `translateX(${offset}px)`;
    }

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.scrollNext(), 5000);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }

    addTouchSupport() {
        let touchStartX = 0;
        let touchEndX = 0;

        this.track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50) {
                this.scrollNext();
            }
            if (touchEndX > touchStartX + 50) {
                this.scrollPrev();
            }
        });
    }
}

// ==================== Featured Slider ====================
class FeaturedSlider {
    constructor() {
        this.track = document.querySelector('.featured-track');
        this.cards = document.querySelectorAll('.news-card.featured');
        this.prevBtn = document.querySelector('.prev-featured');
        this.nextBtn = document.querySelector('.next-featured');
        this.currentIndex = 0;
        this.autoPlayInterval = null;
        this.init();
    }

    init() {
        if (!this.track || !this.prevBtn || !this.nextBtn) return;

        this.prevBtn.addEventListener('click', () => this.scrollPrev());
        this.nextBtn.addEventListener('click', () => this.scrollNext());

        // Auto play
        this.startAutoPlay();

        // Pause on hover
        const slider = document.querySelector('.featured-slider-wrapper');
        if (slider) {
            slider.addEventListener('mouseenter', () => this.stopAutoPlay());
            slider.addEventListener('mouseleave', () => this.startAutoPlay());
        }

        // Touch support
        this.addTouchSupport();
    }

    scrollNext() {
        this.currentIndex = (this.currentIndex + 1) % this.cards.length;
        this.updatePosition();
    }

    scrollPrev() {
        this.currentIndex = (this.currentIndex - 1 + this.cards.length) % this.cards.length;
        this.updatePosition();
    }

    updatePosition() {
        const slider = document.querySelector('.featured-slider');
        const sliderWidth = slider ? slider.offsetWidth : 0;
        const offset = -this.currentIndex * sliderWidth;
        this.track.style.transform = `translateX(${offset}px)`;
    }

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.scrollNext(), 5000);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }

    addTouchSupport() {
        let touchStartX = 0;
        let touchEndX = 0;

        this.track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50) {
                this.scrollNext();
            }
            if (touchEndX > touchStartX + 50) {
                this.scrollPrev();
            }
        });
    }
}

// ==================== Filter Tabs ====================
class FilterTabs {
    constructor() {
        this.tabs = document.querySelectorAll('.filter-tab');
        if (this.tabs.length === 0) return;
        this.init();
    }

    init() {
        this.tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active from all tabs
                this.tabs.forEach(t => t.classList.remove('active'));
                // Add active to clicked tab
                tab.classList.add('active');

                // Filter animation
                const newsCards = document.querySelectorAll('.news-card');
                newsCards.forEach((card, index) => {
                    card.style.animation = `fadeIn 0.5s ease ${index * 0.1}s forwards`;
                });
            });
        });
    }
}

// ==================== Newsletter Form ====================
class Newsletter {
    constructor() {
        this.form = document.querySelector('.newsletter-form');
        if (!this.form) return;
        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            const input = this.form.querySelector('.newsletter-input');
            const email = input.value;

            if (email) {
                // Simulate subscription
                this.showSuccess();
                input.value = '';
            }
        });
    }

    showSuccess() {
        const notification = document.createElement('div');
        notification.textContent = '✓ Siz uğurla abunə oldunuz!';
        notification.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--success), var(--primary));
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4);
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
}

// ==================== Pagination ====================
class Pagination {
    constructor() {
        this.pageButtons = document.querySelectorAll('.pagination-page');
        this.prevBtn = document.querySelector('.pagination-prev');
        this.nextBtn = document.querySelector('.pagination-next');
        this.currentPage = 1;
        this.totalPages = this.pageButtons.length;

        if (!this.prevBtn || !this.nextBtn || this.pageButtons.length === 0) return;
        this.init();
    }

    init() {
        // Page button clicks
        this.pageButtons.forEach((btn, index) => {
            btn.addEventListener('click', () => {
                this.goToPage(index + 1);
            });
        });

        // Prev button click
        this.prevBtn.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.goToPage(this.currentPage - 1);
            }
        });

        // Next button click
        this.nextBtn.addEventListener('click', () => {
            if (this.currentPage < this.totalPages) {
                this.goToPage(this.currentPage + 1);
            }
        });

        // Initial state
        this.updateButtons();
    }

    goToPage(pageNumber) {
        this.currentPage = pageNumber;

        // Update active state
        this.pageButtons.forEach((btn, index) => {
            if (index + 1 === pageNumber) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        this.updateButtons();

        // Smooth scroll to top of news section
        const newsSection = document.querySelector('.news-grid-section');
        if (newsSection) {
            newsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Show loading notification
        this.showNotification(`Səhifə ${pageNumber} yüklənir...`);

        // Simulate loading content
        setTimeout(() => {
            this.showNotification(`Səhifə ${pageNumber} yükləndi`);
        }, 500);
    }

    updateButtons() {
        // Update prev button
        if (this.currentPage === 1) {
            this.prevBtn.disabled = true;
        } else {
            this.prevBtn.disabled = false;
        }

        // Update next button
        if (this.currentPage === this.totalPages) {
            this.nextBtn.disabled = true;
        } else {
            this.nextBtn.disabled = false;
        }
    }

    showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 16px 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-weight: 600;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 1500);
    }
}

// ==================== YouTube Carousel ====================
class YouTubeCarousel {
    constructor() {
        this.track = document.querySelector('.youtube-carousel-track');
        this.cards = document.querySelectorAll('.yt-card');
        this.prevBtn = document.querySelector('.prev-youtube');
        this.nextBtn = document.querySelector('.next-youtube');
        this.currentPosition = 0;
        this.currentIndex = 0;
        this.autoPlayInterval = null;

        if (!this.track || !this.prevBtn || !this.nextBtn) return;
        this.init();
    }

    getVisibleCards() {
        const width = window.innerWidth;
        if (width >= 968) return 3;
        if (width >= 640) return 2;
        return 1;
    }

    getCardWidth() {
        if (this.cards.length === 0) return 0;
        const card = this.cards[0];
        const cardStyle = window.getComputedStyle(card);
        const cardWidth = card.offsetWidth;
        const gap = 20;
        return cardWidth + gap;
    }

    init() {
        this.prevBtn.addEventListener('click', () => this.scrollPrev());
        this.nextBtn.addEventListener('click', () => this.scrollNext());

        // Auto play
        this.startAutoPlay();

        // Pause on hover
        const carousel = document.querySelector('.youtube-carousel-wrapper');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
            carousel.addEventListener('mouseleave', () => this.startAutoPlay());
        }

        // Update on resize
        window.addEventListener('resize', () => {
            this.currentIndex = 0;
            this.currentPosition = 0;
            this.updatePosition();
        });

        // Touch support
        this.addTouchSupport();

        // Update button states
        this.updateButtons();
    }

    scrollNext() {
        const visibleCards = this.getVisibleCards();
        const maxIndex = this.cards.length - visibleCards;

        if (this.currentIndex < maxIndex) {
            this.currentIndex++;
        } else {
            // Loop back to start
            this.currentIndex = 0;
        }
        this.currentPosition = -(this.currentIndex * this.getCardWidth());
        this.updatePosition();
    }

    scrollPrev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.currentPosition = -(this.currentIndex * this.getCardWidth());
            this.updatePosition();
        }
    }

    updatePosition() {
        this.track.style.transform = `translateX(${this.currentPosition}px)`;
        this.updateButtons();
    }

    updateButtons() {
        const visibleCards = this.getVisibleCards();
        const maxIndex = this.cards.length - visibleCards;

        // Disable/enable prev button
        if (this.currentIndex <= 0) {
            this.prevBtn.style.opacity = '0.5';
            this.prevBtn.style.cursor = 'not-allowed';
        } else {
            this.prevBtn.style.opacity = '1';
            this.prevBtn.style.cursor = 'pointer';
        }

        // Disable/enable next button
        if (this.currentIndex >= maxIndex) {
            this.nextBtn.style.opacity = '0.5';
            this.nextBtn.style.cursor = 'not-allowed';
        } else {
            this.nextBtn.style.opacity = '1';
            this.nextBtn.style.cursor = 'pointer';
        }
    }

    addTouchSupport() {
        let touchStartX = 0;
        let touchEndX = 0;

        this.track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        this.track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchEndX < touchStartX - 50) {
                this.scrollNext();
            }
            if (touchEndX > touchStartX + 50) {
                this.scrollPrev();
            }
        });
    }

    startAutoPlay() {
        this.autoPlayInterval = setInterval(() => this.scrollNext(), 4000);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }
}

// ==================== Add CSS Animations ====================
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    @keyframes slideDown {
        from { transform: translateY(-30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    @media (max-width: 768px) {
        .search-modal-content {
            padding: 28px 24px !important;
            border-radius: 24px !important;
        }

        .search-modal-title {
            font-size: 22px !important;
        }

        .search-input {
            font-size: 16px !important;
            padding: 16px 20px 16px 52px !important;
        }

        .search-tags {
            gap: 8px !important;
        }

        .search-tag {
            padding: 8px 16px !important;
            font-size: 13px !important;
        }
    }

    .search-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .search-modal-title {
        font-size: 26px;
        font-weight: 700;
        background: linear-gradient(135deg, #3a91ff, #e84c3d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
    }

    .search-modal-title svg {
        background: linear-gradient(135deg, #3a91ff, #e84c3d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        stroke: url(#gradient);
    }

    .close-search {
        width: 48px;
        height: 48px;
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 14px;
        color: #ef4444;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-search:hover {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-color: rgba(239, 68, 68, 0.5);
        transform: rotate(90deg) scale(1.05);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .close-search svg {
        stroke: currentColor;
    }

    .search-input-wrapper {
        position: relative;
        margin-bottom: 32px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-input-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #3a91ff;
        pointer-events: none;
        z-index: 1;
    }

    .search-input {
        flex: 1;
        padding: 18px 20px 18px 56px;
        background: rgba(58, 145, 255, 0.05);
        border: 2px solid rgba(58, 145, 255, 0.15);
        border-radius: 16px;
        color: var(--text-primary);
        font-size: 17px;
        font-weight: 500;
        outline: none;
        transition: all 0.3s ease;
    }

    .search-submit-btn {
        padding: 18px 28px;
        background: linear-gradient(135deg, #3a91ff, #1e6fd9);
        border: none;
        border-radius: 16px;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(58, 145, 255, 0.3);
    }

    .search-submit-btn:hover {
        background: linear-gradient(135deg, #1e6fd9, #1557b0);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(58, 145, 255, 0.4);
    }

    .search-submit-btn:active {
        transform: translateY(0);
    }

    .search-submit-btn svg {
        width: 18px;
        height: 18px;
    }

    .search-input::placeholder {
        color: var(--text-secondary);
        opacity: 0.8;
    }

    .search-input:focus {
        background: rgba(58, 145, 255, 0.08);
        border-color: #3a91ff;
        box-shadow: 0 0 0 4px rgba(58, 145, 255, 0.15),
                    0 8px 24px rgba(58, 145, 255, 0.2);
    }

    .search-popular {
        margin-bottom: 28px;
    }

    .search-popular-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .search-tag {
        padding: 10px 20px;
        background: rgba(58, 145, 255, 0.08);
        border: 1px solid rgba(58, 145, 255, 0.2);
        border-radius: 24px;
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .search-tag:hover {
        background: linear-gradient(135deg, #3a91ff, #2b7ae5);
        border-color: transparent;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 20px rgba(58, 145, 255, 0.3);
        color: white;
    }

    .search-hint {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px;
        background: rgba(245, 158, 11, 0.05);
        border: 1px dashed rgba(245, 158, 11, 0.3);
        border-radius: 14px;
        color: var(--text-secondary);
        font-size: 15px;
    }

    .search-hint svg {
        color: #f59e0b;
        flex-shrink: 0;
    }

    /* Dark Theme Styles for Search Modal */
    body.dark-theme .search-modal-title {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    body.dark-theme .search-modal-title svg {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        stroke: #6366f1;
    }

    body.dark-theme .search-input-icon {
        color: #6366f1;
    }

    body.dark-theme .search-input {
        background: rgba(99, 102, 241, 0.1);
        border: 2px solid rgba(99, 102, 241, 0.2);
        color: #ffffff;
    }

    body.dark-theme .search-input::placeholder {
        color: #b4b4c8;
    }

    body.dark-theme .search-input:focus {
        background: rgba(99, 102, 241, 0.15);
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2),
                    0 8px 24px rgba(99, 102, 241, 0.3);
    }

    body.dark-theme .search-submit-btn {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    body.dark-theme .search-submit-btn:hover {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.5);
    }

    body.dark-theme .search-popular-title {
        color: #b4b4c8;
    }

    body.dark-theme .search-tag {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #ffffff;
    }

    body.dark-theme .search-tag:hover {
        background: linear-gradient(135deg, #6366f1, #ec4899);
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }

    body.dark-theme .close-search {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    body.dark-theme .search-hint {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.3);
        color: #b4b4c8;
    }
`;
document.head.appendChild(style);

// ==================== Mobile Menu ====================
class MobileMenu {
    constructor() {
        this.menuBtn = document.querySelector('.menu-btn');
        this.body = document.body;
        this.isOpen = false;

        if (!this.menuBtn) return;
        this.init();
    }

    init() {
        // Click handler for menu button
        this.menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleMenu();
        });
    }

    toggleMenu() {
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            this.openMenu();
        } else {
            this.closeMenu();
        }
    }

    openMenu() {
        const overlay = document.getElementById('mobileMenuOverlay');
        const closeBtn = document.getElementById('mobileMenuClose');
        const themeBtn = document.getElementById('mobileThemeToggle');

        if (!overlay) return;

        // Show overlay
        overlay.style.display = 'block';
        overlay.style.animation = 'fadeIn 0.3s ease';

        // Animate menu button to X
        this.menuBtn.classList.add('active');

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.closeMenu();
            }
        });

        // Close button
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.closeMenu();
            });
        }

        // Theme toggle
        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                document.querySelector('.theme-toggle-btn')?.click();
            });
        }

        // Prevent body scroll
        document.body.classList.add('menu-open');
    }

    closeMenu() {
        const overlay = document.getElementById('mobileMenuOverlay');
        if (!overlay) return;

        overlay.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);

        // Animate menu button back to hamburger
        this.menuBtn.classList.remove('active');

        // Restore body scroll
        document.body.classList.remove('menu-open');

        this.isOpen = false;
    }
}

// ==================== Sidebar Banners Footer Control ====================
class SidebarBannersControl {
    constructor() {
        this.banners = document.querySelectorAll('.side-banner');
        this.footer = document.querySelector('.footer');

        if (this.banners.length === 0 || !this.footer) return;

        this.init();
    }

    init() {
        window.addEventListener('scroll', () => this.handleScroll());
        // Initial check
        this.handleScroll();
    }

    handleScroll() {
        const scrolled = window.pageYOffset;
        const windowHeight = window.innerHeight;
        const footerTop = this.footer.getBoundingClientRect().top + scrolled;

        this.banners.forEach(banner => {
            const bannerHeight = 580; // Height of banner
            const bannerBottom = scrolled + windowHeight - (windowHeight - 160 - bannerHeight); // 160 is top offset

            // Hide banners when they would overlap with footer
            if (bannerBottom + 20 >= footerTop) {
                banner.style.opacity = '0';
                banner.style.visibility = 'hidden';
            } else {
                banner.style.opacity = '1';
                banner.style.visibility = 'visible';
            }
        });
    }
}

// ==================== Initialize All ====================
document.addEventListener('DOMContentLoaded', () => {
    new HeroSlider();
    new TrendingCarousel();
    new NewsTicker();
    new ScrollAnimations();
    new HeaderScroll();
    new SearchModal();
    new ThemeToggle();
    new BookmarkSystem();
    new MainFeaturedSlider();
    new FeaturedSlider();
    new FilterTabs();
    new Newsletter();
    new Pagination();
    new YouTubeCarousel();
    new MobileMenu();
    new SidebarBannersControl();

    // Add smooth scroll
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

    // Add parallax effect to background shapes
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const shapes = document.querySelectorAll('.shape');
        shapes.forEach((shape, index) => {
            const speed = 0.5 + (index * 0.2);
            shape.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });

    // Console message
    console.log('%c🎉 News24 - Müasir Xəbər Portalı', 'font-size: 20px; font-weight: bold; color: #6366f1;');
    console.log('%c❤️ ilə 2025-ci ilin son veb dizayn trendlərindən istifadə edərək hazırlanıb', 'font-size: 14px; color: #a1a1b5;');
});
