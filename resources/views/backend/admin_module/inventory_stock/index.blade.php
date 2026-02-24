@extends('backend.layouts.modern')
<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
?>
@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    selectedItems: [], 
    showImportModal: false,
    toggleSelection(id) {
        if (this.selectedItems.includes(id)) {
            this.selectedItems = this.selectedItems.filter(item => item !== id);
        } else {
            this.selectedItems.push(id);
        }
    },
    toggleAll() {
        if (this.selectedItems.length === {{ count($items) }}) {
            this.selectedItems = [];
        } else {
            this.selectedItems = {{ $items->pluck('id') }};
        }
    },
    deleteSelected() {
        if (this.selectedItems.length === 0) return;
        if (confirm('Are you sure you want to delete the selected items?')) {
            window.location.href = '{{ url($url_prefix . '/inventory_stock/delete_multiple') }}/' + this.selectedItems.join(',');
        }
    }
}"><button type="button" 
                    x-show="selectedItems.length > 0"
                    x-on:click="deleteSelected()"
                    class="inline-flex items-center justify-center rounded-md bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-100 transition-all">
                <i class="fas fa-trash mr-2"></i> Delete (<span x-text="selectedItems.length"></span>)
            </button>

            <!-- Import -->
            <button @click="showImportModal = true" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                <i class="fas fa-file-import mr-2 text-slate-400"></i> Import
            </button>

            <!-- Add New -->
            <a href="{{ url($url_prefix . '/inventory_stock/create') }}" class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all">
                <i class="fas fa-plus mr-2"></i> Add Stock
            </a>
        </div>
    </div>

    @include('backend.layouts.includes.notification_alerts')



    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-10">
                            <input type="checkbox" 
                                   @click="toggleAll()" 
                                   :checked="selectedItems.length === {{ count($items) }} && {{ count($items) }} > 0"
                                   class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Item Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Item Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Supplier</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Unit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($items as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" 
                                       value="{{ $item->id }}" 
                                       @click="toggleSelection({{ $item->id }})"
                                       :checked="selectedItems.includes({{ $item->id }})"
                                       class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ $item->item_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                {{ $item->inventorymaster->item_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->supplier->supplier_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 font-semibold">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $item->inventorymaster->unit->unit ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                K {{ number_format($item->purchase_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Status Toggle -->
                                    @if($item->status == 1)
                                        <a href="{{ url($url_prefix . '/inventory_stock/deactivate/'.$item->id) }}" class="text-emerald-600 hover:text-emerald-900 p-1" title="Deactivate">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ url($url_prefix . '/inventory_stock/activate/'.$item->id) }}" class="text-slate-400 hover:text-emerald-600 p-1" title="Activate">
                                            <i class="fas fa-eye-slash"></i>
                                        </a>
                                    @endif

                                    <!-- View -->
                                    <a href="{{ url($url_prefix . '/inventory_stock/view/'.$item->id) }}" class="text-amber-500 hover:text-amber-700 p-1" title="View">
                                        <i class="fas fa-search"></i>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ url($url_prefix . '/inventory_stock/edit/'.$item->id) }}" class="text-primary-600 hover:text-primary-900 p-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete -->
                                    <a href="{{ url($url_prefix . '/inventory_stock/delete/'.$item->id) }}" class="text-red-600 hover:text-red-900 p-1" onclick="return confirm('Are you sure you want to delete this item?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-sm text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-boxes text-4xl text-slate-300 mb-3"></i>
                                    <p>No inventory stock items found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination (if applicable, using standard Laravel links if available or just spacing) -->
        <div class="px-6 py-4 border-t border-slate-200">
            <!-- Add pagination links here if passed from controller -->
        </div>
    </div>

    <!-- Import Modal -->
    <div x-show="showImportModal" 
         style="display: none;"
         class="relative z-50" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <div x-show="showImportModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="showImportModal = false"
                     class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <form action="{{ url($url_prefix . '/inventory_stock/import_item_stock') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fas fa-file-import text-primary-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">Import Stock Items</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-slate-500 mb-4">Select an Excel file to import inventory stock items.</p>
                                        
                                        <div class="mt-4">
                                            <input type="file" name="export_file" required class="block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-primary-50 file:text-primary-700
                                                hover:file:bg-primary-100
                                            "/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto">Import</button>
                            <button type="button" @click="showImportModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Cancel</button>
                            <a href="{{ URL::asset('resources/files/sample/import_item_stock.xlsx')}}" download class="mt-3 sm:mt-0 sm:mr-auto inline-flex items-center justify-center text-sm text-primary-600 hover:text-primary-800">
                                <i class="fas fa-download mr-1"></i> Sample File
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
