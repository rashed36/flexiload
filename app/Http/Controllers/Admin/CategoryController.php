<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\TblRecharge;
use App\Model\TblSim;
use App\Model\TblSlot;
use App\Model\TblUseSlot;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['allData'] = TblSlot::where('deleted_by',1)->get();
        return view('admin.category.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        $data = new TblSlot();
        $data->name = $request->name;
        $data->save();
        return redirect()->back()->with('success','Category Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editData = TblSlot::find($id);
        return view('admin.category.edit',compact('editData'));
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
        $data = TblSlot::find($id);
        $data->name = $request->name;
        $data->save();
        return redirect()->route('view.category')->with('success','Category Updated Successfully');  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = TblSlot::where('id',$id)->get()->first();
        $data->deleted_by='0';
        $data->update();
        return redirect()->back()->with('success','Category Deleted Successfully');
    }
}
