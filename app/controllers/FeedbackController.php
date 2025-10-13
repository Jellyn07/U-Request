<?php
require_once __DIR__ . '/../models/FeedbackModel.php';
$tracking_id = $_GET['tracking_id'] ?? $_POST['tracking_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = new FeedbackModel();

    // Get tracking_id from the form
    $tracking_id = $_POST['tracking_id'] ?? null;

    if (!$tracking_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Tracking ID is missing.'
        ]);
        exit;
    }

    // Ratings and suggestions
    $ratings_A = $_POST['ratings_A'] ?? '{}';
    $ratings_B = $_POST['ratings_B'] ?? '{}';
    $ratings_C = $_POST['ratings_C'] ?? '{}';
    $overall_rating = $_POST['overall_rating'] ?? 0;

    $suggest_process = $_POST['suggest_process'] ?? '';
    $suggest_frontline = $_POST['suggest_frontline'] ?? '';
    $suggest_facility = $_POST['suggest_facility'] ?? '';
    $suggest_overall = $_POST['suggest_overall'] ?? '';

    // Save to database
    $saved = $model->saveFeedback(
        $tracking_id,
        $ratings_A, $ratings_B, $ratings_C, $overall_rating,
        $suggest_process, $suggest_frontline, $suggest_facility, $suggest_overall
    );

    if ($saved) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Thank you! Your feedback has been submitted successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to save feedback. Please try again.'
        ]);
    }
}
?>
