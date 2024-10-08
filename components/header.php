<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Design</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header class="bg-light py-3 shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <img id="" src="img/logo1.png" alt="Bookstore Logo" class="img-fluid" style="max-width: 250px;">
            </div>
            <div class="search-box d-flex">
                <input type="text" class="form-control mr-2" placeholder="Search...">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Product</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cart</a></li>
                </ul>
                <!-- Add ml-auto to move the button group to the right -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <button class="btn btn-outline-light"><a href="login.php">Login</a></button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>