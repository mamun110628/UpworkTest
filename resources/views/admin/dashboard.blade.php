@extends('admin.layout.app')

@section('breadcump')
    <div class="col-sm-6">
        <h1 class="m-0">TestApp Dashboard</h1>
    </div>
    <div class="col-md-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('admin.dasboard')}}">Dashboard</a></li>
           
        </ol>
    </div>

@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
               

                    <div class="card  card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Invite User</h3>

                        </div>

                        <div class="card-body">
                            <form action="{{route('post.invite')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                        </div>
                    </div>
                
            </div>
        </div>
    </div>
</section>
@endsection
