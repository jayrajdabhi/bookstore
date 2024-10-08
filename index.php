<?php include 'components/header.php'; ?>
<?php

$books = [
    [
        "name" => "The Great Gatsby",
        "price" => "$10.99",
        "description" => "A classic novel by F. Scott Fitzgerald, set in the Jazz Age.",
        "image" => "img/great_gatsby.jpg"
    ],
    [
        "name" => "1984",
        "price" => "$8.99",
        "description" => "A dystopian social science fiction novel and cautionary tale by George Orwell.",
        "image" => "img/1984.jpg"
    ],
    [
        "name" => "To Kill a Mockingbird",
        "price" => "$12.50",
        "description" => "A novel by Harper Lee, focused on racial injustice in the Deep South.",
        "image" => "img/to_kill_a_mockingbird.jpg"
    ],
    [
        "name" => "Moby Dick",
        "price" => "$15.00",
        "description" => "A novel by Herman Melville about the voyage of the whaling ship Pequod.",
        "image" => "img/moby_dick.jpg"
    ]
];
?>
<main class="container my-5">
    <div class="book-container">
        <h2 id="book-h2">Available Books</h2>

        <!-- Book List Grid -->
        <div class="book-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-item">
                    <!-- Display book image -->
                    <img src="<?php echo $book['image']; ?>" alt="<?php echo $book['name']; ?>" class="book-image">

                    <!-- Display book details -->
                    <div class="book-name"><?php echo $book['name']; ?></div>
                    <div class="book-price"><?php echo $book['price']; ?></div>
                    <div class="book-description"><?php echo $book['description']; ?></div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>