<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Generate Tickets') }}
                            </h2>
                        </header>

                        <form method="post" action="{{ route('tickets.process-ticket-generation') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="number_of_tickets" :value="__('Number of tickets to generate')" />
                                <x-text-input id="number_of_tickets" name="number_of_tickets" type="text" class="mt-1 block w-full" :value="old('code')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('number_of_tickets')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Generate') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
