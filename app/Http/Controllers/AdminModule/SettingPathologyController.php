<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\PathologyCategory;
use App\Models\Pathology;
use App\Models\PathologyParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use File;

class SettingPathologyController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Pathology";
        $this->page_heading = "Pathology";
        $this->heading_icon = "fa-cogs";
        $this->directory_pathology = "pathology";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_pathology' => $this->directory_pathology];
    }
    


    public function index()
    {
        $items = Pathology::where('delete_status', 0)->with('pathology_category')->orderBy('id', 'desc')->get();
    
        generate_log('Pathology accessed');
        return view('backend.admin_module.pathology.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = Pathology::findorFail($id)->toArray();
        generate_log('Pathology details accessed', $id);
        return view('backend.admin_module.pathology.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $pathology_category = PathologyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $pathology_category=collect($pathology_category)->toArray(); 
        $code=generate_pathology_code();   
        return view('backend.admin_module.pathology.create',compact('pathology_category','code'))->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();
        //$data['code']=generate_pathology_code();         
        $validator = Pathology::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $new_record = Pathology::create($data);
       

        if(isset($request->parameters) || !empty($request->parameters) )
        {
            foreach ($request->parameters as $index => $value)
            {
            if($request->parameters[$index]['parameter_name']!=null)
            {
                $parameters=PathologyParameter::create([
                'pathology_id' => $new_record->id,
                'parameter_name' => $request->parameters[$index]['parameter_name'], 
                'range' => $request->parameters[$index]['range'],  
                'unit' => $request->parameters[$index]['unit'], 
            ]); 
            }
            }
        }

        generate_log('Pathology created', $new_record->id);
        return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology added.');
    }



    public function edit($id)
    {
        $item = Pathology::with('pathology_category')->findorFail($id)->toArray();
        $pathology_category = PathologyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $pathology_parameters = PathologyParameter::where('delete_status', 0)
                                                ->where('pathology_id', $id)
                                                ->orderBy('id', 'asc')
                                                ->get();
        $pathology_category=collect($pathology_category)->toArray();      
        return view('backend.admin_module.pathology.edit', compact('item','pathology_category','pathology_parameters'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        //dd($request);
        $data = $request->all(); 
        $id = $data['id'];        
        $validator = Pathology::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $record = Pathology::findorfail($id);
        $record->update($data);  
        $updateRows = PathologyParameter::where('pathology_id', $id)->update(['delete_status' => 1]);

        if(isset($request->parameters) || !empty($request->parameters) )
        {
            foreach ($request->parameters as $index => $value)
            {
            if($request->parameters[$index]['parameter_name']!=null)
            {
            $parameters=PathologyParameter::updateOrCreate(
                ['id' => $request->parameters[$index]['parameter_id'],
                ],
                [
                    'pathology_id' => $id,
                    'parameter_name' => $request->parameters[$index]['parameter_name'], 
                    'range' => $request->parameters[$index]['range'],  
                    'unit' => $request->parameters[$index]['unit'], 
                    'delete_status' => 0
                ]); 
            }
            }
        }  
        
        
        generate_log('Pathology updated', $id);
        return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology updated.');
    }


    public function destroy($id)
    {        
        Pathology::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Pathology deleted', $id);
        return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {                     
                    Pathology::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Pathology deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology deleted.');
        } else
            return redirect($this->url_prefix . '/pathologys')->with('error_message', 'Please select at least one inventory item master.');
    }


    public function activate($id)
    {
        Pathology::where('id', $id)->update(['status' => 1]);
        generate_log('Pathology activated', $id);
        return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology activated.');
    }


    public function deactivate($id)
    {
        Pathology::where('id', $id)->update(['status' => 0]);
        generate_log('Pathology deactivated', $id);
        return redirect($this->url_prefix . '/pathologys')->with('message', 'Pathology deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = Pathology::all()->where('title', $name);
        else
            $items = Pathology::all()->where('title', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /*********************  name duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = Pathology::all()->where('title',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /*********************  name  duplicate check End*******************************/ 


}
