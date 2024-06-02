<?php
$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/header.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h3>Skikda Immobilier</h3>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="services.php">Les Services</a></li>
                    <li><a href="about.php">A Propos</a></li>
                    <li><a href="my_favorites.php">favorites</a></li>
                    
                </ul>
            </nav>
            <div>
                <?php if ($loggedIn): ?>
                    <button><a href="logout.php">Logout</a></button>
                <?php else: ?>
                    <button><a href="login.php">Log-in</a></button>
                    <button><a href="signup.php">Sign-Up</a></button>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>
</html>
