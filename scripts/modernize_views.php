<?php
/**
 * Batch script to generate modernized Blade views for all remaining modules.
 * Run from project root: php scripts/modernize_views.php
 */

$base = __DIR__ . '/../resources/views/backend/admin_module';

$modules = [
    // ---- HR Setup ----
    [
        'slug' => 'staff_department', 'name' => 'Staff Departments', 'singular' => 'Department', 'icon' => 'building',
        'columns' => ['Department' => '$item->department'],
        'formFields' => [['name' => 'department', 'label' => 'Department Name', 'required' => true]],
        'addRoute' => 'staff_department_add', 'updateRoute' => 'staff_department_update',
        'scripts' => ['validations/staff_department'],
    ],
    [
        'slug' => 'staff_designation', 'name' => 'Staff Designations', 'singular' => 'Designation', 'icon' => 'id-badge',
        'columns' => ['Designation' => '$item->designation'],
        'formFields' => [['name' => 'designation', 'label' => 'Designation Name', 'required' => true]],
        'addRoute' => 'staff_designation_add', 'updateRoute' => 'staff_designation_update',
        'scripts' => ['validations/staff_designation'],
    ],
    [
        'slug' => 'staff_specialist', 'name' => 'Staff Specialists', 'singular' => 'Specialist', 'icon' => 'user-doctor',
        'columns' => ['Specialist' => '$item->specialist'],
        'formFields' => [['name' => 'specialist', 'label' => 'Specialist Name', 'required' => true]],
        'addRoute' => 'staff_specialist_add', 'updateRoute' => 'staff_specialist_update',
        'scripts' => ['validations/staff_specialist'],
    ],
    [
        'slug' => 'staff_role', 'name' => 'Staff Roles', 'singular' => 'Role', 'icon' => 'user-shield',
        'columns' => ['Role' => '$item->role'],
        'formFields' => [['name' => 'role', 'label' => 'Role Name', 'required' => true]],
        'addRoute' => 'staff_role_add', 'updateRoute' => 'staff_role_update',
        'scripts' => ['validations/staff_role'],
    ],
    // ---- Hospital Setup ----
    [
        'slug' => 'hospital_charge', 'name' => 'Hospital Charges', 'singular' => 'Charge', 'icon' => 'receipt',
        'columns' => [
            'Code' => '$item->code',
            'Category' => "(\$item->hospital_charge_category->name ?? '-')",
            'Title' => '$item->title',
            'Rate (K)' => '$item->standard_charge',
        ],
        'formFields' => [
            ['name' => 'code', 'label' => 'Code', 'required' => true, 'readonly' => true, 'valueVar' => '$code'],
            ['name' => 'title', 'label' => 'Title', 'required' => true],
            ['name' => 'standard_charge', 'label' => 'Standard Charge (K)', 'required' => true],
        ],
        'hasCategory' => true, 'categorySlug' => 'hospital_charge_category',
        'addRoute' => 'hospital_charge_add', 'updateRoute' => 'hospital_charge_update',
        'scripts' => ['validations/hospital_charge', 'scripts/hospital_charge'],
        'categorySelect' => ['var' => 'hospital_charge_category', 'field' => 'hospital_charge_category_id', 'selectId' => 'select2_hospital_charge_category_id'],
    ],
    [
        'slug' => 'hospital_charge_category', 'name' => 'Charge Categories', 'singular' => 'Category', 'icon' => 'layer-group',
        'columns' => ['Category Name' => '$item->name', 'Parent' => "(\$item->subcategory->name ?? '-')"],
        'formFields' => [
            ['name' => 'name', 'label' => 'Category Name', 'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
        ],
        'parentSelect' => true,
        'addRoute' => 'hospital_charge_category_add', 'updateRoute' => 'hospital_charge_category_update',
        'scripts' => ['validations/hospital_charge_category', 'scripts/hospital_charge_category'],
        'backLink' => 'hospital_charges',
    ],
    [
        'slug' => 'casualty', 'name' => 'Casualty Types', 'singular' => 'Casualty', 'icon' => 'truck-medical',
        'columns' => ['Casualty' => '$item->casualty'],
        'formFields' => [['name' => 'casualty', 'label' => 'Casualty Name', 'required' => true]],
        'addRoute' => 'casualty_add', 'updateRoute' => 'casualty_update',
        'scripts' => ['validations/casualty'],
    ],
    [
        'slug' => 'center', 'name' => 'Centers', 'singular' => 'Center', 'icon' => 'hospital',
        'columns' => ['Center' => '$item->center'],
        'formFields' => [['name' => 'center', 'label' => 'Center Name', 'required' => true]],
        'addRoute' => 'center_add', 'updateRoute' => 'center_update',
        'scripts' => ['validations/center'],
    ],
    [
        'slug' => 'frequency', 'name' => 'Frequencies', 'singular' => 'Frequency', 'icon' => 'clock',
        'columns' => ['Frequency' => '$item->frequency'],
        'formFields' => [['name' => 'frequency', 'label' => 'Frequency Name', 'required' => true]],
        'addRoute' => 'frequency_add', 'updateRoute' => 'frequency_update',
        'scripts' => ['validations/frequency'],
    ],
    [
        'slug' => 'symptom_type', 'name' => 'Symptom Types', 'singular' => 'Symptom Type', 'icon' => 'thermometer-half',
        'columns' => ['Symptom' => '$item->symptom'],
        'formFields' => [['name' => 'symptom', 'label' => 'Symptom Name', 'required' => true]],
        'addRoute' => 'symptom_type_add', 'updateRoute' => 'symptom_type_update',
        'scripts' => ['validations/symptom_type'],
    ],
    [
        'slug' => 'tpa', 'name' => 'TPA / Insurance', 'singular' => 'TPA', 'icon' => 'shield-halved',
        'columns' => ['TPA' => '$item->tpa'],
        'formFields' => [['name' => 'tpa', 'label' => 'TPA Name', 'required' => true]],
        'addRoute' => 'tpa_add', 'updateRoute' => 'tpa_update',
        'scripts' => ['validations/tpa'],
    ],
    // ---- Inventory ----
    [
        'slug' => 'inventory_category', 'name' => 'Inventory Categories', 'singular' => 'Category', 'icon' => 'boxes-stacked',
        'columns' => ['Name' => '$item->name'],
        'formFields' => [['name' => 'name', 'label' => 'Category Name', 'required' => true]],
        'addRoute' => 'inventory_category_add', 'updateRoute' => 'inventory_category_update',
        'scripts' => ['validations/inventory_category'],
    ],
    // ---- Settings ----
    [
        'slug' => 'setting_supplier', 'name' => 'Suppliers', 'singular' => 'Supplier', 'icon' => 'truck',
        'columns' => ['Suppliers' => '$item->suppliers'],
        'formFields' => [['name' => 'suppliers', 'label' => 'Supplier Name', 'required' => true]],
        'addRoute' => 'setting_supplier_add', 'updateRoute' => 'setting_supplier_update',
        'scripts' => ['validations/setting_supplier'],
    ],
    [
        'slug' => 'setting_quantity', 'name' => 'Quantities', 'singular' => 'Quantity', 'icon' => 'balance-scale',
        'columns' => ['Quantity' => '$item->quantity'],
        'formFields' => [['name' => 'quantity', 'label' => 'Quantity Name', 'required' => true]],
        'addRoute' => 'setting_quantity_add', 'updateRoute' => 'setting_quantity_update',
        'scripts' => ['validations/setting_quantity'],
    ],
    [
        'slug' => 'setting_unit', 'name' => 'Units', 'singular' => 'Unit', 'icon' => 'ruler-combined',
        'columns' => ['Unit' => '$item->unit'],
        'formFields' => [['name' => 'unit', 'label' => 'Unit Name', 'required' => true]],
        'addRoute' => 'setting_unit_add', 'updateRoute' => 'setting_unit_update',
        'scripts' => ['validations/setting_unit'],
    ],
    // ---- Admin ----
    [
        'slug' => 'user_groups', 'name' => 'User Groups', 'singular' => 'User Group', 'icon' => 'users-gear',
        'columns' => ['User Group' => '$item->user_group'],
        'formFields' => [['name' => 'user_group', 'label' => 'Group Name', 'required' => true]],
        'addRoute' => 'user_group_add', 'updateRoute' => 'user_group_update',
        'scripts' => ['validations/user_groups'],
    ],
];

$count = 0;

foreach ($modules as $mod) {
    $slug = $mod['slug'];
    $dir = "$base/$slug";
    
    // Generate INDEX
    $colHeaders = '';
    $colData = '';
    $colCount = count($mod['columns']) + 3;
    
    foreach ($mod['columns'] as $header => $expr) {
        $colHeaders .= "                        <th class=\"px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500\">{$header}</th>\n";
        if (stripos($header, 'category') !== false || stripos($header, 'parent') !== false) {
            $colData .= "                            <td class=\"px-3 py-3 text-sm text-slate-600\">\n";
            $colData .= "                                <span class=\"inline-flex items-center rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-medium text-violet-700\">{{ {$expr} }}</span>\n";
            $colData .= "                            </td>\n";
        } elseif (stripos($header, 'code') !== false) {
            $colData .= "                            <td class=\"px-3 py-3\">\n";
            $colData .= "                                <span class=\"inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600\">{{ {$expr} }}</span>\n";
            $colData .= "                            </td>\n";
        } elseif (stripos($header, 'rate') !== false || stripos($header, 'charge') !== false || stripos($header, 'price') !== false) {
            $colData .= "                            <td class=\"px-3 py-3 text-sm font-semibold text-slate-700\">{{ number_format({$expr}) }}</td>\n";
        } else {
            $colData .= "                            <td class=\"px-3 py-3 text-sm font-medium text-slate-900\">{{ {$expr} }}</td>\n";
        }
    }
    
    $categoryBtn = '';
    if (!empty($mod['hasCategory'])) {
        $catSlug = $mod['categorySlug'];
        $categoryBtn = "            <a href=\"{{ url(\$url_prefix . '/{$catSlug}s') }}\"\n";
        $categoryBtn .= "               class=\"inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors\">\n";
        $categoryBtn .= "                <i class=\"fas fa-folder mr-2 text-slate-400\"></i> Categories\n";
        $categoryBtn .= "            </a>\n";
    }
    
    $listUrl = !empty($mod['backLink']) ? $mod['backLink'] : "{$slug}s";
    $singUrl = $slug;

    $indexContent = "@extends('backend.layouts.modern')\n\n@section('content')\n<div class=\"space-y-6\">\n";
    $indexContent .= "    <div class=\"sm:flex sm:items-center sm:justify-between\">\n";
    $indexContent .= "        <div>\n";
    $indexContent .= "            <h1 class=\"text-2xl font-bold text-slate-900\">{$mod['name']}</h1>\n";
    $indexContent .= "            <p class=\"mt-1 text-sm text-slate-500\">Manage {$mod['name']} records.</p>\n";
    $indexContent .= "        </div>\n";
    $indexContent .= "        <div class=\"mt-4 sm:mt-0 flex items-center gap-3\">\n";
    $indexContent .= $categoryBtn;
    $indexContent .= "            <a href=\"{{ url(\$url_prefix . '/{$singUrl}/create') }}\"\n";
    $indexContent .= "               class=\"inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors\">\n";
    $indexContent .= "                <i class=\"fas fa-plus mr-2\"></i> Add {$mod['singular']}\n";
    $indexContent .= "            </a>\n";
    $indexContent .= "        </div>\n";
    $indexContent .= "    </div>\n\n";
    $indexContent .= "    <div class=\"bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden\">\n";
    $indexContent .= "        <div class=\"overflow-x-auto\">\n";
    $indexContent .= "            <table class=\"min-w-full divide-y divide-slate-200\">\n";
    $indexContent .= "                <thead class=\"bg-slate-50\">\n";
    $indexContent .= "                    <tr>\n";
    $indexContent .= "                        <th class=\"py-3 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-12\">#</th>\n";
    $indexContent .= $colHeaders;
    $indexContent .= "                        <th class=\"px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500\">Status</th>\n";
    $indexContent .= "                        <th class=\"px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 pr-6\">Actions</th>\n";
    $indexContent .= "                    </tr>\n";
    $indexContent .= "                </thead>\n";
    $indexContent .= "                <tbody class=\"divide-y divide-slate-100 bg-white\">\n";
    $indexContent .= "                    @if(isset(\$items) && sizeof(\$items) > 0)\n";
    $indexContent .= "                        @foreach(\$items as \$index => \$item)\n";
    $indexContent .= "                        <tr class=\"hover:bg-slate-50 transition-colors\">\n";
    $indexContent .= "                            <td class=\"py-3 pl-6 pr-3 text-sm text-slate-500\">{{ \$index + 1 }}</td>\n";
    $indexContent .= $colData;
    $indexContent .= "                            <td class=\"px-3 py-3\">\n";
    $indexContent .= "                                @if(\$item->status == 1)\n";
    $indexContent .= "                                    <span class=\"inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700\">Active</span>\n";
    $indexContent .= "                                @else\n";
    $indexContent .= "                                    <span class=\"inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700\">Inactive</span>\n";
    $indexContent .= "                                @endif\n";
    $indexContent .= "                            </td>\n";
    $indexContent .= "                            <td class=\"px-3 py-3 pr-6 text-right\">\n";
    $indexContent .= "                                <div class=\"flex items-center justify-end gap-2\">\n";
    $indexContent .= "                                    @if(\$item->status == 1)\n";
    $indexContent .= "                                        <a href=\"javascript:;\" onclick=\"change_status('{{ url(\$url_prefix . '/{$singUrl}/deactivate/'.\$item->id) }}')\" class=\"text-slate-400 hover:text-amber-500 transition-colors\" title=\"Deactivate\">\n";
    $indexContent .= "                                            <i class=\"fas fa-toggle-on text-emerald-500\"></i>\n";
    $indexContent .= "                                        </a>\n";
    $indexContent .= "                                    @else\n";
    $indexContent .= "                                        <a href=\"javascript:;\" onclick=\"change_status('{{ url(\$url_prefix . '/{$singUrl}/activate/'.\$item->id) }}')\" class=\"text-slate-400 hover:text-emerald-500 transition-colors\" title=\"Activate\">\n";
    $indexContent .= "                                            <i class=\"fas fa-toggle-off\"></i>\n";
    $indexContent .= "                                        </a>\n";
    $indexContent .= "                                    @endif\n";
    $indexContent .= "                                    <a href=\"{{ url(\$url_prefix . '/{$singUrl}/edit/'.\$item->id) }}\" class=\"text-slate-400 hover:text-primary-600 transition-colors\" title=\"Edit\">\n";
    $indexContent .= "                                        <i class=\"fas fa-pen-to-square\"></i>\n";
    $indexContent .= "                                    </a>\n";
    $indexContent .= "                                    <a href=\"javascript:;\" onclick=\"delete_record('{{ url(\$url_prefix . '/{$singUrl}/delete/'.\$item->id) }}')\" class=\"text-slate-400 hover:text-red-500 transition-colors\" title=\"Delete\">\n";
    $indexContent .= "                                        <i class=\"fas fa-trash-can\"></i>\n";
    $indexContent .= "                                    </a>\n";
    $indexContent .= "                                </div>\n";
    $indexContent .= "                            </td>\n";
    $indexContent .= "                        </tr>\n";
    $indexContent .= "                        @endforeach\n";
    $indexContent .= "                    @else\n";
    $indexContent .= "                        <tr>\n";
    $indexContent .= "                            <td colspan=\"{$colCount}\" class=\"px-6 py-12 text-center\">\n";
    $indexContent .= "                                <div class=\"text-slate-400\">\n";
    $indexContent .= "                                    <i class=\"fas fa-{$mod['icon']} text-3xl mb-3\"></i>\n";
    $indexContent .= "                                    <p class=\"text-sm font-medium\">No records found</p>\n";
    $indexContent .= "                                    <p class=\"text-xs mt-1\">Add your first {$mod['singular']} to get started.</p>\n";
    $indexContent .= "                                </div>\n";
    $indexContent .= "                            </td>\n";
    $indexContent .= "                        </tr>\n";
    $indexContent .= "                    @endif\n";
    $indexContent .= "                </tbody>\n";
    $indexContent .= "            </table>\n";
    $indexContent .= "        </div>\n";
    $indexContent .= "    </div>\n";
    $indexContent .= "</div>\n@endsection\n";

    file_put_contents("$dir/index.blade.php", $indexContent);
    $count++;
    echo "  ✓ {$slug}/index.blade.php\n";
    
    // Generate CREATE form
    $formFieldsHtml = '';
    $parentSelectHtml = '';
    $categorySelectHtml = '';
    
    foreach ($mod['formFields'] as $field) {
        $req = !empty($field['required']) ? '<span class="text-red-500">*</span>' : '';
        $type = $field['type'] ?? 'text';
        $readonly = !empty($field['readonly']) ? 'readonly' : '';
        $bgClass = !empty($field['readonly']) ? 'bg-slate-50 text-slate-500 ' : '';
        $valueCreate = !empty($field['valueVar']) ? "{{ {$field['valueVar']} }}" : "{{ old('{$field['name']}') }}";
        
        if ($type === 'textarea') {
            $formFieldsHtml .= "            <div>\n";
            $formFieldsHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">{$field['label']} {$req}</label>\n";
            $formFieldsHtml .= "                <textarea name=\"{$field['name']}\" id=\"{$field['name']}\" rows=\"3\" placeholder=\"Enter {$field['label']}...\"\n";
            $formFieldsHtml .= "                    class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">{{ old('{$field['name']}') }}</textarea>\n";
            $formFieldsHtml .= "            </div>\n";
        } else {
            $formFieldsHtml .= "            <div>\n";
            $formFieldsHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">{$field['label']} {$req}</label>\n";
            $formFieldsHtml .= "                <input type=\"text\" name=\"{$field['name']}\" id=\"{$field['name']}\" value=\"{$valueCreate}\" placeholder=\"Enter {$field['label']}\" {$readonly}\n";
            $formFieldsHtml .= "                    class=\"block w-full rounded-lg border-slate-300 {$bgClass}text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
            $formFieldsHtml .= "            </div>\n";
        }
    }
    
    if (!empty($mod['parentSelect'])) {
        $parentSelectHtml .= "            <div>\n";
        $parentSelectHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">Parent Category</label>\n";
        $parentSelectHtml .= "                <select id=\"select2_charge_category\" class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
        $parentSelectHtml .= "                    <option value=\"0\">None (Top-level)</option>\n";
        $parentSelectHtml .= "                    @foreach(\$parent_category as \$data)\n";
        $parentSelectHtml .= "                        <option value=\"{{ \$data['id'] }}\">{{ \$data['name'] }}</option>\n";
        $parentSelectHtml .= "                    @endforeach\n";
        $parentSelectHtml .= "                </select>\n";
        $parentSelectHtml .= "                <input type=\"hidden\" name=\"parent_id\" id=\"parent_id\" value=\"0\">\n";
        $parentSelectHtml .= "            </div>\n";
    }
    
    if (!empty($mod['categorySelect'])) {
        $cs = $mod['categorySelect'];
        $categorySelectHtml .= "            <div>\n";
        $categorySelectHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">Category <span class=\"text-red-500\">*</span></label>\n";
        $categorySelectHtml .= "                <select id=\"{$cs['selectId']}\" class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
        $categorySelectHtml .= "                    <option value=\"\">Select category</option>\n";
        $categorySelectHtml .= "                    @foreach(\${$cs['var']} as \$data)\n";
        $categorySelectHtml .= "                        <option value=\"{{ \$data['id'] }}\">{{ \$data['name'] }}</option>\n";
        $categorySelectHtml .= "                    @endforeach\n";
        $categorySelectHtml .= "                </select>\n";
        $categorySelectHtml .= "                <input type=\"hidden\" name=\"{$cs['field']}\" id=\"{$cs['field']}\" value=\"{{ old('{$cs['field']}') }}\">\n";
        $categorySelectHtml .= "            </div>\n";
    }
    
    $scriptsHtml = '';
    foreach ($mod['scripts'] as $s) {
        $scriptsHtml .= "<script src=\"{{ URL::asset('assets/backend/js/{$s}.js') }}\" type=\"text/javascript\"></script>\n";
    }
    
    $listUrlForBack = !empty($mod['backLink']) ? $mod['backLink'] : $slug;
    
    $createContent = "@extends('backend.layouts.modern')\n\n@section('content')\n<div class=\"space-y-6\">\n";
    $createContent .= "    <div class=\"sm:flex sm:items-center sm:justify-between\">\n";
    $createContent .= "        <div>\n";
    $createContent .= "            <h1 class=\"text-2xl font-bold text-slate-900\">Add {$mod['singular']}</h1>\n";
    $createContent .= "            <p class=\"mt-1 text-sm text-slate-500\">Create a new {$mod['singular']} record.</p>\n";
    $createContent .= "        </div>\n";
    $createContent .= "        <a href=\"{{ url(\$url_prefix . '/{$listUrlForBack}') }}\"\n";
    $createContent .= "           class=\"inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors\">\n";
    $createContent .= "            <i class=\"fas fa-arrow-left mr-2 text-slate-400\"></i> Back\n";
    $createContent .= "        </a>\n";
    $createContent .= "    </div>\n\n";
    $createContent .= "    <div class=\"bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl\">\n";
    $createContent .= "        {!! Form::open(['route'=>'{$mod['addRoute']}', 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}\n";
    $createContent .= "        <div class=\"p-6 space-y-5\">\n";
    $createContent .= $formFieldsHtml;
    $createContent .= $categorySelectHtml;
    $createContent .= $parentSelectHtml;
    $createContent .= "        </div>\n";
    $createContent .= "        <div class=\"border-t border-slate-200 px-6 py-4 bg-slate-50 flex items-center justify-end gap-3\">\n";
    $createContent .= "            <a href=\"{{ url(\$url_prefix . '/{$listUrlForBack}') }}\" class=\"rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white transition-colors\">Cancel</a>\n";
    $createContent .= "            <button type=\"submit\" class=\"rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors\">\n";
    $createContent .= "                <i class=\"fas fa-check mr-2\"></i> Save\n";
    $createContent .= "            </button>\n";
    $createContent .= "        </div>\n";
    $createContent .= "        {!! Form::close() !!}\n";
    $createContent .= "    </div>\n";
    $createContent .= "</div>\n@endsection\n\n@section('scripts')\n{$scriptsHtml}@endsection\n";

    file_put_contents("$dir/create.blade.php", $createContent);
    $count++;
    echo "  ✓ {$slug}/create.blade.php\n";
    
    // Generate EDIT form
    $editFieldsHtml = '';
    foreach ($mod['formFields'] as $field) {
        $req = !empty($field['required']) ? '<span class="text-red-500">*</span>' : '';
        $type = $field['type'] ?? 'text';
        $readonly = !empty($field['readonly']) ? 'readonly' : '';
        $bgClass = !empty($field['readonly']) ? 'bg-slate-50 text-slate-500 ' : '';
        
        if ($type === 'textarea') {
            $editFieldsHtml .= "            <div>\n";
            $editFieldsHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">{$field['label']} {$req}</label>\n";
            $editFieldsHtml .= "                <textarea name=\"{$field['name']}\" id=\"{$field['name']}\" rows=\"3\" placeholder=\"Enter {$field['label']}...\"\n";
            $editFieldsHtml .= "                    class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">{{ \$item['{$field['name']}'] }}</textarea>\n";
            $editFieldsHtml .= "            </div>\n";
        } else {
            $editFieldsHtml .= "            <div>\n";
            $editFieldsHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">{$field['label']} {$req}</label>\n";
            $editFieldsHtml .= "                <input type=\"text\" name=\"{$field['name']}\" id=\"{$field['name']}\" value=\"{{ \$item['{$field['name']}'] }}\" placeholder=\"Enter {$field['label']}\" {$readonly}\n";
            $editFieldsHtml .= "                    class=\"block w-full rounded-lg border-slate-300 {$bgClass}text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
            $editFieldsHtml .= "            </div>\n";
        }
    }
    
    $editParentSelectHtml = '';
    if (!empty($mod['parentSelect'])) {
        $editParentSelectHtml .= "            <div>\n";
        $editParentSelectHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">Parent Category</label>\n";
        $editParentSelectHtml .= "                <select id=\"select2_charge_category\" class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
        $editParentSelectHtml .= "                    <option value=\"0\">None (Top-level)</option>\n";
        $editParentSelectHtml .= "                    @foreach(\$parent_category as \$data)\n";
        $editParentSelectHtml .= "                        <option value=\"{{ \$data['id'] }}\" {{ (\$data['id'] == \$item['parent_id']) ? 'selected' : '' }}>{{ \$data['name'] }}</option>\n";
        $editParentSelectHtml .= "                    @endforeach\n";
        $editParentSelectHtml .= "                </select>\n";
        $editParentSelectHtml .= "                <input type=\"hidden\" name=\"parent_id\" id=\"parent_id\" value=\"{{ \$item['parent_id'] }}\">\n";
        $editParentSelectHtml .= "            </div>\n";
    }
    
    $editCategorySelectHtml = '';
    if (!empty($mod['categorySelect'])) {
        $cs = $mod['categorySelect'];
        $editCategorySelectHtml .= "            <div>\n";
        $editCategorySelectHtml .= "                <label class=\"block text-sm font-medium text-slate-700 mb-1.5\">Category <span class=\"text-red-500\">*</span></label>\n";
        $editCategorySelectHtml .= "                <select id=\"{$cs['selectId']}\" class=\"block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500\">\n";
        $editCategorySelectHtml .= "                    <option value=\"\">Select category</option>\n";
        $editCategorySelectHtml .= "                    @foreach(\${$cs['var']} as \$data)\n";
        $editCategorySelectHtml .= "                        <option value=\"{{ \$data['id'] }}\" {{ (\$data['id'] == \$item['{$cs['field']}']) ? 'selected' : '' }}>{{ \$data['name'] }}</option>\n";
        $editCategorySelectHtml .= "                    @endforeach\n";
        $editCategorySelectHtml .= "                </select>\n";
        $editCategorySelectHtml .= "                <input type=\"hidden\" name=\"{$cs['field']}\" id=\"{$cs['field']}\" value=\"{{ \$item['{$cs['field']}'] }}\">\n";
        $editCategorySelectHtml .= "            </div>\n";
    }
    
    $editContent = "@extends('backend.layouts.modern')\n\n@section('content')\n<div class=\"space-y-6\">\n";
    $editContent .= "    <div class=\"sm:flex sm:items-center sm:justify-between\">\n";
    $editContent .= "        <div>\n";
    $editContent .= "            <h1 class=\"text-2xl font-bold text-slate-900\">Edit {$mod['singular']}</h1>\n";
    $editContent .= "            <p class=\"mt-1 text-sm text-slate-500\">Update {$mod['singular']} details.</p>\n";
    $editContent .= "        </div>\n";
    $editContent .= "        <a href=\"{{ url(\$url_prefix . '/{$listUrlForBack}') }}\"\n";
    $editContent .= "           class=\"inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors\">\n";
    $editContent .= "            <i class=\"fas fa-arrow-left mr-2 text-slate-400\"></i> Back\n";
    $editContent .= "        </a>\n";
    $editContent .= "    </div>\n\n";
    $editContent .= "    <div class=\"bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl\">\n";
    $editContent .= "        {!! Form::open(['route'=>'{$mod['updateRoute']}', 'id' => 'update_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}\n";
    $editContent .= "        <input type=\"hidden\" name=\"id\" value=\"{{ \$item['id'] }}\">\n";
    $editContent .= "        <div class=\"p-6 space-y-5\">\n";
    $editContent .= $editFieldsHtml;
    $editContent .= $editCategorySelectHtml;
    $editContent .= $editParentSelectHtml;
    $editContent .= "        </div>\n";
    $editContent .= "        <div class=\"border-t border-slate-200 px-6 py-4 bg-slate-50 flex items-center justify-end gap-3\">\n";
    $editContent .= "            <a href=\"{{ url(\$url_prefix . '/{$listUrlForBack}') }}\" class=\"rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white transition-colors\">Cancel</a>\n";
    $editContent .= "            <button type=\"submit\" class=\"rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors\">\n";
    $editContent .= "                <i class=\"fas fa-check mr-2\"></i> Update\n";
    $editContent .= "            </button>\n";
    $editContent .= "        </div>\n";
    $editContent .= "        {!! Form::close() !!}\n";
    $editContent .= "    </div>\n";
    $editContent .= "</div>\n@endsection\n\n@section('scripts')\n{$scriptsHtml}@endsection\n";

    file_put_contents("$dir/edit.blade.php", $editContent);
    $count++;
    echo "  ✓ {$slug}/edit.blade.php\n";
}

echo "\n✅ Generated {$count} modernized view files across " . count($modules) . " modules.\n";
