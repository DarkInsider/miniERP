@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Skills list</div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                            </tr>
                            @foreach($skills as $skill)
                                <tr>
                                    <td>{{$skill->id}}</td>
                                    <td>{{$skill->title}}</td>
                                </tr>
                            @endforeach
                        </table>
                        {{$skills->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
