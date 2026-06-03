@extends('admin.master', ['activePage' => 'announcements'])

@section('content')
<div class="content-header text-sm">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <ul class="nav justify-content-between">
          <span class="nav-item text-left">
            <h4 class="text-secondary"> <i class="far fa-home"></i> Announcements</h4>
          </span>
          @if(auth()->user()->role == 1)
          <span class="nav-item text-right">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createAnnouncement">
              Create
            </button>
          </span>
          @endif
        </ul>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Announcements</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container">
  <div class="row">
    <div class="col-md-7 align-self-end">
      @forelse($announcements as $announcement)
      <div class="card card-widget col-md-11">
        <div class="card-header">
          <div class="user-block">
            <img class="img-circle" src="/image/doh.png" alt="User Image">
            <span class="username"><a href="#">{{ $announcement->publisher }}</a></span>
            <span class="description">published - {{ \Carbon\Carbon::parse($announcement->created_at)->toDayDateTimeString() }}</span>
          </div>

          <div class="card-tools">
            @if(auth()->user()->role == '1')
            @if($announcement->pinned == 'N')
            <a class="btn btn-info btn-sm" href="{{ '/post/' . $announcement->id }}">Pin</a>
            @endif
            <form class="d-inline" action="{{ route('announcements.update', $announcement->id) }}" method="post">
              @csrf
              @method('put')
              @if($announcement->status == 'published')
              <button type="submit" class="btn btn-danger btn-sm">
                Unpublish
              </button>
              @else
              <button type="submit" class="btn btn-success btn-sm">
                Publish
              </button>
              @endif
            </form>

            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>

        </div>

        <div class="card-body">
          <h6 class="text-success">#{{ $announcement->subject }}</h6>

          <lead class="text-sm">{!! $announcement->content !!}</lead>

          @if( \DB::table('likes')->where('announcement_id', $announcement->id)->where('user_id', auth()->user()->id)->where('isLiked', 'Y')->first() )
          <a type="button" href="{{ '/like/' . $announcement->id }}" id="likeBtn" class="btn btn-default btn-sm"><i class="fas fa-heart fa-sm" style="color:red"></i> Liked</a>
          @else
          <a type="button" href="{{ '/like/' . $announcement->id }}" id="likeBtn" class="btn btn-default btn-sm"><i class="far fa-heart fa-sm"></i> Like</a>
          @endif

          <span class="float-right "><a id="viewLikers" data-target="#likersView" data-toggle="modal" data-id="{{ $announcement->id }}" class="text-muted">{{ $announcement->likes }} likes</a></span>
        </div>

      </div>
      @empty
      <div class="card card-widget">

        <div class="card-body text-center">
          <h4>No Announcement(s) found!</h4>
        </div>

      </div>
      @endforelse

      {{ $announcements->links() }}
    </div>

    <!-- Right Side of Announcements -->
    <div class="col-md-5">
      <!-- Pinned Post -->
      <h6 class="text-left text-secondary"><i class="fas fa-thumbtack fa-sm"></i> Pinned Post </h6>
      @if($pinned = \App\Announcement::where('pinned', 'Y')->first() )
      <div class="card card-widget">
        <div class="card-header">
          <div class="user-block">
            <img class="img-circle" src="/image/doh.png" alt="User Image">
            <span class="username"><a href="#">{{ $pinned->publisher }}</a></span>
            <span class="description">published - {{ \Carbon\Carbon::parse($pinned->created_at)->toDayDateTimeString() }}</span>
          </div>

          <div class="card-tools">
            @if(auth()->user()->role == '1')
            <a class="btn btn-danger btn-sm" href="{{ '/post/' . $pinned->id }}">Remove Pin</a>
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>

        </div>
        <div class="card-body">
          <lead class="text-success"> #{{ $pinned->subject }}</lead>
          <lead class="text-sm">{!! $pinned->content !!}</lead>
        </div>
      </div>
      @endif

      <hr>

      <!-- Search -->
      <h6 class="text-left text-secondary">Enhanced Search</h6>
      <form action="enhanced-results.html">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label>Result Type:</label>
                  <select class="form-control form-control-sm select2" multiple="multiple" data-placeholder="Any" style="width: 100%;">
                    <option>Text only</option>
                    <option>Images</option>
                    <option>Video</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label>Sort Order:</label>
                  <select class="form-control form-control-sm select2" style="width: 100%;">
                    <option selected>ASC</option>
                    <option>DESC</option>
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <label>Order By:</label>
                  <select class="form-control form-control-sm select2" style="width: 100%;">
                    <option selected>Title</option>
                    <option>Date</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group input-group-lg">
                <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here" value="Lorem ipsum">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-lg btn-default">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>

      <hr>
      <!-- Recent Announcements -->
      <h6 class="text-left text-secondary">Recent Announcements</h6>
      @forelse($recents = \App\Announcement::where('status', 'published')->orderBy('created_at', 'DESC')->limit(3)->get() as $recent)
      <div class="callout callout-success">
        <div class="comment-text">
          <span class="username text-success">
            #{{ $recent->subject }}
            <span class="text-muted float-right">
              <small><em>
                  <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($recent->created_at)->diffForHumans()}}
                </em></small>
            </span>
          </span>
          <br>
          <span> <small class="font-italic"> posted by {{ $recent->publisher }} </small> 
            <a id="viewRecent" data-target="#recentView" data-toggle="modal" data-id="{{ $recent->id }}" class="text-muted">more</a>
          </span>
        </div>
      </div>
      @empty
      <div class="card">
        <div class="card-header text-center">
          No recent announcement(s)!
        </div>
      </div>
      @endforelse
    </div>

  </div>
</div>

<div class="modal fade" id="createAnnouncement">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success">
          <i class="fas fa-plus-square"></i>
          New Announcement
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('announcements.store') }}" method="post">
          @csrf

          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control col-6" name="subject" id="subject" required>
          </div>
          <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control col-12" name="content" id="summernote" cols="30" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label for="publisher">Published By</label>
            <input type="text" class="form-control col-6" id="publisher" name="publisher" value="{{ auth()->user()->name }}" readonly>
          </div>

          <div class="justify-content-between">
            <button type="submit" class="btn btn-success">Publish</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>

<div class="modal fade" id="likersView">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-success">
          <i class="fas fa-heart fa-sm"></i>
          Secretly Haters
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush" id="displayLikers">

        </ul>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>

<div class="modal fade" id="recentView">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="modal-title text-success" >
          <div class="user-block">
            <img class="img-circle" src="/image/doh.png" alt="User Image">
            <span class="username"><a href="#" id="publisher_r"></a></span>
            <span class="description" id="created_r"></span>
          </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6></i><em class="text-center text-success" id="subject_r"> </em></h6>

        <lead class="text-sm" name="content_r" id="content_r"></lead>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</div>
@endsection