<!-- 
// Starting at: app/modules/user/config
// Go up 3 levels to reach: app/
define('APP_PATH', dirname(__DIR__));

// Components folder inside app/
define('COMPONENTS_PATH', APP_PATH . '/components');

// Adjust depending on your document root setup
define('PUBLIC_URL', '/public');

define('VIEWS_PATH', __DIR__ . '/../app/modules/user/views'); -->
<?php
// Filesystem base (for require_once)
define('APP_PATH', dirname(__DIR__));

// Public base URL (for browser)
define('BASE_URL', '/U--Request');

// Public assets (css/js/img)
define('PUBLIC_URL', '/public');

// Components folder inside app/
define('COMPONENTS_PATH', APP_PATH . '/components');

// Views
define('VIEWS_PATH', APP_PATH . '/modules/user/views');

