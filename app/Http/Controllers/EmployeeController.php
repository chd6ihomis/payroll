<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role == '1'){
			
			$total_employees = Employee::all();
            $employees = Employee::orderBy('employee_name', 'ASC')->get();
            $notifications = Notification::where('receiver' , auth()->user()->office)->where('seen', 'false')->get();

            return view('employees.index', compact('employees', 'notifications', 'total_employees'));
        }elseif(auth()->user()->role == '3'){
            $division = DB::table('office')->where('id', auth()->user()->office)->first()->division;
            $total_employees = Employee::join('office', 'office.id', '=', 'employees.office')->where('office.division', $division)->get();
            $employees = Employee::join('office', 'office.id', '=', 'employees.office')->where('office.division', $division)->select('office.*', 'employees.*')->orderBy('employees.employee_name', 'ASC')->get();
            $notifications = Notification::where('receiver' , auth()->user()->office)->where('seen', 'false')->get();

            return view('employees.index', compact('employees', 'notifications', 'total_employees'));
        }else {

			$total_employees = Employee::where('office', auth()->user()->office)->get();
            $employees = Employee::where('office', auth()->user()->office)->orderBy('employee_name', 'ASC')->get();
            $notifications = Notification::where('receiver' , auth()->user()->office)->where('seen', 'false')->get();
            
            return view('employees.index', compact('employees', 'notifications', 'total_employees'));
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
        $data = request()->validate([
            'employee_id' => 'required',
            'employee_name' => 'required',
            'birthdate' => 'required',
            'contact_num' => 'required',
            'position' => 'required',
            'office' => 'required',
            'monthly_rate' => 'required',
            'fund_source' => 'required',
            'lbp_num' => 'required',
            'tin_num' => 'required',
            'pagibig_num' => 'required',
            'sss_num' => 'required',
            'philhealth_num' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $office = DB::table('office')->where('office_name', $data['office'])->first()->id;
        $start_date = Carbon::parse($data['start_date'])->format('Y-m-d');
        $end_date = Carbon::parse($data['end_date'])->format('Y-m-d');

        // Reject exact re-adds. Matching on employee_id alone would block legitimate
        // rehires and contract renewals, so the whole contract must match.
        $duplicate = Employee::where('employee_id', $data['employee_id'])
            ->where('employee_name', $data['employee_name'])
            ->where('office', $office)
            ->where('start_date', $start_date)
            ->where('end_date', $end_date)
            ->first();

        if ($duplicate) {
            return redirect()->back()->withInput()->with([
                'message' => 'This employee already exists for the same office and period (record #' . $duplicate->id . ')!',
                'alert-type' => 'error'
            ]);
        }

        $employee = new Employee([
            'employee_id' => $data['employee_id'],
            'employee_name' => $data['employee_name'],
            'status' => 'true',
            'birth_date' => Carbon::parse($data['birthdate'])->format('Y-m-d'),
            'contact_num' => $data['contact_num'],
            'position' => $data['position'],
            'office' => $office,
            'monthly_rate' => $data['monthly_rate'],
            'fs' => $data['fund_source'],
            'lbp_num' => $data['lbp_num'],
            'tin_num' => $data['tin_num'],
            'pagibig_num' => $data['pagibig_num'],
            'sss_num' => $data['sss_num'],
            'philhealth_num' => $data['philhealth_num'],
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $employee->save();

        $notification = array(
            'message' => 'Employee successfully added',
            'alert-type' => 'success'
        );

        return redirect('/employees')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
		$fs = DB::table('fund_source')->where('id', $employee->fs)->first();
		
		$output = "<option value='$fs->id'>" . $fs->desc .' ('. $fs->mfo_pap .')' . "</option>";
		
		foreach(DB::table('fund_source')->where('id', '<>' , $fs->id)->get() as $i){
			$output .= "<option value='$i->id'>" . $i->desc .' ('. $i->mfo_pap .')' . "</option>";
		}
		
		$models = [
			'source' => $output,
		];

        $dates = [
            'birthdate' => Carbon::parse($employee->birth_date)->format('m/d/Y'),
            'start_date' => Carbon::parse($employee->start_date)->format('m/d/Y'),
            'end_date' => Carbon::parse($employee->end_date)->format('m/d/Y'),
            'office' => DB::table('office')->where('id', $employee->office)->first()->office_name
        ];
        

        return response()->json([
            'data' => $employee,
            'model' => $models,
            'date' => $dates
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $data = request()->validate([
            'employee_id' => 'required',
            'employee_name' => 'required',
            'birthdate' => 'required',
            'contact_num' => 'required',
            'position' => 'required',
            'office' => 'required',
            'monthly_rate' => 'required',
            'fund_source' => 'required',
            'lbp_num' => 'required',
            'tin_num' => 'required',
            'pagibig_num' => 'required',
            'sss_num' => 'required',
            'philhealth_num' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'id' => 'required'
        ]);

        $status = isset($request->status) ? 'true' : 'false';

        Employee::find($data['id'])->update([
            'employee_id' => $data['employee_id'],
            'employee_name' => $data['employee_name'],
            'birth_date' => Carbon::parse($data['birthdate'])->format('Y-m-d'),
            'contact_num' => $data['contact_num'],
            'position' => $data['position'],
            'office' => DB::table('office')->where('office_name', $data['office'])->first()->id,
            'monthly_rate' => $data['monthly_rate'],
            'fs' => $data['fund_source'],
            'lbp_num' => $data['lbp_num'],
            'tin_num' => $data['tin_num'],
            'pagibig_num' => $data['pagibig_num'],
            'sss_num' => $data['sss_num'],
            'philhealth_num' => $data['philhealth_num'],
            'start_date' => Carbon::parse($data['start_date'])->format('Y-m-d'),
            'end_date' => Carbon::parse($data['end_date'])->format('Y-m-d'),
            'status' => $status
        ]);

        $notification = array(
            'message' => 'Employee successfully Updated!',
            'alert-type' => 'success'
        );
    
        return redirect()->back()->with($notification);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        $notification = array(
            'message' => 'Employee successfully Deleted!',
            'alert-type' => 'error'
        );
    
        return redirect()->back()->with($notification);

    }
}
