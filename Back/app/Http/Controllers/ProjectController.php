<?php

namespace App\Http\Controllers;
use App\Project;
use App\ProjectHasAttachment;
use App\ProjectHasSkill;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class ProjectController extends Controller
{

    public function create(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if($request->title === null){
            array_push($err, 'title is required');
        }
        if($request->description === null){
            array_push($err, 'description is required');
        }else{
            if(strlen($request->description)>2000){
                array_push($err, 'description should be shorter for 2000');
            }
        }
        if($request->type === null){
            array_push($err, 'type is required');
        }else{
            if( (strtolower($request->type) !== 'work') &&
                (strtolower($request->type) !== 'book') &&
                (strtolower($request->type) !== 'course') &&
                (strtolower($request->type) !== 'blog') &&
                (strtolower($request->type) !== 'other') )
            {
                array_push($err, 'type should be (work | book | course | blog | other)');
            }
        }

        if(count($err) > 0){
            return response($err, 400);
        }

        $date = date('Y-m-d H:i:s');

        $inp = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'created_at' => $date,
            'updated_at' => $date,
            'user_id' => $user->id
        ];

        if($request->organization !== null){
            $inp['organization'] = $request->organization;
        }
        if($request->start !== null){
            $inp['start'] = $request->start;
        }
        if($request->end !== null){
            $inp['end'] = $request->end;
        }


        try {
            $ret = Project::create($inp);
        } catch (\Exception $e) {
            return response($e, 500);
        }
        return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);

    }


    public function update(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if($request->project_id === null){
            array_push($err, 'project_id is required');
        }else{
            try{
                $project =   DB::table('projects')
                    ->select()
                    ->where('id', $request->project_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($project === null){
                array_push($err, 'project must exist');
            }
        }
        if($request->description !== null){
            if(strlen($request->description)>2000){
                array_push($err, 'description should be shorter for 2000');
            }
        }
        if($request->type !== null){
            if( (strtolower($request->type) !== 'work') &&
                (strtolower($request->type) !== 'book') &&
                (strtolower($request->type) !== 'course') &&
                (strtolower($request->type) !== 'blog') &&
                (strtolower($request->type) !== 'other') )
            {
                array_push($err, 'type should be (work | book | course | blog | other)');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        if(($user->id !== $project->user_id) && ($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }

        $date = date('Y-m-d H:i:s');

        $inp = [
            'updated_at' => $date,
        ];

        if($request->organization !== null){
            $inp['organization'] = $request->organization;
        }
        if($request->start !== null){
            $inp['start'] = $request->start;
        }
        if($request->end !== null){
            $inp['end'] = $request->end;
        }
        if($request->title !== null){
            $inp['title'] = $request->title;
        }
        if($request->description !== null){
            $inp['description'] = $request->description;
        }
        if($request->type !== null){
            $inp['type'] = $request->type;
        }

        try {
            Project::where('id', $request->project_id)
            ->update($inp);
        } catch (\Exception $e) {
            return response($e, 500);
        }
        try {
           $ret =  Project::find($request->project_id);
        } catch (\Exception $e) {
            return response($e, 500);
        }
        return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);


    }

    public function delete(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if($request->project_id === null){
            array_push($err, 'project_id is required');
        }else{
            try{
                $project =   DB::table('projects')
                    ->select()
                    ->where('id', $request->project_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($project === null){
                array_push($err, 'project must exist');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        if(($user->id !== $project->user_id) && ($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }
        DB::beginTransaction();
        try {
            ProjectHasSkill::where('project_id', $request->project_id)
                ->forceDelete();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
        try {
            ProjectHasAttachment::where('project_id', $request->project_id)
                ->forceDelete();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
        try {
            Project::where('id', $request->project_id)
                ->forceDelete();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
        DB::commit();
        return response(  json_encode('Delete OK', JSON_UNESCAPED_UNICODE), 200);

    }

    public function import(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        $this->validate($request, [
            'file' => 'required|mimes:csv,xlsx,xls',
        ]);

        $array = Excel::toArray(null, request()->file('file'));


        $date = date('Y-m-d H:i:s');


        DB::beginTransaction();

        foreach ($array as $proj){
            $page = 1;
            $inpPrj = [
                'user_id' => $user->id,
                'updated_at' => $date,
                'created_at' => $date,
            ];
            $flag = 0;
            $skills=[];
            foreach ($proj as $block) {
                if ($block[0] === 'title') {
                    $inpPrj['title'] = $block[1];
                    $flag++;
                }
                if ($block[0] === 'description') {
                    $inpPrj['description'] = $block[1];
                    $flag++;
                }
                if ($block[0] === 'type') {
                    if( (strtolower($block[1]) !== 'work') &&
                        (strtolower($block[1]) !== 'book') &&
                        (strtolower($block[1]) !== 'course') &&
                        (strtolower($block[1]) !== 'blog') &&
                        (strtolower($block[1]) !== 'other') )
                    {
                        array_push($err, 'type should be (work | book | course | blog | other) '.'Error in "'.$page.'" page');
                    }else{
                        $inpPrj['type'] = $block[1];
                        $flag++;
                    }
                }

                if ($block[0] === 'organization') {
                    $inpPrj['organization'] = $block[1];
                }
                if ($block[0] === 'start') {
                    $inpPrj['start'] = $block[1];
                }
                if ($block[0] === 'end') {
                    $inpPrj['end'] = $block[1];
                }
                if ($block[0] === 'skills') {
                    for($i = 1; $i < count($block); $i++){
                        array_push($skills, $block[$i]);
                    }
                }
            }
            if($flag < 3){
                array_push($err, 'All required fields must be filled');
            }
            if(count($err) > 0){
                DB::rollback();
                return response($err, 400);
            }
            try {
                $prj = Project::create($inpPrj);
            } catch (\Exception $e) {
                DB::rollback();
                return response($e, 500);
            }

            if(count($skills)>0){
                foreach ($skills as $skill){
                    try{
                        $ret =   DB::table('skills')
                            ->select()
                            ->where('title',$skill)
                            ->first();
                    }
                    catch (Exception $e){
                        DB::rollback();
                        return response($e, 500);
                    }
                    if($ret === null){
                        $inpSkill= [
                            'title' => $skill,
                            'updated_at' => $date,
                            'created_at' => $date,
                        ];
                        try {
                            $ret = Skill::create($inpSkill);
                        } catch (\Exception $e) {
                            DB::rollback();
                            return response($e, 500);
                        }
                    }
                    $inp= [
                        'project_id' => $prj->id,
                        'skill_id' => $ret->id,
                        'updated_at' => $date,
                        'created_at' => $date,
                    ];
                    try {
                        $ret = ProjectHasSkill::create($inp);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response($e, 500);
                    }
                }
            }

            $page++;
        }

        DB::commit();
        return response(  json_encode($array, JSON_UNESCAPED_UNICODE), 200);
    }


    public function get(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }
        try{
            $projects = Project::paginate($request->limit !== null ? $request->limit : 10);
        }
        catch (Exception $e){
            return response($e, 500);
        }



        return response(  json_encode($projects, JSON_UNESCAPED_UNICODE), 200);


    }

    public function getById(Request $request, $id){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select()
                    ->where('token',$request->header('token'))
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($user === null){
                array_push($err, 'bad token');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }


        $ret = [];

        try {
            $prj = DB::table('projects')
                ->join('users', 'users.id', 'projects.user_id')
                ->select('projects.*', 'users.name as user_name')->where([
                    ['projects.id', intval($id) ],
                ])->first();
        } catch (Exception $e) {
            return response($e, 500);
        }

        $ret['project']=$prj;

        try {
            $attach = DB::table('project_has_attachments')
                ->select()->where([
                    ['project_has_attachments.project_id', intval($id) ],
                ])->get();
        } catch (Exception $e) {
            return response($e, 500);
        }
        $ret['attachments']=$attach;

        try {
            $skills = DB::table('project_has_skills')
                ->join('skills', 'skills.id', 'project_has_skills.skill_id')
                ->select('project_has_skills.id as id_key', 'skills.*')->where([
                    ['project_has_skills.project_id', intval($id) ],
                ])->get();
        } catch (Exception $e) {
            return response($e, 500);
        }
        $ret['skills']=$skills;

        return response(  json_encode( $ret , JSON_UNESCAPED_UNICODE), 200);


    }

}
