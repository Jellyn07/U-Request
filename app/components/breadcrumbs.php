<?php
// Get the current request path (without query string)
$path = strtok($_SERVER['REQUEST_URI'], '?');

// Break it into segments
$segments = explode('/', trim($path, '/'));

// Base link
$link = '/';

// Start Breadcrumb container
echo '<nav class="text-sm text-gray-600" aria-label="Breadcrumb"><ol class="inline-flex items-center space-x-1">';

// Loop through segments
foreach ($segments as $i => $segment) {
    // Skip empty segments or "U-Request" folder
    if ($segment === '' || strtolower($segment) === 'u-request') {
        $link .= $segment . '/';
        continue;
    }

    // Build link path
    $link .= $segment . '/';

    // Clean segment name for display
    $name = ucfirst(str_replace(['.php', '-', '_'], ['', ' ', ' '], $segment));

    if ($i < count($segments) - 1) {
        // Clickable breadcrumb
        echo '<li>
                <a href="'.$link.'" class="text-gray-500 hover:text-blue-600">'.$name.'</a>
              </li>
              <li class="text-gray-400">›</li>';
    } else {
        // Last breadcrumb (current page)
        echo '<li class="text-gray-400">'.$name.'</li>';
    }
}

// End container
echo '</ol></nav>';
?>
