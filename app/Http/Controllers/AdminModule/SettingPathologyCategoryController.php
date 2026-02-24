<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\PathologyCategory;
use App\Models\Pathology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class SettingPathologyCategoryController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Pathology Category";
        $this->page_heading = "Pathology Category";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];

    }
    


    public function index()
    {
        $items = PathologyCategory::where('delete_status', 0)->with('subcategory')->orderBy('id', 'desc')->get(); 
        generate_log('Pathology category accessed');
        return view('backend.admin_module.pathology_category.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = PathologyCategory::findorFail($id)->toArray();
        generate_log('Pathology category details accessed', $id);
        return view('backend.admin_module.pathology_category.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $parent_category = PathologyCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $parent_category=collect($parent_category)->toArray();  
        return view('backend.admin_module.pathology_category.create',compact('parent_category'))->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all(); 
        if (!$this->exists($data['name'])) {
            $validator = PathologyCategory::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = PathologyCategory::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A Pathology category with same category name already exists. Please use a different one.');
        generate_log('Pathology category created', $new_record->id);
        return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category added.');
    }



    public function edit($id)
    {
        $item = PathologyCategory::with('subcategory')->findorFail($id)->toArray();
        $parent_category = PathologyCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->where('id', '!=', $id)
                                                ->orderBy('name', 'asc')
                                                ->get();      
        return view('backend.admin_module.pathology_category.edit', compact('item','parent_category'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['name'], $id)) {
            $validator = PathologyCategory::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = PathologyCategory::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A Pathology category with same category name already exists. Please use a different one.');
        generate_log('Pathology category updated', $id);
        return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category updated.');
    }


    public function destroy($id)
    {
        /*$items = inventery::where('role_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/pathology_categorys')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');*/
        $items = PathologyCategory::where('parent_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/pathology_categorys')->with('warning_message', 'There are certain sub-category associated to this category. You can remove this role only once all the associated category are removed or their role is changed to a new one.');
        $items = Pathology::where('pathology_category_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/pathology_categorys')->with('warning_message', 'There are certain medicines associated to this category. You can remove this role only once all the associated medicines are removed or their role is changed to a new one.');
        PathologyCategory::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Inventory Category deleted', $id);
        return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    $items = PathologyCategory::where('parent_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/pathology_categorys')->with('warning_message', 'There are certain sub-category associated to this category. You can remove this role only once all the associated category are removed or their role is changed to a new one.');
                    $items = Pathology::where('pathology_category_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/pathology_categorys')->with('warning_message', 'There are certain medicines associated to this category. You can remove this role only once all the associated medicines are removed or their role is changed to a new one.');
                                PathologyCategory::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Pathology category deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category deleted.');
        } else
            return redirect($this->url_prefix . '/pathology_categorys')->with('error_message', 'Please select at least one category.');
    }


    public function activate($id)
    {
        PathologyCategory::where('id', $id)->update(['status' => 1]);
        generate_log('Pathology category activated', $id);
        return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category activated.');
    }


    public function deactivate($id)
    {
        PathologyCategory::where('id', $id)->update(['status' => 0]);
        generate_log('Pathology category deactivated', $id);
        return redirect($this->url_prefix . '/pathology_categorys')->with('message', 'Pathology category deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = PathologyCategory::all()->where('name', $name);
        else
            $items = PathologyCategory::all()->where('name', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /********************* Staff email duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = PathologyCategory::all()->where('name',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 


}
