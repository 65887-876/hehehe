<?php
session_start();
require_once("connection.php"); 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    die; 
}


$home_id = $_GET['home_id'] ?? null;
$user_id = $_SESSION['user_id']; 

$query = "SELECT * FROM homes WHERE id = '$home_id' AND user_id = '$user_id'";
$result = mysqli_query($con, $query);
$home = mysqli_fetch_assoc($result);

if (!$home) {
    header("Location: index.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title']; 
    $description = $_POST['description']; 
    $picture = $home['picture']; 
    

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
        $update_query = "UPDATE homes SET title = '$title', description = '$description', picture = '$picture' WHERE id = '$home_id' AND user_id = '$user_id'";
        if (mysqli_query($con, $update_query)) {
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
    <title>Edit Home or Villa</title>
    <link rel="stylesheet" href="styles/edit_home.css">
</head>
<body>
    <?php include 'header.php'?>
    <h2>Edit Home or Villa</h2>
    
    <form action="edit_home.php?home_id=<?php echo htmlspecialchars($home_id); ?>" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($home['title']); ?>" required><br> 
        
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($home['description']); ?></textarea><br>
        
        <label for="picture">Picture:</label>
        <?php if (!empty($home['picture'])): ?>
            <img src="<?php echo htmlspecialchars($home['picture']); ?>" alt="Current Picture" style="max-width: 100px;">
        <?php endif; ?>
        <input type="file" name="picture"><br>
        
        <button type="submit">Update Home</button> 
        <p><a href="index.php">Back to Home</a></p> 
    </form>
    
    <?php include 'footer.php'?>
</body>
</html>
