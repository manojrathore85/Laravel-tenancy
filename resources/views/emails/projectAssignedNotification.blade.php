<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Project Assign Notification' }}</title>
      <style>
        <?php echo file_get_contents(resource_path('css/mail.css')) ?>
    </style>
</head>
<body>
    <div class="container">

        <div>
            <span class="title">{{ $project->name ?? 'Project Name' }} - {{ $project->code ?? 'Code' }}</span>
        </div>

        <div class="meta">
            <p>Hi <strong>{{ $user->name }}</strong>,</p>

            <p>
                You have been <strong>added to the project</strong> "<strong>{{ $project->name }}</strong> as a role of <strong>{{ $roleName }}</strong>" 
                (Project Code: <strong>{{ $project->code }}</strong>) on {{ config('app.name') }}.
            </p>

            @if(isset($assignedBy))
                <p>This action was performed by: <strong>{{ $assignedBy->name }}</strong></p>
            @endif

           
        </div>

        <div class="line"></div>

       

        <div class="section-title" style="margin-top: 20px;">Project Details</div>
        <div class="text-content">
            <p><strong>Name:</strong> {{ $project->name ?? 'N/A' }}</p>
            <p><strong>Code:</strong> {{ $project->code ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {!! $project->description ?? '<em>No description available.</em>' !!}</p>
         
            <p><strong>Status:</strong> {{ ucfirst($project->status ?? 'NA') }}</p>
        </div>

        @if (!empty($lead))
        <div class="section-title">Project Lead</div>
        <div class="user-info">
            <img src="{{ $lead->profile_image_url ?? '' }}" class="avatar" alt="Avatar">
            <strong>{{ $lead->name ?? 'N/A' }}</strong>
        </div>
        @endif

        <div class="line"></div>

        <div class="text-content">
            <p>
                You can now access this project and begin contributing based on your role and permissions.
            </p>
            <p>
                If you have any questions, feel free to reach out to your project manager or admin team.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. You are receiving this email because you have been added to a project.
        </div>
    </div>
</body>

<?php  //dd('end'); ?>

</html>