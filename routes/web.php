<?php


// Setup service container (DI)

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TaskController.php';
require_once __DIR__ . '/../repositories/TaskRepository.php';
require_once __DIR__ . '/../services/TaskService.php';
$db = new Database();
$conn = $db->connect();

$repo = new TaskRepository($conn);
$service = new TaskService($repo);
$controller = new TaskController($service);

// Get the full URI path
 $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // e.g. /todoapp/public/create

// Get the base path (e.g., /todoapp/public)
$basePath = dirname($_SERVER['SCRIPT_NAME']); // e.g. /todoapp/public

// Remove base path to get clean URI
$cleanUri = '/' . ltrim(str_replace($basePath, '', $requestUri), '/'); // e.g. /create or /edit/1

// Normalize route
$uri = trim($cleanUri, '/'); // e.g. create or edit/1

// Routing logic
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // /todoapp/create
$segments = explode('/', trim($path, '/')); // ['todoapp', 'create']

// Example:
$projectRoot = $segments[0];  // 'todoapp'
$uri = $segments[1] ?? 'index';
 if(isset($segments[2])){
    $uri=$uri."/".$segments[2];
 }

// 'create'
switch ($uri) {
    case '':
    case 'index':
        $controller->index();
        break;

    case 'create':
        $controller->create();
        break;

    case 'store':
        $controller->store($_POST);
        break;

    default:
        if (preg_match('/^edit\/(\d+)$/', $uri, $matches)) {
            $controller->edit($matches[1]);
        } elseif (preg_match('/^update\/(\d+)$/', $uri, $matches)) {
            $controller->update($matches[1], $_POST);
        } elseif (preg_match('/^delete\/(\d+)$/', $uri, $matches)) {
            $controller->delete($matches[1]);
        } else {
            http_response_code(404);
            echo "404 - Route Not Found";
        }
        break;
}