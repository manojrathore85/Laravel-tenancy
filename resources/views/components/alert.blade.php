@php
    // Detect the message type from session keys
    $alertTypes = ['success', 'error', 'info', 'alert'];
    $type = collect($alertTypes)->first(fn($t) => session($t), 'info');
    $message = session($type);

    // Define colors for different alert types
    $colors = [
        'info' => 'bg-blue-100 border-blue-400 text-blue-700',
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'alert' => 'bg-orange-100 border-orange-400 text-orange-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
    ];

    // Define positions for toast notification
    $positions = [
        'top-center' => 'top-5 left-1/2 transform -translate-x-1/2',
        'top-right' => 'top-5 right-5',
        'top-left' => 'top-5 left-5',
        'bottom-center' => 'bottom-5 left-1/2 transform -translate-x-1/2',
        'bottom-right' => 'bottom-5 right-5',
        'bottom-left' => 'bottom-5 left-5',
    ];

    // Default position
    $place = session('alert-place', 'top-center');
@endphp

@if ($message)
<style>
        @keyframes fadeOut {
            0% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; visibility: hidden; }
        }
        .fade-out {
            animation: fadeOut 10s ease-in-out forwards;
        }
    </style>
    <div class="fixed {{ $positions[$place] }} {{ $colors[$type] }} px-4 py-3 rounded-lg shadow-lg flex items-center w-auto max-w-sm fade-out" role="alert">
        <strong class="font-bold">
            @if($type == 'success') Success! 
            @elseif($type == 'error') Error! 
            @elseif($type == 'alert') Alert! 
            @else Info! 
            @endif
        </strong>
        <span class="block sm:inline ml-2">
            {{ $message }}
        </span>
        <button class="ml-auto text-gray-600 hover:text-gray-800" onclick="this.parentElement.remove();">
            <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 00-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
            </svg>
        </button>
    </div>
@endif
