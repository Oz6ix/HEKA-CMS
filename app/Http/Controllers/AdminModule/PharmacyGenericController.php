<?php

namespace App\Http\Controllers\AdminModule;

use App\Http\Controllers\Controller;
use App\Models\PharmacyGeneric;
use Illuminate\Http\Request;

class PharmacyGenericController extends Controller
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
        $this->page_title = "Generic";
        $this->page_heading = "Generic";
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
        // dd($items);  
        generate_log('Generic accessed');
        $generics = PharmacyGeneric::where('del_status','0')->orderBy('id','DESC')->get();
        return view('backend.admin_module.pharmacy_generic.index', compact('generics'))->with($this->page_info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin_module.pharmacy_generic.create')->with($this->page_info);
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

        $generic = new PharmacyGeneric();
        $generic->generic = $request->name;
        $generic->save();
        generate_log('A new generic created', $generic->id);
        return redirect($this->url_prefix . '/pharmacy_generic')->with('message', 'New generic added');
        
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
        generate_log('Generic edit accessed');
        $generic = PharmacyGeneric::findOrFail($id);

        return view('backend.admin_module.pharmacy_generic.edit', compact('generic'))->with($this->page_info);
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
            
        $generic = PharmacyGeneric::findOrFail($id);

        $generic->generic = $request->name;
        $generic->save();

        return redirect($this->url_prefix . '/pharmacy_generic')->with('message', 'Update successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $generic = PharmacyGeneric::where('id', $id)->first();

        $generic->del_status = '1';
        $generic->save();

        return redirect()->back()->with('message', 'Success Delete');
    }
}
