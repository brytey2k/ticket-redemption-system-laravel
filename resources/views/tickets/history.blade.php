<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ticket Redemption History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <table class="table-auto">
                    <thead>
                    <tr>
                        <th>Ticket Code</th>
                        <th>Redeemed At</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->code }}</td>
                            <td>{{ $ticket->redeemed_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $tickets->links() }}
            </div>
        </div>
    </div>

</x-app-layout>
