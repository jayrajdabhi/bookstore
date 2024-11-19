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

// Fetch the book details if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $book_id = mysqli_real_escape_string($db, $_GET['id']);

    // Fetch the book details from the database
    $query = "SELECT * FROM books WHERE id = '$book_id'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Book not found!";
        header("Location: index.php");
        exit();
    }
} else {
    // If no book id is provided, redirect to the book listing page
    $_SESSION['error_message'] = "No book ID provided!";
    header("Location: index.php");
    exit();
}

// Handle form submission for updating the book
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $author = mysqli_real_escape_string($db, $_POST['author']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $genre_id = mysqli_real_escape_string($db, $_POST['genre_id']);
    $publication_year = mysqli_real_escape_string($db, $_POST['publication_year']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

    // Handle file upload if a new image is provided
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "../img/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        // If no new image is uploaded, keep the old image
        $image = $book['image'];
    }

    // Update the book details in the database
    $query = "UPDATE books 
              SET name = '$name', author = '$author', price = '$price', image = '$image', genre_id = '$genre_id', publication_year = '$publication_year', description = '$description' 
              WHERE id = '$book_id'";

    if ($db->query($query)) {
        $_SESSION['success_message'] = "Book updated successfully!";
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
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h2>Edit Book</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Book Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($book['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="genre_id">Genre</label>
                <select class="form-control" id="genre_id" name="genre_id" required>
                    <?php while ($genre = $genres_result->fetch_assoc()): ?>
                        <option value="<?php echo $genre['id']; ?>" <?php echo $book['genre_id'] == $genre['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genre['genre_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="publication_year">Publication Year</label>
                <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?php echo htmlspecialchars($book['publication_year']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($book['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Book Image</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
