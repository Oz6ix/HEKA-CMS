@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content - Diagnosis List --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Diagnosis Records</h1>
                    <p class="mt-1 text-sm text-slate-500">Clinical diagnosis and treatment history.</p>
                </div>
                <a href="{{ url($url_prefix . '/diagnosis/create/'.$aid) }}"
                   class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Add Diagnosis
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-12">#</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Diagnosis</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Treatment</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Entered By</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Time</th>
                                <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @if(isset($items) && sizeof($items) > 0)
                                @foreach($items as $index => $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 pl-6 pr-3 text-sm text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-3 py-3 text-sm font-medium text-slate-900">{{ $item['diagnosis'] }}</td>
                                    <td class="px-3 py-3">
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">{{ $item['treatment']['title'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-sm text-slate-600">{{ $item->user->name }}</td>
                                    <td class="px-3 py-3 text-sm text-slate-500">{{ $item['checkup_at'] }}</td>
                                    <td class="px-3 py-3 pr-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ url($url_prefix . '/diagnosis/view/'.$item->id) }}" class="text-slate-400 hover:text-amber-500 transition-colors" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ url($url_prefix . '/diagnosis/edit/'.$item['id']) }}" class="text-slate-400 hover:text-primary-600 transition-colors" title="Edit">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                            <a href="javascript:;" onclick="delete_record('{{ url($url_prefix . '/diagnosis/delete/'.$item->id) }}')" class="text-slate-400 hover:text-red-500 transition-colors" title="Delete">
                                                <i class="fas fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-slate-400">
                                            <i class="fas fa-stethoscope text-3xl mb-3"></i>
                                            <p class="text-sm font-medium">No diagnosis records</p>
                                            <p class="text-xs mt-1">Add a diagnosis to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar - Patient & Appointment Details --}}
        <div class="space-y-6">
            @foreach ($patient_details as $detail)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Appointment Details</h3>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Case No.</span>
                        <span class="font-medium text-slate-900">{{ $detail->case_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Date</span>
                        <span class="font-medium text-slate-900">{{ date('M d, Y', strtotime($detail->appointment_date)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Doctor</span>
                        <span class="font-medium text-slate-900">{{ $detail->staff_doctor->name }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Patient Details</h3>
                </div>
                <div class="p-5">
                    <div class="flex flex-col items-center mb-4">
                        @if($detail->patient->patient_photo)
                            <img class="h-20 w-20 rounded-full object-cover ring-2 ring-slate-200" 
                                 src="{{ url('public/uploads/patient/'.$detail->patient->patient_folder_name.'/'.$detail->patient->patient_photo) }}" alt="Patient photo">
                        @else
                            <div class="h-20 w-20 rounded-full bg-primary-100 flex items-center justify-center ring-2 ring-slate-200">
                                <span class="text-2xl font-bold text-primary-600">{{ strtoupper(substr($detail->patient->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <h4 class="mt-2 font-semibold text-slate-900">{{ $detail->patient->name }}</h4>
                        <span class="text-xs text-slate-500">{{ $detail->patient->patient_code }}</span>
                    </div>
                    <div class="space-y-3 text-sm border-t border-slate-100 pt-3">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Phone</span>
                            <span class="font-medium text-slate-900">{{ $detail->patient->phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Email</span>
                            <span class="font-medium text-slate-900">{{ $detail->patient->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Age</span>
                            <span class="font-medium text-slate-900">{{ $detail->patient->age_year }} Y {{ $detail->patient->age_month }} M</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Guardian</span>
                            <span class="font-medium text-slate-900">{{ $detail->patient->guardian_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
