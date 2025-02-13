document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const productsGrid = document.querySelector('.products-grid');
    const cartCounter = document.querySelector('.cart-counter');
    const cartPopup = document.getElementById('cart-popup');
    const counter = document.querySelector('.counter');
    const orderBtn = document.querySelector('.order-btn');
    const cartItems = document.getElementById('cart-items');

    let products = [];
    let filteredProducts = [];
    let cart = {};

    // Funzione per caricare i prodotti dal database
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
                        Aggiungi al carrello
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

    // Gestione del carrello
    function updateCartCounter() {
        const totalItems = Object.values(cart).reduce((sum, quantity) => sum + quantity, 0);
        counter.textContent = totalItems;
    }

    function addToCart(productId) {
        if (!cart[productId]) {
            cart[productId] = 0;
        }
        cart[productId]++;

        // Salva il carrello nel localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Aggiorna il contatore
        updateCartCounter();
    }

    function removeFromCart(productId) {
        if (cart[productId]) {
            cart[productId]--;
            if (cart[productId] === 0) {
                delete cart[productId];
            }

            // Salva il carrello nel localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Aggiorna il contatore
            updateCartCounter();

            // Aggiorna il popup del carrello
            updateCartPopup();
        }
    }

    // Carica il carrello dal localStorage all'avvio
    function loadCart() {
        const savedCart = localStorage.getItem('cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCartCounter();
        }
    }

    function updateCartPopup() {
        cartItems.innerHTML = '';

        // Itera sugli elementi del carrello
        Object.keys(cart).forEach(productId => {
            const product = products.find(p => p.id == productId);
            if (product) {
                const quantity = cart[productId];

                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                cartItem.innerHTML = `
                    <div class="cart-item-info">
                        <h4>${product.name}</h4>
                        <p>Prezzo: €${product.price}</p>
                        <p>Quantità: ${quantity}</p>
                    </div>
                    <button class="remove-item" data-product-id="${productId}">✖</button>
                `;

                cartItems.appendChild(cartItem);
            }
        });
    }

    // Gestione del popup del carrello
    cartCounter.addEventListener('click', function(e) {
        cartPopup.classList.toggle('show');
        if (cartPopup.classList.contains('show')) {
            updateCartPopup();
        }
    });

    // Chiudi il popup quando si clicca fuori
    document.addEventListener('click', function(e) {
        if (!cartPopup.contains(e.target) && !cartCounter.contains(e.target)) {
            cartPopup.classList.remove('show');
        }
    });

    // Gestione dell'ordine
    orderBtn.addEventListener('click', function() {
        localStorage.removeItem('cart');
        cart = {};
        updateCartCounter();
        cartPopup.classList.remove('show');
    });

    // Event per i pulsanti "Aggiungi al carrello"
    productsGrid.addEventListener('click', function(e) {
        const button = e.target.closest('.add-to-cart');
        if (button) {
            const productId = button.dataset.productId;
            addToCart(productId);

            // Animazione del pulsante
            button.classList.add('added');
            setTimeout(() => {
                button.classList.remove('added');
            }, 1000);
        }
    });

    // Event listener per i pulsanti "Rimuovi dal carrello"
    cartItems.addEventListener('click', function(e) {
        const button = e.target.closest('.remove-item');
        if (button) {
            const productId = button.dataset.productId;
            removeFromCart(productId);
        }
    });

    // Event listeners per i filtri
    searchInput.addEventListener('input', filterProducts);
    searchButton.addEventListener('click', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);

    // Inizializzazione
    loadCart();
    loadProducts();
});
