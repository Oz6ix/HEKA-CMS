<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Tpa;
use App\Models\Casualty;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\PatientBriefNote;
use App\Models\SymptomType;
use App\Models\AppointmentBasicsDetail;
use App\Models\PatientDiagnosis;
use App\Models\PatientPrescription;
use App\Models\MedicalConsumableUsed;
use App\Models\PatientMedicalTest;
use App\Models\Center;
use App\Models\HospitalCharge;
use App\Models\PatientBillRadiology;
use App\Models\PatientBillPathology;
use App\Models\PatientBill;
use App\Models\InventoryItemMaster;
use App\Models\InventoryCategory;
use App\Models\Units;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;




class ReportController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Reports";
        $this->page_heading = "Reports";
        $this->heading_icon = "fa-chart-bar";
        $this->directory_staff = "staff";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_staff' => $this->directory_staff];
    }



    public function index($project_id=null)
    {          
        generate_log('Report accessed');
        return view('backend.admin_module.reports.index')->with($this->page_info);
    }

    /* Report events */
    public function appointment_report($doctor_id=null,$patient_id=null)
    {

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile(public_path() . '/uploads/appointment_report.xlsx');
        
        /** Create a style with the StyleBuilder */
        $style = (new StyleBuilder())
                   ->setFontBold()
                   ->setFontSize(15)
                   ->setFontColor(Color::BLUE)
                   ->setShouldWrapText()
                   ->setCellAlignment(CellAlignment::RIGHT)
                   ->setBackgroundColor(Color::YELLOW)
                   ->build();
        
        $selected_doctor_id=$doctor_id;
        $selected_patient_id=$patient_id;
        $doctor_item = Staff::select('name','id','designation_id','department_id','staff_code')
                          ->where('status',1)
                            ->where('delete_status',0)
                            ->whereIn('designation_id',array(1,2))
                            ->with('staff_designation')
                            ->with('staff_department')
                            ->orderBy('name', 'asc')
                            ->get();
        $patient_item = Patient::where('delete_status', 0)->orderBy('id', 'desc')->get();
        if($doctor_id !='null' && $patient_id !='null'){    //echo "1111";            
            $select_appointment = Appointment::where('patient_id',$patient_id)->where('doctor_staff_id',$doctor_id)->with('patient')->with('staff_doctor')->where('delete_status', 0)->orderBy('id', 'desc')->get();
            $select_appointment = collect($select_appointment)->toArray();
        }elseif($doctor_id !='null' && $patient_id =='null'){  //echo "2222";  
            $select_appointment = Appointment::where('doctor_staff_id',$doctor_id)->with('patient')->with('staff_doctor')->where('delete_status', 0)->orderBy('id', 'desc')->get();
            $select_appointment = collect($select_appointment)->toArray();
        }elseif($doctor_id =='null' && $patient_id !='null'){  //echo "3333";  
            $select_appointment = Appointment::where('patient_id',$patient_id)->with('patient')->with('staff_doctor')->where('delete_status', 0)->orderBy('id', 'desc')->get();
            $select_appointment = collect($select_appointment)->toArray();
        }else{  //echo "44444";  
             $select_appointment = Appointment::where('patient_id',$patient_id)->where('doctor_staff_id',$doctor_id)->with('patient')->with('staff_doctor')->where('delete_status', 0)->orderBy('id', 'desc')->get();
            $select_appointment = collect($select_appointment)->toArray();
        }   
/* Creating eport excel */
        $row = WriterEntityFactory::createRowFromArray(['#', 'Patient Name', 'Date','Phone','Case No.','Doctor'], $style);
        $writer->addRow($row);
        if(isset($select_appointment) && sizeof($select_appointment) > 0)  {
            $slno = 0;
            foreach ($select_appointment as $item){
            $slno++;
            $row=WriterEntityFactory::createRowFromArray([  $slno,
            $item['patient']['name'],
            date('M d, Y', strtotime($item['appointment_date'])),
            $item['patient']['phone'],
            $item['case_number'],
            $item['staff_doctor']['name'],
            ]);
            $writer->addRow($row);
        }
    }
        /** Create a row with cells and apply the style to all cells */
     //   $row = WriterEntityFactory::createRowFromArray(['Carl', 'is', 'great'], $style);
        
        /** Add the row to the writer */
       
        $writer->close();
/* End Creating eport excel */

        generate_log('Appointment report accessed');
        return view('backend.admin_module.reports.appointment_report',compact('doctor_item','select_appointment','selected_doctor_id','selected_patient_id','patient_item'))->with($this->page_info);
    }  



    public function revenue_report($type=null)
    {

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile(public_path() . '/uploads/revenue_report.xlsx');
        
        /** Create a style with the StyleBuilder */
        $style = (new StyleBuilder())
                   ->setFontBold()
                   ->setFontSize(15)
                   ->setFontColor(Color::BLUE)
                   ->setShouldWrapText()
                   ->setCellAlignment(CellAlignment::RIGHT)
                   ->setBackgroundColor(Color::YELLOW)
                   ->build();


        //$type=5;;        
        $selected_type=$type;
        if($type !='null' && $type==2){    //pharmacy           
            $items = PatientBill::with('patient')
                            ->with('staff_doctor')
                            ->where('bill_type', $type)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();                 
        }
        else if($type !='null' && $type==3){    //pharmacy           
            $items =  PatientBillPathology::with('patient')
                            ->with('staff_doctor')
                                                    
                            ->where('bill_type', 3)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();                 
        }
        else if($type !='null' && $type==4){    //pharmacy           
            $items =  PatientBillRadiology::with('patient')
                            ->with('staff_doctor')
                                                   
                            ->where('bill_type', 4)
                            ->where('delete_status', 0)
                            ->where('status',1)
                            ->groupBy('bill_number')
                            ->orderBy('id', 'desc')
                            ->get();                 
        }
        else{   
            $items = PatientBill::with('patient')
                                ->with('staff_doctor')
                                ->where('bill_type', 1)
                                ->where('delete_status', 0)
                                ->where('hospital_charge_status', 1)
                                ->where('status',1)
                                ->groupBy('bill_number')
                                ->orderBy('id', 'desc')
                                ->get(); 

        } 

        //dd($items);
        $row = WriterEntityFactory::createRowFromArray(['#', 'Bill No.', 'Date','Patient Name','Doctor','Discount','Tax (%)','Total','Net Amount (K)'], $style);
        $writer->addRow($row);
        if(isset($items) && sizeof($items) > 0)  {
        $slno = 0;
        foreach ($items as $item){
        $slno++;
        $row=WriterEntityFactory::createRowFromArray([  $slno,
        $item['bill_number'],
        date('M d, Y', strtotime($item['bill_date'])),
            $item['patient']['name'],
            $item['staff_doctor']['name'],
            $item['discount'],
            $item['tax'],
            $item['total'],
            $item['net_amount']
                ]);
        $writer->addRow($row);
            }
        }
        /** Create a row with cells and apply the style to all cells */
     //   $row = WriterEntityFactory::createRowFromArray(['Carl', 'is', 'great'], $style);
        
        /** Add the row to the writer */
       
        $writer->close();
        
        
        generate_log('Revenue report accessed');
        return view('backend.admin_module.reports.revenue_report',compact('selected_type','items'))->with($this->page_info);
    }   
    public function download_revenue_report()
    {
        $file = public_path().'/uploads/revenue_report.xlsx';
        return \Response::download($file);
    }    
    public function export_revenue_report(Request $request)
    {
       // dd($request);

       $file = $_FILES['export_file'];
       $tmpName = $file ['tmp_name'];    
       $reader = ReaderEntityFactory::createXLSXReader();
       $reader ->open($tmpName);
        $i=0;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // do stuff with the row
                //echo $cells = $row->getCells();
                if($i==0){
                $row->getCells();
                $cells[1] = "";
                $i++;
                }
                else{
                $cells = $row->getCells();
                }
                if($cells[1]!=""&&$cells[2]!=""&&$cells[3]!=""){
                $inventory_category = InventoryCategory::where('inventory_name',$cells[2])
                ->where('status',1)
                ->where('delete_status', 0)
                ->orderBy('inventory_name', 'asc')
                ->get('id');
                //dd($inventory_category[0]['id']);
                $units = Units::where('unit',$cells[3])
                ->where('status',1)
                ->where('delete_status', 0)
                ->orderBy('unit', 'asc')
                ->get('id');
                if (count($inventory_category) == 0)
                    return redirect($this->url_prefix . '/report/revenue_report')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');
                if (count($units) == 0)
                    return redirect($this->url_prefix . '/report/revenue_report')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');

                $data['master_code']=generate_item_master_code();         
                $data['item_name']=$cells[1];         
                $data['inventory_category_id']=$inventory_category[0]['id'];         
                $data['inventory_unit']=$units[0]['id'];    
                $data['description']=$cells[4];         
                $validator = InventoryItemMaster::validate_add($data);
                if ($validator->fails()) {
                    return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
                }
                $new_record = InventoryItemMaster::create($data);
}               
            }
            
        }
       // dd($cells);
        $reader->close();
    
    }    
    

}