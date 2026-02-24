<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\InventoryStock;
/**
 * Class DashboardController
 * @package App\Http\Controllers\AdminModule
 */

class DashboardController extends Controller
{
     /**
     * DashboardController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */

    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Dashboard";
        $this->page_heading = "Dashboard";
        $this->heading_icon = "fa-home";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,];
    }
    /**
     * List the resources.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $Authuser= Auth::user();        
        $admin_user_count = User::where('delete_status',0)->count();
        $staff_count = Staff::where('delete_status',0)->count();
        $patient_count = Patient::where('delete_status',0)->count(); 
        $appointment_count = Appointment::where('delete_status',0)->count();  
        $inventory_stock_count = InventoryStock::where('delete_status',0)->count();  

        // Today & Tomorrow appointment counts
        $today = \Carbon\Carbon::today();
        $tomorrow = \Carbon\Carbon::tomorrow();
        $today_appointments_count = Appointment::where('delete_status', 0)
            ->whereDate('appointment_date', $today)->count();
        $tomorrow_appointments_count = Appointment::where('delete_status', 0)
            ->whereDate('appointment_date', $tomorrow)->count();

        // Today's appointments for calendar viewer default
        $today_appointments = Appointment::with('patient', 'staff_doctor')
            ->where('delete_status', 0)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_date', 'asc')
            ->get();

        $appointment_items = Appointment::with('patient')->with('staff_doctor')->where('delete_status', 0)->orderBy('id', 'desc')->limit('5')->get();

        // Chart Data: Appointments last 7 days
        $appointment_chart_data = Appointment::selectRaw('DATE(appointment_date) as date, count(*) as count')
            ->where('delete_status', 0)
            ->where('appointment_date', '>=', \Carbon\Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        $chart_dates = $appointment_chart_data->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();
        $chart_counts = $appointment_chart_data->pluck('count')->toArray();

        return view('backend.admin_module.dashboard.dashboard', compact(
            'admin_user_count', 
            'staff_count', 
            'patient_count',
            'appointment_items',
            'appointment_count',
            'today_appointments_count',
            'tomorrow_appointments_count',
            'today_appointments',
            'inventory_stock_count',
            'chart_dates',
            'chart_counts'
        ))->with($this->page_info);
    }

    /**
     * Return appointments for a given date (AJAX).
     */
    public function appointmentsByDate($date)
    {
        try {
            $parsed = \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date'], 422);
        }

        $appointments = Appointment::with('patient', 'staff_doctor')
            ->where('delete_status', 0)
            ->whereDate('appointment_date', $parsed)
            ->orderBy('appointment_date', 'asc')
            ->get()
            ->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'patient_name' => $appt->patient->name ?? 'Unknown',
                    'patient_code' => $appt->patient->patient_code ?? 'N/A',
                    'doctor_name' => $appt->staff_doctor->name ?? 'Unknown',
                    'case_number' => $appt->case_number,
                    'appointment_date' => $appt->appointment_date,
                    'time' => \Carbon\Carbon::parse($appt->appointment_date)->format('h:i A'),
                    'view_url' => route('appointment.show', $appt->id),
                ];
            });

        return response()->json([
            'appointments' => $appointments,
            'count' => $appointments->count(),
            'date_display' => $parsed->format('M d, Y'),
        ]);
    }
}
