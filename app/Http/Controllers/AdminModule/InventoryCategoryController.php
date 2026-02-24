<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class InventoryCategoryController
 * @package App\Http\Controllers\AdminModule
 */

class InventoryCategoryController extends Controller
{ 
    /**
     * InventoryCategoryController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Inventory";
        $this->page_heading = "Inventory";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }
    
    /**
     * List the resources.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $items = InventoryCategory::where('delete_status', 0)->with('subcategory')->orderBy('id', 'desc')->get(); 
        generate_log('Inventory category accessed');
        return view('backend.admin_module.inventory_category.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $item = InventoryCategory::findorFail($id)->toArray();
        generate_log('Inventory category details accessed', $id);
        return view('backend.admin_module.inventory_category.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function create()
    {      
        $parent_category = InventoryCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->orderBy('inventory_name', 'asc')
                                                ->get();
        $parent_category=collect($parent_category)->toArray();  
        return view('backend.admin_module.inventory_category.create',compact('parent_category'))->with($this->page_info);
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
        
        if (!$this->exists($data['inventory_name'])) {
            $validator = InventoryCategory::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = InventoryCategory::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'An inventory with same inventory name already exists. Please use a different one.');
        generate_log('Inventory category created', $new_record->id);
        return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category added.');
    }

	/**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $item = InventoryCategory::with('subcategory')->findorFail($id)->toArray();
        $parent_category = InventoryCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->where('id', '!=', $id)
                                                ->orderBy('inventory_name', 'asc')
                                                ->get();      
        return view('backend.admin_module.inventory_category.edit', compact('item','parent_category'))->with($this->page_info);
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
        if (!$this->exists($data['inventory_name'], $id)) {
            $validator = InventoryCategory::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = InventoryCategory::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A inventory with same inventory name already exists. Please use a different one.');
        generate_log('Inventory Category updated', $id);
        return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category updated.');
    }
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*$items = inventery::where('role_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/inventory_categorys')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');*/
        InventoryCategory::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Inventory Category deleted', $id);
        return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category deleted.');
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
                     /*$items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/inventory_categorys')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    InventoryCategory::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Inventory category deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category deleted.');
        } else
            return redirect($this->url_prefix . '/inventory_categorys')->with('error_message', 'Please select at least one inventory category.');
    }
	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function activate($id)
    {
        InventoryCategory::where('id', $id)->update(['status' => 1]);
        generate_log('Inventory category activated', $id);
        return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category activated.');
    }

	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deactivate($id)
    {
        InventoryCategory::where('id', $id)->update(['status' => 0]);
        generate_log('Inventory category deactivated', $id);
        return redirect($this->url_prefix . '/inventory_categorys')->with('message', 'Inventory category deactivated.');
    }


    /* Custom methods */
    	/**
     * Check if Exist the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = InventoryCategory::all()->where('inventory_name', $name);
        else
            $items = InventoryCategory::all()->where('inventory_name', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /********************* Staff email duplicate check *******************************/
         /**
     *Check if the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ajax_duplicate_name($name) {   
        $item = InventoryCategory::all()->where('inventory_name',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 


}
