<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Issue Notification' }}</title>
    <style>
    body {
        background: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        margin: 0;
    }

    .container {
        background: white;
        border-radius: 8px;
        /* max-width: 600px; */
        margin: auto;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        color: #333;
    }

    .title {
        font-size: 22px;
        font-weight: bold;
        color: #1f2937;
    }

    .issue-id {
        float: right;
        background: #f1f5f9;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: bold;
        color: #374151;
    }

    .meta {
        font-size: 14px;
        color: #555;
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .meta span {
        display: inline-block;
        margin-right: 12px;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        margin-right: 8px;
    }

    .badge-open {
        background: #22c55e;
    }

    .badge-task {
        background: #3b82f6;
    }

    .badge-minor {
        background: #f97316;
    }

    .subscribe-btn {
        display: inline-block;
        padding: 6px 16px;
        background: #facc15;
        color: #000;
        border-radius: 16px;
        font-weight: bold;
        font-size: 13px;
        text-decoration: none;
        margin-top: 10px;
    }

    .line {
        border-top: 1px solid #e5e7eb;
        margin: 24px 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
        color: #555;
        padding-bottom: 4px;
        /* border-bottom: 2px solid #eee; */
    }

    .text-content {
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        font-size: 12px;
        color: #9ca3af;
    }

    .log-container,
    .log-entry {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .log-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 8px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-info img.avtar,
    .avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 1px solid #ccc;
    }

    .user-info h4,
    .user-info h5,
    .user-info span {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #444;
    }

    .date-info {
        font-size: 14px;
        color: #777;
    }

    .log-container ul,
    .log-grid ul {
        padding-left: 20px;
        margin: 8px 0;
    }

    .log-container li,
    .log-grid li {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin-bottom: 6px;
    }

    .log-container strong {
        color: #333;
    }

    .comment {
        background: #f9f9f9;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    hr {
        border: none;
        border-top: 1px dashed #ccc;
        margin: 16px 0 0 0;
    }

    h4,
    h5,
    h6 {
        font-weight: 600;
        margin: 8px 0;
        color: #333;
    }

    em {
        color: #6b7280;
    }

    .section-divider {
        border-top: 1px solid #ccc;
        margin: 20px 0;
    }

    .meta-info {
        list-style: none;
        display: flex;
        gap: 12px;
        padding: 0;
        margin: 0;
        font-size: 0.9rem;
    }

    .log-body {
        margin-top: 10px;
    }

    .activity-log-title {
        margin-top: 15px;
        font-weight: bold;
    }

    .activity-log {
        margin-top: 10px;
        padding: 10px;
        border: 1px dashed #aaa;
        border-radius: 8px;
    }

    .log-grid {
        display: flex;
        flex-direction: column;
        gap: 30px;
        margin-top: 10px;
    }

    .attachments {
        margin-top: 10px;
    }
    @media (max-width: 600px) {
    .body{
        padding: 1px;
    }
    .container{
        padding: 3px;
    }    
    .log-header {        
        align-items: flex-start;
    }
    .user-info {
       font-size: xx-small;
       align-items: flex-start;
    }

    .user-info h4,
    .user-info h5,
    .user-info span,
    .date-info {
        font-size: 10px;
        line-height: 1.4;
    }

    .comment,
    .log-container {
        font-size: 14px;
    }

    .avatar,
    .user-info img.avtar {
        margin-right: 0;
        margin-bottom: 8px;
    }
}

</style>
</head>

<body>
    <div class="container">

        <div>
            <span class="title">{{ $issue->project->name ?? 'Project Name' }} -{{$issue->project->code ?? 'Code'}}</span>
            <span class="issue-id">#{{ $issue->id }}</span>
        </div>
        <!-- Issue Detail -->
        <div class="meta">
            <span>Created By: {{ $issue->createdBy->name }}</span>
            <span>At: {{ $issue->created_at}}</span><br>
            <span>Reporter: {{ $issue->createdBy->name ?? 'N/A' }}</span><br>
            <span>Assign To: {{ $issue->assignedTo->name ?? 'Unassigned' }}</span><br>

            <a href="{{$url.'/issues/'.$issue->id }}" class="subscribe-btn">View Issue</a>
        </div>

        <div class="line"></div>

        <div>
            <span class="badge badge-open">{{ucfirst($issue->status ?? 'Open')}}</span>
            <span class="badge badge-task">{{ ucfirst($issue->issue_type ?? 'Task') }}</span>
            <span class="badge badge-minor">{{ ucfirst($issue->severity ?? 'Minor') }}</span>

        </div>
        <h6>{{$title}}</h6>
        <div class="section-title" style="margin-top: 20px;">
            Summary : {{ $issue->summery ?? 'Issue Summary' }}
        </div>
        <div class="text-content">
            Descriptions: {!! $issue->description ?? '<em>No description provided.</em>' !!}
        </div>

        <!-- Issue Update -->
        @if(isset($changes) && !empty($changes))
        <div class="line"></div>          
            <div class="log-container">
                <div class="log-header">
                    <div class="user-info">
                        <img class="avtar" src="{{ $issue->updatedBy->profile_image_url ?? '' }}" />
                        <span>{{ $issue->updatedBy->name ?? 'N/A' }}</span>
                    </div>
                    <div class="date-info">
                        <span>At: {{ $issue->updated_at ?? 'N/A' }}</span>
                    </div>
                </div>
                <h4>Changes Made:</h4>
                <ul>

                    @foreach($changes as $field => $change)
                    <li>
                        @if($field == 'description' || $field == 'summery')

                        <strong>{{ ucfirst($field) }}</strong>
                        <div class="text-content">From : {!! $change['old'] !!}</div>
                        <div class="text-content">To: {!! $change['new'] !!}</div>
                        @else
                        <h6>{{ ucfirst($field) }}</h6>
                        <em>{{ $change['old'] }}</em> <strong>to</strong> <em>{{ $change['new'] }}</em>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>            
        @endif


        <!-- current comment -->
        @isset($comment)
        <div class="section-title">Current Comment</div>
        <div class="comment">
            <div class="log-header">
                <div class="user-info">
                    <img src="{{ $comment->commentBy->profile_image_url }}" alt="Avatar" class="avatar">
                    <strong>{{ $comment->commentBy->name }}</strong>
                </div>
                <ul class="meta-info">
                    <li><strong>Created:</strong> {{ $comment->created_at }}</li>              
                </ul>
            </div>
            <div class="text-content">
                {!! $comment->description ?? '<em>No description provided.</em>' !!}
            </div>
        </div>
        <div class="line"></div>
        @endisset



        <!-- comment Update -->
        @isset($comment_changes)
        <div class="line"></div>
        @if(!empty($comment_changes))
        <div class="log-container">
            <div class="log-header">
                <div class="user-info">
                    <img class="avtar" src="{{ $issue->updatedBy->profile_image_url ?? '' }}" />
                    <h5>{{ ucfirst($comment->updatedBy->name ?? 'N/A') }}</h5>
                </div>
                <div class="date-info">
                    <span>At: {{ $comment->updated_at ?? 'N/A' }}</span>
                </div>
            </div>
            <h4>Changes Made:</h4>
            <ul>

                @foreach($comment_changes as $field => $change)
                <li>

                    <strong>{{ ucfirst($field) }}</strong>
                    <div class="text-content">From : {!! $change['old'] !!}</div>
                    <div class="text-content">To: {!! $change['new'] !!}</div>

                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @endisset

        <!-- History -->
        @if (!empty($issue->history))      
        <div class="section-title">Issue History</div>
        <?php //dd($issue->history);?>  
        @foreach ($issue->history as $entry)
        @php
        $type = $entry['type'] ?? 'log';
        $user = $type === 'comment' ? $entry['commentBy'] : $entry['user'];
        $name = ucfirst($user['name'] ?? 'N/A');
        $avatar = $user['profile_image_url'] ?? '';
        $createdAt = $entry['created_at'] ?? '';
        $updatedAt = $entry['updated_at'] ?? '';
        @endphp

        <div class="log-entry">
            <div class="log-header">
                <div class="user-info">
                    <img src="{{ $avatar }}" alt="Avatar" class="avatar">
                    <strong>{{ $name }}</strong>
                </div>
                <ul class="meta-info">
                    <li><strong>Created:</strong> {{ $createdAt }}</li>
                    @if ($createdAt !== $updatedAt)
                    <li><strong>Updated:</strong> {{ $updatedAt }}</li>
                    @endif
                    <li><strong>Type:</strong> {{ $type }}</li>
                </ul>
            </div>

            @if ($type === 'comment')

            <div class="log-body">
                {!! $entry['description'] ?? '<em>No description</em>' !!}
            </div>

            @if (!empty($entry->ActivityLog))
            
            <div class="activity-log-title">Coment Log</div>
            @foreach ($entry->ActivityLog as $activity)
            <div class="activity-log">
                <div class="log-header">
                    <div class="user-info">
                        <img src="{{ $activity['causer']['profile_image_url'] ?? '' }}" alt="Avatar" class="avatar">
                        <strong>{{ ucfirst($activity['causer']['name'] ?? 'Unknown') }}</strong>
                    </div>
                    <ul class="meta-info">
                        <li><strong>At:</strong> {{ $activity['created_at'] ?? 'N/A' }}</li>
                        <li><strong>Type:</strong> Log</li>
                    </ul>
                </div>

                <div class="log-body">
                    {!! $activity['description'] ?? '' !!}
                </div>

                <div class="log-grid">
                    <div>
                        <strong>New Value</strong>
                        <ul>
                            @foreach ($activity['properties']['attributes'] ?? [] as $k => $v)
                            <li><strong>{{ ucfirst($k) }}:</strong> {!! nl2br(e($v)) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @if (!empty($activity['properties']['old']))
                    <div>
                        <strong>Old Value</strong>
                        <ul>
                            @foreach ($activity['properties']['old'] ?? [] as $k => $v)
                            <li><strong>{{ ucfirst($k) }}:</strong> {!! nl2br(e($v)) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif

            @if (!empty($entry['attachment']))
            <div class="attachments">
                <strong>Attachment:</strong><br>
                <a href="{{ $entry['attachment'] }}" target="_blank">{{ $entry['attachment'] }}</a>
            </div>
            @endif

            @elseif ($type === 'log')
            <div class="log-body">
                {!! $entry['description'] ?? '' !!}
            </div>
            <div class="log-grid">
                <div>
                    <strong>New Value</strong>
                    <ul>
                        @foreach ($entry['properties']['attributes'] ?? [] as $k => $v)
                        <li><strong>{{ ucfirst($k) }}:</strong> {!! nl2br(e($v)) !!}</li>
                        @endforeach
                    </ul>
                </div>
                @if (!empty($entry['properties']['old']))
                <div>
                    <strong>Old Value</strong>
                    <ul>
                        @foreach ($entry['properties']['old'] ?? [] as $k => $v)
                        <li><strong>{{ ucfirst($k) }}:</strong> {!! nl2br(e($v)) !!}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endforeach
        @endif
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. You are receiving this email because you are {{ $recipientType ?? 'involved' }} in this issue.
        </div>
    </div>
</body>
<?php // dd('end'); ?>

</html>