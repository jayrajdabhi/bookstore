<?php
// Start session
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: ./login.php");
    exit();
}

// Include database configuration
include '../config/database.php'; // Ensure the correct path to your database configuration file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $author = mysqli_real_escape_string($db, $_POST['author']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $genre_id = mysqli_real_escape_string($db, $_POST['genre_id']);
    $publication_year = mysqli_real_escape_string($db, $_POST['publication_year']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "../img/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert into database
    $query = "INSERT INTO books (name, author, price, image, genre_id, publication_year, description) 
              VALUES ('$name', '$author', '$price', '$image', '$genre_id', '$publication_year', '$description')";

    if ($db->query($query)) {
        $_SESSION['success_message'] = "Book added successfully!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $db->error;
    }
}

// Fetch genres for the dropdown
$genres_query = "SELECT * FROM genres";
$genres_result = $db->query($genres_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Add New Book</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Book Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="genre_id">Genre</label>
                <select class="form-control" id="genre_id" name="genre_id" required>
                    <option value="" disabled selected>Select Genre</option>
                    <?php while ($genre = $genres_result->fetch_assoc()): ?>
                        <option value="<?php echo $genre['id']; ?>"><?php echo htmlspecialchars($genre['genre_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="publication_year">Publication Year</label>
                <input type="number" class="form-control" id="publication_year" name="publication_year" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Book Image</label>
                <input type="file" class="form-control-file" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>