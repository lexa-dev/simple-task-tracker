<?php

declare(strict_types=1);

include '../app/vendor/autoload.php';

use App\Storage\FileTaskStorage;
use App\Services\TaskManager;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

$storage = new FileTaskStorage();
$taskManager = new TaskManager($storage);

$method = $_SERVER['REQUEST_METHOD'];

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!isset($_SERVER['REQUEST_URI']) || strpos($_SERVER['REQUEST_URI'], '/api/tasks') !== 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $task = $taskManager->get($id);
                echo json_encode($task->toArray()); // ✅
            } else {
                $tasks = $taskManager->getAll();
                $tasksArray = array_map(fn($task) => $task->toArray(), $tasks);
                echo json_encode($tasksArray); // ✅
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['title'])) {
                throw new InvalidArgumentException('Title is required');
            }

            $task = $taskManager->create(
                $data['title'],
                $data['description'] ?? ''
            );

            http_response_code(201);
            echo json_encode($task->toArray());
            break;

        case 'PUT':
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $pathParts = explode('/', trim($path, '/'));

            $id = null;
            if (isset($pathParts[2]) && is_numeric($pathParts[2])) {
                $id = (int)$pathParts[2];
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data)) {
                throw new InvalidArgumentException('No data to update');
            }

            $task = $taskManager->update($id, $data);
            echo json_encode($task->toArray());
            break;

        case 'DELETE':
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $pathParts = explode('/', trim($path, '/'));

            $id = null;
            if (isset($pathParts[2]) && is_numeric($pathParts[2])) {
                $id = (int)$pathParts[2];
            }

            $taskManager->delete($id);
            http_response_code(204);
            echo '';
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (\InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
}