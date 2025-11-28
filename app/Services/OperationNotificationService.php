<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OperationNotificationService
{
    public function notifyUser(?int $userId, string $title, string $message, array $data = [], ?string $relatedType = null, ?int $relatedId = null): void
    {
        if (!$userId) {
            return;
        }

        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'operation',
            'status' => 'unread',
            'data' => $data,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
        ]);
    }

    public function notifyStaffAssignment(array $assignment): void
    {
        $userId = $assignment['user_id'] ?? null;
        $title = 'Phân công lịch tour mới';
        $message = sprintf(
            'Bạn được phân công vai trò %s cho lịch trình %s.',
            $assignment['role'],
            $assignment['operation_code'] ?? ''
        );

        $this->notifyUser(
            $userId,
            $title,
            $message,
            $assignment,
            'tour_operation',
            $assignment['tour_operation_id'] ?? null
        );
    }

    public function notifyServiceProvider(string $providerName = null, string $contactEmail = null, array $payload = []): void
    {
        Log::info('Operation service notification', [
            'provider' => $providerName,
            'email' => $contactEmail,
            'payload' => $payload,
        ]);
    }
}

