<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form id="transaction-form" action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div>
                            <label class="text-gray-700" for="description">Description</label>
                            <textarea id="description" name="description" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                placeholder="Type your message here."></textarea>
                        </div>
                        <div>
                            <label class="text-gray-700" for="code">Code</label>
                            <input id="code" name="code" type="text"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>
                        <div>
                            <label class="text-gray-700" for="rate">Rate Euro</label>
                            <input id="rate" name="rate" type="number" step="0.01"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                        </div>
                        <div>
                            <label class="text-gray-700" for="date_paid">Date Paid</label>
                            <input id="date_paid" name="date_paid" type="date"
                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                        </div>
                        <div>
                            <label class="text-gray-700" for="category">Category</label>
                            <select id="category" name="category"
                                class="form-select rounded-md shadow-sm mt-1 block w-full">
                                <option value="Income">Income</option>
                                <option value="Expense">Expense</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-medium">Transaction Details</h3>
                        <div id="details" class="mb-4">
                        
                        </div>
                        <div id="preview-list" class="mt-4">
                            <h4 class="text-lg font-medium">Preview of Added Transactions</h4>
                            <!-- Pratinjau yang ditambahkan akan muncul di sini -->
                        </div>
                        <button type="button" id="add-detail-btn"
                            class="mt-4 px-4 py-2 bg-gray-200 text-gray-700 rounded">+ Add Transaction Detail</button>
                        <div class="mt-4">
                            <label class="text-gray-700">Total:</label>
                            <span id="total-amount" class="block mt-1 text-gray-900">0</span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="reset" class="px-4 py-2 bg-red-600 text-white rounded-md">Cancel</button>
                        <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md">Save
                            Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let detailIndex = 0;

        document.getElementById('add-detail-btn').addEventListener('click', function() {
            detailIndex++;
            const details = document.getElementById('details');
            const detailTemplate = `
            <div class="detail bg-gray-100 p-4 mb-4 rounded-md" data-index="${detailIndex}">
                <h4>Category #${detailIndex}</h4>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label class="text-gray-700" for="details[${detailIndex}][name]">Name</label>
                        <input id="details[${detailIndex}][name]" name="details[${detailIndex}][name]" type="text"
                            class="form-input rounded-md shadow-sm mt-1 block w-full">
                    </div>
                    <div>
                        <label class="text-gray-700" for="details[${detailIndex}][amount]">Amount</label>
                        <input id="details[${detailIndex}][amount]" name="details[${detailIndex}][amount]" type="number"
                            step="0.01" class="form-input rounded-md shadow-sm mt-1 block w-full" value="0">
                    </div>
                    <div class="flex items-end">
                        <button type="button"
                            class="add-detail ml-2 px-3 py-2 mb-1 bg-black text-white rounded-md">+</button>
                        <button type="button" class="remove-detail ml-2 px-2 py-1 mb-1 bg-red-600 text-white rounded-md">✖</button>
                    </div>
                </div>
            </div>
            `;
            details.insertAdjacentHTML('beforeend', detailTemplate);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('add-detail')) {
                const detailContainer = e.target.closest('.detail');
                const nameInput = detailContainer.querySelector('input[type="text"]');
                const amountInput = detailContainer.querySelector('input[type="number"]');

                if (nameInput.value && amountInput.value > 0) {
                    const amount = parseFloat(amountInput.value);
                    let total = parseFloat(document.getElementById('total-amount').textContent) || 0;
                    total += amount;
                    document.getElementById('total-amount').textContent = total.toFixed(2);

                    // Add to preview list
                    const previewList = document.getElementById('preview-list');
                    const previewItem = document.createElement('div');
                    previewItem.className =
                        'preview-item bg-gray-200 p-3 mb-2 rounded-md flex justify-between items-center';
                    previewItem.innerHTML = `
                        <span>${nameInput.value} - €${amount.toFixed(2)}</span>
                        <button type="button" class="delete-preview-item px-2 py-1 bg-red-600 text-white rounded-md">Delete</button>
                    `;
                    previewList.appendChild(previewItem);

                    // Reset inputs
                    nameInput.value = '';
                    amountInput.value = '0';
                }
            }

            if (e.target && e.target.classList.contains('remove-detail')) {
                const detailContainer = e.target.closest('.detail');
                detailContainer.remove();
                updateTotal();
            }

            if (e.target && e.target.classList.contains('delete-preview-item')) {
                const previewItem = e.target.closest('.preview-item');
                const amountText = previewItem.querySelector('span').textContent.split('€')[1];
                const amount = parseFloat(amountText);

                // Deduct amount from total
                let total = parseFloat(document.getElementById('total-amount').textContent);
                total -= amount;
                document.getElementById('total-amount').textContent = total.toFixed(2);

                // Remove preview item
                previewItem.remove();
            }
        });

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('input[name*="amount"]').forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }
    </script>
</x-app-layout>