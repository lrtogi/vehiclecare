<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master\Worker;
use App\Models\Master\Package;
use App\Models\Transaction\Payment;
use App\Models\Transaction\Transaction;

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
    public function index()
    {
        $company_id = auth()->user()->company_id;
        $vehicleType = Package::select('m_vehicle.*')->join('m_vehicle', 'm_vehicle.vehicle_id', 'm_package.vehicle_id')->where('m_package.company_id', $company_id)->where('m_package.active', 1)->groupBy('m_vehicle.vehicle_id')->get();

        return view('home')
            ->with('pageTitle', 'Home')
            ->with('vehicleType', $vehicleType);
    }

    public function getDashboard()
    {
        $company_id = auth()->user()->company_id;
        $paymentApproval = Payment::join('transactions', 'transactions.transaction_id', 'payments.transaction_id')->join('m_package', 'm_package.package_id', 'transactions.package_id')->where('approved', 0)->where('m_package.company_id', $company_id)->count();
        $totalWorkers = Worker::where('approved', 1)->where('company_id', $company_id)->where('active', 1)->count();
        $monthlyEarnings = Transaction::join('m_package', 'm_package.package_id', 'transactions.package_id')->where('m_package.company_id', $company_id)->where('transactions.status', 2)->sum('total_price');
        return response()->json([
            'monthlyEarnings' => $monthlyEarnings,
            'paymentApproval' => $paymentApproval,
            'totalWorkers' => $totalWorkers,
            'result' => true
        ]);
    }
}
