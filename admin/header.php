<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brantford Book store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body> 
    <header class="bg-light py-3 shadow">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <!-- Responsive Logo -->
            <div class="logo">
                <a href="../index.php">
                    <img id="" src="../img/logo1.png" alt="Bookstore Logo" class="img-fluid" style="max-width: 250px;">
                </a>
            </div>

            <div class="search-box d-flex">
                <input type="text" class="form-control mr-2" placeholder="Search...">
                <button class="btn btn-primary ">Search</button>
            </div>
            <div class="search-box d-flex">
                <a class="nav-link mt-2" href="#"><img src="../img/cartb.png" alt="cart" style="max-width:35px;"></a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a class="nav-link mt-2" href="../profile.php" data-toggle="tooltip" data-placement="top"
                        title="Profile"><img src="../img/profile.png" alt="profile" id="profile-img"
                            style="max-width:50px;"></a>
                <?php endif ?>
            </div>
        </div>
    </header>

    <!-- Navbar section -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="../admin/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_book.php">Add Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="track_order.php">Manage Orders</a></li>
                </ul>
                <ul class="navbar-nav ml-auto">

                    <!-- Show Username and Logout button if user is logged in, otherwise show Login button -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text mr-2 text-white mt-2">
                                Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a href="../logout.php" class="btn btn-outline-light mt-2">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-outline-light mt-2">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>