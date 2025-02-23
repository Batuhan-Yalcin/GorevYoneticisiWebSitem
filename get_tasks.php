<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

require_once 'config.php';

try {
    $category = $_GET['category'] ?? '';
    
    if (empty($category)) {
        throw new Exception('Kategori belirtilmedi');
    }

    // SQL sorgusu
    $sql = "SELECT * FROM tasks WHERE category = :category ORDER BY created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':category', $category);
    $stmt->execute();

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tasks' => $tasks
    ]);

} catch(Exception $e) {
    error_log("Hata: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 