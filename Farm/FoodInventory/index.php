<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Feed Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    h3 {
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        padding: 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #007bff;
        color: white;
        font-size: 14px;
    }

    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .feed-item {
        display: flex;
        align-items: center;
        margin: 15px 0;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #ffffff;
        transition: box-shadow 0.3s ease;
    }

    .feed-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .feed-item img {
        width: 80px;
        height: 80px;
        margin-right: 20px;
        border-radius: 8px;
        border: 2px solid #ddd;
    }

    .btn-custom {
        display: inline-block;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
        border-radius: 5px;
        margin: 5px;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
        border: none;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-animal {
        font-size: 18px;
        padding: 12px 25px;
        border-radius: 8px;
        background-color: #007bff;
        color: white;
        border: none;
        margin: 10px;
        transition: transform 0.2s, background-color 0.2s;
    }

    .btn-animal:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    #animalSelection {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 30px;
    }

    #feedList {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    #backButton {
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        font-size: 14px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #backButton:hover {
        background-color: #5a6268;
    }
</style>
    <script>
        function fetchBrands(animal) {
            document.getElementById('animalSelection').style.display = 'none';
            document.getElementById('feedList').style.display = 'block';
            document.getElementById('backButton').style.display = 'block';

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_brands.php?animal=' + animal, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('feedList').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function fetchFeedVarieties(brand) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_feed_varieties.php?brand=' + brand, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('feedList').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function updateStock(feedId, action) {
    var quantity = prompt("Enter quantity to " + action + ":");
    if (quantity) {
        // Validate if the input is a valid integer
        if (isNaN(quantity) || parseInt(quantity) <= 0) {
            alert("Invalid input! Please enter a valid positive number.");
            return; // Exit the function if input is invalid
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', action + '_feeds.php', true);  // Action determines the PHP script (restock_feeds.php or sell_feeds.php)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                alert(response); // Display the response message from the server
                if (!response.includes("Error")) {
                    updateStockDisplay(feedId, quantity, action); // Update stock display only on success
                }
            }
        };

        // Send request to the correct PHP script based on action (restock or sell)
        xhr.send('feed_id=' + feedId + '&quantity=' + quantity + '&action=' + action); // Pass action type (restock/sell)
    } else {
        alert("Please enter a quantity!"); // Alert if no input is given
    }
}

function updateStockDisplay(feedId, quantity, action) {
    var stockElement = document.getElementById('stock-' + feedId);
    var currentStock = parseInt(stockElement.textContent);

    // Only update stock if the action was successful
    if (action === 'restock') {
        stockElement.textContent = currentStock + parseInt(quantity);
    } else if (action === 'sell' && currentStock >= quantity) {
        stockElement.textContent = currentStock - parseInt(quantity);
    } else {
        alert("Insufficient stock for this transaction.");
    }
}

        function goBack() {
            document.getElementById('animalSelection').style.display = 'block';
            document.getElementById('feedList').style.display = 'none';
            document.getElementById('backButton').style.display = 'none';
        }
    </script>
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4 text-center"><i class="fas fa-paw"></i> Animal Feed Inventory System</h1>

    <!-- Animal Selection Section -->
    <div id="animalSelection">
        <button class="btn btn-primary btn-animal" onclick="fetchBrands('Pig')">
            <i class="fas fa-piggy-bank"></i> Pig
        </button>
        <button class="btn btn-success btn-animal" onclick="fetchBrands('Chicken')">
            <i class="fas fa-egg"></i> Chicken
        </button>
        <button class="btn btn-warning btn-animal" onclick="fetchBrands('Cow')">
            <i class="fas fa-cow"></i> Cow
        </button>
        <button class="btn btn-info btn-animal" onclick="fetchBrands('Goat')">
            <i class="fas fa-goat"></i> Goat
        </button>
    </div>

    <!-- Feed List Section -->
    <div id="feedList" class="mt-4">
        <!-- Feed list will dynamically update here -->
    </div>

    <!-- Back Button -->
    <button id="backButton" class="btn btn-secondary mt-4" onclick="goBack()" style="display: none;">Back</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>