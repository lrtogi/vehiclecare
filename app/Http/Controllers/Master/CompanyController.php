<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Models\Master\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use DB;

class CompanyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::all();

        return view('admin.master.company.index')
                ->with('company', $company)
                ->with('pageTitle', "Company List");
    }

    public function showForm(Request $request, $id = null){
        try{
            if($id == null)
                $model = new Company();
            else
                $model = Company::findOrFail($id);

            return view('admin.master.company.form')
            ->with('pageTitle', "Company Form")
            ->with('model',$model);
        }
        catch(\Exception $e){
            log::debug($e->getMessage().' on line ' . $e->getLine() . ' on File ' . $e->getFile());
            $message = "Vehicle ID Not Found";
            return redirect()->back()->with('error', $message);
        }
    }

    public function store(Request $request)
    {
        $messages = [
            'vehicle_type.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'vehicle_type' => 'required'
            ], $messages);
    }

    public function void(Request $request)
    {
        $messages = [
            'company_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'company_id' => 'required'
            ], $messages);
        DB::beginTransaction();
        $company = Company::findOrFail($request->company_id);
        $company->active = 0;
        $company->save();
        DB::commit();
        return redirect()->back()->with([
            'success' => "Success deactivate company."
        ]);
    }

    public function unvoid(Request $request)
    {
        $messages = [
            'company_id.required' => ':Attribute can not empty'
        ];
        $this->validate($request, [
            'company_id' => 'required'
            ], $messages);
        DB::beginTransaction();
        $company = Company::findOrFail($request->company_id);
        $company->active = 1;
        $company->save();
        DB::commit();
        return redirect()->back()->with([
            'success' => "Success activate company."
        ]);
    }

    public function getUser(Request $request, $user_type){
        $users = User::where('locked', 0)->whereNull('company_id')->where('user_type', $user_type)->get();
        return response()->json([
            'result' => true,
            'data' => $users
        ]);
    }
}
