@extends('backend.layouts.modern')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Patients</h1>
        <p class="text-slate-500 mt-1">Manage patient records and medical history.</p>
    </div>
    <div class="flex gap-4">
        <a href="{{ route('patient.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all">
            <i class="fas fa-plus mr-2"></i> Add Patient
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="border-b border-slate-200 px-6 py-4 flex items-center justify-between bg-slate-50">
        <h3 class="text-base font-semibold leading-6 text-slate-900">All Patients</h3>
        <!-- Potential Search/Filter Integration here -->
        <div class="relative">
             <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="customSearch" placeholder="Search patients..." class="pl-8 pr-4 py-1.5 text-sm border border-slate-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200" id="patientTable">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-16">
                         ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Patient Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-slate-500">
                        {{ $item->patient_code }}
                    </td>
                     <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($item->patient_photo)
                                <img src="{{ asset('uploads/patient/'.$item->patient_folder_name.'/'.$item->patient_photo) }}" 
                                     alt="{{ $item->name }}" 
                                     class="h-9 w-9 rounded-full object-cover mr-3 ring-1 ring-slate-200">
                            @else
                                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 mr-3 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-slate-900">{{ $item->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->age_year }} Y / {{ $item->gender }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                         <div class="flex flex-col">
                            <span class="text-sm text-slate-700"><i class="fas fa-phone-alt text-slate-400 mr-1.5 text-xs"></i> {{ $item->phone }}</span>
                            @if($item->email)
                            <span class="text-xs text-slate-500 mt-0.5"><i class="fas fa-envelope text-slate-400 mr-1.5 text-xs"></i> {{ $item->email }}</span>
                            @endif
                        </div>
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
                             <a href="{{ url($url_prefix . '/emr/workbench/'.$item->id ) }}" class="text-slate-400 hover:text-indigo-600" title="View Medical Record">
                                <i class="fas fa-clipboard-list"></i>
                            </a>
                             <a href="{{ url($url_prefix . '/appointment/create/'.$item->id ) }}" class="text-slate-400 hover:text-emerald-600" title="Book Appointment">
                                <i class="fas fa-calendar-plus"></i>
                            </a>
                            <a href="{{ route('patient.edit', $item->id) }}" class="text-slate-400 hover:text-primary-600" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                             @if($item->status == 1)
                                <a href="{{ url($url_prefix . '/patient/deactivate/'.$item->id) }}" class="text-slate-400 hover:text-amber-600" title="Deactivate">
                                    <i class="fas fa-ban"></i>
                                </a>
                            @else
                                <a href="{{ url($url_prefix . '/patient/activate/'.$item->id) }}" class="text-slate-400 hover:text-green-600" title="Activate">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            @endif
                            <!-- Delete action usually requires a form or special handling to avoid accidental gets, keeping simple link for now as per legacy but ideally should be form -->
                            <a href="javascript:void(0);" onclick="if(confirm('Are you sure?')) window.location.href='{{ url($url_prefix . '/patient/delete/'.$item->id) }}'" class="text-slate-400 hover:text-red-600" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                <i class="fas fa-users text-slate-400 text-xl"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-semibold text-slate-900">No patients found</h3>
                            <p class="mt-1 text-sm text-slate-500">Get started by adding a new patient record.</p>
                            <div class="mt-6">
                                <a href="{{ route('patient.create') }}" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                                    <i class="fas fa-plus mr-2"></i> Add Patient
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
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Simple client-side search for the table
        $("#customSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#patientTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection
