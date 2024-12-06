<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login_page/login.php");
    exit();
}

// Initialize the role variable from the session
$role = $_SESSION['role'];

// Include necessary files
include '../db.php';
include '../FeedInventory/FeedManager.php'; // Assuming you have a FeedManager class for handling feed operations
include '../Sidebar/sidebar.php';

// Create instances
$feedManager = new FeedManager($conn);
$sidebar = new Sidebar($role);

$errorMessage = '';

// Handle form submission for adding feed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_feed'])) {
        $feedName = $_POST['feed_name'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;

        // Add feed to the database
        if ($feedManager->addFeed($feedName, $quantity)) {
            // Redirect after successful submission
            header("Location: feed_inventory.php");
            exit();
        } else {
            $errorMessage = "Error adding feed.";
        }
    }
}

// Fetch feed data with search feature
$searchTerm = $_GET['search'] ?? '';
$feedData = $feedManager->getAllFeeds($searchTerm); // Assuming you have a method to fetch all feeds

$sidebar->render();

// Define fixed feed types with images
$feedTypes = [
    'Pig' => [
        'types' => [
            [
                'name' => 'Pre-Starter',
                'image' => '../FeedInventory/Images/pre-starter.png' // Path to the image for pre-starter pig feed
            ],
            [
                'name' => 'Starter',
                'image' => '../FeedInventory/Images/pig-starter.jpg' // Path to the image for starter pig feed
            ],
            [
                'name' => 'Grower',
                'image' => '../FeedInventory/Images/grower.png' // Path to the image for grower pig feed
            ],
            [
                'name' => 'Breeder',
                'image' => '../FeedInventory/Images/breeder.png' // Path to the image for breeder pig feed
            ]
        ]
    ],
    'Chicken' => [
        'types' => [
            [
                'name' => 'Integra 1000',
                'image' => '../FeedInventory/Images/integra_1000.jpg' // Path to the image for Integra 1000 chicken feed
            ],
            [
                'name' => 'Integra 2000',
                'image' => '../FeedInventory/Images/integra_2000.jpg' // Path to the image for Integra 2000 chicken feed
            ],
            [
                'name' => 'Integra 2500',
                'image' => '../FeedInventory/Images/integra_2500.jpg' // Path to the image for Integra 2500 chicken feed
            ],
            [
                'name' => 'Integra 3000',
                'image' => '../FeedInventory/Images/integra_3000.jpg' // Path to the image for Integra 3000 chicken feed
            ]
        ]
    ],
    'Cow' => [
        'types' => [
            [
                'name' => 'Cattle Grower',
                'image' => '../FeedInventory/Images/cattle_grower.jpg' // Path to the image for cattle grower feed
            ],
            [
                'name' => 'Dairy Cattle',
                'image' => '../FeedInventory/Images/dairy_cattle.jpg' // Path to the image for dairy cattle feed
            ],
            [
                'name' => 'Hay',
                'image' => '../FeedInventory/Images/hay.jpg' // Path to the image for hay feed
            ]
        ]
    ],
    'Goat' => [
        'types' => [
            [
                'name' => 'Goat 16',
                'image' => '../FeedInventory/Images/goat_16.jpg' // Path to the image for Goat 16 feed
            ],
            [
                'name' => 'Hay',
                'image' => '../FeedInventory/Images/hay.jpg' // Path to the image for goat hay feed
            ]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed Inventory</title>
    <link rel="stylesheet" href="../Design/feed_inventory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="main-content">
    <div class="container">
        <h2>Feed Inventory</h2>
        
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" class="search-input" placeholder="Search feeds" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-button"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <table>
            <tr>
                <th>Feed Name</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($feedData)): ?>
                <?php foreach ($feedData as $feed): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feed['feed_name']); ?></td>
                        <td><?php echo htmlspecialchars($feed['quantity']); ?> kg</td>
                        <td>
                            <a href="update_feedinventory.php?id=<?php echo $feed['id']; ?>" title="Update">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete_id=<?php echo $feed['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this feed?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No feeds found.</td>
                </tr>
            <?php endif; ?>
        </table>

        <h3>Types of Feeds</h3>
        <table>
            <tr>
                <th>Animal Type</th>
                <th>Feed Type</th>
                <th>Image</th>
            </tr>
            <?php foreach ($feedTypes as $animal => $data): ?>
                <?php foreach ($data['types'] as $feedType): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal); ?></td>
                        <td><?php echo htmlspecialchars($feedType['name']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($feedType['image']); ?>" alt="<?php echo htmlspecialchars($feedType['name']); ?> Feed" style="width: 100px; height: auto;"></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>

        <div class="add-feed-form">
            <h3>Add New Feed</h3>
            <?php if ($errorMessage): ?>
                <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="text" name="feed_name" placeholder="Feed Name" required>
                <input type="number" name="quantity" placeholder="Quantity (kg)" required>
                <button type="submit" name="add_feed">Add Feed</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>