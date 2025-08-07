// Shop JavaScript functionality
class ShopManager {
    constructor() {
        this.cart = [];
        this.cartSlider = document.getElementById('cartSlider');
        this.cartFloatingBtn = document.getElementById('cartFloatingBtn');
        this.cartItems = document.getElementById('cartItems');
        this.cartTotal = document.getElementById('cartTotal');
        this.cartCount = document.getElementById('cartCount');
        
        this.init();
    }

    init() {
        this.loadCart();
        this.bindEvents();
        this.initProductFilters();
        this.initProductGallery();
        this.initQuantitySelectors();
        this.initAddToCartButtons();
        this.showFloatingCartBtn();
    }

    bindEvents() {
        // Cart slider events
        if (this.cartFloatingBtn) {
            this.cartFloatingBtn.addEventListener('click', () => this.openCart());
        }

        const closeCartBtn = document.getElementById('closeCart');
        if (closeCartBtn) {
            closeCartBtn.addEventListener('click', () => this.closeCart());
        }

        const cartOverlay = this.cartSlider?.querySelector('.cart-slider__overlay');
        if (cartOverlay) {
            cartOverlay.addEventListener('click', () => this.closeCart());
        }

        // Checkout button
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => this.checkout());
        }

        // ESC key to close cart
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.cartSlider?.classList.contains('active')) {
                this.closeCart();
            }
        });
    }

    initProductFilters() {
        const filterBtns = document.querySelectorAll('.shop__filter-btn');
        const productCards = document.querySelectorAll('.shop__product-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const category = btn.dataset.category;
                
                // Update active button
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Filter products
                productCards.forEach(card => {
                    const cardCategory = card.dataset.category;
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    initProductGallery() {
        const thumbnails = document.querySelectorAll('.gallery__thumbnail');
        const mainImage = document.getElementById('mainImage');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                const img = thumbnail.querySelector('img');
                if (img && mainImage) {
                    // Update active thumbnail
                    thumbnails.forEach(t => t.classList.remove('active'));
                    thumbnail.classList.add('active');
                    
                    // Update main image
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = img.src;
                        mainImage.style.opacity = '1';
                    }, 200);
                }
            });
        });
    }

    initQuantitySelectors() {
        const quantityInputs = document.querySelectorAll('.quantity-selector__input');
        
        quantityInputs.forEach(input => {
            const minusBtn = input.parentNode.querySelector('.quantity-selector__btn--minus');
            const plusBtn = input.parentNode.querySelector('.quantity-selector__btn--plus');
            
            if (minusBtn) {
                minusBtn.addEventListener('click', () => {
                    const currentValue = parseInt(input.value);
                    const minValue = parseInt(input.min) || 1;
                    if (currentValue > minValue) {
                        input.value = currentValue - 1;
                        this.updateQuantityButtonStates(input);
                    }
                });
            }
            
            if (plusBtn) {
                plusBtn.addEventListener('click', () => {
                    const currentValue = parseInt(input.value);
                    const maxValue = parseInt(input.max) || Infinity;
                    if (currentValue < maxValue) {
                        input.value = currentValue + 1;
                        this.updateQuantityButtonStates(input);
                    }
                });
            }

            input.addEventListener('input', () => {
                this.updateQuantityButtonStates(input);
            });

            // Initial state
            this.updateQuantityButtonStates(input);
        });
    }

    updateQuantityButtonStates(input) {
        const minusBtn = input.parentNode.querySelector('.quantity-selector__btn--minus');
        const plusBtn = input.parentNode.querySelector('.quantity-selector__btn--plus');
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.min) || 1;
        const maxValue = parseInt(input.max) || Infinity;

        if (minusBtn) {
            minusBtn.disabled = currentValue <= minValue;
        }
        if (plusBtn) {
            plusBtn.disabled = currentValue >= maxValue;
        }
    }

    initAddToCartButtons() {
        const addToCartBtns = document.querySelectorAll('.product-card__add-btn, .add-to-cart-btn');
        
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (btn.disabled) return;
                
                const productId = btn.dataset.productId;
                let quantity = 1;
                
                // Check if we're on product detail page
                const quantityInput = document.getElementById('quantityInput');
                if (quantityInput) {
                    quantity = parseInt(quantityInput.value) || 1;
                }
                
                this.addToCart(productId, quantity, btn);
            });
        });
    }

    async addToCart(productId, quantity, button) {
        try {
            // Add loading state
            const originalText = button.textContent;
            button.textContent = 'Ajout...';
            button.disabled = true;
            
            const response = await fetch(`/shop/add-to-cart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `quantity=${quantity}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                this.showSuccessMessage('Produit ajouté au panier !');
                
                // Open cart slider briefly
                this.openCart();
                setTimeout(() => {
                    if (!this.cartSlider.matches(':hover')) {
                        // Auto-close if not hovering
                        // this.closeCart();
                    }
                }, 2000);
            } else {
                this.showErrorMessage(data.message || 'Erreur lors de l\'ajout au panier');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showErrorMessage('Erreur de connexion');
        } finally {
            // Restore button state
            button.textContent = originalText;
            button.disabled = false;
        }
    }

    async loadCart() {
        try {
            const response = await fetch('/shop/cart');
            const data = await response.json();
            this.cart = data.cart;
            this.updateCartUI();
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }

    async updateCartItem(productId, quantity) {
        try {
            const response = await fetch(`/shop/update-cart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `quantity=${quantity}`
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
            } else {
                this.showErrorMessage('Erreur lors de la mise à jour');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            this.showErrorMessage('Erreur de connexion');
        }
    }

    async removeFromCart(productId) {
        try {
            const response = await fetch(`/shop/remove-from-cart/${productId}`, {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.cart = data.cart;
                this.updateCartUI();
                this.showSuccessMessage('Produit supprimé du panier');
            } else {
                this.showErrorMessage('Erreur lors de la suppression');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            this.showErrorMessage('Erreur de connexion');
        }
    }

    updateCartUI() {
        this.updateCartItems();
        this.updateCartTotal();
        this.updateCartCount();
    }

    updateCartItems() {
        if (!this.cartItems) return;

        if (Object.keys(this.cart).length === 0) {
            this.cartItems.innerHTML = `
                <div class="cart-items empty">
                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                    <p>Votre panier est vide</p>
                </div>
            `;
        } else {
            this.cartItems.innerHTML = Object.values(this.cart).map(item => `
                <div class="cart-item" data-product-id="${item.product_id}">
                    <img src="/images/products/${item.image}" alt="${item.name}" class="cart-item__image">
                    <div class="cart-item__details">
                        <div class="cart-item__name">${item.name}</div>
                        <div class="cart-item__price">${item.price}€</div>
                        <div class="cart-item__quantity">
                            <button class="cart-item__quantity-btn" onclick="shopManager.updateCartItem(${item.product_id}, ${item.quantity - 1})">-</button>
                            <input type="number" class="cart-item__quantity-input" value="${item.quantity}" min="1" 
                                   onchange="shopManager.updateCartItem(${item.product_id}, this.value)">
                            <button class="cart-item__quantity-btn" onclick="shopManager.updateCartItem(${item.product_id}, ${item.quantity + 1})">+</button>
                            <button class="cart-item__remove" onclick="shopManager.removeFromCart(${item.product_id})" title="Supprimer">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }

    updateCartTotal() {
        if (!this.cartTotal) return;

        const total = Object.values(this.cart).reduce((sum, item) => {
            return sum + (item.price * item.quantity);
        }, 0);

        this.cartTotal.textContent = `${total.toFixed(2)}€`;
    }

    updateCartCount() {
        if (!this.cartCount) return;

        const count = Object.values(this.cart).reduce((sum, item) => {
            return sum + item.quantity;
        }, 0);

        this.cartCount.textContent = count;
        
        // Show/hide floating button based on cart count
        if (count > 0) {
            this.cartFloatingBtn?.classList.add('show');
        } else {
            this.cartFloatingBtn?.classList.remove('show');
        }
    }

    openCart() {
        if (this.cartSlider) {
            this.cartSlider.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus trap
            const focusableElements = this.cartSlider.querySelectorAll(
                'button, input, select, textarea, [href], [tabindex]:not([tabindex="-1"])'
            );
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }
    }

    closeCart() {
        if (this.cartSlider) {
            this.cartSlider.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    checkout() {
        if (Object.keys(this.cart).length === 0) {
            this.showErrorMessage('Votre panier est vide');
            return;
        }

        // Simple checkout redirect for now
        // In a real app, this would go to a proper checkout flow
        alert('Redirection vers le paiement...\n(Fonctionnalité à implémenter)');
    }

    showFloatingCartBtn() {
        // Show floating cart button after a delay on page load
        setTimeout(() => {
            if (Object.keys(this.cart).length > 0) {
                this.cartFloatingBtn?.classList.add('show');
            }
        }, 1000);
    }

    showSuccessMessage(message) {
        this.showToast(message, 'success');
    }

    showErrorMessage(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.textContent = message;
        
        // Style toast
        Object.assign(toast.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '15px 20px',
            borderRadius: '8px',
            color: '#fff',
            fontWeight: '500',
            zIndex: '10000',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease',
            maxWidth: '300px',
            boxShadow: '0 4px 15px rgba(0,0,0,0.2)'
        });

        // Set background color based on type
        if (type === 'success') {
            toast.style.background = 'linear-gradient(135deg, #10b981, #059669)';
        } else if (type === 'error') {
            toast.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
        } else {
            toast.style.background = 'linear-gradient(135deg, #3b82f6, #2563eb)';
        }

        // Add to DOM
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Remove after delay
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
}

// Initialize shop manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.shopManager = new ShopManager();
});

// Additional product detail functionality
document.addEventListener('DOMContentLoaded', () => {
    // Smooth scroll for back button if it goes to an anchor
    const backBtn = document.querySelector('.back-btn');
    if (backBtn && backBtn.getAttribute('href').includes('#')) {
        backBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = backBtn.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Add loading states to product images
    const productImages = document.querySelectorAll('.product-card__image, .gallery__main-image');
    productImages.forEach(img => {
        img.addEventListener('load', () => {
            img.style.opacity = '1';
        });
        img.addEventListener('error', () => {
            img.style.opacity = '0.5';
            // Could add a placeholder image here
        });
    });

    // Lazy loading for product images (if supported)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});