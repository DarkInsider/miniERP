<?php

namespace App\Http\Controllers;
use App\ProjectHasSkill;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectHasSkillController extends Controller
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
        if($request->project_id === null){
            array_push($err, 'project_id is required');
        }else{
            try{
                $prj =   DB::table('projects')
                    ->select()
                    ->where('id',$request->project_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($prj === null){
                array_push($err, 'project must exist');
            }
        }
        if($request->title === null){
            array_push($err, 'title is required');
        }else{
            try{
                $skill =   DB::table('skills')
                    ->select()
                    ->where('title',$request->title)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($skill === null){
               $flag = 1;
            }else {
                $flag = 2;
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }


        if(($user->id !== $prj->user_id) && ($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }
        DB::beginTransaction();
        $date = date('Y-m-d H:i:s');
        if($flag === 1){
            try {
                $skill = Skill::create(
                    [
                        'title' => $request->title,
                        'updated_at' => $date,
                        'created_at' => $date,
                    ]
                );
            } catch (\Exception $e) {
                DB::rollback();
                return response($e, 500);
            }
        }

        try{
            $ret =   DB::table('project_has_skills')
                ->select()
                ->where([
                    ['skill_id', $skill->id],
                    ['project_id', $request->project_id],
                ])
                ->first();
        }
        catch (Exception $e){
            DB::rollback();
            return response($e, 500);
        }
        if($ret !== null){
            array_push($err, 'skill already exist on this project');
        }
        if(count($err) > 0){
            DB::rollback();
            return response($err, 400);
        }



        $inp= [
            'project_id' => $prj->id,
            'skill_id' => $skill->id,
            'updated_at' => $date,
            'created_at' => $date,
        ];
        try {
            $ret = ProjectHasSkill::create($inp);
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
        DB::commit();
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
        if($request->project_has_skill_id === null){
            array_push($err, 'project_has_skill_id is required');
        }else{
            try{
                $ret =   DB::table('project_has_skills')
                    ->select()
                    ->where('id',$request->project_has_skill_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($ret === null){
                array_push($err, 'project_has_skill must exist');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        try{
            $ret2 =   DB::table('projects')
                ->select()
                ->where('id', $ret->project_id)
                ->first();
        }
        catch (Exception $e){
            return response($e, 500);
        }

        if(($user->id !== $ret2->user_id) && ($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }





        try {
            ProjectHasSkill::where('id', $request->project_has_skill_id)
                ->forceDelete();
        } catch (\Exception $e) {
            return response($e, 500);
        }

        return response(  json_encode("Delete OK", JSON_UNESCAPED_UNICODE), 200);
    }
}
