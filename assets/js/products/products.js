document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const productsGrid = document.querySelector('.products-grid');
    const cartCounter = document.querySelector('.cart-counter');
    const cartPopup = document.getElementById('cart-popup');
    const counter = document.querySelector('.counter');
    const orderBtn = document.querySelector('.order-btn');
    const cartItems = document.getElementById('cart-items');
    const total = document.getElementById('total-price');
    const quitCart = document.querySelector('.quitCart');

    let products = [];
    let filteredProducts = [];
    let cart = [];
    let orderHistory = [];



    async function loadProducts() {
        try {
            const response = await fetch('../api/get_products.php');
            const data = await response.json();

            if (data.success) {
                products = data.products;
                filteredProducts = [...products];
                updateProductsGrid();
            } else {
                console.error('Errore nel caricamento dei prodotti:', data.error);
                productsGrid.innerHTML = '<p class="error-message">Errore nel caricamento dei prodotti</p>';
            }
        } catch (error) {
            console.error('Errore nella richiesta:', error);
            productsGrid.innerHTML = '<p class="error-message">Errore nel caricamento dei prodotti</p>';
        }
    }

    function createProductCard(product) {
        return `
            <div class="product-card" data-id="${product.id}">
                <div class="product-image">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <div class="product-meta">
                        <span class="product-category">
                            <i class="fas fa-tag"></i>
                            ${product.category}
                        </span>
                        <span class="product-price">€${product.price}</span>
                    </div>
                    <button class="add-to-cart" data-product-id="${product.id}">
                        Aggiungi all'ordine
                    </button>
                </div>
            </div>
        `;
    }

    function updateProductsGrid() {
        productsGrid.innerHTML = filteredProducts.map(createProductCard).join('');
    }

    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const priceSort = priceFilter.value;

        filteredProducts = products.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm);
            const matchesCategory = selectedCategory === '' || product.category === selectedCategory;
            return matchesSearch && matchesCategory;
        });

        if (priceSort === 'asc') {
            filteredProducts.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
        } else if (priceSort === 'desc') {
            filteredProducts.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
        }

        updateProductsGrid();
    }

    function updateCartCounter() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        counter.textContent = totalItems;
    }

    function addToCart(productId) {
        const product = products.find(p => p.id == productId);
        if (product) {
            const existingItem = cart.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id: productId, quantity: 1, price: product.price, name: product.name });
            }

            localStorage.setItem('cart', JSON.stringify(cart));

            updateCartCounter();
        }
    }

    function removeFromCart(productId) {
        const index = cart.findIndex(item => item.id === productId);
        if (index !== -1) {
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
            } else {
                cart.splice(index, 1);
            }

            localStorage.setItem('cart', JSON.stringify(cart));

            updateCartCounter();

            updateCartPopup();
        }
    }

    function loadCart() {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCartCounter();
        }
    }

    function updateCartPopup() {
        cartItems.innerHTML = '';

        cart.forEach(item => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <p>Prezzo: €${item.price}</p>
                    <p>Quantità: ${item.quantity}</p>
                </div>
                <button class="remove-item" data-product-id="${item.id}">✖</button>
            `;

            cartItems.appendChild(cartItem);
        });

        const totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
        total.innerHTML = 'Totale: €' + totalPrice.toFixed(2);
    }

    cartCounter.addEventListener('click', function(e) {
        cartPopup.classList.toggle('show');
        if (cartPopup.classList.contains('show')) {
            updateCartPopup();
        }
    });

    function loadOrderHistory() {
        const savedHistory = localStorage.getItem('orderHistory');
        if (savedHistory) {
            orderHistory = JSON.parse(savedHistory);
        }
    }

    function saveOrder() {
        if (cart.length === 0) return;
        
        const order = {
            id: Date.now(),
            date: new Date().toLocaleString(),
            items: [...cart],
            total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0)
        };

        orderHistory.unshift(order); 
        localStorage.setItem('orderHistory', JSON.stringify(orderHistory));
        
        cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCounter();
        updateCartPopup();
        
        showOrderConfirmation(order);
        updateOrderHistory();
    }

    function showOrderConfirmation(order) {
        const confirmation = document.createElement('div');
        confirmation.className = 'order-confirmation';
        confirmation.innerHTML = `
            <h3>Ordine Completato!</h3>
            <p>Totale: €${order.total.toFixed(2)}</p>
            <p>Data: ${order.date}</p>
        `;
        document.body.appendChild(confirmation);
        
        setTimeout(() => {
            confirmation.remove();
        }, 3000);
    }

    function updateOrderHistory() {
        const historyContainer = document.getElementById('order-history');
        if (!historyContainer) return;

        if (orderHistory.length === 0) {
            historyContainer.innerHTML = '<p>Nessun ordine effettuato</p>';
            return;
        }

        historyContainer.innerHTML = orderHistory.map(order => `
            <div class="order-card">
                <div class="order-header">
                    <span class="order-date">${order.date}</span>
                    <span class="order-total">Totale: €${order.total.toFixed(2)}</span>
                </div>
                <div class="order-items">
                    ${order.items.map(item => `
                        <div class="order-item">
                            <span class="item-name">${item.name}</span>
                            <span class="item-quantity">x${item.quantity}</span>
                            <span class="item-price">€${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    orderBtn.addEventListener('click', function() {
        saveOrder();
        cartPopup.classList.remove('show');
    });

    quitCart.addEventListener('click', function() {
        cartPopup.classList.remove('show');
    });

    productsGrid.addEventListener('click', function(e) {
        const button = e.target.closest('.add-to-cart');
        if (button) {
            const productId = button.dataset.productId;
            addToCart(productId);

            button.classList.add('added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
        }
    });

    cartItems.addEventListener('click', function(e) {
        const button = e.target.closest('.remove-item');
        if (button) {
            const productId = button.dataset.productId;
            removeFromCart(productId);
        }
    });

    

    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);

    loadProducts();
    loadCart();
    loadOrderHistory();
    updateOrderHistory();
});
