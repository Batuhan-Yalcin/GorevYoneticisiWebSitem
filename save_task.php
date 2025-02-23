<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

require_once 'config.php';

try {
    // LOGLAMA İŞLEMİNİ YAPTIĞIM KISIM UNUTMA BATU :D
    $rawData = file_get_contents('php://input');
    error_log("Gelen veri: " . $rawData);
    
    $data = json_decode($rawData, true);
    
    if ($data === null) {
        throw new Exception('JSON verisi çözümlenemedi');
    }


    $task_text = $data['task_text'];
    $category = $data['category'];
    $priority = $data['priority'];
    $notes = $data['notes'];
    $tags = $data['tags'];
    $status = $data['status'];
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO tasks (task_text, category, priority, notes, tags, status, created_at) 
            VALUES (:task_text, :category, :priority, :notes, :tags, :status, :created_at)";
    
    $stmt = $db->prepare($sql);
    
  
    $stmt->bindParam(':task_text', $task_text);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':notes', $notes);
    $stmt->bindParam(':tags', $tags);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':created_at', $created_at);

  
    $stmt->execute();

    
    echo json_encode([
        'success' => true,
        'message' => 'Görev başarıyla kaydedildi',
        'task_id' => $db->lastInsertId()
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