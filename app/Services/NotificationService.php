<?php
namespace App\Services;
use App\Models\Notification;

class NotificationService {
    public function send(int $userId, string $title, string $message): void {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
        ]);
    }
}
