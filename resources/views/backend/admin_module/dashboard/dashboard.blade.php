@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome back, here's what's happening today.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                {{ date('M d, Y') }}
            </span>
            <a href="{{ route('appointment.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <i class="fas fa-plus mr-2"></i> New Appointment
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <!-- Patients Card -->
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all duration-300 group">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-blue-50 opacity-50 blur-xl group-hover:bg-blue-100 transition-colors"></div>
            <dt>
                <div class="rounded-md bg-blue-100 p-3 w-fit text-blue-600">
                    <i class="fas fa-user-injured text-xl"></i>
                </div>
                <p class="mt-4 truncate text-sm font-medium text-gray-500">Total Patients</p>
            </dt>
            <dd class="flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($patient_count) }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                    <i class="fas fa-arrow-up self-center flex-shrink-0 text-green-500 mr-1"></i>
                    All time
                </p>
            </dd>
        </div>

        <!-- Today's Appointments Card -->
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:ring-purple-600/50 transition-all duration-300 group">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-purple-50 opacity-50 blur-xl group-hover:bg-purple-100 transition-colors"></div>
            <dt>
                <div class="rounded-md bg-purple-100 p-3 w-fit text-purple-600">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
                <p class="mt-4 truncate text-sm font-medium text-gray-500">Today's Appointments</p>
            </dt>
            <dd class="flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($today_appointments_count) }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-purple-600">
                    <i class="fas fa-clock self-center flex-shrink-0 text-purple-400 mr-1"></i>
                    Today
                </p>
            </dd>
        </div>

        <!-- Tomorrow's Appointments Card -->
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:ring-indigo-600/50 transition-all duration-300 group">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-indigo-50 opacity-50 blur-xl group-hover:bg-indigo-100 transition-colors"></div>
            <dt>
                <div class="rounded-md bg-indigo-100 p-3 w-fit text-indigo-600">
                    <i class="fas fa-calendar-plus text-xl"></i>
                </div>
                <p class="mt-4 truncate text-sm font-medium text-gray-500">Tomorrow's Appointments</p>
            </dt>
            <dd class="flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($tomorrow_appointments_count) }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-indigo-600">
                    <i class="fas fa-arrow-right self-center flex-shrink-0 text-indigo-400 mr-1"></i>
                    Tomorrow
                </p>
            </dd>
        </div>

        <!-- Staff Card -->
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all duration-300 group">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-emerald-50 opacity-50 blur-xl group-hover:bg-emerald-100 transition-colors"></div>
            <dt>
                <div class="rounded-md bg-emerald-100 p-3 w-fit text-emerald-600">
                    <i class="fas fa-user-md text-xl"></i>
                </div>
                <p class="mt-4 truncate text-sm font-medium text-gray-500">Medical Staff</p>
            </dt>
            <dd class="flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($staff_count) }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-emerald-600">
                    Active
                </p>
            </dd>
        </div>

        <!-- Inventory Card -->
        <div class="relative overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all duration-300 group">
            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-amber-50 opacity-50 blur-xl group-hover:bg-amber-100 transition-colors"></div>
            <dt>
                <div class="rounded-md bg-amber-100 p-3 w-fit text-amber-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
                <p class="mt-4 truncate text-sm font-medium text-gray-500">Inventory Items</p>
            </dt>
            <dd class="flex items-baseline pb-1 sm:pb-2">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($inventory_stock_count) }}</p>
                <p class="ml-2 flex items-baseline text-sm font-semibold text-gray-500">
                    In Stock
                </p>
            </dd>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 p-6">
             <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Appointment Trends</h3>
                <span class="text-xs text-gray-500">Last 7 Days</span>
            </div>
            <div id="appointmentChart" class="w-full h-80"></div>
        </div>

        <!-- Calendar Appointment Viewer -->
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden flex flex-col">
            <!-- Calendar Header -->
            <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-600 to-primary-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-white">Appointments</h3>
                    <div class="relative">
                        <input type="date" id="calendarDatePicker"
                            value="{{ date('Y-m-d') }}"
                            class="appearance-none bg-white/20 text-white text-sm font-medium rounded-lg border border-white/30 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer [color-scheme:dark]"
                        >
                    </div>
                </div>
                <div class="mt-2 flex items-baseline gap-3">
                    <p class="text-3xl font-bold text-white" id="calendarCount">{{ $today_appointments_count }}</p>
                    <p class="text-sm text-primary-100" id="calendarDateLabel">Today &middot; {{ date('M d') }}</p>
                </div>
            </div>

            <!-- Quick Date Buttons -->
            <div class="px-4 py-2.5 border-b border-gray-100 flex gap-2 bg-gray-50/50">
                <button type="button" onclick="loadDate('{{ date('Y-m-d') }}')" class="cal-btn rounded-full px-3 py-1 text-xs font-medium bg-primary-100 text-primary-700 hover:bg-primary-200 transition-colors">Today</button>
                <button type="button" onclick="loadDate('{{ date('Y-m-d', strtotime('+1 day')) }}')" class="cal-btn rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Tomorrow</button>
                <button type="button" onclick="loadDate('{{ date('Y-m-d', strtotime('+2 days')) }}')" class="cal-btn rounded-full px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ date('D, M d', strtotime('+2 days')) }}</button>
            </div>

            <!-- Appointment List -->
            <div class="flex-1 overflow-y-auto" id="calendarAppointmentList" style="max-height: 360px;">
                @forelse($today_appointments as $appt)
                <a href="{{ route('appointment.show', $appt->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 group">
                    <div class="flex-shrink-0 h-9 w-9 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 text-xs font-bold">
                        {{ strtoupper(substr($appt->patient->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-700">{{ $appt->patient->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500 truncate">
                            <span class="inline-flex items-center"><i class="fas fa-user-md mr-1 text-gray-400"></i>{{ $appt->staff_doctor->name ?? 'Unknown' }}</span>
                            @if($appt->case_number)
                            <span class="mx-1">&middot;</span>
                            <span class="font-mono">{{ $appt->case_number }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <p class="text-xs font-medium text-gray-700">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('h:i A') }}</p>
                    </div>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">
                        <i class="fas fa-calendar-xmark text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500">No appointments</p>
                    <p class="text-xs text-gray-400 mt-1">No appointments scheduled for today</p>
                    <a href="{{ route('appointment.create') }}" class="mt-3 text-xs font-semibold text-primary-600 hover:text-primary-500">
                        <i class="fas fa-plus mr-1"></i>Book an appointment
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('appointment.create') }}" class="group flex items-center p-4 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all">
            <div class="flex-shrink-0 h-11 w-11 rounded-lg bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600 group-hover:bg-primary-100">
                <i class="fas fa-calendar-plus text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-900">Book Appointment</p>
                <p class="text-xs text-gray-500">Schedule a new visit</p>
            </div>
        </a>
        <a href="{{ route('patient.create') }}" class="group flex items-center p-4 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all">
            <div class="flex-shrink-0 h-11 w-11 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 group-hover:bg-blue-100">
                <i class="fas fa-user-plus text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-900">Register Patient</p>
                <p class="text-xs text-gray-500">Add new patient record</p>
            </div>
        </a>
        <a href="{{ route('rcm.create') }}" class="group flex items-center p-4 rounded-xl bg-white shadow-sm ring-1 ring-gray-900/5 hover:ring-primary-600/50 transition-all">
            <div class="flex-shrink-0 h-11 w-11 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100">
                <i class="fas fa-file-invoice-dollar text-lg"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-900">New Invoice</p>
                <p class="text-xs text-gray-500">Create bill for patient</p>
            </div>
        </a>
    </div>

    <!-- Recent Appointments Table -->
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-5 flex items-center justify-between">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Appointments</h3>
            <a href="{{ route('appointment.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-500">View all <span aria-hidden="true">&rarr;</span></a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Case No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultant</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointment_items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->patient->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->patient->patient_code ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ date('M d, Y', strtotime($item->appointment_date)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                            {{ $item->case_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10 mr-2">Dr.</span>
                                {{ $item->staff_doctor->name ?? 'Unknown' }}
                            </div>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('appointment.show', $item->id) }}" class="text-primary-600 hover:text-primary-900">View</a>
                        </td>
                    </tr>
                    @empty
                     <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No recent appointments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Appointment Trends Chart ---
        var options = {
            series: [{
                name: 'Appointments',
                data: @json($chart_counts ?? [])
            }],
            chart: {
                height: 320,
                type: 'area',
                fontFamily: 'inherit',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                type: 'category',
                categories: @json($chart_dates ?? []),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: true },
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.9, stops: [0, 90, 100] }
            },
            colors: ['#3b82f6'],
            grid: { padding: { top: 10, right: 0, bottom: 0, left: 10 } },
            tooltip: { x: { format: 'dd/MM/yy' } },
        };
        var chart = new ApexCharts(document.querySelector("#appointmentChart"), options);
        chart.render();

        // --- Calendar Appointment Viewer ---
        var picker = document.getElementById('calendarDatePicker');
        picker.addEventListener('change', function () { loadDate(this.value); });
    });

    var ajaxBaseUrl = "{{ url($url_prefix . '/dashboard/appointments-by-date') }}";

    function loadDate(dateStr) {
        var picker = document.getElementById('calendarDatePicker');
        picker.value = dateStr;

        var countEl = document.getElementById('calendarCount');
        var labelEl = document.getElementById('calendarDateLabel');
        var listEl = document.getElementById('calendarAppointmentList');

        // Loading state
        countEl.textContent = '...';
        listEl.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div></div>';

        // Highlight active quick-date button
        document.querySelectorAll('.cal-btn').forEach(function(btn) {
            btn.classList.remove('bg-primary-100', 'text-primary-700');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });

        fetch(ajaxBaseUrl + '/' + dateStr, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }})
        .then(function(res) { return res.json(); })
        .then(function(data) {
            countEl.textContent = data.count;

            // Determine label (Today/Tomorrow/date)
            var today = new Date(); today.setHours(0,0,0,0);
            var sel = new Date(dateStr + 'T00:00:00'); sel.setHours(0,0,0,0);
            var diffDays = Math.round((sel - today) / 86400000);
            if (diffDays === 0) labelEl.textContent = 'Today · ' + data.date_display;
            else if (diffDays === 1) labelEl.textContent = 'Tomorrow · ' + data.date_display;
            else labelEl.textContent = data.date_display;

            if (data.appointments.length === 0) {
                listEl.innerHTML =
                    '<div class="flex flex-col items-center justify-center py-12 px-4 text-center">' +
                        '<div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-3">' +
                            '<i class="fas fa-calendar-xmark text-2xl"></i>' +
                        '</div>' +
                        '<p class="text-sm font-medium text-gray-500">No appointments</p>' +
                        '<p class="text-xs text-gray-400 mt-1">No appointments scheduled for this date</p>' +
                    '</div>';
                return;
            }

            var html = '';
            data.appointments.forEach(function(a) {
                var initials = (a.patient_name || 'U').substring(0, 2).toUpperCase();
                html +=
                    '<a href="' + a.view_url + '" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 group">' +
                        '<div class="flex-shrink-0 h-9 w-9 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 text-xs font-bold">' + initials + '</div>' +
                        '<div class="flex-1 min-w-0">' +
                            '<p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-700">' + a.patient_name + '</p>' +
                            '<p class="text-xs text-gray-500 truncate">' +
                                '<span class="inline-flex items-center"><i class="fas fa-user-md mr-1 text-gray-400"></i>' + a.doctor_name + '</span>' +
                                (a.case_number ? ' <span class="mx-1">&middot;</span> <span class="font-mono">' + a.case_number + '</span>' : '') +
                            '</p>' +
                        '</div>' +
                        '<div class="flex-shrink-0 text-right">' +
                            '<p class="text-xs font-medium text-gray-700">' + a.time + '</p>' +
                        '</div>' +
                    '</a>';
            });
            listEl.innerHTML = html;
        })
        .catch(function(err) {
            listEl.innerHTML = '<div class="text-center py-8 text-sm text-red-500">Failed to load appointments.</div>';
            countEl.textContent = '—';
        });
    }
</script>
@endsection