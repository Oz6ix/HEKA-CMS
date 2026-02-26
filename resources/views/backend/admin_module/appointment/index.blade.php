@extends('backend.layouts.modern')

@section('title', 'Appointments')

@section('content')
<div class="space-y-6" x-data="appointmentList()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Appointments</h1>
            <p class="text-slate-500 mt-1">Manage patient appointments and schedules</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- View Toggle -->
            <div class="bg-white rounded-lg border border-slate-200 p-1 flex shadow-sm">
                <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-sm font-medium rounded-md transition-all">
                    <i class="fas fa-list mr-1"></i> List
                </button>
                <button @click="viewMode = 'calendar'; $nextTick(() => initCalendar())" :class="viewMode === 'calendar' ? 'bg-primary-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1.5 text-sm font-medium rounded-md transition-all">
                    <i class="fas fa-calendar-alt mr-1"></i> Calendar
                </button>
            </div>

            <!-- Bulk Delete -->
            <button 
                x-show="selectedItems.length > 0"
                @click="deleteSelected()"
                class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                style="display: none;">
                <i class="fas fa-trash-alt"></i>
                <span x-text="'Delete (' + selectedItems.length + ')'"></span>
            </button>

            <a href="{{ url($url_prefix . '/appointment/create') }}" class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm shadow-primary-600/20">
                <i class="fas fa-plus"></i>
                <span>New Appointment</span>
            </a>
        </div>
    </div>

    <!-- Search/Filter (shown in list mode) -->
    <div x-show="viewMode === 'list'" class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form action="{{ url($url_prefix . '/appointment/appointment_search') }}" method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <!-- Patient Select -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Patient</label>
                <select name="patient_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Patients</option>
                    @foreach($patient_item as $data)
                        <option value="{{ $data->id }}" {{ (isset($selected_patient_id) && $data->id == $selected_patient_id) ? 'selected' : '' }}>
                            {{ $data->name }} ({{ $data->patient_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Case Number -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Case Number</label>
                <select name="case_number" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Cases</option>
                    @foreach($appointment_item as $data)
                        <option value="{{ $data->case_number }}" {{ (isset($selected_case_number) && $data->case_number == $selected_case_number) ? 'selected' : '' }}>
                            {{ $data->case_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Doctor -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Doctor</label>
                <select name="doctor_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Doctors</option>
                    @foreach($doctor_item as $data)
                        <option value="{{ $data->id }}" {{ (isset($selected_doctor_id) && $data->id == $selected_doctor_id) ? 'selected' : '' }}>
                            {{ $data->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date & Search Button -->
            <div class="flex gap-2 items-end">
                <div class="w-full">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                    <input type="date" name="appointment_date" value="{{ $selected_appointment_date ?? '' }}" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- List View -->
    <div x-show="viewMode === 'list'" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-4 w-10">
                            <input type="checkbox" @change="toggleAll" x-model="selectAll" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Patient</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Case No</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Doctor</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4">
                            <input type="checkbox" value="{{ $item->id }}" x-model="selectedItems" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        </td>
                        <td class="p-4 text-sm text-slate-500">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <div class="font-medium text-slate-800">{{ $item->patient->name }}</div>
                            <div class="text-xs text-slate-500">{{ $item->patient->phone }}</div>
                        </td>
                        <td class="p-4 text-sm text-slate-600">{{ $item->case_number }}</td>
                        <td class="p-4 text-sm text-slate-600">{{ $item->staff_doctor->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-slate-600">{{ $item->appointment_date }}</td>
                        <td class="p-4">
                            @if($item->status == 1)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Open</span>
                            @elseif($item->status == 2)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Closed</span>
                            @endif
                        </td>
                        <td class="p-4 text-right space-x-2">
                             <!-- Print Modal Trigger -->
                             <button @click="openPrintModal('{{ $item->id }}')" class="p-1 text-slate-400 hover:text-primary-600 transition-colors" title="Print Details">
                                <i class="fas fa-print"></i>
                            </button>

                            <!-- View -->
                            <a href="{{ url($url_prefix . '/appointment/view/'.$item->id) }}" class="p-1 text-slate-400 hover:text-primary-600 transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- Edit -->
                            <a href="{{ url($url_prefix . '/appointment/edit/'.$item->id) }}" class="p-1 text-slate-400 hover:text-blue-600 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete -->
                            <button @click="deleteItem('{{ url($url_prefix . '/appointment/delete/'.$item->id) }}')" class="p-1 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-500">
                            No appointments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Calendar View -->
    <div x-show="viewMode === 'calendar'" x-cloak class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Legend -->
        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50 flex items-center gap-5 text-xs">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span> Open</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500"></span> Cancelled</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-500"></span> Closed</span>
        </div>
        <div class="p-4">
            <div id="fullCalendar"></div>
        </div>
    </div>

     <!-- Print Modal -->
     <div x-show="showPrintModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <!-- Backdrop -->
        <div x-show="showPrintModal" @click="showPrintModal = false" class="fixed inset-0 bg-black/40 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Content -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showPrintModal" @click.stop class="relative z-10 bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col">
                <!-- Scrollable print area -->
                <div class="overflow-y-auto flex-1 px-6 pt-6 pb-4">
                    <div id="print-content">
                        <div class="flex justify-center py-10">
                            <i class="fas fa-circle-notch fa-spin text-4xl text-primary-500"></i>
                        </div>
                    </div>
                </div>
                <!-- Fixed button bar -->
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-3 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" @click="showPrintModal = false" class="px-4 py-2 rounded-lg border border-slate-300 bg-white text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">
                        Close
                    </button>
                    <button type="button" onclick="printDiv('print-content')" class="px-5 py-2 rounded-lg bg-primary-600 text-white text-sm font-medium hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
    /* FullCalendar custom styling */
    #fullCalendar .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: #1e293b; }
    #fullCalendar .fc-button { background-color: #f1f5f9 !important; border: 1px solid #e2e8f0 !important; color: #475569 !important; font-weight: 500 !important; font-size: 0.8rem !important; text-transform: capitalize !important; }
    #fullCalendar .fc-button:hover { background-color: #e2e8f0 !important; }
    #fullCalendar .fc-button-active { background-color: #3b82f6 !important; color: #fff !important; border-color: #3b82f6 !important; }
    #fullCalendar .fc-daygrid-day-number { font-size: 0.8rem; color: #64748b; padding: 4px 8px; }
    #fullCalendar .fc-daygrid-day.fc-day-today { background-color: #eff6ff !important; }
    #fullCalendar .fc-event { cursor: pointer; border-radius: 4px; padding: 2px 4px; font-size: 0.75rem; }
    #fullCalendar .fc-col-header-cell-cushion { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; }
    #fullCalendar .fc-scrollgrid { border-color: #e2e8f0 !important; }
    #fullCalendar td, #fullCalendar th { border-color: #e2e8f0 !important; }
</style>

<script>
    var calendarInstance = null;

    function appointmentList() {
        return {
            viewMode: 'list',
            selectedItems: [],
            selectAll: false,
            showPrintModal: false,

            toggleAll() {
                this.selectedItems = this.selectAll ? @json($items->pluck('id')) : [];
            },

            deleteSelected() {
                if (confirm('Are you sure you want to delete the selected appointments?')) {
                    const ids = this.selectedItems.join(',');
                    window.location.href = "{{ url($url_prefix . '/appointment/delete_multiple') }}/" + ids;
                }
            },

            deleteItem(url) {
                if (confirm('Are you sure you want to delete this appointment?')) {
                    window.location.href = url;
                }
            },

             openPrintModal(id) {
                this.showPrintModal = true;
                const container = document.getElementById('print-content');
                container.innerHTML = '<div class="flex justify-center py-10"><i class="fas fa-circle-notch fa-spin text-4xl text-primary-500"></i></div>';
                
                fetch(`{{ url($url_prefix . '/appointment/ajax_appt_print_data') }}/${id}`)
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        container.innerHTML = '<div class="text-red-500 text-center">Failed to load print data.</div>';
                    });
            }

        }
    }

    function initCalendar() {
        if (calendarInstance) {
            calendarInstance.render();
            return;
        }

        var calendarEl = document.getElementById('fullCalendar');
        if (!calendarEl) return;

        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            height: 'auto',
            events: {
                url: '{{ url($url_prefix . "/appointment/calendar-events") }}',
                failure: function() {
                    console.error('Failed to load appointment events');
                }
            },
            eventClick: function(info) {
                var url = info.event.extendedProps.view_url;
                if (url) window.location.href = url;
            },
            eventDidMount: function(info) {
                // Tooltip on hover
                var props = info.event.extendedProps;
                info.el.title = props.patient + ' — Dr. ' + props.doctor + (props.case_number ? ' (Case: ' + props.case_number + ')' : '');
            },
            dayMaxEvents: 3,
            navLinks: true,
            editable: false,
        });
        calendarInstance.render();
    }
    
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload(); // Reload to restore scripts/state
    }
</script>
@endsection
