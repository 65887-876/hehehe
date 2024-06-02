<?php

require_once("connection.php");

$user_id = $_SESSION['user_id'] ?? null;

$query = "SELECT homes.*, users.username 
          FROM homes 
          JOIN users ON homes.user_id = users.user_id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Homes for Sale</title>
    <link rel="stylesheet" type="text/css" href="styles/home.css"> 
</head>
<body>
    <div class="home-section">
        <h2 class="home-title">Homes for Sale</h2>

        <div class="home-grid">
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($home = mysqli_fetch_assoc($result)) {
                    $is_favorited = false; // Default to false
                    if ($user_id) {
                        // Check if the current user has favorited this home
                        $favorite_check = mysqli_query($con, "SELECT COUNT(*) AS count FROM favorites WHERE user_id = '$user_id' AND home_id = '{$home['id']}'");
                        $is_favorited = (mysqli_fetch_assoc($favorite_check)['count'] > 0);
                    }

                    echo "<div class='home-card'>"; // Card structure
                    if (!empty($home['picture'])) { // If there's a picture
                        echo "<div class='home-card-image-container'>";
                        echo "<img src='" . htmlspecialchars($home['picture']) . "' alt='" . htmlspecialchars($home['title']) . "' class='home-card-image'>";
                        echo "</div>";
                    }

                    echo "<div class='home-card-content'>"; // Content area for title and description
                    echo "<h3>" . htmlspecialchars($home['title']) . "</h3>"; // Title
                    echo "<p>" . htmlspecialchars($home['description']) . "</p>"; // Description
                    echo "<small>Posted by @" . htmlspecialchars($home['username']) . "</small>"; // Posted by user
                    echo "</div>";

               
                    echo "<div class='home-card-buttons'>"; 

                    // Favorite button with a redirect on submit
                    echo "<form action='favorite_handler.php' method='POST'>"; // Change the action to `favorite_handler.php`
                    echo "<input type='hidden' name='home_id' value='" . htmlspecialchars($home['id']) . "'>"; // Hidden field for home ID
                    echo "<button type='submit' class='favorite-btn" . ($is_favorited ? " favorited" : "") . "'>&hearts;</button>"; // Heart button
                    echo "</form>";

                    if ($user_id && $home['user_id'] === $user_id) {
                        echo "<button class='edit-btn' onclick=\"window.location.href='edit_home.php?home_id=" . htmlspecialchars($home['id']) . "'\">✎ Edit</button>"; 
                    } else {
                        echo "<a href='contact_seller.php?user_id=" . htmlspecialchars($home['user_id']) . "' class='contact-btn'>Contact Seller</a>"; 
                    }

                    echo "</div>";

                    echo "</div>";
                }
            } else {
                echo "<p>No homes currently available.</p>";
            }
            ?>
        </div> 
    </div>
</body>
</html>
