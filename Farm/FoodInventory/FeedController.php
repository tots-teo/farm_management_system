<?php
class FeedController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getFeeds() {
        $query = "SELECT name, brand, stock FROM feeds";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $feeds = [];
        while ($row = $result->fetch_assoc()) {
            $feeds[] = $row;
        }
        return $feeds;
    }

    public function restockFeed($feed_id, $quantity) {
        $query = "UPDATE feeds SET stock = stock + ? WHERE feed_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $quantity, $feed_id);
        return $stmt->execute();
    }

    public function sellFeed($feed_id, $quantity) {
        $query = "SELECT stock FROM feeds WHERE feed_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $feed_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['stock'] >= $quantity) {
                $queryUpdate = "UPDATE feeds SET stock = stock - ? WHERE feed_id = ?";
                $stmtUpdate = $this->db->prepare($queryUpdate);
                $stmtUpdate->bind_param('ii', $quantity, $feed_id);
                return $stmtUpdate->execute();
            } else {
                return "Not enough stock to sell.";
            }
        } else {
            return "Feed not found.";
        }
    }
}
?>