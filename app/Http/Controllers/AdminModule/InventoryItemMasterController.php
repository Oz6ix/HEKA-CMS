<?php
namespace App\Http\Controllers\AdminModule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Redirect;

/**
 * Class InventoryItemMasterController
 * @package App\Http\Controllers\AdminModule
 */
class InventoryItemMasterController extends Controller
{
    protected $inventoryService;
    protected $url_prefix;
    protected $page_title;
    protected $page_heading;
    protected $heading_icon;
    protected $page_info;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Inventory";
        $this->page_heading = "Inventory";
        $this->heading_icon = "fa-cogs";
        $this->page_info = [
            'url_prefix' => $this->url_prefix, 
            'page_title' => $this->page_title, 
            'page_heading' => $this->page_heading, 
            'heading_icon' => $this->heading_icon
        ];
    }

    public function index()
    {
        $items = $this->inventoryService->getAllItems();
        generate_log('Inventory item master accessed');
        return view('backend.admin_module.inventory_master.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = $this->inventoryService->getItemById($id)->toArray();
        generate_log('Inventory category details accessed', $id);
        return view('backend.admin_module.inventory_master.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $data = $this->inventoryService->getDependencyData();
        
        $inventory_category = $data['categories'];
        $units = $data['units'];
        $pharmacy_generic = $data['generics'];
        $dosages = $data['dosages'];

        return view('backend.admin_module.inventory_master.create', compact('inventory_category', 'units', 'pharmacy_generic', 'dosages'))->with($this->page_info);
    }

    public function store(Request $request)
    {
        $validator = \App\Models\InventoryItemMaster::validate_add($request->all());
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }

        $new_record = $this->inventoryService->createItem($request->all());
       
        generate_log('Inventory item master created', $new_record->id);
        return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master added.');
    }

    public function edit($id)
    {
        $item = $this->inventoryService->getItemById($id)->toArray();
        $data = $this->inventoryService->getDependencyData();
        
        $inventory_category = $data['categories'];
        $units = $data['units'];
        $pharmacy_generic = $data['generics'];
        $dosages = $data['dosages'];

        if($item['inventory_category_id'] === 3) {
            $pharmacy_flg = "show";
        } else {
            $pharmacy_flg = "none";
        }
           
        return view('backend.admin_module.inventory_master.edit', compact('item', 'inventory_category', 'pharmacy_generic', 'dosages', 'units', 'pharmacy_flg'))->with($this->page_info);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        
        $validator = \App\Models\InventoryItemMaster::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        
        $this->inventoryService->updateItem($id, $data);      
        generate_log('Inventory item master updated', $id);
        return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master updated.');
    }

    public function destroy($id)
    {
        $this->inventoryService->deleteItem($id);
        generate_log('Inventory item master deleted', $id);
        return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master deleted.');
    }

    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $this->inventoryService->deleteMultipleItems($ids);
            generate_log('Inventory item master deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master deleted.');
        } else {
            return redirect($this->url_prefix . '/inventory_masters')->with('error_message', 'Please select at least one inventory item master.');
        }
    }

    public function activate($id)
    {
        $this->inventoryService->activateItem($id);
        generate_log('Inventory item master activated', $id);
        return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master activated.');
    }

    public function deactivate($id)
    {
        $this->inventoryService->deactivateItem($id);
        generate_log('Inventory item master deactivated', $id);
        return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Inventory item master deactivated.');
    }

    public function exists($name, $id = null)
    {
        return $this->inventoryService->exists($name, $id);
    }

    public function ajax_duplicate_name($name) 
    {   
        return $this->inventoryService->checkDuplicateName($name);
    }

    public function import_item_master(Request $request)
    {
        if(!$request->hasFile('export_file')){
          return redirect($this->url_prefix . '/inventory_masters')->with('warning_message', 'Please select a file');
        }

        try {
            $this->inventoryService->importItems($request->file('export_file'));
            return redirect($this->url_prefix . '/inventory_masters')->with('message', 'Items imported successfully');
        } catch (\Exception $e) {
             return redirect($this->url_prefix . '/inventory_masters')->with('error_message', 'Error importing items: ' . $e->getMessage());
        }
    }    
}
