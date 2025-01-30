<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tenant') }}


            <x-btn-link class="float-right" href="{{ route('tenants.create') }}">Create </x-btn-link>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"> 
                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                        <table class="w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                   
                                        <th class="px-4 py-2 text-left text-gray-700 uppercase border">Name</th>
                                        <th class="px-4 py-2 text-left text-gray-700 uppercase border">ID</th>
                                        <th class="px-4 py-2 text-left text-gray-700 uppercase border">Email</th>
                                        <th class="px-4 py-2 text-left text-gray-700 uppercase border">Domain</th>
                                        <th class="px-4 py-2 text-left text-gray-700 uppercase border">CreatedAt</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                    <tr class="border-t hover:bg-gray-100">
                                      
                                            <td class="px-4 py-2 border">{{ $tenant->name }}</td>
                                            <td class="px-4 py-2 border">{{ $tenant->id }}</td>
                                            <td class="px-4 py-2 border">{{ $tenant->email }}</td>
                                            <td class="px-4 py-2 border">
                                             
                                            @foreach ($tenant->domains as $dm)
                                                {{ $dm->domain }} {{ $loop->last ? '' : ', ';}}
                                            @endforeach
                                            </td>
                                            <td class="px-4 py-2 border">{{ $tenant->created_at }}</td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
