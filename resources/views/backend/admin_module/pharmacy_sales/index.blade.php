@extends('backend.layouts.modern')

@section('title', 'Quick Billing')

@section('content')
<div class="max-w-7xl mx-auto" x-data="pharmacyPOS()">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-cash-register text-primary-500 mr-2"></i> Quick Billing
            </h1>
            <p class="text-slate-500 mt-1">Sell medicines over-the-counter or walk-in customers.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url($url_prefix . '/pharmacy_sales/external') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100">
                <i class="fas fa-file-prescription mr-2"></i> External Rx
            </a>
            <a href="{{ url($url_prefix . '/pharmacy_sales/external/list') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-list mr-2"></i> Rx List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Drug Search + Cart -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Search Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchDrugs()" @keydown.enter.prevent="searchDrugs()"
                           class="block w-full pl-10 pr-20 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Search by medicine name, generic name, or scan barcode..." autofocus>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-xs text-gray-400"><i class="fas fa-barcode mr-1"></i> Barcode Ready</span>
                    </div>
                </div>

                <!-- Search Results -->
                <div x-show="results.length > 0" x-transition class="mt-3 border border-gray-200 rounded-lg overflow-hidden max-h-60 overflow-y-auto">
                    <template x-for="drug in results" :key="drug.id">
                        <button @click="addToCart(drug)" class="w-full flex items-center justify-between px-4 py-3 hover:bg-primary-50 border-b border-gray-100 text-left transition-colors">
                            <div>
                                <div class="font-medium text-gray-900" x-text="drug.title"></div>
                                <div class="text-xs text-gray-500">
                                    <span x-show="drug.generic_name" x-text="drug.generic_name" class="mr-2"></span>
                                    <span x-show="drug.strength" x-text="drug.strength" class="mr-2"></span>
                                    <span x-show="drug.form" x-text="drug.form"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-primary-600" x-text="(drug.mrp || drug.price) + ' Ks'"></div>
                                <div class="text-xs" :class="drug.quantity > 0 ? 'text-green-600' : 'text-red-600'" x-text="'Stock: ' + drug.quantity"></div>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Cart Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800"><i class="fas fa-cart-shopping text-primary-500 mr-2"></i> Cart</h3>
                    <span class="text-sm text-gray-500" x-text="cart.length + ' items'"></span>
                </div>
                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-600 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Medicine</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Unit Price</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in cart" :key="index">
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900" x-text="item.drug_name"></div>
                                    <div class="text-xs text-gray-400" x-text="item.strength"></div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button @click="item.quantity > 1 ? item.quantity-- : null" class="w-7 h-7 rounded bg-gray-100 hover:bg-gray-200 flex items-center justify-center">-</button>
                                        <input type="number" x-model.number="item.quantity" min="1" class="w-14 text-center border border-gray-300 rounded py-1 text-sm">
                                        <button @click="item.quantity++" class="w-7 h-7 rounded bg-gray-100 hover:bg-gray-200 flex items-center justify-center">+</button>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right" x-text="item.unit_price + ' Ks'"></td>
                                <td class="px-4 py-3 text-right font-medium" x-text="(item.quantity * item.unit_price) + ' Ks'"></td>
                                <td class="px-4 py-3 text-right">
                                    <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash-can"></i></button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="cart.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                <i class="fas fa-cart-shopping text-2xl mb-2"></i>
                                <p class="text-sm">Search and add medicines above</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Checkout Panel -->
        <div class="lg:col-span-1">
            <form @submit.prevent="checkout()" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-4 space-y-4">
                <h3 class="font-semibold text-gray-800 text-lg border-b pb-3"><i class="fas fa-receipt text-green-500 mr-2"></i> Checkout</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" x-model="customer.name" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Walk-in Customer">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" x-model="customer.phone" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Optional">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select x-model="paymentMethod" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="upi">UPI / Digital</option>
                    </select>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" x-text="subtotal + ' Ks'"></span>
                    </div>
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-gray-600">Discount</span>
                        <input type="number" x-model.number="discount" min="0" class="w-24 text-right border border-gray-300 rounded py-1 px-2 text-sm" placeholder="0">
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                        <span>Total</span>
                        <span class="text-primary-600" x-text="total + ' Ks'"></span>
                    </div>
                </div>

                <button type="submit" :disabled="cart.length === 0" :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="w-full py-3 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm">
                    <i class="fas fa-check-circle mr-2"></i> Complete Sale
                </button>
            </form>

            <!-- Recent Sales -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-4 p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3"><i class="fas fa-clock-rotate-left mr-1"></i> Recent Sales</h4>
                @forelse($recent_sales as $sale)
                    <a href="{{ url($url_prefix . '/pharmacy_sales/invoice/' . $sale->id) }}" class="flex justify-between items-center py-2 px-3 rounded-lg hover:bg-gray-50 text-sm border-b border-gray-50">
                        <div>
                            <span class="font-mono text-xs text-gray-500">{{ $sale->invoice_no }}</span>
                            <p class="text-gray-700">{{ $sale->customer_name }}</p>
                        </div>
                        <span class="font-medium text-gray-800">{{ number_format($sale->total) }} Ks</span>
                    </a>
                @empty
                    <p class="text-xs text-gray-400 text-center">No sales yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function pharmacyPOS() {
    return {
        searchQuery: '',
        results: [],
        cart: [],
        customer: { name: 'Walk-in Customer', phone: '' },
        paymentMethod: 'cash',
        discount: 0,

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
        },
        get total() {
            return Math.max(0, this.subtotal - this.discount);
        },

        async searchDrugs() {
            if (this.searchQuery.length < 2) { this.results = []; return; }
            try {
                const response = await fetch('{{ route("pharmacy_sales_search_drug") }}?q=' + encodeURIComponent(this.searchQuery));
                this.results = await response.json();
            } catch (e) { console.error(e); }
        },

        addToCart(drug) {
            const existing = this.cart.find(item => item.pharmacy_id === drug.id);
            if (existing) {
                existing.quantity++;
            } else {
                this.cart.push({
                    pharmacy_id: drug.id,
                    drug_name: drug.title,
                    strength: drug.strength || '',
                    quantity: 1,
                    unit_price: drug.mrp || drug.price || 0,
                    stock: drug.quantity
                });
            }
            this.results = [];
            this.searchQuery = '';
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        async checkout() {
            if (this.cart.length === 0) return;
            if (!this.customer.name) { alert('Customer name is required'); return; }

            const formData = {
                _token: '{{ csrf_token() }}',
                customer_name: this.customer.name,
                customer_phone: this.customer.phone,
                payment_method: this.paymentMethod,
                discount: this.discount,
                items: this.cart.map(item => ({
                    pharmacy_id: item.pharmacy_id,
                    drug_name: item.drug_name,
                    quantity: item.quantity,
                    unit_price: item.unit_price
                }))
            };

            try {
                const response = await fetch('{{ route("pharmacy_sales_store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(formData)
                });
                
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    const text = await response.text();
                    // Laravel redirects via session, so just redirect back
                    window.location.href = '{{ url($url_prefix . "/pharmacy_sales") }}';
                }
            } catch (e) {
                console.error(e);
                alert('Error creating sale');
            }
        }
    };
}
</script>
@endsection
