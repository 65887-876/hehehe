<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT homes.*, users.username 
          FROM favorites 
          JOIN homes ON favorites.home_id = homes.id 
          JOIN users ON homes.user_id = users.user_id 
          WHERE favorites.user_id = '$user_id'";

$result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Favorites</title>
    <link rel="stylesheet" type="text/css" href="styles/home.css">
    <style>
.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #c3e6cb;
    text-align: center;
    margin: 15px 0;
    font-weight: bold;
}
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="home-section">
        <?php if (isset($_SESSION['favorite_message'])): ?>
            <div class="success-message">
                <?php 
                echo htmlspecialchars($_SESSION['favorite_message']);
                unset($_SESSION['favorite_message']);
                ?>
            </div>
        <?php endif; ?>

        <h2 class="home-title">My Favorites</h2>

        <div class="home-grid">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($home = mysqli_fetch_assoc($result)) {
                    echo "<div class='home-card'>";
                    if (!empty($home['picture'])) {
                        echo "<div class='home-card-image-container'>";
                        echo "<img src='" . htmlspecialchars($home['picture']) . "' alt='" . htmlspecialchars($home['title']) . "' class='home-card-image'>";
                        echo "</div>";
                    }
                    echo "<div class='home-card-content'>";
                    echo "<h3>" . htmlspecialchars($home['title']) . "</h3>";
                    echo "<p>" . htmlspecialchars($home['description']) . "</p>";
                    echo "<p>Posted by @" . htmlspecialchars($home['username']) . "</p>";
                    echo "</div>";

                    echo "<div class='home-card-buttons'>";
                    echo "<a href='contact_seller.php?user_id=" . htmlspecialchars($home['user_id']) . "' class='contact-btn'>Contact Seller</a>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>You don't have any favorite homes yet.</p>";
            }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
