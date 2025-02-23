<?php
require_once 'config.php';
require_once 'EmailService.php';

try {
    // Log başlangıcı
    error_log("Hatırlatma kontrolü başladı: " . date('Y-m-d H:i:s'));

    // Zamanı gelmiş ve henüz email gönderilmemiş görevleri al
    $sql = "SELECT * FROM tasks 
            WHERE reminder_time <= NOW() 
            AND reminder_sent = 0 
            AND status != 'completed'";
            
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("Bulunan görev sayısı: " . count($tasks));
    
    $emailService = new EmailService();
    
    foreach ($tasks as $task) {
        error_log("Görev işleniyor: " . $task['task_text'] . " - Email: " . $task['email']);
        
        // Email gönder
        $subject = "Görev Hatırlatması";
        $message = "Yapmayı unuttuğunuz bir göreviniz var: <strong>" . htmlspecialchars($task['task_text']) . "</strong>";
        
        if ($task['notes']) {
            $message .= "<br>Not: " . htmlspecialchars($task['notes']);
        }
        
        $message .= "<br><br>Hatırlatma zamanı: " . date('d.m.Y H:i', strtotime($task['reminder_time']));
        
        if ($emailService->sendEmail($task['email'], $subject, $message)) {
            error_log("Email başarıyla gönderildi: " . $task['email']);
            
            // Görevi güncelle
            $updateSql = "UPDATE tasks SET reminder_sent = 1 WHERE id = :id";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->execute([':id' => $task['id']]);
        } else {
            error_log("Email gönderilemedi: " . $task['email']);
        }
    }
    
    error_log("Hatırlatma kontrolü tamamlandı: " . date('Y-m-d H:i:s'));
    
} catch(Exception $e) {
    error_log("Hatırlatma gönderme hatası: " . $e->getMessage());
}


?> 