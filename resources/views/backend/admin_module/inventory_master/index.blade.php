@extends('backend.layouts.modern')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inventory Items</h1>
        <p class="text-slate-500 mt-1">Manage all inventory item masters, categories, and units.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <!-- Import Button -->
        <button type="button" @click="$dispatch('open-modal', 'import-modal')" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
            <i class="fas fa-file-import mr-2 text-slate-400"></i> Import
        </button>

        <!-- Add New Button -->
        <a href="{{ url($url_prefix . '/inventory_master/create') }}" class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all">
            <i class="fas fa-plus mr-2"></i> Add Item
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header with Search and Bulk Actions -->
    <div class="border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50">
        <h3 class="text-base font-semibold leading-6 text-slate-900">All Items</h3>
        
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative flex-1 sm:flex-initial">
                 <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="customSearch" placeholder="Search items..." class="w-full sm:w-64 pl-8 pr-4 py-1.5 text-sm border border-slate-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Bulk Delete (Hidden by default, shown when checkboxes checked) -->
            <button type="button" id="bulkDeleteBtn" style="display: none;" class="inline-flex items-center justify-center rounded-md bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-600 hover:bg-red-100 transition-all border border-red-200">
                <i class="fas fa-trash-alt mr-2"></i> Delete Selected
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200" id="itemTable">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left w-10">
                        <input type="checkbox" class="rounded border-slate-300 text-primary-600 focus:ring-primary-600 m-group-checkable">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Item Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Item Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Unit</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <input type="checkbox" value="{{ $item->id }}" class="rounded border-slate-300 text-primary-600 focus:ring-primary-600 kt-checkable m-checkable">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-500">
                        {{ $item->master_code }}
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-slate-900">{{ $item->item_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                            {{ $item->inventory_category->inventory_name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                         {{ $item->unit->unit ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->status == 1)
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                             @if($item->status == 1)
                                <a href="javascript:;" onclick="change_status('{{ url($url_prefix . '/inventory_master/deactivate/'.$item->id) }}');" class="text-slate-400 hover:text-amber-600" title="Deactivate">
                                    <i class="fas fa-ban"></i>
                                </a>
                            @else
                                <a href="javascript:;" onclick="change_status('{{ url($url_prefix . '/inventory_master/activate/'.$item->id) }}');" class="text-slate-400 hover:text-green-600" title="Activate">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            @endif
                            
                            <a href="{{ url($url_prefix . '/inventory_master/edit/'.$item->id) }}" class="text-slate-400 hover:text-primary-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="javascript:;" onclick="delete_record('{{ url($url_prefix . '/inventory_master/delete/'.$item->id) }}');" class="text-slate-400 hover:text-red-600" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                <i class="fas fa-boxes text-slate-400 text-xl"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-semibold text-slate-900">No items found</h3>
                            <p class="mt-1 text-sm text-slate-500">Get started by creating a new inventory item.</p>
                            <div class="mt-6">
                                <a href="{{ url($url_prefix . '/inventory_master/create' ) }}" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    <i class="fas fa-plus mr-2"></i> Add Item
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal using Alpine.js -->
<div x-data="{ show: false }" 
     @open-modal.window="if ($event.detail === 'import-modal') show = true" 
     x-show="show" 
     class="relative z-50" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
    
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/75 transition-opacity" 
         @click="show = false"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-file-import text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold leading-6 text-slate-900" id="modal-title">Import Item List</h3>
                        <div class="mt-2">
                             <form method="POST" action="{{ url($url_prefix . '/inventory_master/import_item_master') }}" enctype="multipart/form-data" id="importForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="export_file" class="block text-sm font-medium leading-6 text-slate-900">Excel File</label>
                                    <div class="mt-2">
                                        <input type="file" name="export_file" id="export_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" required>
                                    </div>
                                    <p class="mt-2 text-xs text-slate-500">Supported formats: .xlsx, .xls</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <a href="{{ URL::asset('resources/files/sample/import_item_master.xlsx')}}" download class="text-sm font-medium text-primary-600 hover:text-primary-500">
                                        <i class="fas fa-download mr-1"></i> Download Sample
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="importForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto">Import</button>
                    <button type="button" @click="show = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Search functionality
        $("#customSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#itemTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Checkbox handling
        $(".m-group-checkable").change(function() {
            var isChecked = $(this).prop('checked');
            $(".kt-checkable").prop('checked', isChecked);
            toggleBulkActions();
        });

        $(".kt-checkable").change(function() {
            toggleBulkActions();
        });

        function toggleBulkActions() {
            var checkedCount = $(".kt-checkable:checked").length;
            if (checkedCount > 0) {
                $("#bulkDeleteBtn").fadeIn(200);
            } else {
                $("#bulkDeleteBtn").fadeOut(200);
            }
        }
        // Bulk Delete Handler
        $("#bulkDeleteBtn").click(function() {
            var selectedIds = [];
            $(".kt-checkable:checked").each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                if(confirm('Are you sure you want to delete the selected items?')) {
                     window.location.href = '{{ url($url_prefix . '/inventory_master/delete_multiple') }}/' + selectedIds.join(',');
                }
            }
        });
    });
</script>
@endsection
