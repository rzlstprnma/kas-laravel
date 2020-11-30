<?php

namespace App\Http\Controllers;

use App\Income;
use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $totalIncome = Income::where('user_id', Auth::user()->id)->sum('nominal');
        $totalExpense = Expense::where('user_id', Auth::user()->id)->sum('nominal');
        $balance = $totalIncome - $totalExpense;

        $chartjs = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['Saldo', 'Pemasukan', 'Pengeluaran'])
        ->datasets([
            [
                'backgroundColor' => ['#4E73DF', '#59C98A', '#3CB9CC'],
                'data' => [$balance, $totalExpense, $totalExpense]
            ]
        ])
        ->options([]);
        // dd($balance);
        return view('admin.home', compact('totalIncome', 'totalExpense', 'balance', 'chartjs'));
    }

    public function logout () {
        //logout user
        auth()->logout();
        // redirect to homepage
        return redirect('/login');
    }
}
