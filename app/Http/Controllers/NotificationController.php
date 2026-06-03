<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Payroll;
use App\Salary;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PHPUnit\Framework\Error\Notice;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'salary_id' => 'required',
            'payroll_id' => 'required',
            'comment' => 'required|max:255',
        ]);

        if(auth()->user()->role == '0'){
            $receiver = 1;
        }else{
            $receiver = Payroll::where('id', $data['payroll_id'])->first()->office;
        }

        if(auth()->user()->office == '1'){
            Salary::find($data['salary_id'])->update([
                'isCorrect' => 'N'
            ]);
        }


        $notification = new Notification([
            'salary_id' => $data['salary_id'],
            'payroll_id' => $data['payroll_id'],
            'comment' => $data['comment'],
            'sender' => auth()->user()->name,
            'receiver' => $receiver
        ]);

        $notification->save();

        $message = array(
            'message' => 'Message successfully sent to concerned office!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notifications = Notification::where('salary_id', $id)->orderBy('created_at', 'DESC')->get();
        $payroll_id = Salary::find($id)->first()->payroll_id;

        $output = '';

        foreach ($notifications as $notification) {
            if (auth()->user()->name == $notification->sender) {
                $output .= "<div class='direct-chat-msg right'>";
                $output .= "<div class='direct-chat-infos clearfix'>";
                $output .= "<span class='direct-chat-name float-right'>$notification->sender</span>";
                $output .= "<span class='direct-chat-timestamp float-left'> $notification->created_at </span>";
                $output .= "</div>";
                $output .= "<img class='direct-chat-img' src='/image/userlogo.png' alt='message user image'>";
                $output .= "<div class='direct-chat-text'>$notification->comment</div>";
            }else{
                $output .= "<div class='direct-chat-msg'>";
                $output .= "<div class='direct-chat-infos clearfix'>";
                $output .= "<span class='direct-chat-name float-left'>$notification->sender</span>";
                $output .= "<span class='direct-chat-timestamp float-right'> $notification->created_at </span>";
                $output .= "</div>";
                $output .= "<img class='direct-chat-img' src='/image/adminlogo.png' alt='message user image'>";
                $output .= "<div class='direct-chat-text'>$notification->comment</div>";
            }
            $output .="</div>";
        }

        return response()->json([
            'data' => $output,
            'salary' => $id,
            'payroll' => $payroll_id
        ]);
        exit;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        Salary::find($id)->update([
            'isCorrect' => 'Y'
        ]);

        Notification::where('salary_id', $id)->update([
            'seen' => 'true'
        ]);

        $message = array(
            'message' => 'Thread successfully closed!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
	
	public function chatStore(Request $request){
		
		$data = request()->validate([
            'message' => 'required|max:1000',
			'convo' => 'nullable',
			'to' => 'required'
        ]);
		
		$last_id = isset(DB::table('chats')->orderBy('convo_id', 'DESC')->first()->convo_id) ? DB::table('chats')->orderBy('convo_id', 'DESC')->first()->convo_id : 0;
		
		if($data['convo'] == ''){
			
			DB::table('chats')->insert([
				'convo_id' => $last_id + 1,
				'message' => $data['message'],
				'from' => auth()->user()->id,
				'to' => $data['to'],
				'timestamp' => now()
			]);
			
		}else{
			
			$convo = DB::table('chats')->where('convo_id', $data['convo'])->first()->convo_id;
			DB::table('chats')->insert([
				'convo_id' => $data['convo'],
				'message' => $data['message'],
				'from' => auth()->user()->id,
				'to' => $data['to'],
				'timestamp' => now()
			]);
		}
		
		return redirect()->back();
	}
	public function chats($id){
	
		$userId = auth()->user()->id;
		$convo = DB::table('chats')->where('convo_id', $id)->get();

		if($convo->count() > 0){
			
			$chats = DB::table('chats')->where('convo_id', $id)->orderBy('timestamp', 'DESC')->get();
			
			$cid = $id;
			$output = '';
			
			foreach ($chats as $chat) {
				
				if ($userId == $chat->from) {
					$output .= "<div class='direct-chat-msg right'>";
					$output .= "<div class='direct-chat-infos clearfix'>";
					$output .= "<span class='direct-chat-name float-right'>" . DB::table('users')->where('id', $chat->from)->first()->name . "</span>";
					$output .= "<span class='direct-chat-timestamp float-left'>" . Carbon::parse($chat->timestamp)->toDayDateTimeString() . "</span>";
					$output .= "</div>";
					$output .= "<img class='direct-chat-img' src='/image/userlogo.png' alt='message user image'>";
					$output .= "<div class='direct-chat-text'>$chat->message</div>";
				}else{
					$output .= "<div class='direct-chat-msg'>";
					$output .= "<div class='direct-chat-infos clearfix'>";
					$output .= "<span class='direct-chat-name float-left'>" . DB::table('users')->where('id', $chat->from)->first()->name . "</span>";
					$output .= "<span class='direct-chat-timestamp float-right'>" . Carbon::parse($chat->timestamp)->toDayDateTimeString() . "</span>";
					$output .= "</div>";
					$output .= "<img class='direct-chat-img' src='/image/adminlogo.png' alt='message user image'>";
					$output .= "<div class='direct-chat-text'>$chat->message</div>";
				}
				
				$output .="</div>";
			}
		}else{
			$cid = '';
			$output = '';
		}

        return response()->json([
            'data' => $output,
            'convo' => $cid,
			'user' => $id
        ]); 
        exit;
	}
}
