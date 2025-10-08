<?php
/**
 * Global reusable helper functions
 */

if (!function_exists('formatName')) {
    function formatName($name) {
        // Trim whitespace and capitalize each word
        return ucwords(strtolower(trim($name)));
    }
}
