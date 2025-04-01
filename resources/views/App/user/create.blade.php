<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="p-2">
        <div class=" mx-auto sm:px-2 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900"> 
                    <form method="POST" action="{{ $user ? route('user.update', $user->id) : route('user.store') }}">
                        @csrf
                        @if($user)
                        @method ('PUT')
                        @endif
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', optional($user)->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', optional($user)->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <x-select id="role" name="role" class="block mt-1 w-full" :options="$roles"   :selected="old('role', $user?->roles->first()?->name ?? '')"  />
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        @if (!$user)
                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />

                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                            type="password"
                                            name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        @endif
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Phone No.')" />

                            <x-text-input id="phone" class="block mt-1 w-full"
                                            type="text"
                                            name="phone"  :value="old('phone', optional($user)->phone)" required autocomplete="Phone" />

                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ $user ? __('Update') : __('Create') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


