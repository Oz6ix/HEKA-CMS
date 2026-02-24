@extends('backend.layouts.modern')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Stock</h1>
            <p class="text-slate-500 mt-1">Update inventory stock details.</p>
        </div>
        <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back to List
        </a>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
         x-data="{
            categoryId: '{{ old('inventory_category_id', $item['inventorymaster']['inventory_category_id']) }}',
            isLoadingItems: false,
            fetchItems() {
                if (!this.categoryId) {
                     document.getElementById('relatedPart').innerHTML = '<select class=\'block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 bg-slate-50 opacity-50 cursor-not-allowed sm:text-sm sm:leading-6\' disabled><option>Select Category First</option></select>';
                    return;
                }
                this.isLoadingItems = true;
                fetch('{{ route('ajax_fecth_item_master') }}/' + this.categoryId)
                    .then(response => response.text())
                    .then(html => {
                        let styledHtml = html.replace(/form-control/g, 'block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6');
                        styledHtml = styledHtml.replace(/kt-select2/g, ''); 
                        document.getElementById('relatedPart').innerHTML = styledHtml;
                        this.isLoadingItems = false;
                    })
                    .catch(error => {
                         console.error('Error fetching items:', error);
                        this.isLoadingItems = false;
                    });
            }
         }"
         x-init="$watch('categoryId', value => fetchItems())">
        
        <form action="{{ route('inventory_stock_update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="id" value="{{$item['id']}}">
            
            <div class="space-y-8">
                <!-- Stock Information -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-slate-900 border-b border-slate-200 pb-2 mb-6">Stock Details</h3>
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Category -->
                        <div class="col-span-1">
                            <label for="inventory_category_id" class="block text-sm font-medium leading-6 text-slate-900">Category <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="inventory_category_id" name="inventory_category_id" x-model="categoryId" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                                    @foreach($inventory_category as $data)
                                        <option value="{{ $data['inventory_category']['id'] }}" {{ ($data['inventory_category']['id'] == $item['inventorymaster']['inventory_category_id']) ? 'selected' : '' }}>
                                            {{ $data['inventory_category']['inventory_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('inventory_category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Item Name (Dynamic) -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium leading-6 text-slate-900">Item Name <span class="text-red-500">*</span></label>
                            <div class="mt-2 relative">
                                <span id="relatedPart">
                                    <select class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" name="inventory_master_id">
                                         @foreach($select_inventory_category as $data)
                                            <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['inventorymaster']['id']) ? 'selected' : '' }}>
                                                {{ $data['item_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </span>
                                <div x-show="isLoadingItems" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-spinner fa-spin text-primary-500"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier -->
                        <div class="col-span-1">
                            <label for="supplier_id" class="block text-sm font-medium leading-6 text-slate-900">Supplier <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="supplier_id" name="supplier_id" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                                    <option value="">Select Supplier</option>
                                    @foreach($supplier as $data)
                                        <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['supplier_id']) ? 'selected' : '' }}>{{ $data['supplier_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity" class="block text-sm font-medium leading-6 text-slate-900">Quantity <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="number" name="quantity" id="quantity" value="{{ $item['quantity'] }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter quantity" required>
                            </div>
                        </div>

                        <!-- Purchase Price -->
                        <div class="col-span-1">
                            <label for="purchase_price" class="block text-sm font-medium leading-6 text-slate-900">Purchase Price <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-slate-500 sm:text-sm">K</span>
                                    </div>
                                    <input type="text" name="purchase_price" id="purchase_price" value="{{ $item['purchase_price'] }}" class="block w-full rounded-md border-0 py-1.5 pl-7 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <!-- Selling Price -->
                        <div class="col-span-1">
                            <label for="selling_price" class="block text-sm font-medium leading-6 text-slate-900">Selling Price <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-slate-500 sm:text-sm">K</span>
                                    </div>
                                    <input type="text" name="selling_price" id="selling_price" value="{{ $item['selling_price'] }}" class="block w-full rounded-md border-0 py-1.5 pl-7 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-span-1">
                            <label for="date" class="block text-sm font-medium leading-6 text-slate-900">Date <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="date" name="date" id="date" value="{{ $item['date'] }}" min="1935-01-01" max="2050-12-31" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Description</label>
                            <div class="mt-2">
                                <textarea id="description" name="description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter description (optional)">{!! $item['description'] !!}</textarea>
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="col-span-2">
                            <label for="document" class="block text-sm font-medium leading-6 text-slate-900">Document</label>
                            <div class="mt-2 flex items-center gap-4">
                                <div class="flex-grow">
                                     <input type="file" name="document" id="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx" class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-primary-50 file:text-primary-700
                                        hover:file:bg-primary-100
                                    ">
                                    <p class="mt-1 text-xs text-slate-500">Accepted files: pdf, doc, docx, xls, xlsx. Max size: 2MB.</p>
                                </div>
                                @if(isset($item['document']) && !empty($item['document']))
                                    <div class="flex-shrink-0">
                                        <a href="{{ URL::asset('uploads/inventory_stock_document/' .$item['document']) }}" download class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-800">
                                            <i class="fas fa-file-download text-xl"></i>
                                            <span class="text-sm font-medium">Download Existing</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-end gap-x-6 bg-slate-50 px-4 py-4 -mx-6 -mb-6 border-t border-slate-200">
                <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700">Cancel</a>
                <button type="submit" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Update Stock</button>
            </div>
        </form>
    </div>
</div>
@endsection