<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Payroll;
use App\Salary;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

class SalaryController extends Controller
{
    /** Monthly rate at or above which the PhilHealth premium is capped. */
    private const PHILHEALTH_SALARY_CEILING = 100000;

    /** Capped premium for a full-month computation. */
    private const PHILHEALTH_CAP_MONTHLY = 5000;

    /** Capped premium for a single cutoff. */
    private const PHILHEALTH_CAP_CUTOFF = 2500;

    /** salaryComputation code that covers a whole month. */
    private const SALARY_COMPUTATION_FULL_MONTH = '2';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function filter()
    {
        $salaries = Salary::select('period')->distinct()->orderBy('period', 'DESC')->get();
        $divisions = Salary::select('division')->distinct()->get();
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        return view('salaries.modal', compact('salaries', 'divisions', 'notifications'));
    }

    public function remittance()
    {
        $salaries = Salary::select('period')->distinct()->orderBy('period', 'DESC')->get();
        $divisions = Salary::select('division')->distinct()->get();
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        return view('salaries.modal-remittance', compact('salaries', 'divisions', 'notifications'));
    }

    public function showall()
    {
        $data = request()->validate([
            'period' => 'required',
            'type' => 'required',
            'status' => 'required',
            'division' => 'required'
        ]);

        if ($data['status'] == '2') {
            $salaries = Salary::where('division', $data['division'])->where('isCorrect', 'N')->where('period', $data['period'])->where('payroll_type', $data['type'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
        } else {
            $salaries = Salary::where('division', $data['division'])->where('period', $data['period'])->where('payroll_type', $data['type'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
        }

        $period = $data['period'];
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();


        return view('salaries.filter', compact('salaries', 'period', 'notifications'));
    }

    public function remittanceShowAll()
    {
        $data = request()->validate([
            'period' => 'required',
            'type' => 'required',
            'division' => 'required'
        ]);

        $salaries = Salary::where('division', $data['division'])->where('isCorrect', 'Y')->where('period', $data['period'])->where('payroll_type', $data['type'])->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
        $period = $data['period'];
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();


        return view('salaries.remittance', compact('salaries', 'period', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'payroll_id' => 'required',
            'period' => 'required',
            'payroll_type' => 'required',
            'salaryComputation' => 'required',
            'employee_id' => 'required',
            'day' => 'required',
            'hrs' => 'required',
            'mins' => 'required',
            'tax' => 'required',
            'pagibig' => 'required',
            'sss' => 'required',
            'philhealth_otc' => 'required',
            'coop' => 'required',
            'coop_loan' => 'required',
            'comm_allowance' => 'required',
            'working_days' => 'required'
        ]);

        $exists = Salary::where('payroll_id', $data['payroll_id'])
            ->where('employee_id', $data['employee_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with([
                'message' => 'Employee already exists in this payroll!',
                'alert-type' => 'error'
            ]);
        }
        $employee = Employee::find($data['employee_id']);
        $fund_source = DB::table('fund_source')->where('id', $employee->fs)->first();
        $payroll = Payroll::find($data['payroll_id']);
        $initial_month_end = Carbon::parse($payroll->period_end)->endOfMonth()->format('Y-m-d');
        $calendarDays = intval(Carbon::parse($payroll->period_start)->endOfMonth()->format('d'));
        $diffinDays = Carbon::parse($payroll->period_start)->diffInDays($payroll->period_end) + 1;
        $diffinDaysInitial = Carbon::parse($employee->start_date)->diffInDays($initial_month_end) + 1; //Initial Salary
        $diffinDaysCustom = Carbon::parse($payroll->period_start)->diffInDays($payroll->period_end) + 1; //Custom
        $detect_week = Carbon::parse($payroll->period_end)->weekOfMonth;
        $detect_month = Carbon::parse($payroll->period_end)->format('m');
        $base_init_basic = floatval($diffinDaysInitial / $calendarDays) * $employee->monthly_rate;
        $customPeriodEnd = intval(Carbon::parse($payroll->period_end)->format('d')); //16-26 computation
        $rate_half = $employee->monthly_rate / 2;
        $lastDay = Carbon::parse($employee->end_date)->format('d');
        $remarks = '';

        switch ($data['salaryComputation']) {
            case '1':
                if ($detect_week < 4) {
                    $basic = round(floatval($rate_half), 2);
                } else {
                    $basic = bcdiv(floatval($rate_half), 1, 2);
                }
                $rate = $employee->monthly_rate;
                break;
            case '2':
                $basic = round($employee->monthly_rate, 2);
                $rate = $employee->monthly_rate;
                break;
            case '3':
                $basic = round($base_init_basic, 2);
                $remarks = '(SD)' . Carbon::parse($employee->start_date)->format('m/d/Y');
                $rate = $employee->monthly_rate;
                break;
            case '4':
                $basic = round(floatval($base_init_basic - ($rate_half)), 2);
                $remarks = '(SD)' . Carbon::parse($employee->start_date)->format('m/d/Y');
                $rate = $employee->monthly_rate;
                break;
            case '5':
                $basic = bcdiv(floatval((($customPeriodEnd / $calendarDays) * $employee->monthly_rate) - $rate_half), 1, 2);
                $rate = $employee->monthly_rate;
                break;
            case '6':
                $basic = bcdiv(floatval((($diffinDaysCustom / $calendarDays) * $employee->monthly_rate)), 1, 3);
                $rate = $employee->monthly_rate;
                break;
            case '7':
                if ($detect_week < 4) {
                    $basic = bcdiv(floatval((($lastDay / $calendarDays) * $employee->monthly_rate) - $rate_half), 1, 2);
                } else {
                    $basic = round(floatval((($lastDay / $calendarDays) * $employee->monthly_rate) - $rate_half), 2);
                }
                $rate = $employee->monthly_rate;
                $remarks = '(LD)' . Carbon::parse($employee->end_date)->format('m/d/Y');
                break;
            case '8':
                $basic = round(floatval(($lastDay / $calendarDays) * $employee->monthly_rate), 2);
                $rate = $employee->monthly_rate;
                $remarks = '(LD)' . Carbon::parse($employee->end_date)->format('m/d/Y');
                break;

            //Custom Computations here
            case '9':
                $basic = round(floatval(($employee->monthly_rate / ($calendarDays / $customPeriodEnd)) - ($employee->monthly_rate / 2)), 2);
                $rate = $employee->monthly_rate;
                $remarks = 'Oct.16-27';
                break;

            case '10':
                $previousSalary = round(floatval(($employee->monthly_rate / ($calendarDays / 27)) - ($employee->monthly_rate / 2)), 2);
                $basic = round(floatval(($employee->monthly_rate / 2) - $previousSalary), 2);
                $rate = $employee->monthly_rate;
                $remarks = 'Oct.28-31';
                break;

            case '11':
                $basic = round(floatval($rate_half), 2);
                $remarks = 'WD: ' . $data['working_days'];
                $rate = $rate_half;
                break;

            case '12':
                $basic = bcdiv(floatval((($diffinDaysCustom / $calendarDays) * $employee->monthly_rate)), 1, 3);
                $rate = $employee->monthly_rate;
                $remarks = Carbon::parse($payroll->period_end)->format('m') . ' /' . Carbon::parse($payroll->period_start)->format('d') . ' - ' . Carbon::parse($payroll->period_end)->format('d') . ' /' . Carbon::parse($payroll->period_end)->format('y');
                break;

            case '13':
                $basic = bcdiv(floatval((($customPeriodEnd / $calendarDays) * $employee->monthly_rate)), 1, 3);
                $rate = $employee->monthly_rate;
                $remarks = Carbon::parse($payroll->period_end)->format('m') . ' /' . '1' . ' - ' . Carbon::parse($payroll->period_end)->format('d') . ' /' . Carbon::parse($payroll->period_end)->format('y');
                break;

            //End
        }

        // Compute Deductions
        $day = round(($rate / $data['working_days']) * $data['day'], 2);
        $hrs = round((($rate / $data['working_days']) / 8) * $data['hrs'], 2);
        $mins = round(((($rate / $data['working_days']) / 8) / 60) * $data['mins'], 2);
        $deductions = $day + $hrs + $mins;

        $soa = $basic - $deductions;


        $philhealth = $this->computePhilhealth($soa, $employee->monthly_rate, $data['salaryComputation'], $data['philhealth_otc']);
        /** 5% for philhealth contribution excluding the premium 1.20
        $calculatePhilhealth = (($soa / 1.20) * 0.05) - $data['philhealth_otc']; //5% for philhealth contribution excluding the premium 1.20 */

        /**
        if (in_array($data['salaryComputation'], ['1', '2']) && $detect_month === '12') {
            $philhealth = max(500, $calculatePhilhealth);
        } else {
            $philhealth = max(0, $calculatePhilhealth);
        }

        $philhealth = 0;
        */



        $remittance = round($philhealth + $data['sss'] + $data['pagibig'] + $data['coop'] + $data['coop_loan'], 2);
        $net_amount = $soa - $remittance - $data['tax'] + $data['comm_allowance'];
        $calculation = $data['salaryComputation'];

        $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
        $office = DB::table('office')->where('id', auth()->user()->office)->first()->shortname;

        $salary = new Salary([
            'payroll_id' => $data['payroll_id'],
            'period' => $data['period'],
            'payroll_type' => $payroll->type,
            'payroll_date' => Carbon::parse($payroll->period_end)->startOfMonth()->format('Y-m-d'),
            'employee_id' => $data['employee_id'],
            'division' => $division,
            'working_day' => $diffinDays,
            'working_days' => $data['working_days'],
            'monthly_rate' => $employee->monthly_rate,
            'basic' => $basic,
            'day' => $data['day'],
            'hr' => $data['hrs'],
            'min' => $data['mins'],
            'deductions' => $deductions,
            'soa' => $soa,
            'tax' => $data['tax'],
            'pagibig' => $data['pagibig'],
            'sss' => $data['sss'],
            'philhealth' => $philhealth,
            'philhealth_otc' => $data['philhealth_otc'],
            'coop' => $data['coop'],
            'coop_loan' => $data['coop_loan'],
            'comm_allowance' => $data['comm_allowance'],
            'net_amt' => $net_amount,
            'remarks' => $remarks,
            'fund_source' => $fund_source->desc,
            'isConap' => $fund_source->isConap,
            'calculation' => $calculation,
            'office' => $office
        ]);

        try {
            $salary->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()->with([
                    'message' => 'Employee already exists in this payroll!',
                    'alert-type' => 'error'
                ]);
            }
            throw $e;
        }

        $notification = array(
            'message' => 'Salary successfully added',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payroll = Payroll::find($id);
        $salaries = Salary::where('payroll_id', $payroll->id)->join('employees', 'employees.id', '=', 'salaries.employee_id')->select('salaries.*', 'employees.*', 'salaries.id as sid', 'salaries.monthly_rate as smonthly_rate')->orderBy('employees.employee_name')->get();
        $detect_month = Carbon::parse($payroll->period_end)->format('m');
        $detect_week = Carbon::parse($payroll->period_end)->weekOfMonth;
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();



        return view('salaries.create', compact('payroll', 'salaries', 'detect_month', 'detect_week', 'notifications'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salary::find($id);
        $employee = Employee::find($salary->employee_id);

        return response()->json([
            'data' => $salary,
            'employee' => $employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $salary = Salary::find($request->salary_id);

        if (!$salary) {
            return redirect()->back()->with([
                'message' => 'Salary record not found!',
                'alert-type' => 'error'
            ]);
        }

        // Check if salary already corrected
        $check_status = $salary->isCorrect;

        $data = request()->validate([
            'working_days' => 'required',
            'day' => 'required',
            'hr' => 'required',
            'min' => 'required',
            'tax' => 'required',
            'pagibig' => 'required',
            'sss' => 'required',
            'philhealth_otc' => 'required',
            'salary_id' => 'required',
            'payroll_id' => 'required',
            'calculation' => 'required',
            'coop' => 'required',
            'coop_loan' => 'required',
            'comm_allowance' => 'required',
        ]);

        if ($check_status == 'D' || $check_status == 'N') {

            // Resolve the employee from the salary row itself. Matching on name alone
            // returned the wrong record whenever two employees shared a name.
            $employee = $salary->employee;

            if (!$employee) {
                return redirect()->back()->with([
                    'message' => 'Employee record for this salary no longer exists!',
                    'alert-type' => 'error'
                ]);
            }

            $payroll = Payroll::find($data['payroll_id']);
            $initial_month_end = Carbon::parse($payroll->period_end)->endOfMonth()->format('Y-m-d');
            $calendarDays = intval(Carbon::parse($payroll->period_start)->endOfMonth()->format('d'));
            $diffinDays = Carbon::parse($payroll->period_start)->diffInDays($payroll->period_end) + 1;
            $diffinDaysInitial = Carbon::parse($employee->start_date)->diffInDays($initial_month_end) + 1; //Initial Salary
            $diffinDaysCustom = Carbon::parse($payroll->period_start)->diffInDays($payroll->period_end) + 1; //Custom
            $detect_week = Carbon::parse($payroll->period_end)->weekOfMonth;
            $detect_month = Carbon::parse($payroll->period_end)->format('m');
            $base_init_basic = floatval($diffinDaysInitial / $calendarDays) * $employee->monthly_rate;
            $customPeriodEnd = intval(Carbon::parse($payroll->period_end)->format('d')); //16-26 computation
            $rate_half = $employee->monthly_rate / 2;
            $lastDay = Carbon::parse($employee->end_date)->format('d');
            $remarks = '';

            switch ($data['calculation']) {
                case '1':
                    if ($detect_week < 4) {
                        $basic = round(floatval($rate_half), 2);
                    } else {
                        $basic = bcdiv(floatval($rate_half), 1, 2);
                    }
                    break;
                case '2':
                    $basic = round($employee->monthly_rate, 2);
                    break;
                case '3':
                    $basic = round($base_init_basic, 2);
                    $remarks = '(SD)' . Carbon::parse($employee->start_date)->format('m/d/Y');
                    break;
                case '4':
                    $basic = round(floatval($base_init_basic - ($rate_half)), 2);
                    $remarks = '(SD)' . Carbon::parse($employee->start_date)->format('m/d/Y');
                    break;
                case '5':
                    $basic = bcdiv(floatval((($customPeriodEnd / $calendarDays) * $employee->monthly_rate) - $rate_half), 1, 2);
                    break;
                case '6':
                    $basic = bcdiv(floatval((($diffinDaysCustom / $calendarDays) * $employee->monthly_rate)), 1, 3);
                    break;
                case '7':
                    if ($detect_week < 4) {
                        $basic = bcdiv(floatval((($lastDay / $calendarDays) * $employee->monthly_rate) - $rate_half), 1, 2);
                    } else {
                        $basic = round(floatval((($lastDay / $calendarDays) * $employee->monthly_rate) - $rate_half), 2);
                    }
                    $remarks = '(LD)' . Carbon::parse($employee->end_date)->format('m/d/Y');
                    break;
                case '8':
                    $basic = round(floatval(($lastDay / $calendarDays) * $employee->monthly_rate), 2);
                    $remarks = '(LD)' . Carbon::parse($employee->end_date)->format('m/d/Y');
                    break;
                //Custom Computations here
                case '9':
                    $basic = round(floatval(($employee->monthly_rate / ($calendarDays / $customPeriodEnd)) - ($employee->monthly_rate / 2)), 2);
                    $remarks = 'Oct.16-27';
                    break;

                case '10':
                    $previousSalary = round(floatval(($employee->monthly_rate / ($calendarDays / 27)) - ($employee->monthly_rate / 2)), 2);
                    $basic = round(floatval(($employee->monthly_rate / 2) - $previousSalary), 2);
                    $remarks = 'Oct.28-31';
                    break;

                case '11':
                    $basic = round(floatval($rate_half), 2);
                    $remarks = 'WD: ' . $data['working_days'];
                    $rate = $rate_half;
                    break;

                case '12':
                    $basic = bcdiv(floatval((($diffinDaysCustom / $calendarDays) * $employee->monthly_rate)), 1, 3);
                    $rate = $employee->monthly_rate;
                    $remarks = Carbon::parse($payroll->period_end)->format('m') . ' /' . Carbon::parse($payroll->period_start)->format('d') . ' - ' . Carbon::parse($payroll->period_end)->format('d') . ' /' . Carbon::parse($payroll->period_end)->format('y');
                    break;

                case '13':
                    $basic = bcdiv(floatval((($customPeriodEnd / $calendarDays) * $employee->monthly_rate)), 1, 3);
                    $rate = $employee->monthly_rate;
                    $remarks = Carbon::parse($payroll->period_end)->format('m') . ' /' . '1' . ' - ' . Carbon::parse($payroll->period_end)->format('d') . ' /' . Carbon::parse($payroll->period_end)->format('y');
                    break;

                //End
            }

            // Compute Deductions
            $day = round(($employee->monthly_rate / $data['working_days']) * $data['day'], 2);
            $hrs = round((($employee->monthly_rate / $data['working_days']) / 8) * $data['hr'], 2);
            $mins = round(((($employee->monthly_rate / $data['working_days']) / 8) / 60) * $data['min'], 2);
            $deductions = $day + $hrs + $mins;

            $soa = $basic - $deductions;

            $philhealth = $this->computePhilhealth($soa, $employee->monthly_rate, $data['calculation'], $data['philhealth_otc']);

            /**
            $calculatePhilhealth = (($soa / 1.20) * 0.05) - $data['philhealth_otc']; //5% for philhealth contribution excluding the premium 1.20

            if (in_array($data['calculation'], ['1', '2']) && $detect_month === '12') {
                $philhealth = max(500, $calculatePhilhealth);
            } else {
                $philhealth = max(0, $calculatePhilhealth);
            }
            */

            //$philhealth = 0;

            $remittance = round($data['pagibig'] + $data['sss'] + $philhealth + $data['coop'] + $data['coop_loan'], 2);
            $net_amount = $soa - $remittance - $data['tax'] + $data['comm_allowance'];

            $salary->update([
                'payroll_id' => $data['payroll_id'],
                'payroll_type' => $payroll->type,
                'working_days' => $data['working_days'],
                'monthly_rate' => $employee->monthly_rate,
                'basic' => $basic,
                'day' => $data['day'],
                'hr' => $data['hr'],
                'min' => $data['min'],
                'deductions' => $deductions,
                'soa' => $soa,
                'tax' => $data['tax'],
                'pagibig' => $data['pagibig'],
                'sss' => $data['sss'],
                'philhealth' => $philhealth,
                'philhealth_otc' => $data['philhealth_otc'],
                'coop' => $data['coop'],
                'coop_loan' => $data['coop_loan'],
                'comm_allowance' => $data['comm_allowance'],
                'net_amt' => $net_amount,
                'remarks' => $remarks
            ]);

            $notification = array(
                'message' => 'Salary successfully Updated!',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } else {

            $notification = array(
                'message' => 'Salary has already been corrected and cannot be edited!',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Salary::find($id)->delete();

        $notification = array(
            'message' => 'Salary successfully deleted!',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function salaryIncorrect($id)
    {
        $salary = Salary::find($id);

        return response()->json([
            'data' => $salary
        ]);
    }

    public function printsoa($id)
    {
        $salary = Salary::find($id);

        $emp = $salary->employee;

        if ($salary->calculation == '3' || $salary->calculation == '4') {
            $payroll = $salary->payroll;
            $period = Carbon::parse($payroll->period_start)->shortMonthName . " "
                . Carbon::parse($emp->start_date)->day . "-" . Carbon::parse($payroll->period_end)->day
                . ", " . Carbon::parse($payroll->period_start)->year;
        } else {
            $period = $salary->period;
        }

        $arr = explode('.', (string) $salary->soa, 2);

        if (strlen($arr[1]) == 1) {
            $decimal = ($arr[1] * 10) . '/' . 100;
        } else {
            $decimal = $arr[1] . '/' . 100;
        }

        $base_amt = $arr[0];
        $converter = new NumberFormatter('en_UK', NumberFormatter::SPELLOUT);

        $amount_to_words = $converter->format($base_amt) . ' & ' . $decimal;

        return view('soa', compact('salary', 'emp', 'amount_to_words', 'period'));
    }

    public function reportUtilization()
    {

        $office = DB::table('office')->where('id', auth()->user()->office)->first()->shortname;
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();

        $periods = Salary::where('office', $office)->select('payroll_date')->distinct()->orderBy('payroll_date', 'DESC')->get();

        return view('reports.utilization', compact('periods', 'notifications'));
    }

    /**
     * PhilHealth deduction for one salary row.
     *
     * Employees earning below the salary ceiling keep the standard share:
     * 5% of the SOA excluding the 1.20 premium. At or above the ceiling the
     * premium is a flat amount per period, not prorated by days worked, so
     * absences and partial periods do not reduce it.
     *
     * @param  float   $soa               SOA for the period.
     * @param  float   $monthlyRate       Employee monthly rate.
     * @param  string  $salaryComputation salaryComputation / calculation code.
     * @param  float   $philhealthOtc     Amount already paid over the counter.
     * @return float
     */
    private function computePhilhealth($soa, $monthlyRate, $salaryComputation, $philhealthOtc)
    {
        if ($monthlyRate >= self::PHILHEALTH_SALARY_CEILING) {
            $share = (string) $salaryComputation === self::SALARY_COMPUTATION_FULL_MONTH
                ? self::PHILHEALTH_CAP_MONTHLY
                : self::PHILHEALTH_CAP_CUTOFF;
        } else {
            $share = ($soa / 1.20) * 0.05; //5% for philhealth contribution excluding the premium 1.20
        }

        return $share - $philhealthOtc;
    }
}
