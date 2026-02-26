<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:inset-0 shadow-xl flex flex-col">
    <!-- Brand -->
    <div class="flex h-16 shrink-0 items-center px-5 bg-slate-950/50 border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('assets/brand/heka-icon.png') }}" alt="HEKA" class="h-9 w-9 rounded-lg object-contain">
            <div>
                <span class="text-lg font-bold tracking-tight bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">HEKA</span>
                <p class="text-[8px] uppercase tracking-widest text-slate-500 -mt-0.5 font-medium">Clinic Management</p>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scroll-smooth">
        
        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">Core</p>
        
        <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-th-large w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Dashboard
        </a>

        <a href="{{ route('rcm.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('rcm.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-file-invoice-dollar w-6 {{ request()->routeIs('rcm.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Revenue Cycle (RCM)
        </a>

        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Clinical</p>
        
        <a href="{{ route('emr.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('emr.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-user-md w-6 {{ request()->routeIs('emr.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Doctor Workbench
        </a>
        
        <a href="{{ route('appointment.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('appointment.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
             <i class="fas fa-calendar-check w-6 {{ request()->routeIs('appointment.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Appointments
        </a>

        <a href="{{ route('patient.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('patient.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-hospital-user w-6 {{ request()->routeIs('patient.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Patients
        </a>

        <a href="{{ route('referrals.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/referral*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-share-from-square w-6 {{ request()->is('*/referral*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Referrals
        </a>

        <a href="{{ route('medical_certificates.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/medical_certificate*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-file-medical w-6 {{ request()->is('*/medical_certificate*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Certificates
        </a>

        {{-- Pharmacy --}}
        @php $pharmacyActive = request()->routeIs('pharmacy*'); @endphp
        <div x-data="sidebarMenu('pharmacy', {{ $pharmacyActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $pharmacyActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-pills w-6 transition-colors" :class="open ? 'text-white' : 'text-slate-500 group-hover:text-white'"></i>
                    Pharmacy
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ route('pharmacys.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pharmacys.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Medicine List
                </a>
                <a href="{{ route('pharmacy_categorys.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pharmacy_categorys.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Categories
                </a>
                <a href="{{ route('pharmacy_generic.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pharmacy_generic.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Generics
                </a>
                <a href="{{ route('pharmacy_dosage.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('pharmacy_dosage.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Dosages
                </a>
                <div class="border-t border-slate-700 my-1"></div>
                <a href="{{ route('pharmacy_sales') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/pharmacy_sales') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <i class="fas fa-cash-register w-4 mr-2 text-xs"></i> Quick Billing
                </a>
                <a href="{{ route('pharmacy_sales_external_list') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/pharmacy_sales/external*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <i class="fas fa-file-prescription w-4 mr-2 text-xs"></i> External Rx
                </a>
            </div>
        </div>

        {{-- Diagnostics --}}
        @php $diagActive = request()->is('*/pathology*') || request()->is('*/radiology*'); @endphp
        <div x-data="sidebarMenu('diagnostics', {{ $diagActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $diagActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-flask w-6 transition-colors" :class="open ? 'text-white' : 'text-slate-500 group-hover:text-white'"></i>
                    Diagnostics
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ url($url_prefix ?? 'admin') }}/pathologys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/pathology*') && !request()->is('*/pathology_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Pathology Tests
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/pathology_categorys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/pathology_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Pathology Categories
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/radiologys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/radiology*') && !request()->is('*/radiology_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Radiology Tests
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/radiology_categorys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/radiology_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Radiology Categories
                </a>
            </div>
        </div>

        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Administration</p>

        <a href="{{ route('staffs.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('staffs.*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-users w-6 {{ request()->routeIs('staffs.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Staff
        </a>

        {{-- HR Setup --}}
        @php $hrActive = request()->is('*/staff_department*') || request()->is('*/staff_designation*') || request()->is('*/staff_role*') || request()->is('*/staff_specialist*'); @endphp
        <div x-data="sidebarMenu('hr_setup', {{ $hrActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $hrActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-sitemap w-6 text-slate-500 group-hover:text-white transition-colors"></i>
                    HR Setup
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ url($url_prefix ?? 'admin') }}/staff_departments" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/staff_department*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Departments
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/staff_designations" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/staff_designation*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Designations
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/staff_roles" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/staff_role*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Roles
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/staff_specialists" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/staff_specialist*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Specializations
                </a>
            </div>
        </div>

        {{-- Inventory --}}
        @php $invActive = request()->routeIs('inventory_*'); @endphp
        <div x-data="sidebarMenu('inventory', {{ $invActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $invActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-boxes w-6 transition-colors" :class="open ? 'text-white' : 'text-slate-500 group-hover:text-white'"></i>
                    Inventory
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ route('inventory_stocks.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('inventory_stocks.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Stock List
                </a>
                <a href="{{ route('inventory_masters.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('inventory_masters.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Item Master
                </a>
                <a href="{{ route('inventory_categorys.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('inventory_categorys.*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Categories
                </a>
            </div>
        </div>

        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Settings</p>

        {{-- Hospital Setup --}}
        @php $hospActive = request()->is('*/hospital_charge*') || request()->is('*/tpa*') || request()->is('*/casualty*') || request()->is('*/symptom*') || request()->is('*/center*') || request()->is('*/frequency*'); @endphp
        <div x-data="sidebarMenu('hospital_setup', {{ $hospActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $hospActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-hospital w-6 text-slate-500 group-hover:text-white transition-colors"></i>
                    Hospital Setup
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ url($url_prefix ?? 'admin') }}/hospital_charges" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/hospital_charge*') && !request()->is('*/hospital_charge_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Charges
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/hospital_charge_categorys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/hospital_charge_category*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Charge Categories
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/tpa" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/tpa*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    TPA
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/casualty" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/casualty*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Casualty
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/symptom_type" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/symptom*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Symptom Types
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/center" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/center*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Centers
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/frequency" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/frequency*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Frequencies
                </a>
            </div>
        </div>

        {{-- General Settings --}}
        @php $genActive = request()->is('*/setting_*') || request()->is('*/general_settings*'); @endphp
        <div x-data="sidebarMenu('general_settings', {{ $genActive ? 'true' : 'false' }})" class="space-y-1">
            <button @click="toggle()" type="button" class="group flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200" :class="open || {{ $genActive ? 'true' : 'false' }} ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800'">
                <div class="flex items-center">
                     <i class="fas fa-cog w-6 text-slate-500 group-hover:text-white transition-colors"></i>
                    General Settings
                </div>
                <i :class="open ? 'rotate-90' : ''" class="fas fa-chevron-right w-4 h-4 transition-transform duration-200 text-slate-500"></i>
            </button>
            <div x-show="open" x-transition.duration.150ms class="space-y-1 pl-11">
                <a href="{{ url($url_prefix ?? 'admin') }}/general_settings" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/general_settings*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Site Settings
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/setting_suppliers" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/setting_supplier*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Suppliers
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/setting_units" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/setting_unit*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Units
                </a>
                <a href="{{ url($url_prefix ?? 'admin') }}/setting_quantitys" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/setting_quantity*') ? 'text-primary-400 bg-primary-600/10' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    Quantities
                </a>
            </div>
        </div>

        <a href="{{ url($url_prefix ?? 'admin') }}/admin_users" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/admin_user*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-user-shield w-6 {{ request()->is('*/admin_user*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            Admin Users
        </a>

        <a href="{{ url($url_prefix ?? 'admin') }}/user_groups" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->is('*/user_group*') ? 'bg-primary-600 text-white shadow-md shadow-primary-900/20' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-users-cog w-6 {{ request()->is('*/user_group*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition-colors"></i>
            User Groups
        </a>

    </nav>
    
    <!-- User Footer -->
    <div class="border-t border-slate-800 p-4 bg-slate-950/30">
        <button type="button" onclick="if(confirm('Are you sure you want to sign out?')) window.location.href='{{ route('logout') }}'" class="flex items-center gap-3 w-full p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors group cursor-pointer">
            <div class="h-8 w-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-semibold text-white group-hover:bg-red-600 transition-colors">
                 <i class="fas fa-power-off"></i>
            </div>
            <div class="flex-1 text-sm font-medium text-left">
                Sign Out
            </div>
        </button>
    </div>

    <script>
    /**
     * Sidebar Menu Component — persists expansion state across page navigations
     * Uses sessionStorage so each menu remembers if the user opened/closed it
     */
    document.addEventListener('alpine:init', function() {
        Alpine.data('sidebarMenu', function(menuId, routeActive) {
            return {
                open: false,
                menuId: menuId,
                init() {
                    var stored = sessionStorage.getItem('sidebar-menu-' + this.menuId);
                    if (stored !== null) {
                        // User explicitly toggled this menu — respect their choice
                        this.open = stored === '1';
                    } else {
                        // No stored preference — open if route is active
                        this.open = routeActive;
                    }
                },
                toggle() {
                    this.open = !this.open;
                    sessionStorage.setItem('sidebar-menu-' + this.menuId, this.open ? '1' : '0');
                }
            };
        });
    });

    /**
     * Sidebar Scroll Persistence
     * Hybrid approach:
     * 1. On link click: save scroll position synchronously, then navigate
     * 2. On page load: wait for Alpine to finish, then restore saved position
     * 3. Fallback: scroll active item into view
     */
    (function() {
        var nav = document.getElementById('sidebar-nav');
        if (!nav) return;
        var STORAGE_KEY = 'heka-sidebar-scroll';

        // Save scroll on every scroll event (sync, no debounce)
        nav.addEventListener('scroll', function() {
            try { sessionStorage.setItem(STORAGE_KEY, nav.scrollTop); } catch(e) {}
        }, { passive: true });

        // Intercept sidebar link clicks: save scroll, then navigate
        nav.addEventListener('click', function(e) {
            var link = e.target.closest('a[href]');
            if (link && link.getAttribute('href') && link.getAttribute('href') !== '#') {
                e.preventDefault();
                e.stopPropagation();
                try { sessionStorage.setItem(STORAGE_KEY, nav.scrollTop); } catch(e2) {}
                // Use a microtask to ensure storage is flushed
                Promise.resolve().then(function() {
                    window.location.href = link.href;
                });
                return false;
            }
        }, true); // capture phase to run before Alpine handlers

        // Restore function
        function restore() {
            var saved = sessionStorage.getItem(STORAGE_KEY);
            if (saved !== null) {
                var pos = parseInt(saved, 10);
                if (pos > 0 && nav.scrollHeight > nav.clientHeight) {
                    nav.scrollTop = pos;
                    return true;
                }
            }
            return false;
        }

        // Scroll active item into view as fallback
        function scrollToActive() {
            var el = nav.querySelector('.bg-primary-600') ||
                     nav.querySelector('[class*="bg-primary-600"]');
            if (el) {
                el.scrollIntoView({ block: 'center', behavior: 'instant' });
            }
        }

        // After Alpine is fully done, restore scroll
        var restored = false;
        function tryRestore() {
            if (restored) return;
            if (restore()) {
                restored = true;
            }
        }

        // Watch for DOM changes (Alpine expanding menus)
        var mutCount = 0;
        var obs = new MutationObserver(function() {
            mutCount++;
            tryRestore();
            if (mutCount > 20) obs.disconnect();
        });
        obs.observe(nav, { childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'class'] });

        // Multiple timing attempts
        [0, 50, 150, 300, 500, 800, 1200].forEach(function(ms) {
            setTimeout(function() {
                tryRestore();
                // Final attempt: if no saved position, scroll to active
                if (ms === 1200) {
                    obs.disconnect();
                    if (!restored) scrollToActive();
                }
            }, ms);
        });
    })();
    </script>
</div>
