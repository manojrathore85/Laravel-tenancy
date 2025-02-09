<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant') }}


            <x-button-link class="float-right" href="{{ route('tenant.create') }}">Create </x-button-link>
        </h2>
    </x-slot>

    <div class="p-2">
        <div class=" mx-auto sm:px-2 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                        <x-table 
                            :headers="['id', 'name', 'email', 'database_name','domain', 'created_at']"
                            :data="$tenants->map(fn($tenant) => [
                                'id' => $tenant->id,
                                'name' => $tenant->name,
                                'email' => $tenant->email,
                                'domain' => $tenant->domains->pluck('domain')->join(', '),
                                'database_name' => $tenant->database_name,
                                'created_at' => $tenant->created_at->format('d M Y h:i A'),
                            ])->toArray()"
                            :actions="fn($tenant) => view('components.table-actions', ['id' => $tenant['id'], 'deleteRouteName' => 'tenant.destroy'])->render()"
                            />
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>