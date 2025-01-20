document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const productsGrid = document.querySelector('.products-grid');

    let products = [];
    let filteredProducts = [];

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
                    <button class="add-to-cart" id="${product.id}">Aggiungi al carrello</button>
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

        // Filtra i prodotti
        filteredProducts = products.filter(product => {
            const matchesSearch = product.name.toLowerCase().includes(searchTerm);
            const matchesCategory = selectedCategory === '' || product.category === selectedCategory;

            return matchesSearch && matchesCategory;
        });

        // Ordina i prodotti per prezzo
        if (priceSort === 'asc') {
            filteredProducts.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
        } else if (priceSort === 'desc') {
            filteredProducts.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
        }

        updateProductsGrid();
    }

    // Se qualsiasi cosa nei filtri cambia 
    searchInput.addEventListener('input', filterProducts);
    searchButton.addEventListener('click', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);

    loadProducts();
});