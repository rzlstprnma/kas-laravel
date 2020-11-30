<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ExpenseCategory::where('user_id', Auth::user()->id)->get();
        return view('admin.expense', compact('categories'));
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        if(!empty($request->from_date)){
            $expenses = Expense::where('user_id', Auth::user()->id)->whereBetween('created_at', array($request->from_date, $request->to_date))->with(['expenseCategory'])->orderBy("created_at", "DESC")->get();    
            return DataTables::of($expenses)->addIndexColumn()->addColumn('action', function($expenses){
                return '
                <a onClick="editExpense('.$expenses->id.')" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#exampleModal">Edit</a>            
                <a onClick="destroyExpense('.$expenses->id.')" class="btn btn-danger text-white btn-sm">Delete</a>
                ';
            })->make(true);            
        }else{
            $expenses = Expense::where('user_id', Auth::user()->id)->with(['expenseCategory'])->orderBy("created_at", "DESC")->get();    
            return DataTables::of($expenses)->addIndexColumn()->addColumn('action', function($expenses){
                return '
                <a onClick="editExpense('.$expenses->id.')" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#exampleModal">Edit</a>            
                <a onClick="destroyExpense('.$expenses->id.')" class="btn btn-danger text-white btn-sm">Delete</a>
                ';
            })->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'expense_category_id' => 'required',
            'expense_name' => 'required',
            'nominal' => 'required'
        ]);
        $now = Carbon::now()->toDateTime();
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            Expense::create([
                'user_id' => $request->user_id,
                'expense_category_id' => $request->expense_category_id,
                'expense_name' => $request->expense_name,
                'nominal' => $request->nominal,
                'date' => $now
            ]);
            return response()->json(["msg"   => "Data berhasil disimpan"]);
        }
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
        $expense = Expense::find($id)->with(['expenseCategory'])->orderBy("created_at", "DESC")->first();
        return response()->json($expense);
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
            'expense_category_id' => 'required',
            'expense_name' => 'required',
            'nominal' => 'required'
        ]);
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            $now = Carbon::now()->toDateTime();
            $expense = Expense::find($id);
            $expense->update([
                'user_id' => $request->user_id,
                'expense_category_id' => $request->expense_category_id,
                'expense_name' => $request->expense_name,
                'nominal' => $request->nominal,
                'date' => $now
            ]);
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
        Expense::destroy($id);
    }

    public function report(){
        return view('admin.expense_report');
    }
}
