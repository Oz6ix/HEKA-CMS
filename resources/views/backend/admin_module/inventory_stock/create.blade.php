@extends('backend.layouts.modern')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Add Stock</h1>
            <p class="text-slate-500 mt-1">Add new stock to inventory.</p>
        </div>
        <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back to List
        </a>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
         x-data="{
            categoryId: '{{ old('inventory_category_id') }}',
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
                        // The legacy response returns a select element. We need to ensure it's styled correctly.
                        // Since we can't easily restyle the returned HTML without parsing it, 
                        // we might need to rely on global CSS or replace classes after injection.
                        // However, for now, let's inject it and see. If the backend returns raw HTML with Metronic classes,
                        // it might look off. 
                        // Ideally, we'd parse the options and rebuild the select, but that's complex if we don't know the exact HTML structure.
                        // Let's assume standard select behavior and try to apply Tailwind classes to the container styling or 
                        // see if we can perform a quick string replace on the response.
                        
                        // Attempt to replace legacy classes with Tailwind classes in the response string
                        let styledHtml = html.replace(/form-control/g, 'block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6');
                        styledHtml = styledHtml.replace(/kt-select2/g, ''); // Remove header select2 class if present
                        
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
        
        <form action="{{ route('inventory_stock_add') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
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
                                    <option value="">Select Category</option>
                                    @foreach($inventory_category as $data)
                                        <option value="{{ $data['inventory_category']['id'] }}">{{ $data['inventory_category']['inventory_name'] }}</option>
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
                                    <select class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 bg-slate-50 opacity-50 cursor-not-allowed sm:text-sm sm:leading-6" disabled>
                                        <option>Select Category First</option>
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
                                        <option value="{{ $data['id'] }}" {{ old('supplier_id') == $data['id'] ? 'selected' : '' }}>{{ $data['supplier_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity" class="block text-sm font-medium leading-6 text-slate-900">Quantity <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter quantity" required>
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
                                    <input type="text" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" class="block w-full rounded-md border-0 py-1.5 pl-7 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.00" required>
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
                                    <input type="text" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" class="block w-full rounded-md border-0 py-1.5 pl-7 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-span-1">
                            <label for="date" class="block text-sm font-medium leading-6 text-slate-900">Purchase Date <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="date" name="date" id="date" value="{{ old('date') }}" min="1935-01-01" max="2050-12-31" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Batch & Expiry Tracking -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-slate-900 border-b border-slate-200 pb-2 mb-6 flex items-center gap-2">
                        <i class="fas fa-boxes-stacked text-amber-500"></i> Batch & Expiry Tracking
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Batch Number -->
                        <div class="col-span-1">
                            <label for="batch_number" class="block text-sm font-medium leading-6 text-slate-900">Batch Number</label>
                            <div class="mt-2">
                                <input type="text" name="batch_number" id="batch_number" value="{{ old('batch_number') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="e.g. BT-2026-001">
                            </div>
                        </div>

                        <!-- Expiry Date -->
                        <div class="col-span-1">
                            <label for="expiry_date" class="block text-sm font-medium leading-6 text-slate-900">Expiry Date</label>
                            <div class="mt-2">
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <!-- MRP -->
                        <div class="col-span-1">
                            <label for="mrp" class="block text-sm font-medium leading-6 text-slate-900">MRP</label>
                            <div class="mt-2">
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-slate-500 sm:text-sm">K</span>
                                    </div>
                                    <input type="text" name="mrp" id="mrp" value="{{ old('mrp') }}" class="block w-full rounded-md border-0 py-1.5 pl-7 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Reorder Level -->
                        <div class="col-span-1">
                            <label for="reorder_level" class="block text-sm font-medium leading-6 text-slate-900">Reorder Level</label>
                            <div class="mt-2">
                                <input type="number" name="reorder_level" id="reorder_level" value="{{ old('reorder_level', 10) }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="10" min="0">
                                <p class="mt-1 text-xs text-slate-500">Alert when stock falls below this level.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-slate-900 border-b border-slate-200 pb-2 mb-6">Additional Details</h3>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Description</label>
                            <div class="mt-2">
                                <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <!-- Document Upload -->
                        <div class="col-span-2">
                            <label for="document" class="block text-sm font-medium leading-6 text-slate-900">Document</label>
                            <div class="mt-2">
                                <input type="file" name="document" id="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.ppt,.pptx" class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-primary-50 file:text-primary-700
                                    hover:file:bg-primary-100
                                ">
                                <p class="mt-1 text-xs text-slate-500">Accepted files: pdf, doc, docx, xls, xlsx. Max size: 2MB.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-end gap-x-6 bg-slate-50 px-4 py-4 -mx-6 -mb-6 border-t border-slate-200">
                <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700">Cancel</a>
                <button type="submit" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Save Stock</button>
            </div>
        </form>
    </div>
</div>
@endsection

