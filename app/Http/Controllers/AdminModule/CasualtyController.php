<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Casualty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class CasualtyController
 * @package App\Http\Controllers\AdminModule
 */
class CasualtyController extends Controller
{
    /**
     * CasualtyController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Casualty Manage";
        $this->page_heading = "Casualty Manage";
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
     * List the Casualty.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $items = Casualty::where('delete_status', 0)->orderBy('casualty', 'asc')->get();        
        generate_log('Casualty accessed');
        return view('backend.admin_module.casualty.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $item = Casualty::findorFail($id)->toArray();
        generate_log('Casualty details accessed', $id);
        return view('backend.admin_module.casualty.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function create()
    {        
        return view('backend.admin_module.casualty.create')->with($this->page_info);
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
        $item = Casualty::where('casualty',$data['casualty'])->where('delete_status',0)->count();
        if ($item==0) {
            $validator = Casualty::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Casualty::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A Casualty with same Casualty name already exists. Please use a different one.');
        generate_log('Casualty created', $new_record->id);
        return redirect($this->url_prefix . '/casualty')->with('message', 'Casualty added.');
    }
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $item = Casualty::findorFail($id)->toArray();
        return view('backend.admin_module.casualty.edit', compact('item'))->with($this->page_info);
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
        $item = Casualty::where('casualty',$data['casualty'])->where('delete_status',0)->where('id', '!=', $id)->count();
        if ($item==0) {
            $validator = Casualty::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Casualty::findorfail($id);
            $record->update($data);
        } else
            return Redirect:: back()->withInput($request->input())->with('error_message', 'A casualty with same casualty name already exists. Please use a different one.');
        generate_log('Casualty updated', $id);
        return redirect($this->url_prefix . '/casualty')->with('message', 'Casualty updated.');
    }
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = Appointment::where('casualty_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/casualty')->with('warning_message', 'There are certain Appointments associated to this casualty. You can remove this casualty only once all the associated staffs are removed or their casualty is changed to a new one.');
            Casualty::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Casualty deleted', $id);
        return redirect($this->url_prefix . '/casualty')->with('message', 'Casualty deleted.');
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
                     $items = Appointment::where('casualty_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/casualty')->with('warning_message', 'There are certain appointments associated to this casualty. You can remove this casualty only once all the associated appointments are removed or their casualty is changed to a new one.');
                        Casualty::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Casualty deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/casualty')->with('message', 'Casualty deleted.');
        } else
            return redirect($this->url_prefix . '/casualty')->with('error_message', 'Please select at least one casualty.');
    }
	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        Casualty::where('id', $id)->update(['status' => 1]);
        generate_log('Casualty activated', $id);
        return redirect($this->url_prefix . '/casualty')->with('message', 'Casualty activated.');
    }
	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        Casualty::where('id', $id)->update(['status' => 0]);
        generate_log('Casualty deactivated', $id);
        return redirect($this->url_prefix . '/casualty')->with('message', 'casualty deactivated.');
    }
    /* Custom methods */
	/**
     * Check if Exist the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exists($casualty, $id = null)
    {
        if ($id == null)
            $items = Casualty::all()->where('Casualty', $casualty);
        else
            $items = Casualty::all()->where('Casualty', $casualty)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
