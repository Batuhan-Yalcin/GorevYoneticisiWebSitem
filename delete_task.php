<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

require_once 'config.php';

try {
    $rawData = file_get_contents('php://input');
    error_log("Gelen veri: " . $rawData); // Gelen veriyi logla
    
    $data = json_decode($rawData, true);
    
    if ($data === null) {
        throw new Exception('JSON verisi çözümlenemedi');
    }


    $task_text = $data['task_text'];
    $category = $data['category'];

    
    $task_text = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $task_text);
    $task_text = trim($task_text);

    error_log("Temizlenmiş görev metni: " . $task_text); 
    error_log("Kategori: " . $category); 

    
    $sql = "DELETE FROM tasks WHERE task_text LIKE :task_text AND category = :category";
    
    $stmt = $db->prepare($sql);
    
   
    $searchText = '%' . $task_text . '%';
    $stmt->bindParam(':task_text', $searchText, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category);

    error_log("SQL Sorgusu: " . $sql); 
    error_log("Arama metni: " . $searchText); 

    
    $stmt->execute();

    $rowCount = $stmt->rowCount();
    error_log("Etkilenen satır sayısı: " . $rowCount); 

    if ($rowCount > 0) {
        $response = [
            'success' => true,
            'message' => 'Görev başarıyla silindi',
            'affected_rows' => $rowCount
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Görev veritabanında bulunamadı',
            'task_text' => $task_text,
            'category' => $category
        ];
    }

    echo json_encode($response);
    error_log("Gönderilen yanıt: " . json_encode($response)); 

} catch(Exception $e) {
    error_log("Hata oluştu: " . $e->getMessage());
    $response = [
        'success' => false,
        'message' => 'Hata: ' . $e->getMessage()
    ];
    echo json_encode($response);
}
?> 