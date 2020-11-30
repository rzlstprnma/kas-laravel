<?php

namespace App\Http\Controllers;

use App\Income;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $i = 1;
        $incomes = Income::all();
        return view('admin.income', compact('incomes', 'i'));
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        if(!empty($request->from_date)){
            $incomes = Income::where('user_id', Auth::user()->id)->whereBetween('created_at', array($request->from_date, $request->to_date))->orderBy('created_at', 'DESC')->get();
            
            return DataTables::of($incomes)->addIndexColumn()->addColumn('action', function($income){
                return '
                <a onClick="editIncome('.$income->id.')" class="btn btn-warning text-white btn-sm">Edit</a>            
                <a onClick="destroyIncome('.$income->id.')" class="btn btn-danger text-white btn-sm">Delete</a>
                ';
            })->make(true);
        }else{
            $incomes = Income::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
            
            return DataTables::of($incomes)->addIndexColumn()->addColumn('action', function($income){
                return '
                <a onClick="editIncome('.$income->id.')" class="btn btn-warning text-white btn-sm">Edit</a>            
                <a onClick="destroyIncome('.$income->id.')" class="btn btn-danger text-white btn-sm">Delete</a>
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
            'income_name' => 'required',
            'nominal' => 'required'
        ]);
        $now = Carbon::now()->toDateTime();
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            Income::create([
                'user_id' => $request->user_id,
                'income_name' => $request->income_name,
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
        $income = Income::find($id);
        return response()->json($income);
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
            'income_name' => 'required',
            'nominal' => 'required'
        ]);
        if($validation->fails()){
            return response()->json([
                'message' => 'Error, Ada kesalahan dalam pengisian Data',
                'data'   => $validation->errors()
            ],401);
        }else{
            $now = Carbon::now()->toDateTime();
            $income = Income::find($id);
            $income->update([
                'user_id' => $request->user_id,
                'income_name' => $request->income_name,
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
        Income::destroy($id);
    }
}
