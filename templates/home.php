
<?php
    $products = [
        [
            'name' => 'Prodotto 1',
            'description' => 'Descrizione del prodotto 1',
            'price' => '19.99',
            'image' => 'https://via.placeholder.com/150'
        ],
        [
            'name' => 'Prodotto 2',
            'description' => 'Descrizione del prodotto 2',
            'price' => '29.99',
            'image' => 'https://via.placeholder.com/150'
        ],
        [
            'name' => 'Prodotto 3',
            'description' => 'Descrizione del prodotto 3',
            'price' => '39.99',
            'image' => 'https://via.placeholder.com/150'
        ]
    ];
?>

<div class="products-container">
        <div class="products-content">
            <h1>Benvenuto nel nostro E-commerce</h1>
            <p>Scopri i nostri prodotti e le offerte speciali</p>
            
            <div class="featured-products">
                <h2>Prodotti in evidenza</h2>
                <div class="product-list">
                    <?php
                    foreach ($products as $product) {
                        echo '<div class="product-item">';
                        echo '<img src="' . $product['image'] . '" alt="' . $product['name'] . '">';
                        echo '<h2>' . $product['name'] . '</h2>';
                        echo '<p>' . $product['description'] . '</p>';
                        echo '<p class="price">Prezzo: ' . $product['price'] . '</p>';
                        echo '<button class="add-to-cart-button">Aggiungi al carrello</button>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
</div>