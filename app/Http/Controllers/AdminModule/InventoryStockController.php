<?php
namespace App\Http\Controllers\AdminModule;
use App\Models\Units; 
use Illuminate\Http\Request;
use App\Models\InventoryStock;
use App\Models\StockAdjustment;
use App\Models\SettingsSupplier;
use App\Models\InventoryCategory;
use App\Models\InventoryItemMaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
/**
 * Class InventoryStockController
 * @package App\Http\Controllers\AdminModule
 */
class InventoryStockController extends Controller
{
        /**
     * InventoryStockController constructor.
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
        $this->directory_inventory_stock = "inventory_stock_document";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_inventory_stock' => $this->directory_inventory_stock];
    }
    /**
     * List the resources.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = InventoryStock::where('delete_status', 0)->with('inventorymaster')->with('inventorymaster.unit')->with('supplier')->orderBy('id', 'desc')->get(); 
       // dd($items);     
        generate_log('Inventory stock accessed');
        return view('backend.admin_module.inventory_stock.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $item = InventoryStock::with('inventorymaster')->with('inventorymaster.inventory_category')->with('supplier')->findorFail($id)->toArray();
        generate_log('Inventory category details accessed', $id);
        return view('backend.admin_module.inventory_stock.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        // category list in the master item   
        $inventory_category = InventoryItemMaster::with('inventory_category')->where('delete_status', 0)->get();
        $inventory_category=collect($inventory_category)->unique('inventory_category_id')->toArray();
        $supplier = SettingsSupplier::where('delete_status', 0)
                        ->orderBy('supplier_name', 'asc')
                        ->get();
        $supplier=collect($supplier)->toArray();
        return view('backend.admin_module.inventory_stock.create',compact('inventory_category','supplier'))->with($this->page_info);
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
        $data['balance']=$data['quantity'];
        $data['item_code']=generate_item_stock_code(); 
        $data['date_str']=strtotime($data['date']);
        $validator = InventoryStock::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }

         // document file upload    
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            if (verify_file_mime_type($file, 'special')) {
                if (validate_file_size($file, '10485760 ')) {
                    $data['document'] = upload_file($file, $this->directory_inventory_stock);
                    $ext = pathinfo($data['document'], PATHINFO_EXTENSION);
                    $data['resume_file_type'] = $ext;
                } else
                    return redirect($this->url_prefix . '/inventory_stock/create')->with('error_message', 'Please upload less than 10 mb in size for document.')->with($this->page_info);

            } else
                return redirect($this->url_prefix . '/inventory_stock/create')->with('error_message', 'Please upload a valid document file.')->with($this->page_info);
        } 
        $new_record = InventoryStock::create($data);       
        generate_log('Inventory stock created', $new_record->id);
        return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock added.');
    }
	/**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = InventoryStock::with('inventorymaster')->with('supplier')->findorFail($id)->toArray();
        // category list in the master item   
        $inventory_category = InventoryItemMaster::with('inventory_category')->where('delete_status', 0)->get(); 
        $inventory_category=collect($inventory_category)->unique('inventory_category_id')->toArray(); 


        $select_inventory_category = InventoryItemMaster::where('inventory_category_id',$item['inventorymaster']['inventory_category_id'])->with('inventory_category')->where('delete_status', 0)->get(); 
        $select_inventory_category=collect($select_inventory_category)->toArray(); 

        //dd($select_inventory_category);

        $supplier = SettingsSupplier::where('delete_status', 0)
                        ->orderBy('supplier_name', 'asc')
                        ->get();
        $supplier=collect($supplier)->toArray();      
        return view('backend.admin_module.inventory_stock.edit', compact('item','inventory_category','supplier','select_inventory_category'))->with($this->page_info);
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
        $data['date_str']=strtotime($data['date']);     
        $validator = InventoryStock::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }

         // document file upload    
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            if (verify_file_mime_type($file, 'special')) {
                if (validate_file_size($file, '10485760 ')) {
                    $data['document'] = upload_file($file, $this->directory_inventory_stock);
                    $ext = pathinfo($data['document'], PATHINFO_EXTENSION);
                    $data['resume_file_type'] = $ext;
                } else
                    return redirect($this->url_prefix . '/inventory_stock/create')->with('error_message', 'Please upload less than 10 mb in size for document.')->with($this->page_info);

            } else
                return redirect($this->url_prefix . '/inventory_stock/create')->with('error_message', 'Please upload a valid document file.')->with($this->page_info);
        } 


        $record = InventoryStock::findorfail($id);
        $record->update($data);        
        generate_log('Inventory stock updated', $id);
        return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock updated.');
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
            return redirect($this->url_prefix . '/inventory_stocks')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');*/
        InventoryStock::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Inventory stock deleted', $id);
        return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock deleted.');
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
                        return redirect($this->url_prefix . '/inventory_stocks')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    InventoryStock::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Inventory stock deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock deleted.');
        } else
            return redirect($this->url_prefix . '/inventory_stocks')->with('error_message', 'Please select at least one inventory stock.');
    }
	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        InventoryStock::where('id', $id)->update(['status' => 1]);
        generate_log('Inventory stock activated', $id);
        return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock activated.');
    }
	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        InventoryStock::where('id', $id)->update(['status' => 0]);
        generate_log('Inventory stock deactivated', $id);
        return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Inventory stock deactivated.');
    }

    /**
     * Display inventory alerts: near-expiry, expired, low stock
     */
    public function alerts()
    {
        $near_expiry = InventoryStock::where('delete_status', 0)
            ->nearExpiry()
            ->with('inventorymaster', 'supplier')
            ->get();
            
        $expired = InventoryStock::where('delete_status', 0)
            ->expired()
            ->with('inventorymaster', 'supplier')
            ->get();
            
        $low_stock = InventoryStock::where('delete_status', 0)
            ->lowStock()
            ->with('inventorymaster', 'supplier')
            ->get();

        $adjustments = StockAdjustment::with('inventoryItem.inventorymaster', 'adjustedByUser')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('backend.admin_module.inventory_stock.alerts', compact('near_expiry', 'expired', 'low_stock', 'adjustments'))->with($this->page_info);
    }

    /**
     * Store a stock adjustment
     */
    public function store_adjustment(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|integer',
            'type' => 'required|string|in:damage,expiry,loss,return,correction',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
        ]);
        
        $data['adjusted_by'] = auth()->id();
        $data['adjustment_date'] = now()->toDateString();
        
        // Deduct from stock
        $stock = InventoryStock::findOrFail($data['inventory_item_id']);
        if ($data['type'] !== 'return') {
            $stock->decrement('quantity', $data['quantity']);
        } else {
            $stock->increment('quantity', $data['quantity']);
        }
        
        StockAdjustment::create($data);
        
        generate_log('Stock adjustment: ' . $data['type'], $data['inventory_item_id']);
        return redirect($this->url_prefix . '/inventory_stock/alerts')->with('message', 'Stock adjustment recorded.');
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
            $items = InventoryStock::all()->where('item_name', $name);
        else
            $items = InventoryStock::all()->where('item_name', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /********************* Staff email duplicate check *******************************/
    /**
     *Check if the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajax_duplicate_name($name) {   
        $item = InventoryItemMaster::all()->where('item_name',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 

    /**
     *Ajax for fetch item master
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function ajax_fecth_item_master($id){ 
     
      /*****Based on Category listing of all offers*********/
        $inventory_category = InventoryItemMaster::where('inventory_category_id',$id)->with('inventory_category')->where('delete_status', 0)->get();
        $inventory_category=collect($inventory_category)->toArray();        
        $html='';           
          if(!empty($inventory_category)) { 
            $html='<select id="select2_inventory_master" class="form-control kt-select2" name="inventory_master_id">';
            $html.= '<option value="">Select Item</option>';
              foreach($inventory_category as $key => $item){ 
                $html.= '<option value="'.$item['id'].'">'.$item['item_name'].'</option>';
              }
            $html.= '</select>';
          } 
          else { 
              $html='<select id="select2_inventory_master" class="form-control kt-select2" name="inventory_master_id">';
              $html.= '<option value="">No Item Name</option>';
              $html.= '</select>';
          }   echo $html; exit;
  }

	/**
     * import the specified resources in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
  public function import_item_stock(Request $request)
  {
    if(!isset($_FILES['export_file'])||$_FILES['export_file']['name']==""){
    return redirect($this->url_prefix . '/inventory_stocks')->with('warning_message', 'Please select a file');
    }
     $file = $_FILES['export_file'];
     $tmpName = $file ['tmp_name'];    
     $reader = ReaderEntityFactory::createXLSXReader();
     $reader ->open($tmpName);
     $error_column="";
      $i=0;
      foreach ($reader->getSheetIterator() as $sheet) {
          foreach ($sheet->getRowIterator() as $row) {
              // do stuff with the row
              if($i==0){
              $row->getCells();
              $i++;
              $cells[1] ="";
              }
              else{
                $i++;
                $cells = $row->getCells();
              }
              
              if(!empty($cells[1])&&!empty($cells[2]->getValue())&&!empty($cells[3])&&!empty($cells[4])&&!empty($cells[5])&&!empty($cells[6])){
                //dd(empty($cells[2]->getValue()));
                $item_master_exist = InventoryItemMaster ::join('hospital_inventory_category', 'hospital_inventory_master.inventory_category_id', '=', 'hospital_inventory_category.id')
                ->where('hospital_inventory_category.inventory_name','=',$cells[1])
                ->where('hospital_inventory_master.item_name','=',$cells[2])
                ->where('hospital_inventory_master.status',1)
                ->where('hospital_inventory_master.delete_status', 0)
                ->first('hospital_inventory_master.id');
                $item_stock_exist = InventoryStock ::join('hospital_inventory_master', 'hospital_inventory_master.id', '=', 'hospital_inventory_items.inventory_master_id')
                ->join('hospital_inventory_category', 'hospital_inventory_master.inventory_category_id', '=', 'hospital_inventory_category.id')
                ->where('hospital_inventory_category.inventory_name','=',$cells[1])
                ->where('hospital_inventory_master.item_name','=',$cells[2])
                ->where('hospital_inventory_items.status',1)
                ->where('hospital_inventory_items.delete_status', 0)
                ->first('hospital_inventory_items.id');
                $supplier_exist = SettingsSupplier::where('supplier_name',$cells[3])
                ->where('status',1)
                ->where('delete_status', 0)
                ->first('id');
                
            if(!empty($supplier_exist)){
                
                if (!empty($item_master_exist) ) { 
                    $quantity=$cells[4]->getValue();
                    $purchase_price=$cells[5]->getValue();
                    $data['inventory_master_id']=$item_master_exist['id'];         
                    $data['supplier_id']=$supplier_exist['id'];         
                    $data['quantity']=$quantity;    
                    $data['purchase_price']=$purchase_price;  
                    $data['date']=$cells[6]->getValue(); 
                    $data['description']=$cells[7];  
                    if(!empty($item_stock_exist)){
                        //update querry
                        $id=$item_stock_exist['id'];
                        $validator = InventoryStock::validate_update($data,$id);
                        if (!$validator->fails()) {
                        $new_record = InventoryStock::where('id',$id)->update($data);
                        
                        }
                    } 
                    else{
                        $data['item_code']=generate_item_stock_code(); 

                        $validator = InventoryStock::validate_add($data);
                        if (!$validator->fails()) {
                            $new_record = InventoryStock::create($data);  
                        }
                    }     
                }
            }    
          }    
          else{
              $error_column.=($i-1).',';
          }           
          }
      }
      return redirect($this->url_prefix . '/inventory_stocks')->with('message', 'Items imported successfully ');
      $reader->close();
  }    
}
