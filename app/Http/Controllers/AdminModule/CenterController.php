<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\PatientMedicalTest;
use App\Models\PatientPrescription;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class CenterController
 * @package App\Http\Controllers\AdminModule
 */
class CenterController extends Controller
{
    /**
     * CenterController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Center Manage";
        $this->page_heading = "Center Manage";
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
     * List the Admin users.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $items = Center::where('delete_status', 0)->orderBy('id', 'asc')->get();        
        generate_log('Center accessed');
        return view('backend.admin_module.center.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $item = Center::findorFail($id)->toArray();
        generate_log('Center details accessed', $id);
        return view('backend.admin_module.center.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function create()
    {        
        return view('backend.admin_module.center.create')->with($this->page_info);
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
        $item = Center::where('center',$data['center'])->where('delete_status',0)->count();
//dd($item);
        if ($item==0) {
            $validator = Center::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Center::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A Center with same Center name already exists. Please use a different one.');
        generate_log('Center created', $new_record->id);
        return redirect($this->url_prefix . '/center')->with('message', 'Center added.');
    }
	/**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $item = Center::findorFail($id)->toArray();
        return view('backend.admin_module.center.edit', compact('item'))->with($this->page_info);
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
        $item = Center::where('center',$data['center'])->where('delete_status',0)->where('id', '!=', $id)->count();
        if ($item==0) {
            $validator = Center::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Center::findorfail($id);
            $record->update($data);
        } else
            return Redirect:: back()->withInput($request->input())->with('error_message', 'A casualty with same casualty name already exists. Please use a different one.');
        generate_log('Center updated', $id);
        return redirect($this->url_prefix . '/center')->with('message', 'Center updated.');
    }
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = PatientMedicalTest::where('reffered_center_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/center')->with('warning_message', 'There are certain Test associated to this center. You can remove this center only once all the associated Test are removed or their center is changed to a new one.');
            Center::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Center deleted', $id);
        return redirect($this->url_prefix . '/center')->with('message', 'Center deleted.');
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
                     $items = PatientMedicalTest::where('reffered_center_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/center')->with('warning_message', 'There are certain Test associated to this center. You can remove this center only once all the Test are removed or their center is changed to a new one.');
                        Center::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Center deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/center')->with('message', 'Center deleted.');
        } else
            return redirect($this->url_prefix . '/center')->with('error_message', 'Please select at least one center.');
    }

	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function activate($id)
    {
        Center::where('id', $id)->update(['status' => 1]);
        generate_log('Center activated', $id);
        return redirect($this->url_prefix . '/center')->with('message', 'Center activated.');
    }

	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deactivate($id)
    {
        Center::where('id', $id)->update(['status' => 0]);
        generate_log('Center deactivated', $id);
        return redirect($this->url_prefix . '/center')->with('message', 'Center deactivated.');
    }


    /* Custom methods */
	/**
     * Check if Exist the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exists($center, $id = null)
    {
        if ($id == null)
            $items = Center::all()->where('center', $center);
        else
            $items = Center::all()->where('center', $center)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
