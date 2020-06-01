<?php

namespace App\Http\Controllers;
use App\ProjectHasSkill;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
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
        }else{
            try{
                $ret =   DB::table('skills')
                    ->select()
                    ->where('title',$request->title)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($ret !== null){
                array_push($err, 'skill must be unique');
            }
        }

        if(count($err) > 0){
            return response($err, 400);
        }

        if(($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }

        $date = date('Y-m-d H:i:s');

        $inp = [
            'title' => $request->title,
            'created_at' => $date,
            'updated_at' => $date,
        ];

        try {
            $ret = Skill::create($inp);
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
        if($request->skill_id === null){
            array_push($err, 'skill_id is required');
        }else{
            try{
                $skill =   DB::table('skills')
                    ->select()
                    ->where('id', $request->skill_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($skill === null){
                array_push($err, 'skill must exist');
            }
        }
        if($request->title === null){
            array_push($err, 'title is required');
        }else{
            try{
                $ret =   DB::table('skills')
                    ->select()
                    ->where('title',$request->title)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if(($ret !== null) && ($skill->id !== $ret->id)){
                array_push($err, 'skill must be unique');
            }
        }

        if(count($err) > 0){
            return response($err, 400);
        }

        if(($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }

        $date = date('Y-m-d H:i:s');

        $inp = [
            'title' => $request->title,
            'updated_at' => $date,
        ];

        try {
             Skill::find($request->skill_id)
            ->update($inp);
        } catch (\Exception $e) {
            return response($e, 500);
        }
        try {
            $ret = Skill::find($request->skill_id);
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
        if($request->skill_id === null){
            array_push($err, 'skill_id is required');
        }else{
            try{
                $skill =   DB::table('skills')
                    ->select()
                    ->where('id', $request->skill_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($skill === null){
                array_push($err, 'skill must exist');
            }
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        if(($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }
        DB::beginTransaction();
        try {
            ProjectHasSkill::where('skill_id', $request->skill_id)
                ->forceDelete();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }


        try {
            Skill::where('id', $request->skill_id)
                ->forceDelete();
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 500);
        }
        DB::commit();
        return response(  json_encode('Delete OK', JSON_UNESCAPED_UNICODE), 200);

    }


}
