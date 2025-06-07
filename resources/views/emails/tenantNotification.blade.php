<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Tenant App Created' }}</title>
    <style>
        <?php echo file_get_contents(resource_path('css/mail.css')); ?>
    </style>
</head>

<body>
    <div class="container">

        <div>
            <span class="title">Welcome to {{ config('app.name') }}</span>
        </div>

        <div class="meta">
            <p>Hi <strong>{{ $tenant->name }}</strong>,</p>

            <p>
                Your new application instance <strong>{{ $tenant->domain }}</strong> has been successfully created.
                You can now log in and start managing your projects and users within your dedicated workspace.
            </p>

            <p>
                Here are your login details:
            </p>
            <ul>
                <li><strong>Login URL:</strong> <a href="{{ $url }}">{{ $url }}</a></li>
                <li><strong>Email:</strong> {{ $tenant->email }}</li>
                <li><strong>Password:</strong> (use the password you registered or the one provided)</li>
            </ul>

        </div>

        <div class="line"></div>

        <div class="section-title" style="margin-top: 20px;">You registered the following Tenant Details</div>
        <div class="text-content">
            <p><strong>Name:</strong> {{ $tenant->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $tenant->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $tenant->phone ?? 'N/A' }}</p>
        </div>

        <div class="line"></div>

        <div class="text-content">
            <p>
                Please log in to get started. If you have any questions or need support, feel free to reach out to us.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. You are receiving this email because a tenant app was created for you.
        </div>
    </div>
</body>

</html>
