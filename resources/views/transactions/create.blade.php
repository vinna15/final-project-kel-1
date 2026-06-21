<x-layouts.app title="Transaksi Baru">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Pilih Barang --}}
        <div class="lg:col-span-2">
            <x-card title="Pilih Barang">

                <div class="flex gap-3 mb-4">
                    <select id="product-select"
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-nama="{{ $product->nama_barang }}"
                                data-harga="{{ $product->harga }}" data-stok="{{ $stocks[$product->id] ?? 0 }}">
                                {{ $product->nama_barang }}
                                (Stok: {{ $stocks[$product->id] ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                    <x-button type="button" variant="primary" onclick="addItem()">+ Tambah</x-button>
                </div>

                {{-- Tabel Item --}}
                <x-table :headers="['Barang', 'Harga', 'Qty', 'Subtotal', '']">
                    <tbody id="cart-body">
                        <tr id="empty-row">
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400 text-sm">
                                Belum ada barang dipilih.
                            </td>
                        </tr>
                    </tbody>
                </x-table>

            </x-card>
        </div>

        {{-- Ringkasan & Submit --}}
        <div>
            <x-card title="Ringkasan">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total Item</span>
                        <span id="total-item" class="font-medium">0</span>
                    </div>
                    <div class="flex justify-between text-base font-bold text-gray-800 border-t pt-3">
                        <span>Total</span>
                        <span id="total-display">Rp 0</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('transactions.store') }}" id="transaction-form" class="mt-4">
                    @csrf
                    <div id="hidden-inputs"></div>
                    <x-button type="submit" variant="success" class="w-full justify-center">
                        <x-heroicon-o-check class="w-4 h-4" />
                        Selesaikan Transaksi
                    </x-button>
                </form>
            </x-card>
        </div>

    </div>

    <script>
        let cart = {};

        function addItem() {
            const select = document.getElementById('product-select');
            const option = select.options[select.selectedIndex];

            if (!option.value) return alert('Pilih barang terlebih dahulu.');

            const id = option.value;
            const nama = option.dataset.nama;
            const harga = parseFloat(option.dataset.harga);
            const stok = parseInt(option.dataset.stok);

            if (stok === 0) return alert('Stok habis!');

            if (cart[id]) {
                if (cart[id].qty >= stok) return alert('Melebihi stok tersedia!');
                cart[id].qty++;
            } else {
                cart[id] = {
                    id,
                    nama,
                    harga,
                    qty: 1,
                    stok
                };
            }

            renderCart();
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) {
                delete cart[id];
            } else if (cart[id].qty > cart[id].stok) {
                cart[id].qty = cart[id].stok;
                alert('Melebihi stok!');
            }
            renderCart();
        }

        function removeItem(id) {
            delete cart[id];
            renderCart();
        }

        function renderCart() {
            const body = document.getElementById('cart-body');
            const emptyRow = document.getElementById('empty-row');
            const hiddenInputs = document.getElementById('hidden-inputs');
            let total = 0;
            let totalItem = 0;
            let html = '';
            let inputs = '';

            if (Object.keys(cart).length === 0) {
                body.innerHTML =
                    `<tr id="empty-row"><td colspan="5" class="px-4 py-6 text-center text-gray-400 text-sm">Belum ada barang dipilih.</td></tr>`;
                document.getElementById('total-item').textContent = 0;
                document.getElementById('total-display').textContent = 'Rp 0';
                hiddenInputs.innerHTML = '';
                return;
            }

            let i = 0;
            for (const id in cart) {
                const item = cart[id];
                const subtotal = item.harga * item.qty;
                total += subtotal;
                totalItem += item.qty;

                html += `
                <tr>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">${item.nama}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">Rp ${item.harga.toLocaleString('id-ID')}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="changeQty('${id}', -1)"
                                class="w-6 h-6 rounded bg-gray-200 text-gray-700 text-sm hover:bg-gray-300">−</button>
                            <span class="w-6 text-center text-sm font-medium">${item.qty}</span>
                            <button type="button" onclick="changeQty('${id}', 1)"
                                class="w-6 h-6 rounded bg-gray-200 text-gray-700 text-sm hover:bg-gray-300">+</button>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="px-4 py-3">
                        <button type="button" onclick="removeItem('${id}')"
                            class="text-red-400 hover:text-red-600 text-sm">✕</button>
                    </td>
                </tr>`;

                inputs += `<input type="hidden" name="items[${i}][product_id]" value="${id}">`;
                inputs += `<input type="hidden" name="items[${i}][qty]" value="${item.qty}">`;
                i++;
            }

            body.innerHTML = html;
            hiddenInputs.innerHTML = inputs;
            document.getElementById('total-item').textContent = totalItem;
            document.getElementById('total-display').textContent =
                'Rp ' + total.toLocaleString('id-ID');
        }

        // Cegah submit jika cart kosong
        document.getElementById('transaction-form').addEventListener('submit', function(e) {
            if (Object.keys(cart).length === 0) {
                e.preventDefault();
                alert('Keranjang masih kosong!');
            }
        });
    </script>

</x-layouts.app>
