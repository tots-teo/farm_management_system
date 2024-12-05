CREATE DATABASE FoodInventory;

USE FoodInventory;

CREATE TABLE Feeds (
    feed_id INT AUTO_INCREMENT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL,        
    brand VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    stock INT NOT NULL DEFAULT 0,  -- Former 'quantity' column replaced with 'stock'
    price DECIMAL(10, 2) NOT NULL    -- Feed price
);

CREATE TABLE Animals (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    feedId INT NOT NULL,
    FOREIGN KEY (feedId) REFERENCES Feeds(feed_id) ON DELETE CASCADE
);

CREATE TABLE FeedUsage (
    usage_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT,
    feed_id INT,
    quantity_used INT,
    usage_date DATE,
    FOREIGN KEY (animal_id) REFERENCES Animals(animal_id),
    FOREIGN KEY (feed_id) REFERENCES Feeds(feed_id)
);

CREATE TABLE stock_transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    feed_id INT,
    quantity INT,
    transaction_type ENUM('restock', 'sell') NOT NULL, -- 'restock' for adding stock, 'sell' for removing stock
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (feed_id) REFERENCES Feeds(feed_id)
);

-- Insert updated data without the 'quantity' column, using the values for 'stock' instead
INSERT INTO Feeds (name, brand, image, stock, price) VALUES
('Pig Feed - Integra 1000', 'Integra', 'integra_1000.jpg', 100, 250.50),
('Pig Feed - Integra 2000', 'Integra', 'integra_2000.jpg', 200, 300.00),
('Pig Feed - Integra 2500', 'Integra', 'integra_2500.jpg', 150, 350.00),
('Pig Feed - Integra 3000', 'Integra', 'integra_3000.jpg', 80, 400.00),
('Chicken Feed - Chick Starter', 'Sarimanok', 'chick_starter.jpg', 120, 150.00),
('Chicken Feed - Broiler Starter', 'Sarimanok', 'broiler_starter.jpg', 130, 170.50),
('Chicken Feed - Chick Booster', 'Sarimanok', 'chick_booster.jpg', 110, 180.00),
('Chicken Feed - Chicken Grower', 'Sarimanok', 'chicken_grower.jpg', 90, 190.00),
('Cow Feed - Dairy Cattle', 'RumSol', 'dairy_cattle.jpg', 50, 450.00),
('Cow Feed - Cattle Grower', 'RumSol', 'cattle_grower.jpg', 70, 400.00),
('Goat Feed - Goat 16', 'Family Farm', 'parlor_16.jpg', 100, 220.00),
('Goat Feed - Parlor 16', 'Noble Goat', 'goat_16.jpg', 80, 240.00);

-- Updated the Animals table to reflect the change
INSERT INTO Animals (name, feedId) VALUES
('Pig', 1),   -- Integra 1000
('Pig', 2),   -- Integra 2000
('Pig', 3),   -- Integra 2500
('Pig', 4),   -- Integra 3000
('Chicken', 5),  -- Chick Starter (Sarimanok)
('Chicken', 6),  -- Broiler Starter (Sarimanok)
('Chicken', 7),  -- Chick Booster (Sarimanok)
('Chicken', 8),  -- Chicken Grower (Sarimanok)
('Cow', 9),      -- Dairy Cattle (RumSol)
('Cow', 10),     -- Cattle Grower (RumSol)
('Goat', 11),     -- Goat 16 (Family Farm)
('Goat', 12);     -- Parlor 16 (Noble Goat)