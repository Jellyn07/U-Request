<?php
// filepath: app/controllers/ActivityLogsController.php

require_once __DIR__ . '/../models/ActivityLogsModel.php';

class ActivityLogsController {
    private $model;

    public function __construct() {
        $this->model = new ActivityLogsModel();
    }

    public function renderLogs($tableFilter = 'all', $actionFilter = 'all', $dateFilter = 'all') {
        $logs = $this->model->getLogs($tableFilter, $actionFilter, $dateFilter, 100);

        if (empty($logs)) {
            return "<tr><td colspan='5' class='text-center text-gray-500 py-4'>No logs found.</td></tr>";
        }

        $html = "";
        foreach ($logs as $row) {
            $actionClass = "text-gray-700"; // you can adjust colors by action type
            $logData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

            $html .= '
            <tr 
                data-log="' . $logData . '"
                @click="selected = JSON.parse($el.dataset.log); showDetails = true"
                class="hover:bg-gray-100 cursor-pointer text-left border-b border-gray-100 transition-colors duration-150 ease-in-out"
            >
                <td class="pl-8 py-3">' . date('M d, Y', strtotime($row['timestamp'])) . '</td>
                <td class="px-4 py-3">' . htmlspecialchars($row['source']) . '</td>
                <td class="px-4 py-3 ' . $actionClass . '">' . htmlspecialchars($row['action_type']) . '</td>
                <td class="px-4 py-3">' . htmlspecialchars($row['affected_item']) . '</td>
                <td class="px-4 py-3">' . htmlspecialchars($row['details']) . '</td>
            </tr>';
        }
        return $html;
    }
    
}
