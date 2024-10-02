<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="block mb-8">
                        <div class="flex space-x-4">
                            <a href="{{ route('transactions.all') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">List</a>
                            <a href="{{ route('transactions.add') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Form</a>
                            <a href="{{ route('transactions.recap') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Recap</a>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-4">
                        <input type="text" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Search name...">
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Category</button>
                        <input type="date" class="form-input rounded-md shadow-sm mt-1 block w-full">
                        <input type="date" class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>

                    <div class="bg-white shadow-md rounded overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200 text-gray-600">
                                <tr>
                                    <th class="py-2 text-left">No</th>
                                    <th class="py-2 text-left">Code</th>
                                    <th class="py-2 text-left">Desc</th>
                                    <th class="py-2 text-left">Rate Eur</th>
                                    <th class="py-2 text-left">Date Paid</th>
                                    <th class="py-2 text-left">Category</th>
                                    <th class="py-2 text-left">Name</th>
                                    <th class="py-2 text-left">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @foreach ($transactions as $transaction)
                                    <tr class="border-b">
                                        <td class="py-2 px-2">{{ $transaction->id }}</td>
                                        <td class="py-2 px-2">{{ $transaction->code }}</td>
                                        <td class="py-2 px-2">{{ $transaction->description }}</td>
                                        <td class="py-2 px-2">{{ $transaction->rate }}</td>
                                        <td class="py-2 px-2">{{ \Carbon\Carbon::parse($transaction->date_paid)->format('d M Y') }}</td>
                                        <td class="py-2 px-2">
                                            <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded">{{ $transaction->category }}</span>
                                        </td>
                                        @if(count($transaction->details) > 0)
                                            <td class="py-2 px-2">{{ $transaction->details[0]->name }}</td>
                                            <td class="py-2 px-2">{{ $transaction->details[0]->amount }}</td>
                                        @else
                                            <td class="py-2 px-2">-</td>
                                            <td class="py-2 px-2">-</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <p>0 of {{ $transactions->count() }} row(s) selected.</p>
                    </div>

                    <div class="flex justify-between mt-4">
                        <select class="form-select rounded-md shadow-sm">
                            <option>10</option>
                            <option>20</option>
                            <option>50</option>
                        </select>
                        <div>
                            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Previous</button>
                            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>