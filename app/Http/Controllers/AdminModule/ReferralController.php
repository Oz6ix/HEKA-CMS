<?php
namespace App\Http\Controllers\AdminModule;

use App\Models\Referral;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Referrals";
        $this->page_heading = "Referrals";
        $this->heading_icon = "fa-share-from-square";
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon
        ];
    }

    public function index()
    {
        $referrals = Referral::where('delete_status', 0)
            ->with('patient', 'creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.admin_module.referrals.index', compact('referrals'))
            ->with($this->page_info);
    }

    public function create()
    {
        $patients = Patient::where('delete_status', 0)->orderBy('first_name')->get();
        $doctors = Staff::where('delete_status', 0)->where('staff_type', 'doctor')->orderBy('first_name')->get();

        return view('backend.admin_module.referrals.create', compact('patients', 'doctors'))
            ->with($this->page_info);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|integer',
            'referral_type' => 'required|string|in:incoming,outgoing',
            'referred_by' => 'nullable|string|max:255',
            'referred_to' => 'nullable|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:2000',
            'referral_date' => 'required|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $data['created_by'] = auth()->id();
        Referral::create($data);

        generate_log('Referral created');
        return redirect($this->url_prefix . '/referrals')->with('message', 'Referral recorded.');
    }

    public function update_status(Request $request, $id)
    {
        $referral = Referral::findOrFail($id);
        $referral->update(['status' => $request->status]);

        generate_log('Referral status updated', $id);
        return redirect($this->url_prefix . '/referrals')->with('message', 'Referral status updated.');
    }

    public function destroy($id)
    {
        Referral::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Referral deleted', $id);
        return redirect($this->url_prefix . '/referrals')->with('message', 'Referral deleted.');
    }
}
