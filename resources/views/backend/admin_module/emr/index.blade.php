@extends('backend.layouts.modern')

@section('content')
<div class="space-y-5">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Doctor Workbench</h1>
            <p class="text-slate-500 mt-1">Manage your patient queue and medical records.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ url($url_prefix . '/emr/list') }}" class="flex flex-wrap items-end gap-4">
            <!-- Date Filter -->
            <div class="flex-shrink-0">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
                <div class="flex items-center gap-2">
                    <a href="{{ url($url_prefix . '/emr/list?date=' . date('Y-m-d')) }}"
                       class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-semibold transition-all {{ $filterDate == date('Y-m-d') ? 'bg-primary-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Today
                    </a>
                    <a href="{{ url($url_prefix . '/emr/list?date=' . date('Y-m-d', strtotime('+1 day'))) }}"
                       class="inline-flex items-center rounded-lg px-3 py-2 text-xs font-semibold transition-all {{ $filterDate == date('Y-m-d', strtotime('+1 day')) ? 'bg-primary-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Tomorrow
                    </a>
                    <input type="date" name="date" value="{{ $filterDate }}"
                           class="rounded-lg border-slate-300 text-sm py-2 px-3 focus:ring-primary-500 focus:border-primary-500"
                           onchange="this.form.submit()">
                </div>
            </div>

            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Search Patient</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name, patient code, phone..."
                           class="w-full rounded-lg border-slate-300 text-sm py-2 pl-9 pr-3 focus:ring-primary-500 focus:border-primary-500"
                           title="Search across ALL patients and dates">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex-shrink-0">
                <button type="submit" class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                    <i class="fas fa-filter mr-1.5"></i> Filter
                </button>
                @if(!empty($search) || $filterDate != date('Y-m-d'))
                <a href="{{ url($url_prefix . '/emr/list') }}" class="ml-2 inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center">
                <i class="fas fa-users text-slate-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $items->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Patients</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-amber-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
                <p class="text-xs text-slate-500 font-medium">Pending</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-5 py-4 flex items-center gap-4">
            <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-emerald-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-600">{{ $diagnosedCount }}</p>
                <p class="text-xs text-slate-500 font-medium">Diagnosed</p>
            </div>
        </div>
    </div>

    <!-- Patient Queue Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4 flex items-center justify-between bg-slate-50">
            <h3 class="text-base font-semibold leading-6 text-slate-900">
                @if(!empty($search))
                    <i class="fas fa-search mr-1.5 text-primary-500"></i>
                    Search Results for "{{ $search }}"
                    <span class="text-xs font-normal text-slate-400 ml-2">(all dates)</span>
                @else
                    <i class="fas fa-calendar-day mr-1.5 text-primary-500"></i>
                    Patient Queue — {{ date('D, M d, Y', strtotime($filterDate)) }}
                @endif
            </h3>
            <span class="text-xs text-slate-500">{{ $items->count() }} patient(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-12">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Patient Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Appointment Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Doctor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($items as $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                            {{ $loop->iteration }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 mr-3 group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ $item->patient->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">Code: {{ $item->patient->patient_code ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <i class="far fa-clock mr-1 text-slate-400"></i>
                            {{ date('d M Y', strtotime($item->appointment_date)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            Dr. {{ $item->staff_doctor->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->diagnosis_status == 1)
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    <i class="fas fa-check-circle mr-1"></i> Diagnosed
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                    <i class="fas fa-hourglass-half mr-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ url($url_prefix . '/emr/workbench/' . $item->id) }}" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all">
                                <i class="fas fa-stethoscope mr-1.5"></i>
                                {{ $item->diagnosis_status == 1 ? 'View Record' : 'Start Consult' }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <i class="fas fa-user-check text-slate-400 text-xl"></i>
                                </div>
                                <h3 class="mt-2 text-sm font-semibold text-slate-900">No patients in queue</h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    @if(!empty($search))
                                        No patients match "{{ $search }}" for this date.
                                    @else
                                        No appointments found for {{ date('M d, Y', strtotime($filterDate)) }}.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
