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
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM VW_feedback WHERE tracking_id = ?");
        $stmt->bind_param("s", $tracking_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }

    ///////////////////////////////////////////
    // ✅ Compute average feedback including overall_rating
    ///////////////////////////////////////////
    public function getFeedbackAverages() {
        $sql = "SELECT ratings_A, ratings_B, ratings_C, overall_rating FROM VW_feedback";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $sumA = []; $countA = [];
        $sumB = []; $countB = [];
        $sumC = []; $countC = [];
        $sumOverall = 0;
        $countOverall = 0;

        while ($row = $result->fetch_assoc()) {
            $ratingsA = json_decode($row['ratings_A'], true);
            $ratingsB = json_decode($row['ratings_B'], true);
            $ratingsC = json_decode($row['ratings_C'], true);
            $overall = floatval($row['overall_rating'] ?? 0);

            // Section A
            if (is_array($ratingsA)) {
                foreach ($ratingsA as $index => $value) {
                    $sumA[$index] = ($sumA[$index] ?? 0) + $value;
                    $countA[$index] = ($countA[$index] ?? 0) + 1;
                }
            }

            // Section B
            if (is_array($ratingsB)) {
                foreach ($ratingsB as $index => $value) {
                    $sumB[$index] = ($sumB[$index] ?? 0) + $value;
                    $countB[$index] = ($countB[$index] ?? 0) + 1;
                }
            }

            // Section C
            if (is_array($ratingsC)) {
                foreach ($ratingsC as $index => $value) {
                    $sumC[$index] = ($sumC[$index] ?? 0) + $value;
                    $countC[$index] = ($countC[$index] ?? 0) + 1;
                }
            }

            // ✅ Overall Rating
            if ($overall > 0) {
                $sumOverall += $overall;
                $countOverall++;
            }
        }

        $stmt->close();

        // Compute averages
        $avgA = [];
        $avgB = [];
        $avgC = [];

        foreach ($sumA as $i => $total) {
            $avgA[$i] = round($total / $countA[$i], 2);
        }
        foreach ($sumB as $i => $total) {
            $avgB[$i] = round($total / $countB[$i], 2);
        }
        foreach ($sumC as $i => $total) {
            $avgC[$i] = round($total / $countC[$i], 2);
        }

        // ✅ Compute final overall average
        $avgOverall = $countOverall > 0 ? round($sumOverall / $countOverall, 2) : 0;

        return [
            'avgA' => $avgA,
            'avgB' => $avgB,
            'avgC' => $avgC,
            'avgOverall' => $avgOverall
        ];
    }

    ///////////////////////////////////////////
    // ✅ Fixed to use MySQLi instead of PDO
    ///////////////////////////////////////////
    public function getAllFeedback() {
        $stmt = $this->db->prepare("SELECT ratings_A, ratings_B, ratings_C FROM VW_feedback");
        $stmt->execute();
        $result = $stmt->get_result();
        $feedback = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $feedback;
    }
}
