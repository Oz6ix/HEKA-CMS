<?php
/**
 * Created By Anu Abraham
 * Created at : 23-07-2021
 * Modified At :09-11-2021
 * 
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\SymptomType;
use App\Models\Casualty;
use App\Models\Tpa;
use App\Models\AppointmentBasicsDetail;
use App\Models\Center;
use App\Models\HospitalCharge;
use App\Models\Units;
use App\Models\PatientDiagnosis;
use App\Models\PatientPrescription;
use App\Models\Frequency;
use App\Models\MedicalConsumableUsed;
use App\Models\PatientMedicalTest;
use App\Models\PatientBriefNote;
use App\Models\PatientBill;
use App\Models\PatientBillConsumable;
use App\Models\PatientBillPathology;
use App\Models\PatientBillRadiology;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use App\Models\SettingsSiteGeneral;
/**
 * Class CustomerBillController
 * @package App\Http\Controllers\AdminModule
 */
class CustomerBillController extends Controller
{
    /**
     * CustomerBillController constructor.
     * 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Bills";
        $this->page_heading = "Bills";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

        /**
     * This method is used for list a bill
     * @return \Illuminate\Http\RedirectResponse
     */

    public function index()              
    {
        //dd('1x1x1x1x');
        $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->where('patient_id', Auth::guard('blogger')->user()->id)
                            ->where('bill_type', 2)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();
//dd($items);
        generate_log('Bill list accessed');
        return view('frontend.bills.index', compact('items'))->with($this->page_info);
    }
        
    public function list_bill_set($id)
    {
    $item = Appointment::with('patient')
                                ->with('staff_doctor')
                                ->with('casualty')
                                ->with('tpa')
                                ->where('delete_status', 0)
                                ->where('id', $id)
                                ->get();
    $bill_number = PatientBill::where('appointment_id', $id)->orderBy('id', 'desc')->first();
    $bill_number =$bill_number['bill_number'];
    //return response()->json($item);
    return view('frontend.bills.show', compact('item','bill_number'))->with($this->page_info);
    }



    public function ajax_fetch_bill_print_data_pharmacy($id = null){
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray();     
        $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->with(['bill_medicine' => function ($query) {
                                $query->orderBy('id', 'asc')
                                    ->where('delete_status', 0)
                                    ->get()
                                    ->toArray();
                            }])                            
                            ->where('bill_type', 2)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_number',$id)
                            /*->groupBy('bill_number')*/
                            ->orderBy('id', 'desc')
                            ->get();
        $items  =collect($items)->toArray();
        //dd($items);
        $html=''; 
        $html.= '<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid ">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-invoice-2">
                    <div class="kt-invoice__head">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="kt-invoice__brand"> 
                                            <div href="#" class="kt-invoice__logo">
                                            <a href="#"><img src="' . \URL::asset('resources/files/uploads/logos/cms-logo-png.png') . '"></a>                                       
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-5">
                                    <div class="kt-invoice__items">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Address:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['hospital_address'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Phone Number:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_phone'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Email:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_email'].'<br>Fredrick Nebraska 20620</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Website:</b></span>
                                    <span class="kt-invoice__text">hospital.com</span>
                                </div>
                            </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <hr>                
                    <div class="row">
                    <div class="col-md-6">Bill No: <span>'.$id.'</span></div>
                    <div class="col-md-6" style="text-align:right">Date: <span>Date: '. date('M d, Y').'</span></div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-md-6">Patient Name: <span>'.$items[0]['patient']['name'].'</span></div>
                    <div class="col-md-6">Doctor: <span>'.$items[0]['staff_doctor']['name'].'</span></div>
                    </div>
                    <hr> 
                    <div class="kt-invoice__body">
                        <div class="kt-invoice__container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total (K)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                if(!empty($items)) {          
                                    foreach($items as $key => $item){
                                        $html.= '<tr>
                                            <td>'.$item['bill_medicine']['drug_name'].' </td>
                                            <td>'.$item['bill_medicine']['quantity'].'</td>
                                            <td>'.$item['medicine_price'].'</td>
                                            <td class="kt-font-danger kt-font-lg">'.$item['medicine_price']*$item['bill_medicine']['quantity'].'</td>
                                        </tr>';
                                          }
                                }else {              
                                         $html.= 'No Record Found';             
                                }   
                                    $html.= ' </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-5"style="text-align:right">
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Total:</span> <span>K '.$item['total'].'</span></div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Discount:</span> <span>'.$item['discount'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Tax:</span> <span>'.$item['tax'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Net Amount:</span> <span>K '.$item['net_amount'].'</span></div>
                                    </div>
                                </div>                               
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>'; 
        echo $html; exit;
    }
    public function ajax_fetch_bill_print_data_consumable($id = null){
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray();     
        $items = PatientBillConsumable::with('patient')
                            ->with('staff_doctor')
                            ->with('bill_consumable_used')
                            ->with(['bill_consumable.inventorymaster' => function ($query) {
                                $query->orderBy('id', 'asc')
                                    ->where('delete_status', 0)
                                    ->get()
                                    ->toArray();
                            }])                            
                            ->where('bill_type', 5)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_number',$id)
                            /*->groupBy('bill_number')*/
                            ->orderBy('id', 'desc')
                            ->get();

        $items  =collect($items)->toArray();
        //dd($items);
        $html=''; 
        $html.= '<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid ">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-invoice-2">
                    <div class="kt-invoice__head">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="kt-invoice__brand"> 
                                            <div href="#" class="kt-invoice__logo">
                                            <a href="#"><img src="' . \URL::asset('resources/files/uploads/logos/cms-logo-png.png') . '"></a>                                       
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-5">
                                    <div class="kt-invoice__items">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Address:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['hospital_address'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Phone Number:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_phone'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Email:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_email'].'<br>Fredrick Nebraska 20620</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Website:</b></span>
                                    <span class="kt-invoice__text">hospital.com</span>
                                </div>
                            </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <hr>                
                    <div class="row">
                    <div class="col-md-6">Bill No: <span>'.$id.'</span></div>
                    <div class="col-md-6" style="text-align:right">Date: <span>Date: '. date('M d, Y').'</span></div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-md-6">Patient Name: <span>'.$items[0]['patient']['name'].'</span></div>
                    <div class="col-md-6">Doctor: <span>'.$items[0]['staff_doctor']['name'].'</span></div>
                    </div>
                    <hr> 
                    <div class="kt-invoice__body">
                        <div class="kt-invoice__container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total (K)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                if(!empty($items)) {          
                                    foreach($items as $key => $item){
                                        $html.= '<tr>
                                            <td>'.$item['bill_consumable']['inventorymaster']['item_name'].' </td>
                                            <td>'.$item['bill_consumable_used']['quantity'].'</td>
                                            <td>'.$item['consumable_price'].'</td>
                                            <td class="kt-font-danger kt-font-lg">'.$item['bill_consumable_used']['quantity']*$item['consumable_price'].'</td>
                                        </tr>';
                                          }
                                }else {              
                                         $html.= 'No Record Found';             
                                }   
                                    $html.= ' </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-5"style="text-align:right">
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Total:</span> <span>K '.$item['total'].'</span></div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Discount:</span> <span>'.$item['discount'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Tax:</span> <span>'.$item['tax'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Net Amount:</span> <span>K '.$item['net_amount'].'</span></div>
                                    </div>
                                </div>                               
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>'; 
        echo $html; exit;
    }

    public function ajax_fetch_bill_print_data_other($id = null){
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray();     
        $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->with(['treatment' => function ($query) {
                                $query->orderBy('id', 'asc')
                                    ->where('delete_status', 0)
                                    ->get()
                                    ->toArray();
                            }])                            
                            ->where('bill_type', 1)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_number',$id)
                            /*->groupBy('bill_number')*/
                            ->orderBy('id', 'desc')
                            ->get();
        $items  =collect($items)->toArray();
        //dd($items);
        $html=''; 
        $html.= '<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid ">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-invoice-2">
                    <div class="kt-invoice__head">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="kt-invoice__brand"> 
                                            <div href="#" class="kt-invoice__logo">
                                            <a href="#"><img src="' . \URL::asset('resources/files/uploads/logos/cms-logo-png.png') . '"></a>                                       
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-5">
                                    <div class="kt-invoice__items">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Address:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['hospital_address'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Phone Number:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_phone'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Email:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_email'].'<br>Fredrick Nebraska 20620</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Website:</b></span>
                                    <span class="kt-invoice__text">hospital.com</span>
                                </div>
                            </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <hr>                
                    <div class="row">
                    <div class="col-md-6">Bill No: <span>'.$id.'</span></div>
                    <div class="col-md-6" style="text-align:right">Date: <span>Date: '. date('M d, Y').'</span></div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-md-6">Patient Name: <span>'.$items[0]['patient']['name'].'</span></div>
                    <div class="col-md-6">Doctor: <span>'.$items[0]['staff_doctor']['name'].'</span></div>
                    </div>
                    <hr> 
                    <div class="kt-invoice__body">
                        <div class="kt-invoice__container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Treatment</th>
                                            <th>Code</th>
                                            <th>Unit Price</th>
                                            <th>Total (K)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                if(!empty($items)) {          
                                    foreach($items as $key => $item){
                                        $html.= '<tr>
                                            <td>'.$item['treatment']['title'].' </td>
                                            <td>'.$item['treatment']['code'].'</td>
                                            <td>'.$item['hospital_charge_price'].'</td>
                                            <td class="kt-font-danger kt-font-lg">'.$item['hospital_charge_price'].'</td>
                                        </tr>';
                                          }
                                }else {              
                                         $html.= 'No Record Found';             
                                }   
                                    $html.= ' </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-5"style="text-align:right">
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Total:</span> <span>K '.$item['total'].'</span></div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Discount:</span> <span>'.$item['discount'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Tax:</span> <span>'.$item['tax'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Net Amount:</span> <span>K '.$item['net_amount'].'</span></div>
                                    </div>
                                </div>                               
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>'; 
        echo $html; exit;
    }
    public function ajax_fetch_bill_print_data_pathology($id = null){
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray();     
        $items = PatientBillPathology::with('patient')
                            ->with('staff_doctor')
                            ->with(['bill_test.pathology_data' => function ($query) {
                                $query->orderBy('id', 'asc')
                                    ->where('delete_status', 0)
                                    ->get()
                                    ->toArray();
                            }])                            
                            ->where('bill_type', 3)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_number',$id)
                            /*->groupBy('bill_number')*/
                            ->orderBy('id', 'desc')
                            ->get();

        $items  =collect($items)->toArray();
        //dd($items);
        $html=''; 
        $html.= '<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid ">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-invoice-2">
                    <div class="kt-invoice__head">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="kt-invoice__brand"> 
                                            <div href="#" class="kt-invoice__logo">
                                            <a href="#"><img src="' . \URL::asset('resources/files/uploads/logos/cms-logo-png.png') . '"></a>                                       
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-5">
                                    <div class="kt-invoice__items">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Address:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['hospital_address'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Phone Number:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_phone'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Email:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_email'].'<br>Fredrick Nebraska 20620</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Website:</b></span>
                                    <span class="kt-invoice__text">hospital.com</span>
                                </div>
                            </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <hr>                
                    <div class="row">
                    <div class="col-md-6">Bill No: <span>'.$id.'</span></div>
                    <div class="col-md-6" style="text-align:right">Date: <span>Date: '. date('M d, Y').'</span></div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-md-6">Patient Name: <span>'.$items[0]['patient']['name'].'</span></div>
                    <div class="col-md-6">Doctor: <span>'.$items[0]['staff_doctor']['name'].'</span></div>
                    </div>
                    <hr> 
                    <div class="kt-invoice__body">
                        <div class="kt-invoice__container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Report Days</th>
                                            <th>Unit Price</th>
                                            <th>Total (K)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                if(!empty($items)) {          
                                    foreach($items as $key => $item){
                                        $html.= '<tr>
                                            <td>'.$item['bill_test']['test_name'].' </td>
                                            <td>'.$item['bill_test']['pathology_data']['report_days'].'</td>
                                            <td>'.$item['test_price'].'</td>
                                            <td class="kt-font-danger kt-font-lg">'.$item['test_price'].'</td>
                                        </tr>';
                                          }
                                }else {              
                                         $html.= 'No Record Found';             
                                }   
                                    $html.= ' </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-5"style="text-align:right">
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Total:</span> <span>K '.$item['total'].'</span></div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Discount:</span> <span>'.$item['discount'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Tax:</span> <span>'.$item['tax'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Net Amount:</span> <span>K '.$item['net_amount'].'</span></div>
                                    </div>
                                </div>                               
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>'; 
        echo $html; exit;
    }
    public function ajax_fetch_bill_print_data_radiology($id = null){
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray();     
        $items = PatientBillRadiology::with('patient')
                            ->with('staff_doctor')
                            ->with(['bill_test.radiology_data' => function ($query) {
                                $query->orderBy('id', 'asc')
                                    ->where('delete_status', 0)
                                    ->get()
                                    ->toArray();
                            }])                            
                            ->where('bill_type', 4)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_number',$id)
                            /*->groupBy('bill_number')*/
                            ->orderBy('id', 'desc')
                            ->get();

        $items  =collect($items)->toArray();
        //dd($items);
        $html=''; 
        $html.= '<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid ">
        <div class="kt-portlet">
            <div class="kt-portlet__body kt-portlet__body--fit">
                <div class="kt-invoice-2">
                    <div class="kt-invoice__head">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="kt-invoice__brand"> 
                                            <div href="#" class="kt-invoice__logo">
                                            <a href="#"><img src="' . \URL::asset('resources/files/uploads/logos/cms-logo-png.png') . '"></a>                                       
                                        </div>
                                    </div>                                
                                </div>
                                <div class="col-md-5">
                                    <div class="kt-invoice__items">
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Address:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['hospital_address'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Phone Number:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_phone'].'</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Email:</b></span>
                                    <span class="kt-invoice__text">'.$item_general['contact_email'].'<br>Fredrick Nebraska 20620</span>
                                </div>
                                <div class="kt-invoice__item">
                                    <span class="kt-invoice__subtitle"><b>Website:</b></span>
                                    <span class="kt-invoice__text">hospital.com</span>
                                </div>
                            </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                    <hr>                
                    <div class="row">
                    <div class="col-md-6">Bill No: <span>'.$id.'</span></div>
                    <div class="col-md-6" style="text-align:right">Date: <span>Date: '. date('M d, Y').'</span></div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-md-6">Patient Name: <span>'.$items[0]['patient']['name'].'</span></div>
                    <div class="col-md-6">Doctor: <span>'.$items[0]['staff_doctor']['name'].'</span></div>
                    </div>
                    <hr> 
                    <div class="kt-invoice__body">
                        <div class="kt-invoice__container">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Report Days</th>
                                            <th>Unit Price</th>
                                            <th>Total (K)</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                if(!empty($items)) {          
                                    foreach($items as $key => $item){
                                        $html.= '<tr>
                                            <td>'.$item['bill_test']['test_name'].' </td>
                                            <td>'.$item['bill_test']['radiology_data']['report_days'].'</td>
                                            <td>'.$item['test_price'].'</td>
                                            <td class="kt-font-danger kt-font-lg">'.$item['test_price'].'</td>
                                        </tr>';
                                          }
                                }else {              
                                         $html.= 'No Record Found';             
                                }   
                                    $html.= ' </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="kt-invoice__footer">
                        <div class="kt-invoice__container">
                            <div class="row">
                                <div class="col-md-7"></div>
                                <div class="col-md-5"style="text-align:right">
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Total:</span> <span>K '.$item['total'].'</span></div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Discount:</span> <span>'.$item['discount'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Tax:</span> <span>'.$item['tax'].'</span>(%)</div>
                                    <div><span style="font-size:16px;font-weight:600;color:#212526;">Net Amount:</span> <span>K '.$item['net_amount'].'</span></div>
                                    </div>
                                </div>                               
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>'; 
        echo $html; exit;

    }


}