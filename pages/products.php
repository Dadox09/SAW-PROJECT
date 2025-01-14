<?php
include '../templates/header.php';
?>

<link rel="stylesheet" href="../assets/css/pages/products.css">

<div class="products-container">
    <div class="search-section">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Cerca prodotti...">
            <button id="search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="filters">
            <select id="category-filter">
                <option value="">Tutte le categorie</option>
                <option value="caffè">Caffè</option>
                <option value="té">Té</option>
                <option value="dolci">Dolci</option>
                <option value="snack">Snack</option>
            </select>
            <select id="price-filter">
                <option value="">Prezzo</option>
                <option value="asc">Prezzo crescente</option>
                <option value="desc">Prezzo decrescente</option>
            </select>
        </div>
    </div>

    <div class="products-grid">
    </div>
</div>

<div id="product-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
        </div>
    </div>
</div>

<script src="../assets/js/products/products.js"></script>

<?php include '../templates/footer.php'; ?>