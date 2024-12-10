<?php
include 'components/header.php';
include 'config/database.php';

// Initialize search, price range, and genre filter variables
$search = '';
$minPrice = '';
$maxPrice = '';
$genre = '';

// Get filter values from GET parameters
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (isset($_GET['min_price'])) {
    $minPrice = $_GET['min_price'];
}

if (isset($_GET['max_price'])) {
    $maxPrice = $_GET['max_price'];
}

if (isset($_GET['genre'])) {
    $genre = $_GET['genre'];
}


// Build the query dynamically based on filter conditions
$query = "
    SELECT b.id, b.name, b.author, b.price, b.image, g.genre_name 
    FROM books b
    LEFT JOIN genres g ON b.genre_id = g.id
    WHERE (b.name LIKE ? OR b.author LIKE ? OR g.genre_name LIKE ?)
";

// If price filter is set, add conditions for price range
if ($minPrice !== '') {
    $query .= " AND b.price >= ?";
}
if ($maxPrice !== '') {
    $query .= " AND b.price <= ?";
}

// If genre filter is set, add condition for genre
if ($genre !== '') {
    $query .= " AND g.genre_name = ?";
}


// Prepare and bind parameters
$stmt = $db->prepare($query);

// Bind the parameters based on filter values
$searchTerm = '%' . $search . '%';
$paramTypes = 'sss'; // Start with 3 string parameters for search
$params = [$searchTerm, $searchTerm, $searchTerm];

// Add parameters for price filters if provided
if ($minPrice !== '') {
    $paramTypes .= 'd';  // Add double for numeric value
    $params[] = $minPrice;
}
if ($maxPrice !== '') {
    $paramTypes .= 'd';  // Add double for numeric value
    $params[] = $maxPrice;
}

// Add parameter for genre filter if provided
if ($genre !== '') {
    $paramTypes .= 's';  // Add string for genre filter
    $params[] = $genre;
}

// Bind the parameters dynamically
$stmt->bind_param($paramTypes, ...$params);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);

// Fetch available genres for the filter dropdown
$genreQuery = "SELECT genre_name FROM genres";
$genreResult = $db->query($genreQuery);
$genres = $genreResult->fetch_all(MYSQLI_ASSOC);
?>

<main class="my-5">
    <!-- Promotional Carousel -->
    <div id="promoCarousel" class="carousel slide mb-4 container" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 1">
            </div>
            <div class="carousel-item">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 2">
            </div>
            <div class="carousel-item">
                <img src="img/banner.jpg" class="d-block w-100" alt="Promotion 3">
            </div>
        </div>
        <a class="carousel-control-prev" href="#promoCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#promoCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div>
        <div class="row">
            <div class="col-md-2">
                <div class="mt-5 container">
                    <h3 class="text-center">Filters</h3>

                    <!-- Filter Form -->
                    <form action="index.php" method="get">
                        <!-- Price Range Filter -->
                        <div class="form-group">
                            <label for="min_price">Min Price</label>
                            <input type="number" name="min_price" id="min_price" class="form-control" placeholder="0"
                                value="<?php echo htmlspecialchars($minPrice); ?>">
                        </div>
                        <div class="form-group">
                            <label for="max_price">Max Price</label>
                            <input type="number" name="max_price" id="max_price" class="form-control" placeholder="1000"
                                value="<?php echo htmlspecialchars($maxPrice); ?>">
                        </div>

                        <!-- Genre Filter -->
                        <div class="form-group">
                            <label for="genre">Genre</label>
                            <select name="genre" id="genre" class="form-control">
                                <option value="">All Genres</option>
                                <?php foreach ($genres as $g): ?>
                                    <option value="<?php echo htmlspecialchars($g['genre_name']); ?>" <?php echo $genre === $g['genre_name'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($g['genre_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>

            <!-- Book List Grid -->
            <div class="col-md-10">
                <div class="book-container">
                    <h2 id="book-h2">Available Books</h2>

                    <!-- Search Form -->
                    <form action="index.php" method="get" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, author, or genre..."
                                value="<?php echo htmlspecialchars($search); ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>

                    <!-- Book Grid Display -->
                    <div class="book-grid">
                        <?php if (count($books) > 0): ?>
                            <?php foreach ($books as $book): ?>
                                <div class="book-item">
                                    <a href="book_details.php?id=<?php echo $book['id']; ?>">
                                        <img src="img/<?php echo htmlspecialchars($book['image']); ?>"
                                            alt="<?php echo htmlspecialchars($book['name']); ?>" class="book-image">
                                        <div class="book-name"><?php echo htmlspecialchars($book['name']); ?></div>
                                        <div class="book-author">Author: <?php echo htmlspecialchars($book['author']); ?></div>
                                        <div class="book-price">Price: $<?php echo number_format($book['price'], 2); ?></div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No books found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>