<?php

namespace App\Http\Controllers;
use App\ProjectHasAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProjectHasAttachmentController extends Controller
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
        if(count($err) > 0){
            return response($err, 400);
        }

        $this->validate($request, [
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf|max:2048',
        ]);

        if(($user->id !== $prj->user_id) && ($user->role !== 'administrator')){
            return response(json_encode('forbidden', JSON_UNESCAPED_UNICODE), 403);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = $user->id.'_'.$prj->id.'_'. time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/prjFiles');
            $file->move($destinationPath, $name);


            $date = date('Y-m-d H:i:s');
            $inp = [
                'project_id' => $request->project_id,
                'url' => $name,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            try {
                $ret= ProjectHasAttachment::create($inp);
            } catch (\Exception $e) {
                return response($e, 500);
            }
            return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);
        }
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
        if($request->project_has_attachment_id === null){
            array_push($err, 'project_has_attachment_id is required');
        }else{
            try{
                $ret =   DB::table('project_has_attachments')
                    ->select()
                    ->where('id',$request->project_has_attachment_id)
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($ret === null){
                array_push($err, 'project_has_attachment must exist');
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

        if (File::exists(public_path('/prjFiles').'/'.$ret->url)) {
            File::delete(public_path('/prjFiles').'/'.$ret->url);
        }

        try {
            ProjectHasAttachment::where('id', $request->project_has_attachment_id)
                ->forceDelete();
        } catch (\Exception $e) {
            return response($e, 500);
        }

        return response(  json_encode("Delete OK", JSON_UNESCAPED_UNICODE), 200);
    }
}
