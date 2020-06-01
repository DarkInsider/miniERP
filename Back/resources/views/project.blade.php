@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><a  href="{{ url()->previous() }}"><-</a></div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                            </tr>
                                <tr>
                                    <td>{{$project->id}}</td>
                                    <td>{{$project->title}}</td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
