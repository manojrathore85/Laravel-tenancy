@props([
    'deleteRouteName' => null,  // Default to null to prevent undefined error
    'editRouteName' => null,    // Default to null
    'id' => null                // Default to null
])
<form method="POST" action="{{ route($deleteRouteName, $id) }}" class="inline-block">
    @csrf @method('DELETE')
    @if($deleteRouteName) 
        <x-button type="submit" btnType="danger" class="w-15" >Delete</x-danger-button> 
    @endif
    @if($editRouteName)
        <x-button-link href="{{ route($editRouteName, $id) }}" type="info" class="mr-2 w-55">Edit</x-button-link>
    @endif
</form>
