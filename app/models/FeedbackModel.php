<?php
require_once __DIR__ . '/../core/BaseModel.php';

class FeedbackModel extends BaseModel {

    // ✅ Save feedback with tracking_id included
    public function saveFeedback($tracking_id, $ratings_A, $ratings_B, $ratings_C, $overall_rating,
                                 $suggest_process, $suggest_frontline, $suggest_facility, $suggest_overall) {
        $stmt = $this->db->prepare("
            INSERT INTO feedback 
            (tracking_id, ratings_A, ratings_B, ratings_C, overall_rating, 
             suggest_process, suggest_frontline, suggest_facility, suggest_overall)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssdssss",
            $tracking_id, $ratings_A, $ratings_B, $ratings_C, $overall_rating,
            $suggest_process, $suggest_frontline, $suggest_facility, $suggest_overall
        );

        return $stmt->execute();
    }

    // ✅ Check if feedback already exists for a given tracking_id
    public function hasFeedback($tracking_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM feedback WHERE tracking_id = ?");
        $stmt->bind_param("s", $tracking_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
}
