<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\RadiologyCategory;
use App\Models\Radiology;
use App\Models\RadiologyParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingRadiologyController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Radiology";
        $this->page_heading = "Radiology";
        $this->heading_icon = "fa-cogs";
        $this->directory_radiology = "radiology";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_radiology' => $this->directory_radiology];
    }
    


    public function index()
    {
        $items = Radiology::where('delete_status', 0)->with('radiology_category')->orderBy('id', 'desc')->get();
    
        generate_log('Radiology accessed');
        return view('backend.admin_module.radiology.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = Radiology::findorFail($id)->toArray();
        generate_log('Radiology details accessed', $id);
        return view('backend.admin_module.radiology.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $radiology_category = RadiologyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $radiology_category=collect($radiology_category)->toArray(); 
        $code=generate_radiology_code();   
        return view('backend.admin_module.radiology.create',compact('radiology_category','code'))->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();
        //$data['code']=generate_radiology_code();         
        $validator = Radiology::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $new_record = Radiology::create($data);
       

        if(isset($request->parameters) || !empty($request->parameters) )
        {
            foreach ($request->parameters as $index => $value)
            {
            if($request->parameters[$index]['parameter_name']!=null)
            {
                $parameters=RadiologyParameter::create([
                'radiology_id' => $new_record->id,
                'parameter_name' => $request->parameters[$index]['parameter_name'], 
                'range' => $request->parameters[$index]['range'],  
                'unit' => $request->parameters[$index]['unit'], 
            ]); 
            }
            }
        }

        generate_log('Radiology created', $new_record->id);
        return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology added.');
    }



    public function edit($id)
    {
        $item = Radiology::with('radiology_category')->findorFail($id)->toArray();
        $radiology_category = RadiologyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $radiology_parameters = RadiologyParameter::where('delete_status', 0)
                                                ->where('radiology_id', $id)
                                                ->orderBy('id', 'asc')
                                                ->get();
        $radiology_category=collect($radiology_category)->toArray();      
        return view('backend.admin_module.radiology.edit', compact('item','radiology_category','radiology_parameters'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        //dd($request);
        $data = $request->all(); 
        $id = $data['id'];        
        $validator = Radiology::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $record = Radiology::findorfail($id);
        $record->update($data);  
        $updateRows = RadiologyParameter::where('radiology_id', $id)->update(['delete_status' => 1]);

        if(isset($request->parameters) || !empty($request->parameters) )
        {
            foreach ($request->parameters as $index => $value)
            {
            if($request->parameters[$index]['parameter_name']!=null)
            {
            $parameters=RadiologyParameter::updateOrCreate(
                ['id' => $request->parameters[$index]['parameter_id'],
                ],
                [
                    'radiology_id' => $id,
                    'parameter_name' => $request->parameters[$index]['parameter_name'], 
                    'range' => $request->parameters[$index]['range'],  
                    'unit' => $request->parameters[$index]['unit'], 
                    'delete_status' => 0
                ]); 
            }
            }
        }  
        
        
        generate_log('Radiology updated', $id);
        return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology updated.');
    }


    public function destroy($id)
    {        
        Radiology::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Radiology deleted', $id);
        return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {                     
                    Radiology::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Radiology deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology deleted.');
        } else
            return redirect($this->url_prefix . '/radiologys')->with('error_message', 'Please select at least one inventory item master.');
    }


    public function activate($id)
    {
        Radiology::where('id', $id)->update(['status' => 1]);
        generate_log('Radiology activated', $id);
        return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology activated.');
    }


    public function deactivate($id)
    {
        Radiology::where('id', $id)->update(['status' => 0]);
        generate_log('Radiology deactivated', $id);
        return redirect($this->url_prefix . '/radiologys')->with('message', 'Radiology deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = Radiology::all()->where('title', $name);
        else
            $items = Radiology::all()->where('title', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /*********************  name duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = Radiology::all()->where('title',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /*********************  name  duplicate check End*******************************/ 


}
