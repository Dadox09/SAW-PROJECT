<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coccole e Croissant B&B </title>
</head>
<body>

    <?php require_once 'templates/header.php'; ?>

    <?php require_once 'templates/hero.php'; ?>

    <?php require_once 'templates/main.php'; ?>


    <?php require_once 'functions/ratings.php';
    
    displayRatingForm();
    displayRatings();
    
    ?>


    <?php require_once 'templates/aboutus.php'; ?>

    <?php require_once 'templates/newsletterform.php'; ?>


    <?php require_once 'templates/footer.php'; ?>
</body>
</html>