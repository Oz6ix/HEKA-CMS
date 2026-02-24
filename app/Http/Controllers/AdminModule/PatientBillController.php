<?php
/**
 * Created By Anu Abraham
 * Created at : 23-07-2021
 * Modified At :09-11-2021
 * 
 */
namespace App\Http\Controllers\AdminModule;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\SettingsSiteGeneral;
/**
 * Class PatientBillController
 * @package App\Http\Controllers\AdminModule
 */
class PatientBillController extends Controller
{
    /**
     * PatientBillController constructor.
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
        /* $total=PatientBill::selectRaw("SUM(medicine_price*quantity) as total" )
        ->where('bill_type', 2)
        ->groupBy('bill_number')
        ->orderBy('id', 'desc')
        ->get(); */
        $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->where('bill_type', 2)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();
        generate_log('Bill list accessed');
        return view('backend.admin_module.bills.index', compact('items'))->with($this->page_info);
    }
        /**
     * This method is used for list a pharmacy bill
     * @return \Illuminate\Http\RedirectResponse
     */

    /* public function pharmacy_bill_list()              
    {
        $total=PatientBill::selectRaw("SUM(hospital_charge_price) + SUM(medicine_price) as total" )
                                    ->groupBy('appointment_id')
                                    ->get();
        $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();
        generate_log('Bill list accessed');
        return view('backend.admin_module.bills.index', compact('items','total'))->with($this->page_info);
    } */
        /**
     * This method is used for display an diagnosis details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function show($id)
    {

        
    }

        /**
     * This method is used for create an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */

    public function create()
    {
        $items = Appointment::with('patient')
                            ->where('status', 1)
                            ->where('pharmacy_bill_status', '<>',1)
                            ->where('delete_status', 0)
                            ->orderBy('id', 'desc')
                            ->get();

        return view('backend.admin_module.bills.create', compact('items'))->with($this->page_info);

    }
        /**
     * This method is used for dispaly an appointment details for billing via ajax
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ajax_casenumber($appointment_id)
    {
        $item_appointment = Appointment::with('patient')
                            ->with('staff_doctor')
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('id',$appointment_id)
                            ->get();
        $item_bill = PatientBill::where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->get();
        if(count($item_bill)>0){
        $billnumber = $item_bill[0]['bill_number'];  
        }
        else{
            $billnumber = '00000';  
        }                          
        $item_bill_check = PatientBill::where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->where('bill_type', 2)
                            ->groupBy('bill_number')
                            ->get();
/* dd(count($item_bill_check)); */
$total=0;
        if(count($item_bill_check)>0){
        $item_prescription = PatientBill::with('bill_medicine')
                             ->where('appointment_id', $appointment_id)
                             ->where('delete_status', 0)
                             ->where('bill_type', 2)
                             ->where('status', 1)
                             ->get(); 
        $item_prescription_out = PatientPrescription::where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('drug_id', 0)
                            ->where('status', 1)
                            ->get();                              
        }else{
        $item_prescription = PatientPrescription::with('pharmacy_data')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('drug_id', '<>',0)
                            ->where('status', 1)
                            ->get(); 
        $total=$item_prescription->sum('pharmacy_data.price');                    
        $item_prescription_out = PatientPrescription::with('unit')
                            ->with('frequency')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('drug_id', 0)
                            ->where('status', 1)
                            ->get(); 
        }     
        $patient_name=$item_appointment[0]['patient']['name'];
        $patient_id=$item_appointment[0]['patient']['id'];
        $appointment_date = $item_appointment[0]['appointment_date'];
        $doctor_name = $item_appointment[0]['staff_doctor']['name'];
        $doctor_id = $item_appointment[0]['staff_doctor']['id'];
        



        return response()->json(['status'=>"success",
                         'patient_name'=>$patient_name,
                         'total'=>$total,
                         'patient_id'=>$patient_id,
                         'appointment_date'=>$appointment_date,
                         'doctor_name'=>$doctor_name,
                         'doctor_id'=>$doctor_id,
                         'billnumber'=>$billnumber,
                         'item_prescription'=>$item_prescription,
                         'item_prescription_out'=>$item_prescription_out,
                        ]);

    }
        /**
     * This method is used for store an diagnosis details in diagnosis 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $data = $request->except('_token');

        $validator = PatientBill::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        else{

        if($data['appointment_id']!=null){
            
            $data['bill_type'] =2;
            if($data['tax']==""||$data['tax']==NULL)
            $data['tax']=0;
            if($data['discount']==""||$data['discount']==NULL)
            $data['discount']=0;
            
            //PatientBill::create($data);
            $deletedRows = PatientBill::where('bill_type',2)->where('appointment_id',$data['appointment_id'])->update(['delete_status' => 1]);

            if(isset($request->prescription) || !empty($request->prescription) ){
                //dd($data);

                foreach ($request->prescription as $index => $value)
                {
                    if($request->prescription[$index]['drug_name']!=""&&
                    $request->prescription[$index]['quantity']!=""&&
                    $request->prescription[$index]['medicine_price']!=""&&
                    $request->prescription[$index]['diagnosis_id']!=""
                    ){
                        $data['diagnosis_id'] =$request->prescription[$index]['diagnosis_id'];
                        $data['prescription_id'] =$request->prescription[$index]['prescription_id'];
                        $data['medicine_price'] =$request->prescription[$index]['medicine_price'];
                        $data['bill_date'] = date('Y-m-d H:i:s');
                        PatientBill::create($data);
                    }
                    else if($request->prescription[$index]['drug_name']==""&&
                    $request->prescription[$index]['quantity']==""&&
                    $request->prescription[$index]['medicine_price']==""&&
                    $request->prescription[$index]['diagnosis_id']==""
                    ){
                       
                    }
                    else{
                        return response()->json(['validation'=>"All Prescription fields are not filled"]);
                    }
                }
                Appointment::where('id', $data['appointment_id'])->update(['pharmacy_bill_status' => 1]);

                generate_log('Pharmacy bill created');
                return redirect($this->url_prefix . '/bills')->with('message', 'Pharmacy bill added.');
            } 
        }
        }
    }

    /**
     * This method is used for edit an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {

    }


    /**
     * This method is used for updating an diagnosis details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {

    }

    /**
     * This method is used for deleting an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($id)
    {
    }
    /**
     * This method is used for deleting set of  an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy_multiple($ids)
    {
    }


    /* Custom methods */
    public function change_status($status,$id = null)
    {
    } 

    /**
     * This method is used for fetch data for billing 
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function ajax_fetch_bill_print_data($id = null){
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



}