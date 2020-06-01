<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function login(Request $request){
       $err=[];
       if($request->email === null){
           array_push($err, 'email is required');
       }
       if($request->password === null){
           array_push($err, 'password is required');
       }
       if(count($err) > 0){
           return response($err, 400);
       }

       try {
           $user_id = User::select('id')
               ->where([
                   ['email', $request->email],
                   ['password', md5($request->password)],
               ])
               ->first();
       } catch (\Exception $e) {
           return response($e, 500);
       }

       if($user_id !== null){
           $date = date('Y-m-d H:i:s');
           $token =  md5($request->email) . md5($date) . md5(rand(0, 100000));
           try {
               DB::table('users')
               ->where('id', $user_id->id)
               ->update([
                   'token' => $token,
                   'updated_at' => $date,
               ]);
           } catch (\Exception $e) {
               return response($e, 500);
           }

           try {
               $ret= User::find($user_id);
           } catch (\Exception $e) {
               return response($e, 500);
           }

           return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);

       }else{
           return response('email or password incorrect', 400);
       }
   }

    public function logout(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        try{
            $date = date('Y-m-d H:i:s');
            DB::table('users')
            ->where('users.token', $request->header('token'))
            ->update([
                'token' => null,
                'updated_at' => $date,
            ]);
        }
        catch (Exception $e){
            return response($e, 500);
        }
        return response(  json_encode('Logout OK', JSON_UNESCAPED_UNICODE), 200);
    }

    public function registration(Request $request){
        $err=[];
        if($request->email === null){
            array_push($err, 'email is required');
        }else {
            try{
                $ret = DB::table('users')
                    ->where([
                        ['email', $request->email]
                    ])
                    ->first();
            }
            catch (Exception $e){
                return response($e, 500);
            }
            if($ret !== null){
                array_push($err, 'email must be unique');
            }
        }
        if($request->password === null){
            array_push($err, 'password is required');
        }
        if($request->name === null){
            array_push($err, 'name is required');
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        $date = date('Y-m-d H:i:s');
        try {
            $ret = User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => md5($request->password),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]
            );
        } catch (\Exception $e) {
            return response($e, 500);
        }
        return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);

    }

    public function geUserByToken(Request $request){
        $err=[];
        if($request->header('token') === null){
            array_push($err, 'token is required');
        }else {
            try{
                $user =   DB::table('users')
                    ->select('id', 'email', 'role', 'name', 'photo_url', 'token')
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

        return response(  json_encode($user, JSON_UNESCAPED_UNICODE), 200);
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
        if($request->email === null){
            array_push($err, 'email is required');
        }else {
            if($user->email !== $request->email){
                try{
                    $ret = DB::table('users')
                        ->where([
                            ['email', $request->email]
                        ])
                        ->first();
                }
                catch (Exception $e){
                    return response($e, 500);
                }
                if($ret !== null){
                    array_push($err, 'email must be unique');
                }
            }
        }
        if($request->name === null){
            array_push($err, 'name is required');
        }
        if(count($err) > 0){
            return response($err, 400);
        }

        $date = date('Y-m-d H:i:s');
        try {
            if($request->email !== null){
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'email' => $request->email,
                        'name' => $request->name,
                        'password' => md5($request->password),
                        'updated_at' => $date,
                    ]);
            }else{
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'email' => $request->email,
                        'name' => $request->name,
                        'updated_at' => $date,
                    ]);
            }
        } catch (\Exception $e) {
            return response($e, 500);
        }

        try {
            $ret= User::find($user->id);
        } catch (\Exception $e) {
            return response($e, 500);
        }

        return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);
    }

    public function loadPhoto(Request $request){
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
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = $user->id.'_'. time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            if (File::exists(public_path('/images').'/'.$user->photo_url)) {
                File::delete(public_path('/images').'/'.$user->photo_url);
            }


            $date = date('Y-m-d H:i:s');
            try {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'photo_url' => $name,
                        'updated_at' => $date,
                    ]);
            } catch (\Exception $e) {
                return response($e, 500);
            }
            try {
                $ret= User::find($user->id);
            } catch (\Exception $e) {
                return response($e, 500);
            }
            return response(  json_encode($ret, JSON_UNESCAPED_UNICODE), 200);
        }
        if(count($err) > 0){
            return response($err, 400);
        }
    }


}
