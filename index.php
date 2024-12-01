<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animal Feed Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .feed-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .feed-item img {
            width: 100px;
            height: 100px;
            margin-right: 15px;
            border-radius: 5px;
        }
        .btn-custom {
            margin-right: 10px;
        }
        .btn-sell {
            background-color: red;
            color: white;
        }
        .btn-sell:hover {
            background-color: darkred;
        }
        #animalSelection {
            display: block;
            text-align: center;
        }
        #feedList, #backButton {
            display: none;
        }
        .btn-animal {
            font-size: 20px;
            padding: 15px 30px;
            margin: 10px;
        }
        .btn-animal i {
            margin-right: 10px;
        }
        .feed-item .btn {
            margin-left: 10px;
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
            // Prompt the user for input
            var quantity = prompt("Enter quantity to " + action + ":");
            if (quantity) {
                // Validate if the input is a valid integer
                if (isNaN(quantity) || parseInt(quantity) <= 0) {
                    alert("Invalid input! Please enter a valid positive number.");
                    return; // Exit the function if input is invalid
                }

                var xhr = new XMLHttpRequest();
                xhr.open('POST', action + '_feed.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert(xhr.responseText); // Success message from the server
                        updateStockDisplay(feedId, quantity, action); // Update stock display
                    }
                };
                xhr.send('feed_id=' + feedId + '&quantity=' + quantity);
            } else {
                alert("Please enter a quantity!"); // Alert if no input is given
            }
        }

        function updateStockDisplay(feedId, quantity, action) {
            var stockElement = document.getElementById('stock-' + feedId);
            var currentStock = parseInt(stockElement.textContent);
            stockElement.textContent = action === 'restock' ? currentStock + parseInt(quantity) : currentStock - parseInt(quantity);
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
