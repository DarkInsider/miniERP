@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/projects') }}">Projects list</a>
                        </li>
                    You are logged in! {{Auth::user()->role}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
