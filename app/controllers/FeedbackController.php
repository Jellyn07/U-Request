<?php
error_reporting(0);
header('Content-Type: application/json');

require_once __DIR__ . '/../models/FeedbackModel.php';
require_once __DIR__ . '/../config/constants.php';

$model = new FeedbackModel();
$method = $_SERVER['REQUEST_METHOD'];

// ======================================================
// 📩 POST — Save new feedback
// ======================================================
if ($method === 'POST') {
    $tracking_id = $_POST['tracking_id'] ?? null;

    if (!$tracking_id) {
        echo json_encode(['status' => 'error', 'message' => 'Tracking ID is missing.']);
        exit;
    }

    $ratings_A = $_POST['ratings_A'] ?? '{}';
    $ratings_B = $_POST['ratings_B'] ?? '{}';
    $ratings_C = $_POST['ratings_C'] ?? '{}';
    $overall_rating = $_POST['overall_rating'] ?? 0;

    $suggest_process = $_POST['suggest_process'] ?? '';
    $suggest_frontline = $_POST['suggest_frontline'] ?? '';
    $suggest_facility = $_POST['suggest_facility'] ?? '';
    $suggest_overall = $_POST['suggest_overall'] ?? '';

    try {
        $saved = $model->saveFeedback(
            $tracking_id,
            $ratings_A,
            $ratings_B,
            $ratings_C,
            $overall_rating,
            $suggest_process,
            $suggest_frontline,
            $suggest_facility,
            $suggest_overall
        );

        echo json_encode([
            'status' => $saved ? 'success' : 'error',
            'message' => $saved
                ? 'Thank you! Your feedback has been submitted successfully.'
                : 'Failed to save feedback. Please try again.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// ======================================================
// 🆕 GET — Fetch individual feedback by tracking_id
// ======================================================
if ($method === 'GET' && isset($_GET['tracking_id'])) {
    try {
        $data = $model->getFeedbackByTrackingId($_GET['tracking_id']);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error fetching specific feedback.']);
    }
    exit;
}

// ======================================================
// 📊 GET — Fetch all feedback averages
// ======================================================
try {
    $feedbackList = $model->getAllFeedback();

    if (empty($feedbackList)) {
        echo json_encode(['status' => 'success', 'data' => null, 'message' => 'No feedback records found.']);
        exit;
    }

    $sums = ['A' => [], 'B' => [], 'C' => []];
    $counts = ['A' => [], 'B' => [], 'C' => []];

    foreach ($feedbackList as $feedback) {
        $ratingsA = json_decode($feedback['ratings_A'], true) ?? [];
        $ratingsB = json_decode($feedback['ratings_B'], true) ?? [];
        $ratingsC = json_decode($feedback['ratings_C'], true) ?? [];

        foreach ($ratingsA as $i => $val) {
            $sums['A'][$i] = ($sums['A'][$i] ?? 0) + $val;
            $counts['A'][$i] = ($counts['A'][$i] ?? 0) + 1;
        }
        foreach ($ratingsB as $i => $val) {
            $sums['B'][$i] = ($sums['B'][$i] ?? 0) + $val;
            $counts['B'][$i] = ($counts['B'][$i] ?? 0) + 1;
        }
        foreach ($ratingsC as $i => $val) {
            $sums['C'][$i] = ($sums['C'][$i] ?? 0) + $val;
            $counts['C'][$i] = ($counts['C'][$i] ?? 0) + 1;
        }
    }

    $averages = [
        'avgA' => [],
        'avgB' => [],
        'avgC' => []
    ];

    foreach ($sums as $section => $items) {
        foreach ($items as $i => $sum) {
            $averages["avg$section"][$i] = round($sum / $counts[$section][$i], 2);
        }
    }

    echo json_encode(['status' => 'success', 'data' => $averages]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching feedback data: ' . $e->getMessage()]);
}
