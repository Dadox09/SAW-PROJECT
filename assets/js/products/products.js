document.addEventListener('DOMContentLoaded', function() {
    // Elementi DOM
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const productsGrid = document.querySelector('.products-grid');
    const modal = document.getElementById('product-modal');
    const modalContent = modal.querySelector('.modal-body');
    const closeModal = modal.querySelector('.close-modal');

    // Carrello
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartBadge = document.createElement('div');
    cartBadge.className = 'cart-badge';
    document.querySelector('.navbar-item[href*="cart"]')?.appendChild(cartBadge);
    updateCartBadge();

    let products = [
        {
            id: 1,
            name: 'Espresso Classico',
            description: 'Il nostro blend signature per un espresso perfetto.',
            category: 'caffè',
            price: 2.50,
            image: '../assets/images/espressoclassico.webp'
        },
        {
            id: 2,
            name: 'Cappuccino',
            description: 'Espresso con schiuma di latte vellutata.',
            category: 'caffè',
            price: 3.00,
            image: '../assets/images/cappuccino.webp'
        },
        {
            id: 3,
            name: 'Latte Macchiato',
            description: 'Espresso con latte vellutata e una dolcezza unica.',
            category: 'caffè',
            price: 3.50,
            image: '../assets/images/macchiato.webp'
        },
        {
            id: 4,
            name: 'Té verde',
            description: 'Té verde con una colazione unica.',
            category: 'té',
            price: 3.50,
            image: '../assets/images/teverde.webp'
        },
        {
            id: 5,
            name: 'Caffe Latte',
            description: 'Espresso con latte vellutato.',
            category: 'caffè',
            price: 3.50,
            image: '../assets/images/caffelatte.webp'
        }
    ];

    let filteredProducts = [...products];

    function createProductCard(product) {
        return `
            <div class="product-card" data-id="${product.id}">
                <div class="product-image">
                    <img src="${product.image}" alt="${product.name}">
                    <div class="product-overlay">
                        <button class="view-details">
                            <i class="fas fa-eye"></i>
                            Dettagli
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>${product.name}</h3>
                    <p class="product-description">${product.description}</p>
                    <div class="product-meta">
                        <span class="product-category">
                            <i class="fas fa-tag"></i>
                            ${product.category}
                        </span>
                        <span class="product-price">€${product.price.toFixed(2)}</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Funzione per aggiornare la griglia dei prodotti
    function updateProductsGrid() {
        productsGrid.innerHTML = filteredProducts.map(createProductCard).join('');
        
        // Aggiungi event listener per i pulsanti dettagli
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', (e) => {
                const card = e.target.closest('.product-card');
                const productId = parseInt(card.dataset.id);
                showProductDetails(productId);
            });
        });
    }

    // Funzione per mostrare i dettagli del prodotto
    function showProductDetails(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        modalContent.innerHTML = `
            <div class="product-details">
                <img src="${product.image}" alt="${product.name}">
                <div class="details-content">
                    <h2>${product.name}</h2>
                    <p class="description">${product.description}</p>
                    <div class="category">
                        <i class="fas fa-tag"></i>
                        ${product.category}
                    </div>
                    <div class="price">€${product.price.toFixed(2)}</div>
                    <button class="add-to-cart" data-id="${product.id}">
                        <i class="fas fa-shopping-cart"></i>
                        Aggiungi al carrello
                    </button>
                </div>
            </div>
        `;

        // Aggiungi event listener per il pulsante "Aggiungi al carrello"
        const addToCartBtn = modalContent.querySelector('.add-to-cart');
        addToCartBtn.addEventListener('click', () => addToCart(product));

        modal.style.display = 'block';
    }

    // Funzione per aggiungere al carrello
    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.image,
                quantity: 1
            });
        }

        // Salva il carrello nel localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Aggiorna il badge del carrello
        updateCartBadge();

        // Animazione pulsante
        const addToCartBtn = modalContent.querySelector('.add-to-cart');
        addToCartBtn.classList.add('added', 'animating');
        addToCartBtn.innerHTML = '<i class="fas fa-check"></i> Aggiunto';

        // Rimuovi le classi dopo l'animazione
        setTimeout(() => {
            addToCartBtn.classList.remove('animating');
        }, 500);

        // Mostra il messaggio di successo
        showSuccessMessage(product.name);
    }

    // Funzione per aggiornare il badge del carrello
    function updateCartBadge() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        if (cartBadge) {
            cartBadge.textContent = totalItems;
            cartBadge.classList.toggle('show', totalItems > 0);
        }
    }

    // Funzione per mostrare il messaggio di successo
    function showSuccessMessage(productName) {
        const message = document.createElement('div');
        message.className = 'success-message';
        message.innerHTML = `
            <i class="fas fa-check-circle"></i>
            ${productName} aggiunto al carrello!
        `;
        
        document.body.appendChild(message);
        
        // Mostra il messaggio
        setTimeout(() => message.classList.add('show'), 100);
        
        // Rimuovi il messaggio dopo 3 secondi
        setTimeout(() => {
            message.classList.remove('show');
            setTimeout(() => message.remove(), 300);
        }, 3000);
    }

    // Event Listeners
    searchInput.addEventListener('input', filterProducts);
    searchButton.addEventListener('click', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Funzione per filtrare i prodotti
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        const priceSort = priceFilter.value;

        filteredProducts = products.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm) ||
                                product.description.toLowerCase().includes(searchTerm);
            const matchesCategory = !category || product.category === category;

            return matchesSearch && matchesCategory;
        });

        // Ordinamento per prezzo
        if (priceSort === 'asc') {
            filteredProducts.sort((a, b) => a.price - b.price);
        } else if (priceSort === 'desc') {
            filteredProducts.sort((a, b) => b.price - a.price);
        }

        updateProductsGrid();
    }

    updateProductsGrid();
});