<?php

namespace App\Http\Controllers;

use App\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
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
            'subject' => 'required',
            'content' => 'required',
            'publisher' => 'required'
        ]);

        $announcement = new Announcement([
            'subject' => $data['subject'],
            'content' => $data['content'],
            'publisher' => $data['publisher']
        ]);

        $announcement->save();

        $notification = array(
            'message' => 'Announcement successfully created',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->status == 'unpublished') {
            Announcement::find($announcement->id)->update([
                'status' => 'published'
            ]);

            $notification = array(
                'message' => 'Announcement successfully published',
                'alert-type' => 'success'
            );
        } else {

            Announcement::find($announcement->id)->update([
                'status' => 'unpublished'
            ]);

            $notification = array(
                'message' => 'Announcement successfully unpublished',
                'alert-type' => 'error'
            );
        }



        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        //
    }

    public function liked($id)
    {

        $checkIfLiked = DB::table('likes')->where('announcement_id', $id)->where('user_id', auth()->user()->id)->first();
        $total_likes = Announcement::find($id)->first();

        if ($checkIfLiked) {

            if ($checkIfLiked->isLiked == 'Y') {

                $checkIfLiked = DB::table('likes')->where('announcement_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->update([
                        'isLiked' => 'N'
                    ]);

                Announcement::find($id)->decrement('likes');

            } elseif ($checkIfLiked->isLiked == 'N') {

                $checkIfLiked = DB::table('likes')->where('announcement_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->update([
                        'isLiked' => 'Y'
                    ]);

                Announcement::find($id)->increment('likes');

            }

        } else {

            DB::table('likes')->insert([
                'announcement_id' => $id,
                'user_id' => auth()->user()->id,
                'isLiked' => 'Y',
            ]);

            Announcement::find($id)->increment('likes');
            
        }


        return redirect()->back();
    }

    public function pin($id)
    {
        $checkIfPinned = Announcement::where('id', $id)->where('pinned', 'Y')->first();

        if ($checkIfPinned) {

            Announcement::find($id)->update([
                'pinned' => 'N'
            ]);

            $notification = array(
                'message' => 'Announcement successfully unpinned!',
                'alert-type' => 'error'
            );
        } else {

            Announcement::find($id)->update([
                'pinned' => 'Y'
            ]);

            $notification = array(
                'message' => 'Announcement successfully pinned!',
                'alert-type' => 'success'
            );
        }

        return redirect()->back()->with($notification);
    }

    public function likers($id)
    {
        $likers = DB::table('likes')->where('announcement_id', $id)->where('isLiked', 'Y')->join('users', 'users.id', '=', 'likes.user_id')->select('users.*')->get();

        $output = '';
        foreach ($likers as $liker) {
            $output .= "<li class='list-group-item text-left p-1'> <i class='far fa-user fa-sm' style='color:green'></i> $liker->name </span>";
        }

        return $output;
    }

    public function recentPost($id){
        $announcement = Announcement::find($id);

        $data = [
            'subject' => $announcement->subject,
            'publisher' => $announcement->publisher,
            'content' => $announcement->content,
            'created_at' => Carbon::parse($announcement->created_at)->toDayDateTimeString(),
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}
