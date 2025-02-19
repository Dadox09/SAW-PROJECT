<?php include '../templates/header.php'; ?>


<link rel="stylesheet" href="../assets/css/pages/products.css">

<div class="products-container">
    <div class="cart-counter" id="cart-counter">
        <i class="fas fa-shopping-cart"></i>
        <span class="counter">0</span>
    </div>

    <div class="cart-overlay" id="cart-popup">
        <div class="cart-content">
        <button class="quitCart">✖</button>
            <h2>Carrello</h2>
            <ul id="cart-items"></ul>
            <span id="total-price"></span>
            <button class="order-btn">Ordina ora</button>
        </div>
    </div>

    <div class="search-section">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Cerca prodotti...">
            <button id="search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <div class="order-history-section">
        <h2>I tuoi ordini</h2>
        <div id="order-history">
        </div>
    </div>

    <div class="products-grid">
    </div>


</div>


<script src="../assets/js/products/products.js"></script>

<?php include '../templates/footer.php'; ?>