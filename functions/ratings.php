<?php
require_once dirname(__FILE__) . '/../config/db_connect.php';

function saveRating($userId, $rating, $comment) {
    $conn = connectDB();
    
    $userId = intval($userId);
    $rating = intval($rating);
    $comment = htmlspecialchars($comment);
    
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    $query = "INSERT INTO ratings (user_id, rating, comment, created_at) 
              VALUES (?, ?, ?, NOW())";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $userId, $rating, $comment);
    $result = $stmt->execute();
    mysqli_close($conn);
    return $result;
}


function displayRatings() {
    $conn = connectDB();
    
    $query = "SELECT r.*, CONCAT(u.first_name, ' ', u.last_name) as full_name 
              FROM ratings r 
              JOIN users u ON r.user_id = u.id 
              ORDER BY r.created_at DESC";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        echo '<div class="ratings-list">';
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="rating-item">
                <div class="rating-stars">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $row['rating']) {
                            echo '<span class="star filled">&#9733;</span>';
                        } else {
                            echo '<span class="star">&#9733;</span>';
                        }
                    }
                    ?>
                </div>
                <div class="rating-details">
                    <p class="comment"><?php echo htmlspecialchars($row['comment']); ?></p>
                    <p class="author">- <?php echo htmlspecialchars($row['full_name']); ?></p>
                    <small class="date"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></small>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
    mysqli_close($conn);
}

?>