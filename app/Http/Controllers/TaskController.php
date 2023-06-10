<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\traits\ResponseHandeler;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    use ResponseHandeler;

    public function __construct()
    {    
        $this->middleware(['JWTauthanticate:user-api']);
    }



    public function index(Request $request)
    {
        
        $user_id = auth('user-api')->user()->id;
        $tasks = DB::table('users')->join('tasks', 'tasks.user_id', "=", 'users.id')
            ->select('tasks.prioirty')->orderBy('tasks.start_date', 'desc')
            ->where('users.id', $user_id)->get();

        return  $this->success($tasks, 200);
    }


    public function create(TaskRequest $request)

    {
        try {
            $user_id =  Auth::user()->id;
            if ($request->user_id !=  $user_id) {
                return $this->error('there is something went wrong!', 302);
            }

            // $request->request->add(['user_id' => $user_id]);  // Q 

            $task_id =  DB::table('tasks')->insertGetId($request->all());

            $task  =  DB::table('tasks')->where('id', $task_id)->get();
            return  $this->success($task, 200);
        } catch (\Exception $e) {
            return $this->error('there is something went wrong!', 302);
        }
    }


    public function update(TaskRequest $request, $id)
    {

        try {
            $user_id =  Auth::user()->id;
            if ($request->user_id !=  $user_id) {
                return $this->error('there is something went wrong!', 302);
            }
            // $request->request->add(['user_id' => $user_id]);

            DB::table('tasks')->join('users', 'tasks.user_id', "=", 'users.id')->where('tasks.id', '=', $id)
                ->update($request->all());

            $task =  DB::table('tasks')->join('users', 'tasks.user_id', "=", 'users.id')->where('tasks.id', '=', $id)
                ->select('tasks.*')->get();
            return $this->success(' task updated successfully', 200);
        } catch (\Exception $e) {
            return $this->error('update task faild , please try again', 302);
        }
    }


    public function destroy($id)
    {
        try {
            DB::table('tasks')->where('id', $id)->delete();
            return $this->success(' task deleted successfully', 200);

        } catch (\Exception $ex) {
            return $this->error(' delete faild , please try again', 302);
        }
    }



    public function changeStatu($id, Request $request)
    {

        $task =  DB::table('tasks')->where('id', $id);
        try {
            $errors =  $request->validate([
                'status'  => ['required', Rule::in(['completed', 'in progress', 'not started yet'])]
            ], $request->status);


            if ($errors || !$task) {
                return $this->error('some thing went wrong, please try again later!', 200);
            }

            DB::table('tasks')->where('id', $id)->update($request->status);
            return $this->success(' task updated successfully', 200);
        } catch (\Exception $ex) {

            return $this->error('update task faild , please try again', 302);
        }
    }
    public function changePriority($id, Request $request)
    {

        $task =  DB::table('tasks')->where('id', $id);
        try {
            $errors =  $request->validate([
                'prioirty'  => ['required', Rule::in(['high', 'mid', 'low'])]
            ], $request->prioirty);


            if ($errors || !$task) {
                return $this->error('some thing went wrong, please try again later!', 200);
            }

            DB::table('tasks')->where('id', $id)->update($request->prioirty);
            return $this->success(' task updated successfully', 200);
        } catch (\Exception $ex) {

            return $this->error('update task faild , please try again', 302);
        }
    }






    public function deleteCompleteTasks()
    {
        try {
            $user_id  = Auth::user()->id;
            DB::table('tasks')->join('users' , 'tasks.user_id', "=", 'users.id' )->where('status' , "="  , 'completed')
            ->where('users.id' , '=' , $$user_id)
            ->delete();
            return $this->success(' completed task deleted successfully', 200);

        } catch (\Exception $ex) {
            return $this->error(' delete faild , please try again', 302);
        }
    }
}
