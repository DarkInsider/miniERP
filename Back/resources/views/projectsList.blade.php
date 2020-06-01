@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div style="display: flex" class="card-header flex-row justify-content-between"><span> Projects list</span> <span><a  href="{{ url('/projects/new') }}">+</a></span></div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                            @foreach($projects as $project)
                                <tr>
                                    <td><a  href="{{ url('/projects/'.$project->id) }}">{{$project->id}}</a> </td>
                                    <td>{{$project->title}}</td>
                                    <td>{{$project->organization}}</td>
                                    <td>{{$project->start}}</td>
                                    <td>{{$project->end}}</td>
                                </tr>
                            @endforeach
                        </table>
                        {{$projects->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
