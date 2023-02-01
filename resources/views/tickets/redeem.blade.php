<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Redeem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Redeem Ticket') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Type in your ticket code to redeem it.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('tickets.process-redemption') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="code" :value="__('Ticket Code')" />
                                <x-text-input id="name" name="code" type="text" class="mt-1 block w-full" :value="old('code')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('code')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Redeem') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
