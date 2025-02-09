<x-tenant-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}


            <x-button-link class="float-right" href="{{ route('user.create') }}">Create </x-button-link>
        </h2>
    </x-slot>



    <div class="p-2">
        <div class=" mx-auto sm:px-2 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">

                        <x-table 
                            :headers="['id', 'name', 'email', 'roles', 'created_at']"
                            :data="$users->map(fn($user) => [
                                'id' => $user->id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'roles' => $user->roles->pluck('name')->join(', '),
                                'created_at' => $user->created_at->format('d M Y'),
                            ])->toArray()"
                            :actions="fn($user) => view('components.table-actions', ['id' => $user['id'], 'editRouteName' => 'user.edit', 'deleteRouteName' => 'user.destroy'])->render()"
                            
                            
                            />

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-tenant-app-layout>