@extends('backend.layouts.modern')

@section('title', 'Inventory Alerts')

@section('content')
<div class="max-w-7xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Inventory Alerts</h1>
            <p class="text-slate-500 mt-1">Monitor stock levels, expiry dates, and adjustments.</p>
        </div>
        <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back to Stock
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-xmark text-red-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-700">{{ $expired->count() }}</p>
                    <p class="text-sm text-red-600">Expired Items</p>
                </div>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-700">{{ $near_expiry->count() }}</p>
                    <p class="text-sm text-amber-600">Expiring in 30 Days</p>
                </div>
            </div>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-orange-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-orange-700">{{ $low_stock->count() }}</p>
                    <p class="text-sm text-orange-600">Low Stock Items</p>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ activeTab: 'expired' }" class="space-y-6">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6">
                <button @click="activeTab = 'expired'" :class="activeTab === 'expired' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-all">
                    <i class="fas fa-skull-crossbones mr-1"></i> Expired ({{ $expired->count() }})
                </button>
                <button @click="activeTab = 'near_expiry'" :class="activeTab === 'near_expiry' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-all">
                    <i class="fas fa-hourglass-half mr-1"></i> Near Expiry ({{ $near_expiry->count() }})
                </button>
                <button @click="activeTab = 'low_stock'" :class="activeTab === 'low_stock' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-all">
                    <i class="fas fa-battery-quarter mr-1"></i> Low Stock ({{ $low_stock->count() }})
                </button>
                <button @click="activeTab = 'adjustments'" :class="activeTab === 'adjustments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-all">
                    <i class="fas fa-sliders mr-1"></i> Adjustments
                </button>
            </nav>
        </div>

        <!-- Expired Tab -->
        <div x-show="activeTab === 'expired'" x-transition>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-red-50">
                        <tr>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Expiry Date</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expired as $item)
                        <tr class="border-b hover:bg-red-25">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->inventorymaster->item_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $item->batch_number ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->supplier->supplier_name ?? '-' }}</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $item->quantity }}</span></td>
                            <td class="px-4 py-3 text-red-600 font-medium">{{ $item->expiry_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('inventory_stock_adjustment') }}" class="inline" onsubmit="return confirm('Remove expired stock?')">
                                    @csrf
                                    <input type="hidden" name="inventory_item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="type" value="expiry">
                                    <input type="hidden" name="quantity" value="{{ $item->quantity }}">
                                    <input type="hidden" name="reason" value="Expired on {{ $item->expiry_date?->format('d M Y') }}">
                                    <button type="submit" class="text-xs px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fas fa-check-circle text-green-500 mr-2"></i> No expired items!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Near Expiry Tab -->
        <div x-show="activeTab === 'near_expiry'" x-transition>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-amber-50">
                        <tr>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Expiry Date</th>
                            <th class="px-4 py-3">Days Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($near_expiry as $item)
                        <tr class="border-b hover:bg-amber-25">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->inventorymaster->item_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $item->batch_number ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->supplier->supplier_name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-amber-600 font-medium">{{ $item->expiry_date?->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    {{ now()->diffInDays($item->expiry_date) }} days
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fas fa-check-circle text-green-500 mr-2"></i> No items expiring soon!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Tab -->
        <div x-show="activeTab === 'low_stock'" x-transition>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-orange-50">
                        <tr>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Supplier</th>
                            <th class="px-4 py-3">Current Qty</th>
                            <th class="px-4 py-3">Reorder Level</th>
                            <th class="px-4 py-3">Deficit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($low_stock as $item)
                        <tr class="border-b hover:bg-orange-25">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $item->inventorymaster->item_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $item->supplier->supplier_name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $item->quantity == 0 ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->reorder_level }}</td>
                            <td class="px-4 py-3 text-red-600 font-medium">{{ max(0, $item->reorder_level - $item->quantity) }} units needed</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400"><i class="fas fa-check-circle text-green-500 mr-2"></i> All items are well-stocked!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stock Adjustments Tab -->
        <div x-show="activeTab === 'adjustments'" x-transition>
            <!-- Adjustment Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4"><i class="fas fa-plus-circle text-blue-500 mr-2"></i> New Stock Adjustment</h3>
                <form method="POST" action="{{ route('inventory_stock_adjustment') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                            <select name="inventory_item_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                <option value="">Select Item</option>
                                @php
                                    $all_items = \App\Models\InventoryStock::where('delete_status', 0)->with('inventorymaster')->get();
                                @endphp
                                @foreach($all_items as $si)
                                    <option value="{{ $si->id }}">{{ $si->inventorymaster->item_name ?? 'Item #'.$si->id }} (Qty: {{ $si->quantity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                                <option value="damage">Damage</option>
                                <option value="expiry">Expiry</option>
                                <option value="loss">Loss</option>
                                <option value="return">Return (adds stock)</option>
                                <option value="correction">Correction</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" min="1" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <input type="text" name="reason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" placeholder="Brief reason">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                            <i class="fas fa-check mr-1"></i> Record Adjustment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Adjustment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Recent Adjustments</h3>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Item</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Reason</th>
                            <th class="px-4 py-3">By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adj)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $adj->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $adj->inventoryItem?->inventorymaster?->item_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $adj->type_color }}-100 text-{{ $adj->type_color }}-700">
                                    {{ $adj->type_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono {{ $adj->type === 'return' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $adj->type === 'return' ? '+' : '-' }}{{ $adj->quantity }}
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $adj->reason ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $adj->adjustedByUser?->name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No adjustments recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
