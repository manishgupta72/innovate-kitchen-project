<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$userType = $_SESSION['user']['user_type'] ?? null;

require_once 'assets/database.php';
require_once 'assets/config.php';

// Include the routes
$routes = require 'assets/routes.php';

$request = $_SERVER['REQUEST_URI'];

// Parse the URL
$url_components = parse_url($request);
$path = $url_components['path'] ?? '';

// Remove the leading part of the URL from the path
$path = str_replace("/new-mvc", "", $path);
if (empty($path)) {
    $path = '/';
}

// Debug output
error_log("Request URI: " . $request);
error_log("Parsed Path: " . $path);

// Check if the route exists in the routes array
$routeFound = false;

foreach ($routes as $route => $routeSettings) {
    error_log("Checking route: " . $route);

    if (strpos($route, 'regex:') === 0) {
        // Handle regex routes
        $pattern = substr($route, 6);
        if (preg_match($pattern, $path, $matches)) {
            $routeFound = true;
            error_log("Matched regex route: " . $route);
            if (is_callable($routeSettings['handler'])) {
                $routeSettings['handler']($matches);
            } else {
                error_log("Handler for route " . $route . " is not callable");
            }
            break;
        }
    } elseif ($route === $path) {
        $routeFound = true;
        error_log("Matched route: " . $route);

        // ✅ Handle Closure-based routes directly
        if (is_callable($routeSettings)) {
            $routeSettings(); // Execute closure
            exit;
        }

        // ✅ Continue only if route is an array
        if (!is_array($routeSettings)) {
            error_log("Invalid route definition for: " . $route);
            throw new Exception("Invalid route definition for: " . $route);
        }

        // ✅ Handle protected route
        if (isset($routeSettings['protected']) && $routeSettings['protected']) {
            ensureLoggedIn();
        }

        // ✅ Set the page title
        $page_title = $routeSettings['page_title'] ?? 'Default Page Title';
        $tab_title  = $routeSettings['tab_title'] ?? 'Scorezada';
        $title      = $routeSettings['title'] ?? 'Title';


        // ✅ Handle controller + method (POST or GET logic)
        if (isset($routeSettings['controller'])) {
            require_once $routeSettings['controller'];

            $controllerClass = pathinfo($routeSettings['controller'], PATHINFO_FILENAME);

            // 💡 Only try to instantiate if class exists
            if (isset($routeSettings['method'])) {
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();

                    if (method_exists($controller, $routeSettings['method'])) {
                        $method = $routeSettings['method'];
                        $controller->$method();
                        exit;
                    } else {
                        error_log("Method not found in controller:");
                    }
                } else {
                    error_log("Class $controllerClass not found — skipping class instantiation.");
                }
            }
        }




        // ✅ Load view if specified
        if (isset($routeSettings['view'])) {
            if (file_exists($routeSettings['view'])) {
                require $routeSettings['view'];
            } else {
                error_log("View file not found: " . $routeSettings['view']);
                throw new Exception("View file not found: " . $routeSettings['view']);
            }
        } else {
            error_log("No view specified for route: " . $route);
        }

        break;
    }
}

// 404 fallback
if (!$routeFound) {
    error_log("No route found for path: " . $path);
    http_response_code(404);
    $notFoundPath = __DIR__ . '/404.php';
    if (file_exists($notFoundPath)) {
        require $notFoundPath;
    } else {
        echo "404 - Page Not Found";
    }
}
