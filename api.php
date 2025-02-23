<?php
header('Content-Type: application/json');
require_once 'config.php';

// CORS ayarları
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$action = $_GET['action'] ?? '';

switch($action) {
    case 'addTask':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $conn->prepare("INSERT INTO tasks (category, task_text, priority, notes, tags) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['category'],
            $data['text'],
            $data['priority'],
            $data['notes'],
            $data['tags']
        ]);
        
        echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
        break;

    case 'getTasks':
        $stmt = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
        break;

    case 'updateTask':
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $conn->prepare("UPDATE tasks SET completed = ?, completed_at = ? WHERE id = ?");
        $completed_at = $data['completed'] ? date('Y-m-d H:i:s') : null;
        $stmt->execute([$data['completed'], $completed_at, $data['id']]);
        
        echo json_encode(['success' => true]);
        break;

    case 'deleteTask':
        $id = $_GET['id'] ?? 0;
        
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
?> 