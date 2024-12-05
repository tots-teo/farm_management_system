<?php
class Feed {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getFeedsByBrand($brand) {
        $query = "SELECT feed_id, name, image, stock FROM feeds WHERE brand = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $brand);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateStock($feedId, $quantity, $action) {
        // Check the current stock
        $query = "SELECT stock FROM feeds WHERE feed_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $feedId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $current = $result->fetch_assoc();
            $currentStock = $current['stock'];
    
            // Calculate the new stock level based on action
            if ($action === 'restock') {
                $newStock = $currentStock + $quantity;
                $updateQuery = "UPDATE feeds SET stock = ? WHERE feed_id = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bind_param('ii', $newStock, $feedId);
                $updateStmt->execute();
    
                return "Restocked successfully. New stock: $newStock";
            } else if ($action === 'sell') {
                // Prevent selling more than the current stock
                if ($quantity > $currentStock) {
                    return "Error: Not enough stock to sell.";
                }
                $newStock = $currentStock - $quantity;
                $updateQuery = "UPDATE feeds SET stock = ? WHERE feed_id = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bind_param('ii', $newStock, $feedId);
                $updateStmt->execute();
    
                return "Sold successfully. Remaining stock: $newStock";
            } else {
                return "Error: Invalid action.";
            }
        }
    
        return "Feed not found.";
    }
}
?>

