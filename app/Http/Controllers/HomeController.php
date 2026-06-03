<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Notification;
use App\Payroll;
use App\Salary;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

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
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->orderBy('created_at', 'DESC')->get();

        if (auth()->user()->role == '1') {
            $announcements = Announcement::orderBy('created_at', 'DESC')->paginate(3);
        } else {
            $announcements = Announcement::where('status', 'published')->orderBy('created_at', 'DESC')->paginate(3);
        }


        return view('dashboard', compact('notifications', 'announcements'));
    }

    public function changepassword(User $user)
    {
        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();
        return view('auth.change', compact('notifications'));
    }

    public function confirmchange()
    {
        $data = request()->validate([
            'oldpassword' => ['required', new MatchOldPassword],
            'password' => 'required|confirmed|min:8|',
            'user' => 'required'
        ]);

        User::find($data['user'])->update([
            'password' => Hash::make($data['password'])
        ]);

        $notification = array(
            'message' => 'Password successfully changed',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function refsUser(){
        $users = User::all();

        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();
        return view('references.users', compact('users', 'notifications'));
    }
	
	public function refsFundsource()
    {
        $fundsources = DB::table('fund_source')->get();

        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();
        return view('references.fundsource', compact('fundsources', 'notifications'));
    }

    public function refsFundsourceAdd(Request $request)
    {
        $data = request()->validate([
            'desc' => 'required',
            'mfo_pap' => 'required',
            'isConap' => 'required'
        ]);

        DB::table('fund_source')->insert([
            'desc' => $data['desc'],
            'mfo_pap' => $data['mfo_pap'],
            'isConap' => $data['isConap']
        ]);

        $notification = array(
            'message' => 'Fund Source has been added',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function refsFundsourceEdit($id){
        $fundsource = DB::table('fund_source')->where('id', $id)->first();
        
        $choice = $fundsource->isConap == 'N' ? 'NO' : 'YES';
        $conap = "<option value='$fundsource->isConap'>" .  $choice . "</option>";

        $conap .= "<option value='N'>NO</option>";
        $conap .= "<option value='Y'>YES</option>";

        $isConap = [
            'source' => $conap
        ];

        return response()->json([
            'data' => $fundsource,
            'output' => $isConap
        ]);
    }

    public function refsFundsourceUpdate(Request $request){
        
        $data = request()->validate([
            'id' => 'required',
            'desc' => 'required',
            'mfo_pap' => 'required',
            'isConap' => 'required',
        ]);

        DB::table('fund_source')->where('id', $data['id'])->update([
            'desc' => $data['desc'],
            'mfo_pap' => $data['mfo_pap'],
            'isConap' => $data['isConap'],
        ]);

        $notification = array(
            'message' => 'Fund Source has been updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function refsFundsourceDelete($id){
        
        DB::table('fund_source')->where('id', $id)->delete();

        $notification = array(
            'message' => 'Fund Source has been deleted',
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function refsSignatory()
    {
        $signatories = DB::table('signatories')->get();

        $notifications = Notification::where('receiver', auth()->user()->office)->where('seen', 'false')->get();
        return view('references.signatories', compact('signatories', 'notifications'));
    }

    public function refsSignatoryEdit($id)
    {
        $signatory = DB::table('signatories')->where('id', $id)->first();


        return response()->json([
            'data' => $signatory
        ]);
    }

    public function refsSignatoryUpdate(){

        $data = request()->validate([
            'id' => 'required',
            'name' => 'required',
            'position' => 'required',
            'division' => '',
        ]);

        DB::table('signatories')->where('id', $data['id'])->update([
            'name' => $data['name'],
            'position' => $data['position'],
            'division' => $data['division'],
        ]);

        $notification = array(
            'message' => 'Signatory has been updated',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function resetpassword($id){
        
        User::find($id)->update([
            'password' => Hash::make('Payroll1234')
        ]);

        $notification = array(
            'message' => 'Password has been reset',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    }
}
