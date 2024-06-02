<?php
session_start();
require_once("connection.php");

// Check if the user is authenticated
$user_id = $_SESSION['user_id'] ?? null;

$query = "SELECT homes.*, users.username, 
          (SELECT COUNT(*) FROM favorites WHERE user_id = '$user_id' AND home_id = homes.id) AS is_favorited 
          FROM homes 
          JOIN users ON homes.user_id = users.user_id";

$result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Explore Houses</title>
    <link rel="stylesheet" type="text/css" href="styles/home.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="home-section">
        <h2 class="home-title">Explore Houses</h2>
        <div class="home-grid">

            <div class="home-card add-house-card"> 
                <a href="add_home.php">
                    <div class="plus-sign">+</div>
                    <p>Add a House</p>
                </a>
            </div> <!-- End of the "Add a House" card -->

            <?php
            if ($result && mysqli_num_rows($result) > 0) { // If there are homes to display
                while ($home = mysqli_fetch_assoc($result)) { // Loop through each home
                    $is_favorited = $home['is_favorited'] > 0; // Check if favorited

                    echo "<div class='home-card'>";
                    if (!empty($home['picture'])) {
                        echo "<div class='home-card-image-container'>";
                        echo "<img src='" . htmlspecialchars($home['picture']) . "' alt='" . htmlspecialchars($home['title']) . "' class='home-card-image'>"; // Display the image
                        echo "</div>";
                    }

                    echo "<div class='home-card-content'>"; 
                    echo "<h3>" . htmlspecialchars($home['title']) . "</h3>"; // Title
                    echo "<p>" . htmlspecialchars($home['description']) . "</p>"; // Description
                    echo "<p>Posted by @" . htmlspecialchars($home['username']) . "</p>"; // Posted by user
                    echo "</div>";

                    // Button section
                    echo "<div class='home-card-buttons'>"; 
                    echo "<button class='buy-btn'>Buy</button>"; // Buy button
                    
                    // Link to contact the seller
                    echo "<a href='contact_seller.php?user_id=" . htmlspecialchars($home['user_id']) . "' class='contact-btn'>Contact Seller</a>"; 
                    
                    echo "</div>"; 

                    echo "</div>"; // End of card
                }
            } else {
                echo "<p>No homes currently available.</p>"; 
            }
            ?>
        </div> 
    </div>
    <?php include 'footer.php'; ?> 
</body>
</html>
