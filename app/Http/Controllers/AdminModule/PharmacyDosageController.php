<?php

namespace App\Http\Controllers\AdminModule;

use Illuminate\Http\Request;
use App\Models\PharmacyDosage;
use App\Http\Controllers\Controller;

class PharmacyDosageController extends Controller
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
         $this->page_title = "Dosage";
         $this->page_heading = "Dosage";
         $this->heading_icon = "fa-cogs";
         $this->directory_inventory_stock = "inventory_stock_document";
         $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        generate_log('Dosage accessed');
        $dosages = PharmacyDosage::where('del_status','0')->orderBy('id','DESC')->get();
        return view('backend.admin_module.pharmacy_dosage.index', compact('dosages'))->with($this->page_info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin_module.pharmacy_dosage.create')->with($this->page_info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set('asia/yangon');
        $this->validate($request, [
            'name'=>'required',
        ]);

        $dosage = new PharmacyDosage();
        $dosage->dosage = $request->name;
        $dosage->save();
        generate_log('A new Dosage created', $dosage->id);
        return redirect($this->url_prefix . '/pharmacy_dosage')->with('message', 'New dosage added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        date_default_timezone_set('asia/yangon');
        generate_log('Dosage edit accessed');
        $dosage = PharmacyDosage::findOrFail($id);

        return view('backend.admin_module.pharmacy_dosage.edit', compact('dosage'))->with($this->page_info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        date_default_timezone_set('asia/yangon');
        $this->validate($request, [
            'name'=>'required',
        ]);
            
        $dosage = PharmacyDosage::findOrFail($id);

        $dosage->dosage = $request->name;
        $dosage->save();

        return redirect($this->url_prefix . '/pharmacy_dosage')->with('message', 'Update successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dosage = PharmacyDosage::where('id', $id)->first();

        $dosage->del_status = '1';
        $dosage->save();

        return redirect()->back()->with('message', 'Success Delete');
    }
}
