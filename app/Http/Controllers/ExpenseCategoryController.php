<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        return view('admin.expenseCategory');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        $expenseCategory = ExpenseCategory::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();     
        
        return DataTables::of($expenseCategory)->addIndexColumn()->addColumn('action', function($expenseCategory){
            return '
            <a onClick="editCategory('.$expenseCategory->id.')" class="btn btn-warning text-white btn-sm">Edit</a>            
            <a onClick="destroyCategory('.$expenseCategory->id.')" class="btn btn-danger text-white btn-sm">Delete</a>
            ';
        })->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'category_name' => 'required'
        ]);
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            ExpenseCategory::create(['user_id' => $request->user_id, 'category_name' => $request->category_name]);
            return response()->json(["msg"   => "Data berhasil disimpan"]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expenseCategory = ExpenseCategory::find($id);
        return response()->json($expenseCategory);
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
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'category_name' => 'required'
        ]);
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            $expenseCategory = ExpenseCategory::find($id);
            $expenseCategory->update(['user_id' => $request->user_id, 'category_name' => $request->category_name]);
            return response()->json(["msg"   => "Data berhasil disimpan"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ExpenseCategory::destroy($id);
    }
}
