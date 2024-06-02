<?php
session_start();
require_once("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $picture = null;

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['picture'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            $unique_filename = uniqid() . '.' . $file_extension;
            $file_path = 'uploads/' . $unique_filename;
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $picture = $file_path;
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF image.";
        }
    }

    if (!empty($title) && !empty($description)) {
        $query = "INSERT INTO homes (user_id, title, description, picture) VALUES ('$user_id', '$title', '$description', '$picture')";
        if (mysqli_query($con, $query)) {
            header("Location: index.php");
            die;
        } else {
            echo "Database error: " . mysqli_error($con);
        }
    } else {
        echo "Please enter a valid title and description.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Home or Villa</title>
    <link rel="stylesheet" href="styles/addhome.css">
</head>
<body>
<?php include 'header.php' ?>

    <h2>Add a New Home or Villa</h2>

    <form action="add_home.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>
        
        <label for="picture">Picture:</label>
        <input type="file" name="picture"><br>

        <div class="line">
            <button type="submit">Add Home</button>
            <p><a href="index.php">Back to Home</a></p>
        </div>
    </form>
    
    <?php include 'footer.php' ?>
</body>
</html>
