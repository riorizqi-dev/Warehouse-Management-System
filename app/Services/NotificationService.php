<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Schema;

class NotificationService
{
    public function notifyUser(int $userId, string $title, string $message, string $type = 'info', ?string $relatedType = null, $relatedId = null): void
    {
        if ($userId <= 0) {
            return;
        }
        if (! Schema::hasTable('notifications')) {
            return;
        }

        UserNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
        ]);
    }

    public function notifyRoles(array $roles, string $title, string $message, string $type = 'info', ?string $relatedType = null, $relatedId = null): void
    {
        $aliases = $this->legacyAliases($roles);
        $allRoles = array_values(array_unique(array_merge($roles, $aliases)));
        if (! Schema::hasTable('users')) {
            return;
        }

        User::query()
            ->whereIn('role', $allRoles)
            ->pluck('id')
            ->each(function ($userId) use ($title, $message, $type, $relatedType, $relatedId) {
                $this->notifyUser((int) $userId, $title, $message, $type, $relatedType, $relatedId);
            });
    }

    private function legacyAliases(array $roles): array
    {
        $map = [
            User::ROLE_STAFF_GUDANG => ['warehouse_admin', 'user'],
            User::ROLE_STAFF_PENJUALAN => ['sales_admin'],
        ];

        $aliases = [];
        foreach ($roles as $role) {
            if (isset($map[$role])) {
                $aliases = array_merge($aliases, $map[$role]);
            }
        }

        return array_values(array_unique($aliases));
    }
}
