// Eloscope Main JavaScript

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Initialize Application
function initializeApp() {
    initScrollAnimations();
    initCounters();
    initFormHandlers();
    initWhatsAppIntegration();
    initSmoothScrolling();
    initLoadingStates();
    initAnalytics();
    initAccessibility();
    initPerformanceOptimizations();
}

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // Trigger counter animation if element has counter class
                if (entry.target.classList.contains('counter')) {
                    animateCounter(entry.target);
                }
            }
        });
    }, observerOptions);

    // Observe all elements with animation classes
    const animatedElements = document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .counter');
    animatedElements.forEach(el => observer.observe(el));
}

// Initialize Counters
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    counters.forEach(counter => {
        counter.textContent = '0';
        counter.classList.add('counter');
    });
}

// Counter Animation
function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-counter')) || parseInt(element.getAttribute('data-target')) || 0;
    const duration = parseInt(element.getAttribute('data-duration')) || 2000;
    const increment = target / (duration / 16); // 60fps
    let current = 0;

    const updateCounter = () => {
        current += increment;
        if (current < target) {
            element.textContent = Math.floor(current);
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    };

    updateCounter();
}

// Form Handlers
function initFormHandlers() {
    // Newsletter Form
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', handleNewsletterSubmit);
    }

    // Contact Form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }

    // Consultation Form
    const consultationForm = document.getElementById('consultation-form');
    if (consultationForm) {
        consultationForm.addEventListener('submit', handleConsultationSubmit);
    }

    // Real-time form validation
    const formInputs = document.querySelectorAll('.form-input');
    formInputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
}

// Newsletter Form Handler
async function handleNewsletterSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    try {
        setLoadingState(submitBtn, true);
        
        const response = await fetch('/api/newsletter.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Inscrição realizada com sucesso!', 'success');
            form.reset();
            
            // Track conversion
            trackEvent('newsletter_signup', {
                email: formData.get('email')
            });
        } else {
            showNotification(result.message || 'Erro ao processar inscrição', 'error');
        }
    } catch (error) {
        console.error('Newsletter error:', error);
        showNotification('Erro de conexão. Tente novamente.', 'error');
    } finally {
        setLoadingState(submitBtn, false);
    }
}

// Contact Form Handler
async function handleContactSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    try {
        setLoadingState(submitBtn, true);
        
        const response = await fetch('/api/contact.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Mensagem enviada com sucesso!', 'success');
            form.reset();
            
            // Track conversion
            trackEvent('contact_form', {
                name: formData.get('name'),
                email: formData.get('email')
            });
        } else {
            showNotification(result.message || 'Erro ao enviar mensagem', 'error');
        }
    } catch (error) {
        console.error('Contact error:', error);
        showNotification('Erro de conexão. Tente novamente.', 'error');
    } finally {
        setLoadingState(submitBtn, false);
    }
}

// Consultation Form Handler
async function handleConsultationSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    try {
        setLoadingState(submitBtn, true);
        
        const response = await fetch('/api/consultation.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Solicitação enviada! Entraremos em contato em breve.', 'success');
            form.reset();
            
            // Track high-value conversion
            trackEvent('consultation_request', {
                name: formData.get('name'),
                email: formData.get('email'),
                company: formData.get('company'),
                revenue: formData.get('revenue')
            });
            
            // Facebook Pixel Lead Event
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Lead', {
                    content_name: 'Consultation Request',
                    value: 1000,
                    currency: 'BRL'
                });
            }
        } else {
            showNotification(result.message || 'Erro ao processar solicitação', 'error');
        }
    } catch (error) {
        console.error('Consultation error:', error);
        showNotification('Erro de conexão. Tente novamente.', 'error');
    } finally {
        setLoadingState(submitBtn, false);
    }
}

// Field Validation
function validateField(e) {
    const field = e.target;
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute('required');
    
    clearFieldError(field);
    
    if (required && !value) {
        showFieldError(field, 'Este campo é obrigatório');
        return false;
    }
    
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, 'Email inválido');
            return false;
        }
    }
    
    if (type === 'tel' && value) {
        const phoneRegex = /^[\d\s\(\)\+\-]{10,}$/;
        if (!phoneRegex.test(value)) {
            showFieldError(field, 'Telefone inválido');
            return false;
        }
    }
    
    return true;
}

function showFieldError(field, message) {
    field.classList.add('border-red-500');
    
    let errorElement = field.parentNode.querySelector('.field-error');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'field-error text-red-400 text-sm mt-1';
        field.parentNode.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// WhatsApp Integration
function initWhatsAppIntegration() {
    const whatsappButtons = document.querySelectorAll('.whatsapp-btn');
    
    whatsappButtons.forEach(btn => {
        btn.addEventListener('click', handleWhatsAppClick);
    });
}

function handleWhatsAppClick(e) {
    e.preventDefault();
    
    const btn = e.currentTarget;
    const message = btn.getAttribute('data-message') || 'Olá! Gostaria de saber mais sobre a Eloscope.';
    const phone = '5511999999999'; // Replace with actual WhatsApp number
    
    const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    
    // Track WhatsApp click
    trackEvent('whatsapp_click', {
        source: btn.getAttribute('data-source') || 'unknown',
        message: message
    });
    
    window.open(whatsappUrl, '_blank');
}

// Smooth Scrolling
function initSmoothScrolling() {
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    
    scrollLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                const targetPosition = targetElement.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Loading States
function initLoadingStates() {
    // Add loading class to elements that need it
    const loadingElements = document.querySelectorAll('.loading');
    
    // Simulate loading completion after images load
    window.addEventListener('load', () => {
        loadingElements.forEach(el => {
            el.classList.remove('loading');
        });
    });
}

function setLoadingState(element, isLoading) {
    if (isLoading) {
        element.disabled = true;
        element.classList.add('loading');
        element.setAttribute('data-original-text', element.textContent);
        element.textContent = 'Processando...';
    } else {
        element.disabled = false;
        element.classList.remove('loading');
        element.textContent = element.getAttribute('data-original-text') || element.textContent;
    }
}

// Analytics Integration
function initAnalytics() {
    // Track page view
    trackEvent('page_view', {
        page: window.location.pathname,
        title: document.title
    });
    
    // Track scroll depth
    let maxScroll = 0;
    window.addEventListener('scroll', throttle(() => {
        const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
        
        if (scrollPercent > maxScroll) {
            maxScroll = scrollPercent;
            
            // Track milestone scrolls
            if ([25, 50, 75, 90].includes(scrollPercent)) {
                trackEvent('scroll_depth', {
                    percent: scrollPercent
                });
            }
        }
    }, 1000));
    
    // Track time on page
    let startTime = Date.now();
    window.addEventListener('beforeunload', () => {
        const timeOnPage = Math.round((Date.now() - startTime) / 1000);
        trackEvent('time_on_page', {
            seconds: timeOnPage
        });
    });
}

function trackEvent(eventName, parameters = {}) {
    // Google Analytics 4
    if (typeof gtag !== 'undefined') {
        gtag('event', eventName, parameters);
    }
    
    // Facebook Pixel
    if (typeof fbq !== 'undefined') {
        fbq('trackCustom', eventName, parameters);
    }
    
    // Console log for debugging
    console.log('Event tracked:', eventName, parameters);
}

// Accessibility
function initAccessibility() {
    // Skip link functionality
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(skipLink.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView();
            }
        });
    }
    
    // Keyboard navigation for custom elements
    const interactiveElements = document.querySelectorAll('.card, .btn-primary, .btn-secondary');
    interactiveElements.forEach(el => {
        if (!el.hasAttribute('tabindex')) {
            el.setAttribute('tabindex', '0');
        }
        
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                el.click();
            }
        });
    });
    
    // Announce dynamic content changes
    const announcer = document.createElement('div');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    announcer.className = 'sr-only';
    document.body.appendChild(announcer);
    
    window.announceToScreenReader = (message) => {
        announcer.textContent = message;
        setTimeout(() => {
            announcer.textContent = '';
        }, 1000);
    };
}

// Performance Optimizations
function initPerformanceOptimizations() {
    // Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Preload critical resources
    const criticalResources = [
        '/assets/css/style.css',
        '/assets/js/main.js'
    ];
    
    criticalResources.forEach(resource => {
        const link = document.createElement('link');
        link.rel = 'preload';
        link.href = resource;
        link.as = resource.endsWith('.css') ? 'style' : 'script';
        document.head.appendChild(link);
    });
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type} fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm`;
    
    const bgColor = {
        success: 'bg-green-600',
        error: 'bg-red-600',
        warning: 'bg-yellow-600',
        info: 'bg-blue-600'
    }[type] || 'bg-blue-600';
    
    notification.classList.add(bgColor);
    notification.innerHTML = `
        <div class="flex items-center justify-between text-white">
            <span>${message}</span>
            <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                ×
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
    
    // Announce to screen readers
    if (window.announceToScreenReader) {
        window.announceToScreenReader(message);
    }
}

// Utility Functions
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// API Helper
class APIClient {
    static async request(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(endpoint, config);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API request failed:', error);
            throw error;
        }
    }
    
    static async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }
    
    static async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }
}

// Export for use in other scripts
window.EloscopeApp = {
    trackEvent,
    showNotification,
    APIClient,
    announceToScreenReader: () => window.announceToScreenReader
};

// Service Worker Registration (for PWA capabilities)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}

// Error Handling
window.addEventListener('error', (e) => {
    console.error('Global error:', e.error);
    
    // Track errors for debugging
    trackEvent('javascript_error', {
        message: e.message,
        filename: e.filename,
        lineno: e.lineno,
        colno: e.colno
    });
});

// Unhandled Promise Rejection
window.addEventListener('unhandledrejection', (e) => {
    console.error('Unhandled promise rejection:', e.reason);
    
    trackEvent('promise_rejection', {
        reason: e.reason?.toString() || 'Unknown'
    });
});

// Development helpers
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    window.EloscopeDebug = {
        trackEvent,
        showNotification,
        APIClient
    };
    
    console.log('Eloscope Debug tools available at window.EloscopeDebug');
}