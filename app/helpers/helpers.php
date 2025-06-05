<?php



use Illuminate\Support\Facades\Notification;

if (!function_exists('tenant_url')) {
    function tenant_url($type = 'backend'): string
    {
        $tenant = tenant();
        $protocol = config('app.tenant_protocol');
        if($type === 'frontend'){
            $base = $tenant->domains()->first()->frontend_url;
        }else{
            $base = $tenant->domains()->first()->domain;
        }
        return "{$protocol}://{$base}";
    }
}
if (!function_exists('sendNotificationEmails')) {
    function sendNotificationEmails(array|string $emails,  $notification ): void
    {
        // Split comma-separated emails and remove whitespace
        if (is_string($emails)) {
            $emails = array_map('trim', explode(',', $emails));
        }

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Notification::route('mail', $email)
                    ->notify($notification);
            }
        }
    }
}

if (!function_exists('notify_once')) {
    function notify_once(&$notifiedUserIds, $user, $notification, $skipIfAuthUser = false)
    {
        if (!$user) return;

        if ($skipIfAuthUser && $user->id === auth()->id()) return;

        if (!in_array($user->id, $notifiedUserIds)) {
            if ($notification instanceof \Illuminate\Notifications\Notification) {
                $user->notify($notification);
            } else {
                // Assuming it's a mailable or custom email handler
                sendNotificationEmails($user->email, $notification);
            }
            $notifiedUserIds[] = $user->id;
        }
    }
}

