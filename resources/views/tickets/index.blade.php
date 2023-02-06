<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="get" action="{{ route('tickets.index') }}" class="mt-6 space-y-6">
                <div>
                    <x-input-label for="code" :value="__('Ticket Code')" />
                    <x-text-input id="name" name="code" type="text" class="mt-1 block w-full" :value="old('code', request()->query('code'))" autofocus />
                </div>

                <div>
                    <x-input-label for="status" :value="__('Select Status')" />
                    <x-select id="status" name="status" class="mt-1 block w-full">
                        <option value="">All</option>
                        <option value="redeemed" @selected(request()->query('status') === 'redeemed')>Redeemed</option>
                        <option value="not_redeemed" @selected(request()->query('status') === 'not_redeemed')>Not Redeemed</option>
                    </x-select>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Search Ticket') }}</x-primary-button>
                </div>
            </form>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg ">
                @php
                    $classes = "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150";
                @endphp

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('tickets.generate') }}" class="{{ $classes }}">Generate Tickets</a>
                @endif

                <table class="table-auto" style="width: 100%;">
                    <thead>
                    <tr style="text-align: left">
                        <th>Ticket Code</th>
                        <th>Status</th>
                        <th>Redeemed By</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ $ticket->redeemedBy?->name }}</td>
                            <td>
                                @if($ticket->status === 'not_redeemed')
                                    <form onsubmit="return confirm('Confirm action?')" action="{{ route('tickets.process-redemption') }}" method="post">
                                        @method('PATCH')
                                        @csrf

                                        <input type="hidden" name="code" value="{{ $ticket->code }}">
                                        <x-primary-button>{{ __('Redeem') }}</x-primary-button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $tickets->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
