<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use Log;

class AdminController extends Controller
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
        $companyPending = Company::where('approved', 0)->count();
        return view('admin.home')
        ->with('companyPending', $companyPending);
    }

    public function getSearch(Request $request, $active, $approved) {
        $offset = $request->start;
        $per_page = $request->length;
        $limit = $per_page;
        $keyword = $request->search['value'];
        $order = $request->order[0];
        $sort = [];
        foreach ($request->order as $key => $o) {
            $columnIdx = $o['column'];
            $sortDir = $o['dir'];
            $sort[] = [
                'column' => $request->columns[$columnIdx]['name'],
                'dir' => $sortDir
            ];
        }
        $columns = $request->columns;
        $draw = $request->draw;
        $current_page = $offset / $limit + 1;
        $model = new Company();
        $fields = $model->getTableColumns();
        
        $company = Company::where('active', $active)->where('approved', $approved);
        // search data
        if ($keyword != null) {
            if (!empty($keyword)) {
                $company->where(function ($query) use ($keyword, $fields) {
                    foreach ($fields as $column) {
                        $query->orWhere('m_company.'.$column, 'LIKE', "%$keyword%");
                    }
                });
            }
        }
        $filteredData = $company->get();

        // sort data
        if (!empty($sort)) {
            if (!is_array($sort)) {
                $message = "Invalid array for parameter sort";
                $data = [
                    'result' => FALSE,
                    'message' => $message
                ];
                $this->logActivity($request->path(), $message, print_r($_POST, TRUE));
                return response()->json($data);
            }

            foreach ($sort as $key => $s) {
                $column = $s['column'];
                $direction = $s['dir'];
                $company->orderBy($column, $direction);
            }
        } else {
            $company->orderBy('m_company.company_name', 'asc');
        }
        $total_rows = count($company->get());

        // paginate data
        if ($current_page != null) {
            $page = $current_page;
            $limit = count($company->get());
            if ($per_page != null) {
                $limit = $per_page;
            }
            $offset = ($page - 1) * $limit;
            if ($offset < 0) {
                $offset = 0;
            }
            $company->skip($offset)->take($limit);
        }
        $companyList = $company->get();

        $table['draw'] = $draw;
        $table['recordsTotal'] = $total_rows;
        $table['recordsFiltered'] = count($filteredData);
        $table['data'] = $companyList;
        // $table['post_data'] = $post_data;

        return json_encode($table);
    }

    public function getPendingCompany(){
        $companyPending = Company::where('approved', 0)->count();
        return response()->json([
            'total' => $companyPending,
            'result' => true
        ]);
    }

}
