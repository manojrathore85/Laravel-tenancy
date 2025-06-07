<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Issue Notification' }}</title>
    <style>
        <?php echo file_get_contents(resource_path('css/mail.css')) ?>
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