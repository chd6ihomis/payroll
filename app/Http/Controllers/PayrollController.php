<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Payroll;
use App\Salary;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role == 0) {

            $payrolls = Payroll::where('office', auth()->user()->office)->orderBy('created_at', 'DESC')->get();
            $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

            return view('payrolls.index', compact('payrolls', 'notifications'));
        } else {
            $payrolls = Payroll::orderBy('created_at', 'DESC')->get();
            $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

            return view('payrolls.index', compact('payrolls', 'notifications'));
        }
    }

    public function filter()
    {

        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $payrolls = Payroll::join('office', 'office.id', '=', 'payrolls.office')->where('office.division', $division)->select('payrolls.period')->distinct()->get();
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        return view('payrolls.modal', compact('payrolls', 'notifications'));
    }

    public function showall()
    {

        $data = request()->validate([
            'period' => 'required',
            'type' => 'required'
        ]);

        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $arr = [];
        $period = $data['period'];
        $type = $data['type'];
        $salaries = Salary::where('period', $data['period'])
            ->where('payroll_type', $data['type'])
            ->where('division', $division)
            ->where('isCorrect', 'Y')
            ->join('employees', 'employees.id', '=', 'salaries.employee_id')
            ->select('salaries.*', 'employees.*', 'salaries.id as sid', 'salaries.monthly_rate as smonthly_rate')
            ->orderBy('employees.employee_name')->get();

        $employees = Salary::join('payrolls', 'payrolls.id', '=', 'salaries.payroll_id')->where('salaries.period', $period)->where('division', $division)->select('salaries.*')->distinct()->get();

        foreach ($employees as $emp) {
            array_push($arr, $emp->employee_id);
        }

        $salaries_no_processed = Employee::where('status', 'true')->join('office', 'office.id', '=', 'employees.office')->where('office.division', $division)->whereNotIn('employees.id', $arr)->get();
        $salaries_for_checking = Salary::where('period', $data['period'])->where('division', $division)->where('isCorrect', 'D')->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid')->orderBy('employees.employee_name')->get();
        $salaries_for_correction = Salary::where('period', $data['period'])->where('division', $division)->where('isCorrect', 'N')->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid')->orderBy('employees.employee_name')->get();

        $total_employees = Employee::join('office', 'office.id', '=', 'employees.office')->where('office.division', $division)->where('status', 'true')->get();

        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        return view('payrolls.filter', compact('salaries', 'period', 'total_employees', 'notifications', 'salaries_no_processed', 'salaries_for_checking', 'salaries_for_correction', 'type'));
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
        $data = request()->validate([
            'payroll_period' => 'required',
            'type' => 'required',
        ]);

        $arr = explode('-', $data['payroll_period'], 2);

        $period_start = Carbon::parse(str_replace(" ", '', $arr[0]))->format('Y-m-d');
        $period_end = Carbon::parse(str_replace(" ", '', $arr[1]))->format('Y-m-d');

        $period = Carbon::parse($period_start)->shortMonthName . " "
            . Carbon::parse($period_start)->day . "-" . Carbon::parse($period_end)->day
            . ", " . Carbon::parse($period_start)->year;

        $payroll = new Payroll([
            'office' => auth()->user()->office,
            'period' => $period,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'period_raw' => $data['payroll_period'],
            'type' => $data['type'],
        ]);

        $payroll->save();

        $notification = array(
            'message' => 'Payroll successfully created',
            'alert-type' => 'success'
        );

        return redirect('/payrolls')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payroll = Payroll::find($id);
        $salaries = Salary::where('payroll_id', $payroll->id)->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->paginate(20);
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        return view('payrolls.show', compact('payroll', 'salaries', 'notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payroll  $payroll
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payroll $payroll)
    {
        Salary::where('payroll_id', $payroll->id)->delete();

        $payroll->delete();

        $notification = array(
            'message' => 'Payroll and included Employees salaries successfully deleted!',
            'alert-type' => 'error'
        );

        return redirect('payrolls')->with($notification);
    }

    public function printpayroll(Request $request)
    {

        $data = request()->validate([
            'period' => 'required',
            'type' => 'required',
        ]);

        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $period = $data['period'];

        if (isset($request->isconap)) {
            $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')->where('isConap', 'Y')->where('payroll_type', $data['type'])->where('period', $data['period'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
            $desc = 'CONAP';
        } else {
            $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')->where('isConap', 'N')->where('payroll_type', $data['type'])->where('period', $data['period'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
            $desc = 'REGULAR';
        }

        $pagibig = isset($request->pagibig) ? 1 : 0;
        $sss = isset($request->sss) ? 1 : 0;
        $philhealth = isset($request->philhealth) ? 1 : 0;
        $comm_allowance = isset($request->comm_allowance) ? 1 : 0;

        $style = $pagibig + $sss + $philhealth;
        $pages = round($salaries->count() / 10) + 1;
        $rows = 10;

        $userDiv = DB::table('office')->where('id', auth()->user()->office)->first();
        $chiefDiv = DB::table('signatories')->where('division', $userDiv->division)->first();
        $rd = DB::table('signatories')->where('id', 1)->first();
        $accountant = DB::table('signatories')->where('id', 2)->first();
        $cashier = DB::table('signatories')->where('id', 3)->first();

        return view('template', compact('salaries', 'period', 'pagibig', 'sss', 'philhealth', 'comm_allowance', 'style', 'pages', 'rows', 'chiefDiv', 'rd', 'accountant', 'cashier', 'desc'));
    }

    public function cashiercopy(Request $request)
    {

        $data = request()->validate([
            'period' => 'required',
            'type' => 'required',
        ]);

        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $period = $data['period'];

        if (isset($request->isconap)) {
            $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')->where('isConap', 'Y')->where('payroll_type', $data['type'])->where('period', $data['period'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
            $desc = 'CONAP';
        } else {
            $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')->where('isConap', 'N')->where('payroll_type', $data['type'])->where('period', $data['period'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
            $desc = 'REGULAR';
        }

        $pages = round($salaries->count() / 20) + 1;
        $rows = 20;



        return view('template2', compact('salaries', 'period', 'pages', 'rows', 'desc'));
    }

    public function getPayrollId($id)
    {
        $payroll = Payroll::find($id);

        return response()->json([
            'data' => $payroll
        ]);
    }

    public function duplicatepayroll(Request $request)
    {

        // Duplicate Payroll
        $payroll = Payroll::find($request->getpayroll);

        $arr = explode('-', $request->payroll_period, 2);
        $period_start = Carbon::parse(str_replace(" ", '', $arr[0]))->format('Y-m-d');
        $period_end = Carbon::parse(str_replace(" ", '', $arr[1]))->format('Y-m-d');
        $period = Carbon::parse($period_start)->shortMonthName . " "
            . Carbon::parse($period_start)->day . "-" . Carbon::parse($period_end)->day
            . ", " . Carbon::parse($period_start)->year;

        $new = $payroll->replicate()->fill([
            'period' => $period,
            'period_start' => $period_start,
            'period_end' => $period_end,
            'period_raw' => $request->payroll_period,
            'type' => $request->type,
        ]);

        $new->save();

        // Duplicate Salaries
        $salaries = Salary::where('payroll_id', $request->getpayroll)->get();

        foreach ($salaries as $salary) {

            $new_salary = $salary->replicate()->fill([
                'payroll_id' => $new->id,
                'payroll_type' => $new->type,
                'payroll_date' => Carbon::parse(str_replace(" ", '', $arr[1]))->startOfMonth()->format('Y-m-d'),
                'period' => $new->period,
                'day' => 0,
                'hr' => 0,
                'min' => 0,
                'deductions' => 0.00,
                'soa' => $salary->basic,
                'coop' => 0,
                'tax' => 0,
                'pagibig' => 0,
                'sss' => 0,
                'philhealth' => 0,
                'comm_allowance' => 0,
                'net_amt' => $salary->basic,
                'isCorrect' => 'D',
            ]);

            $new_salary->save();
        }

        $notification = array(
            'message' => 'Payroll and Salaries have been successfully duplicated!',
            'alert-type' => 'success'
        );

        return redirect('payrolls')->with($notification);

    }

    public function printobr($period, $type)
    {
        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')
            ->where('payroll_type', $type)
            ->where('period', $period)
            ->where('isConap', 'N')
            ->select('office', 'fund_source', DB::raw('sum(soa) as total'))
            ->groupBy('office', 'fund_source')->get();

        $userDiv = DB::table('office')->where('id', auth()->user()->office)->first();
        $chiefDiv = DB::table('signatories')->where('division', $userDiv->division)->first();
        $budget = DB::table('signatories')->where('id', 4)->first();
        $desc = 'REGULAR';


        return view('obr', compact('chiefDiv', 'budget', 'salaries', 'period', 'desc'));
    }

    public function printobrConap($period, $type)
    {
        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')
            ->where('payroll_type', $type)
            ->where('period', $period)
            ->where('isConap', 'Y')
            ->select('office', 'fund_source', DB::raw('sum(soa) as total'), DB::raw('sum(comm_allowance) as comm_allowance'))
            ->groupBy('office', 'fund_source')->get();

        $userDiv = DB::table('office')->where('id', auth()->user()->office)->first();
        $chiefDiv = DB::table('signatories')->where('division', $userDiv->division)->first();
        $budget = DB::table('signatories')->where('id', 4)->first();
        $desc = 'CONAP';


        return view('conap', compact('chiefDiv', 'budget', 'salaries', 'period', 'desc'));
    }

    public function printdv($period, $type)
    {
        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')
            ->where('isConap', 'N')
            ->where('payroll_type', $type)
            ->where('period', $period)
            ->select(
                'fund_source',
                DB::raw('sum(net_amt) as net'),
                DB::raw('sum(basic) as basic'),
                DB::raw('sum(deductions) as deductions'),
                DB::raw('sum(comm_allowance) as comm_allowance'),
                DB::raw('sum(soa) as soa'),
                DB::raw('sum(pagibig)  as pagibig'),
                DB::raw('sum(sss)  as sss'),
                DB::raw('sum(philhealth)  as philhealth'),
                DB::raw('sum(tax) as tax'),
                DB::raw('sum(coop) as coop'),
                DB::raw('sum(coop_loan) as coop_loan')
            )
            ->groupBy('fund_source')->get();

        $userDiv = DB::table('office')->where('id', auth()->user()->office)->first();
        $chiefDiv = DB::table('signatories')->where('division', $userDiv->division)->first();
        $rd = DB::table('signatories')->where('id', 1)->first();
        $accountant = DB::table('signatories')->where('id', 2)->first();
        $desc = 'REGULAR';

        return view('dv', compact('chiefDiv', 'rd', 'accountant', 'salaries', 'period', 'desc'));
    }

    public function printdvConap($period, $type)
    {
        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $salaries = Salary::where('division', $division)->where('isCorrect', 'Y')
            ->where('isConap', 'Y')
            ->where('payroll_type', $type)
            ->where('period', $period)
            ->select(
                'fund_source',
                DB::raw('sum(net_amt) as net'),
                DB::raw('sum(basic) as basic'),
                DB::raw('sum(deductions) as deductions'),
                DB::raw('sum(comm_allowance) as comm_allowance'),
                DB::raw('sum(soa) as soa'),
                DB::raw('sum(pagibig)  as pagibig'),
                DB::raw('sum(sss)  as sss'),
                DB::raw('sum(philhealth)  as philhealth'),
                DB::raw('sum(tax) as tax'),
                DB::raw('sum(coop) as coop')
            )
            ->groupBy('fund_source')->get();

        $userDiv = DB::table('office')->where('id', auth()->user()->office)->first();
        $chiefDiv = DB::table('signatories')->where('division', $userDiv->division)->first();
        $rd = DB::table('signatories')->where('id', 1)->first();
        $accountant = DB::table('signatories')->where('id', 2)->first();
        $desc = "CONAP";

        return view('dv-conap', compact('chiefDiv', 'rd', 'accountant', 'salaries', 'period', 'desc'));
    }
}
