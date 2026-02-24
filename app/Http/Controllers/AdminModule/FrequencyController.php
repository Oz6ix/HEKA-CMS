<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\PatientPrescription;
use App\Models\Frequency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class FrequencyController
 * @package App\Http\Controllers\AdminModule
 */

class FrequencyController extends Controller
{
    /**
     * FrequencyController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Frequency Manage";
        $this->page_heading = "Frequency Manage";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
/*     public function appointment_manage()
    {        
       
        generate_log('Appointment manage list accessed');
        return view('backend.admin_module.appointment_manage.appointment_manage')->with($this->page_info);
    }
 */
    /**
     * List the resources.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $items = Frequency::where('delete_status', 0)->orderBy('frequency', 'asc')->get();        
        generate_log('Frequency accessed');
        return view('backend.admin_module.frequency.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $item = Frequency::findorFail($id)->toArray();
        generate_log('Frequency details accessed', $id);
        return view('backend.admin_module.frequency.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function create()
    {        
        return view('backend.admin_module.frequency.create')->with($this->page_info);
    }
	/**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();               
        $item = Frequency::where('frequency',$data['frequency'])->where('delete_status',0)->count();
//dd($item);
        if ($item==0) {
            $validator = frequency::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Frequency::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A frequency with same frequency name already exists. Please use a different one.');
        generate_log('Frequency created', $new_record->id);
        return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency added.');
    }
	/**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $item = Frequency::findorFail($id)->toArray();
        return view('backend.admin_module.frequency.edit', compact('item'))->with($this->page_info);
    }

	/**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        $item = Frequency::where('frequency',$data['frequency'])->where('delete_status',0)->where('id', '!=', $id)->count();
        if ($item==0) {
            $validator = Frequency::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Frequency::findorfail($id);
            $record->update($data);
        } else
            return Redirect:: back()->withInput($request->input())->with('error_message', 'A casualty with same casualty name already exists. Please use a different one.');
        generate_log('Frequency updated', $id);
        return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency updated.');
    }

	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $items = PatientPrescription::where('frequency_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/frequency')->with('warning_message', 'There are certain prescription associated to this Frequency. You can remove this Frequency only once all the associated prescriptions are removed or their Frequency is changed to a new one.');
            Frequency::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Frequency deleted', $id);
        return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency deleted.');
    }
	/**
     * Remove the specified resources from storage.
     * @param int[] $ids An array of integer objects.
     * @return \Illuminate\Http\Response
     */
    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = PatientPrescription::where('frequency_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/frequency')->with('warning_message', 'There are certain prescription associated to this Frequency. You can remove this Frequency only once all the prescriptions are removed or their Frequency is changed to a new one.');
                        Frequency::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Frequency deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency deleted.');
        } else
            return redirect($this->url_prefix . '/frequency')->with('error_message', 'Please select at least one Frequency.');
    }
	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        Frequency::where('id', $id)->update(['status' => 1]);
        generate_log('Frequency activated', $id);
        return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency activated.');
    }
	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        Frequency::where('id', $id)->update(['status' => 0]);
        generate_log('Frequency deactivated', $id);
        return redirect($this->url_prefix . '/frequency')->with('message', 'Frequency deactivated.');
    }


    /* Custom methods */
	/**
     * Check if Exist the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exists($frequency, $id = null)
    {
        if ($id == null)
            $items = Frequency::all()->where('frequency', $frequency);
        else
            $items = Frequency::all()->where('frequency', $frequency)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
