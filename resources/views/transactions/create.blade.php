<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Data Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <form id="transaction-form" action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    <!-- Header Transaksi -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-gray-700" for="description">Deskripsi</label>
                            <textarea id="description" name="description" class="form-input rounded-md shadow-sm mt-1 block w-full"
                                placeholder="Transaksi Bulan Agustus"></textarea>
                        </div>
                        <div>
                            <label class="block text-gray-700" for="code">Kode</label>
                            <input id="code" name="code" type="text"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="123456">
                        </div>
                        <div>
                            <label class="block text-gray-700" for="rate">Rate Euro</label>
                            <input id="rate" name="rate" type="number" step="0.01"
                                class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="15.000">
                        </div>
                        <div>
                            <label class="block text-gray-700" for="date_paid">Tanggal Bayar</label>
                            <input id="date_paid" name="date_paid" type="date"
                                class="form-input rounded-md shadow-sm mt-1 block w-full">
                        </div>
                    </div>

                    <!-- Detail Transaksi -->
                    <div id="details-container">
                        <div class="mb-4 p-4 border rounded-lg detail-template" style="display: none;">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-gray-700">Kategori</label>
                                <button type="button" class="remove-category text-red-600">✖</button>
                            </div>
                            <select name="details[0][category]"
                                class="form-select rounded-md shadow-sm block w-full mb-4 category-select">
                                @if ($categories->isEmpty())
                                    <option value="Income">Income</option>
                                    <option value="Expense">Expense</option>
                                @else
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="grid grid-cols-4 gap-4 items-center mb-2">
                                <label class="block text-gray-700 col-span-2">Nama Transaksi</label>
                                <label class="block text-gray-700 col-span-2">Nominal (IDR)</label>
                            </div>
                            <div class="grid grid-cols-4 gap-4 mb-3">
                                <input type="text" name="details[0][name]"
                                    class="form-input rounded-md shadow-sm col-span-2"
                                    placeholder="Contoh: Mobil Agustus">
                                <input type="number" name="details[0][amount]"
                                    class="form-input rounded-md shadow-sm col-span-1" placeholder="800.000">
                                <button type="button" class="add-transaction text-blue-600 col-span-1">+</button>
                            </div>
                        </div>
                        <button type="button"
                            class="add-category bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Tambah
                            Kategori</button>
                    </div>

                    <!-- Preview List -->
                    <div id="preview-list" class="mt-8 mb-4 p-4 border rounded-lg">
                        <h4 class="text-lg font-medium">Pratinjau Transaksi Ditambahkan</h4>
                        <!-- Transaksi yang ditambahkan akan muncul di sini -->
                    </div>

                    <!-- Tombol Simpan dan Batal -->
                    <div class="mt-6 flex justify-end">
                        <button type="reset" class="bg-red-600 text-white px-4 py-2 rounded-md">Batal</button>
                        <button type="submit" class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-md">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailsContainer = document.getElementById('details-container');
            const detailTemplate = document.querySelector('.detail-template');
            const previewList = document.getElementById('preview-list');

            document.querySelector('.add-category').addEventListener('click', function() {
                const newDetail = detailTemplate.cloneNode(true);
                newDetail.style.display = 'block';
                newDetail.classList.remove('detail-template');
                detailsContainer.insertBefore(newDetail, this);
            });

            detailsContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-category')) {
                    const detail = event.target.closest('.mb-4');
                    detail.remove();
                }

                if (event.target.classList.contains('add-transaction')) {
                    const detail = event.target.closest('.mb-4');
                    const nameInput = detail.querySelector('input[name="details[0][name]"]');
                    const amountInput = detail.querySelector('input[name="details[0][amount]"]');
                    const categorySelect = detail.querySelector('.category-select');

                    if (nameInput.value && amountInput.value) {
                        const amount = parseFloat(amountInput.value);
                        const previewItem = document.createElement('div');
                        previewItem.className =
                            'preview-item bg-gray-100 p-2 mb-2 rounded-md flex justify-between items-center';
                        previewItem.innerHTML = `
                            <span>${categorySelect.value}: ${nameInput.value} - IDR ${amount.toFixed(2)}</span>
                            <button type="button" class="delete-preview-item text-red-600">✖</button>
                        `;
                        previewList.appendChild(previewItem);

                        nameInput.value = '';
                        amountInput.value = '';
                    }
                }
            });

            previewList.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-preview-item')) {
                    const previewItem = event.target.closest('.preview-item');
                    previewItem.remove();
                }
            });
        });
    </script>
</x-app-layout>
