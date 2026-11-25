<?php
// VMAX Software - Front Controller

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

// Class Autoloader
spl_autoload_register(function ($className) {
    $directories = [
        __DIR__ . '/classes/',
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
    ];
    
    foreach ($directories as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Resolve requested path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files directly if using PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $filePath = __DIR__ . $requestUri;
    if (is_file($filePath)) {
        return false;
    }
}

// Load route registry
$routes = require_once __DIR__ . '/config/routes.php';

$matchedRoute = null;
$controllerName = null;
$methodName = null;
$routeParams = [];

// Match exact and regex patterns
foreach ($routes as $routePattern => $controllerAction) {
    // Escape forward slashes, replace variables if pattern has parameters
    $regexPattern = '#^' . str_replace('/', '\/', $routePattern) . '$#';
    
    if (preg_match($regexPattern, $requestUri, $matches)) {
        $matchedRoute = $routePattern;
        list($controllerName, $methodName) = explode('@', $controllerAction);
        array_shift($matches); // Remove full match
        $routeParams = $matches;
        break;
    }
}

if ($matchedRoute === null) {
    try {
        $db = DB::getInstance();
        $stmt = $db->getConnection()->prepare("SELECT * FROM pages WHERE slug = ? AND is_published = 1 LIMIT 1");
        $stmt->execute([$requestUri]);
        $page = $stmt->fetch();
        if ($page) {
            echo $page['content_html'];
            exit;
        }
    } catch (Exception $e) {
        // Silent fail if DB is offline or table doesn't exist yet
    }

    // Send standard 404 response
    http_response_code(404);
    echo "<h1>404 Not Found</h1><p>Sorry, the page you requested ('" . htmlspecialchars($requestUri) . "') does not exist.</p>";
    exit;
}

// Instantiate and call controller method
try {
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $routeParams);
        } else {
            die("Controller Action <strong>$controllerName::$methodName</strong> is not defined.");
        }
    } else {
        die("Controller Class <strong>$controllerName</strong> does not exist.");
    }
} catch (Exception $e) {
    http_response_code(500);
    die("An unexpected error occurred: " . $e->getMessage());
}
