@extends('backend.layouts.modern')

@section('title', 'Pharmacy Management')

@section('content')
<div x-data="{
    selectedItems: [],
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selectedItems = {{ $items->pluck('id') }};
        } else {
            this.selectedItems = [];
        }
    },
    toggleItem(id) {
        if (this.selectedItems.includes(id)) {
            this.selectedItems = this.selectedItems.filter(item => item !== id);
            this.allSelected = false;
        } else {
            this.selectedItems.push(id);
            if (this.selectedItems.length === {{ $items->count() }}) {
                this.allSelected = true;
            }
        }
    },
    bulkDelete() {
        if (this.selectedItems.length === 0) {
            alert('Please select items to delete.');
            return;
        }
        if (confirm('Are you sure you want to delete selected items?')) {
            window.location.href = '{{ url($url_prefix . '/pharmacy/delete_multiple') }}/' + this.selectedItems.join(',');
        }
    }
}">
    <!-- Messages -->
    @include('backend.layouts.includes.notification_alerts')

    <!-- Info Banner -->
    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 flex items-center" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <div>
            Manage your pharmacy inventory, medicines, and stock.
        </div>
    </div>

    <!-- Main Content -->
    <div>
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Header -->
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Medicine List</h2>
                        <p class="text-sm text-gray-500 mt-1">Total Medicines: {{ $items->count() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="bulkDelete()" x-show="selectedItems.length > 0" x-transition 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete Selected
                        </button>
                        
                        <a href="{{ url($url_prefix . '/pharmacy_categorys' ) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Manage Categories
                        </a>
                        
                        <button type="button" @click="$dispatch('open-import-modal')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import
                        </button>

                        <a href="{{ url($url_prefix . '/pharmacy/create' ) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Medicine
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                                    </div>
                                </th>
                                <th scope="col" class="px-4 py-3">Code</th>
                                <th scope="col" class="px-4 py-3">Medicine</th>
                                <th scope="col" class="px-4 py-3">Type</th>
                                <th scope="col" class="px-4 py-3">Schedule</th>
                                <th scope="col" class="px-4 py-3">Strength</th>
                                <th scope="col" class="px-4 py-3">Qty</th>
                                <th scope="col" class="px-4 py-3">Price</th>
                                <th scope="col" class="px-4 py-3">MRP</th>
                                <th scope="col" class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" value="{{ $item->id }}" @click="toggleItem({{ $item->id }})" :checked="selectedItems.includes({{ $item->id }})" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500">
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $item->code }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $item->title }}</div>
                                    @if($item->generic_name)
                                        <div class="text-xs text-gray-400">{{ $item->generic_name }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @php $typeColors = ['allopathy'=>'blue','ayurveda'=>'green','nutrition'=>'amber','psychology'=>'purple']; @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $typeColors[$item->medicine_type ?? 'allopathy'] ?? 'gray' }}-100 text-{{ $typeColors[$item->medicine_type ?? 'allopathy'] ?? 'gray' }}-700">
                                        {{ ucfirst($item->medicine_type ?? 'allopathy') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php $schedColors = ['OTC'=>'gray','H'=>'amber','H1'=>'orange','X'=>'red']; @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $schedColors[$item->schedule ?? 'OTC'] ?? 'gray' }}-100 text-{{ $schedColors[$item->schedule ?? 'OTC'] ?? 'gray' }}-700">
                                        {{ $item->schedule ?? 'OTC' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->strength ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-700">{{ number_format($item->price, 0) }} K</td>
                                <td class="px-4 py-3 font-medium text-gray-700">{{ $item->mrp ? number_format($item->mrp, 0).' K' : '-' }}</td>
                                <td class="px-4 py-3 text-right space-x-1">
                                    @if($item->status == 1)
                                        <a href="{{ url($url_prefix . '/pharmacy/deactivate/'.$item->id) }}" class="inline-flex items-center p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Deactivate">
                                            <i class="fas fa-toggle-on"></i>
                                        </a>
                                    @else
                                        <a href="{{ url($url_prefix . '/pharmacy/activate/'.$item->id) }}" class="inline-flex items-center p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Activate">
                                            <i class="fas fa-toggle-off"></i>
                                        </a>
                                    @endif
                                    <a href="{{ url($url_prefix . '/pharmacy/edit/'.$item->id) }}" class="inline-flex items-center p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-pen-to-square"></i>
                                    </a>
                                    <button @click="if(confirm('Are you sure you want to delete this medicine?')) window.location.href='{{ url($url_prefix . '/pharmacy/delete/'.$item->id) }}'" class="inline-flex items-center p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-pills text-3xl mb-3"></i>
                                    <p class="text-sm font-medium">No medicines found</p>
                                    <p class="text-xs mt-1">Click "Add Medicine" to create one.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination (if applicable) -->
                @if(method_exists($items, 'links'))
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $items->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Import Modal (Alpine.js / Tailwind) -->
<div x-data="{ open: false }" @open-import-modal.window="open = true">
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
            <!-- Modal -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Upload Medicine List</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form method="POST" action="{{ url($url_prefix . '/pharmacy/import_medicines') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="export_file" class="block mb-2 text-sm font-medium text-gray-900">Upload File</label>
                        <input type="file" name="export_file" id="export_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2" />
                        <p class="mt-1 text-xs text-gray-500">CSV or Excel files only</p>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <a href="{{ URL::asset('resources/files/sample/import_medicine.xlsx')}}" download class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Sample
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

