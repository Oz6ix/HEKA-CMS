<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\PharmacyCategory;
use App\Models\Pharmacy;
use App\Http\Requests\StorePharmacyRequest;
use App\Http\Requests\UpdatePharmacyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use File;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class SettingPharmacyController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Pharmacy";
        $this->page_heading = "Pharmacy";
        $this->heading_icon = "fa-cogs";
        $this->directory_pharmacy = "pharmacy";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_pharmacy' => $this->directory_pharmacy];
    }
    public function index()
    {
        $items = Pharmacy::where('delete_status', 0)->with('pharmacy_category')->orderBy('id', 'desc')->get();
        generate_log('Pharmacy accessed');
        return view('backend.admin_module.pharmacy.index', compact('items'))->with($this->page_info);
    }
    public function show($id)
    {
        $item = Pharmacy::findorFail($id)->toArray();
        generate_log('Pharmacy details accessed', $id);
        return view('backend.admin_module.pharmacy.show', compact('item'))->with($this->page_info);
    }
    public function create()
    {      
        $hospital_category = PharmacyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get()
                                                ->toArray();
        $generic_drugs = Pharmacy::where('delete_status', 0)
                                    ->where('is_generic', true)
                                    ->orderBy('title', 'asc')
                                    ->get();
        return view('backend.admin_module.pharmacy.create', compact('hospital_category', 'generic_drugs'))->with($this->page_info);
    }


    public function store(StorePharmacyRequest $request)
    {
        $data = $request->validated();
        $data['code']=generate_pharmacy_code();         

        // Validation handled by FormRequest
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if (verify_file_mime_type($file, 'image')) {
                    $imageName = strtotime(now()).rand(11111,99999).'.'.$file->getClientOriginalExtension();
                    $data['photo']=$imageName;
                    $request->file('photo')->move(public_path() . '/uploads/pharmacy', $imageName);
            } else
                return redirect($this->url_prefix . '/pharmacy/create')->with('error_message', 'Please upload a valid image file.')->with($this->page_info);
        }   
        $new_record = Pharmacy::create($data);
       
        generate_log('Pharmacy created', $new_record->id);
        return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy added.');
    }



    public function edit($id)
    {
        $item = Pharmacy::with('pharmacy_category')->findorFail($id)->toArray();
        $hospital_category = PharmacyCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get()
                                                ->toArray();
        $generic_drugs = Pharmacy::where('delete_status', 0)
                                    ->where('is_generic', true)
                                    ->where('id', '!=', $id)
                                    ->orderBy('title', 'asc')
                                    ->get();
        return view('backend.admin_module.pharmacy.edit', compact('item', 'hospital_category', 'generic_drugs'))->with($this->page_info);
    }


    public function update(UpdatePharmacyRequest $request)
    {
        $data = $request->validated();
        $id = $data['id']; // Or get from route if passed, but typically passed in hidden field or route 
        
        // Validation handled by FormRequest
        $record = Pharmacy::findorfail($id);
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if (verify_file_mime_type($file, 'image')) {

                if($record['photo']!=NULL){
                    if(\Storage::disk('uploads')->exists('pharmacy/'.$record['photo'])){
                        \Storage::disk('uploads')->delete('pharmacy/'.$record['photo']);
                    }
                }             
                /*if (validate_image_dimension($file, $this->staff_photo_width, $this->staff_photo_height))*/
                   // $data['photo'] = upload_file($file, public_path().'/uploads/pharmacy');
                    
                    $imageName = strtotime(now()).rand(11111,99999).'.'.$file->getClientOriginalExtension();
                    $data['photo']=$imageName;
                    
                    // Use Storage facade
                    \Storage::disk('uploads')->putFileAs('pharmacy', $file, $imageName);

              /*  else
                    return redirect($this->url_prefix . '/staff/create')->with('error_message', 'Please upload image with valid file dimension.')->with($this->page_info);*/
            } else
                return redirect($this->url_prefix . '/pharmacy/create')->with('error_message', 'Please upload a valid image file.')->with($this->page_info);
        }   

      
        $record->update($data);        
        generate_log('Pharmacy updated', $id);
        return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy updated.');
    }
    public function destroy($id)
    {        
        Pharmacy::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Pharmacy deleted', $id);
        return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy deleted.');
    }
    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {                     
                    Pharmacy::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Pharmacy deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy deleted.');
        } else
            return redirect($this->url_prefix . '/pharmacys')->with('error_message', 'Please select at least one inventory item master.');
    }
    public function activate($id)
    {
        Pharmacy::where('id', $id)->update(['status' => 1]);
        generate_log('Pharmacy activated', $id);
        return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy activated.');
    }
    public function deactivate($id)
    {
        Pharmacy::where('id', $id)->update(['status' => 0]);
        generate_log('Pharmacy deactivated', $id);
        return redirect($this->url_prefix . '/pharmacys')->with('message', 'Pharmacy deactivated.');
    }
    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = Pharmacy::all()->where('title', $name);
        else
            $items = Pharmacy::all()->where('title', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }
     /*********************  name duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = Pharmacy::all()->where('title',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /*********************  name  duplicate check End*******************************/ 
     /*********************  import medicines *******************************/
     public function import_medicines(Request $request)
     {
         //dd($_FILES['export_file']['name']);
        if(!isset($_FILES['export_file'])||$_FILES['export_file']['name']==""){
          return redirect($this->url_prefix . '/pharmacys')->with('warning_message', 'Please select a file');

        }
        $file = $_FILES['export_file'];
        $tmpName = $file ['tmp_name'];    
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader ->open($tmpName);
         $i=0;
        

         foreach ($reader->getSheetIterator() as $sheet) {
            
             foreach ($sheet->getRowIterator() as $row) {
                 // do stuff with the row
                 //$cells = $row->getCells();
                 
                 if($i==0){
                    $cells =$row->getCells();
                    $cells[1] = "";
                    $i++;
                 }
                 else{
                    $cells = $row->getCells();
                    $i++;
                    
                 }
                 
                 if($cells[1]!=""&&$cells[2]!=""&&$cells[3]!=""&&$cells[4]!=""&&$cells[5]!=""&&$cells[6]!="")
                 { 
                     
                     $pharmacy_category = PharmacyCategory::where('name',$cells[2])
                     ->where('status',1)
                     ->where('delete_status', 0)
                     ->orderBy('name', 'asc')
                     ->get('id');
                    
                     //dd($inventory_category[0]['id']);
                     /* $units = Units::where('unit',$cells[3])
                     ->where('status',1)
                     ->where('delete_status', 0)
                     ->orderBy('unit', 'asc')
                     ->get('id'); */
                     if (count($pharmacy_category) == 0){
                         return redirect($this->url_prefix . '/pharmacys')->with('warning_message', 'There are certain categories not found.Please add it first and upload the file.Check line number');
                     }
                         /* if (count($units) == 0)
                         return redirect($this->url_prefix . '/pharmacys')->with('warning_message', 'There are certain units not found.Please add it first and upload the file'); */
                         $data['title']=$cells[1];         
                         $data['pharmacy_category_id']=$pharmacy_category[0]['id'];         
                         $data['company_name']=$cells[3];    
                         $data['unit']=$cells[4];         
                         $data['quantity']=$cells[5];         
                         $data['price']=$cells[6];         
 
                     $items=Pharmacy::where('title', $cells[1])->where('status',1)->where('delete_status', 0)->get('id');
                     
                    if (count($items)<=0) 
                    {
                        $data['code']=generate_pharmacy_code();
                        $data['import_status']=1;         
                        $validator = Pharmacy::validate_add($data);
                        if ($validator->fails()) 
                        {
                            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
                        }
                        $new_record = Pharmacy::create($data);
                    }
                    else
                    {
                    //Update already exist data
                    $data['import_update_status']=1;         
                    $new_record = Pharmacy::where('id', $items[0]['id'])->update($data);
                    }




                    }      
             }
         }
         
         return redirect($this->url_prefix . '/pharmacys')->with('message', 'Medicines imported successfully');
         $reader->close();
     }    
     /*********************  import medicines End*******************************/ 


}
