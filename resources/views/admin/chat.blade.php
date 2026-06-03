<aside class="control-sidebar control-sidebar-dark">
   <div class="p-3">
	<h4 class="mt-4 mb-2">Direct Chat</h4>
    <!-- Contacts are loaded here -->
      <ul class="contacts-list">
	  @foreach(\App\User::where('id', '<>', auth()->user()->id)->orderBy('name')->get() as $user)
        <li>
          <a id="showChats" data-toggle="modal" data-target="#chats" data-id="{{ \DB::table('chats')->where('from', $user->id)->where('to', auth()->user()->id)->get()->count()> 0 ? \DB::table('chats')->where('from', $user->id)->where('to', auth()->user()->id)->first()->convo_id : 0 }}">
            <img class="contacts-list-img" src="/image/userlogo.png">
            <div class="contacts-list-info text-sm">
              <span class="contacts-list-name">
			  {{ $user->name }}
              </span>
              <span class="contacts-list-msg">{{ \DB::table('office')->where('id', $user->office)->first()->shortname }}
			  <span class="badge bg-danger">{{ $chats = \DB::table('chats')->where('from', $user->id)->get()->count() }}</span>
			  </span>
			
            </div>
            <!-- /.contacts-list-info -->
          </a>
        </li>
        <!-- End Contact Item -->
		@endforeach
      </ul>
      <!-- /.contacts-list -->

   </div>

</aside>

<!-- NOTIFICATIONS -->
<div class="modal fade" id="chats">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">
                    <i class="fab fa-facebook-messenger"></i>
                    Chat Thread
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="card direct-chat direct-chat-primary">
                <div class="card-body">
                    <div class="direct-chat-messages" id="showchats">
                        <!-- comments -->
                    </div>
                </div>
            </div>

            <div class="modal-body justify-content-between">
                <form action="{{ route('chats.store') }}" method="post">

                    @csrf

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control col-12" name="message" id="message"></textarea>
                    </div>
					
					<input type="text" class="form-control col-6" name="convo" id="convo_notif" readonly>
					<input type="text" class="form-control col-6" name="to" id="to_notif" readonly>
					
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-info">Send</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>