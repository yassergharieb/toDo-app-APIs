<?php

namespace App\Http\Requests;

use App\Http\traits\DatesTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Http\traits\ResponseHandeler;

class TaskRequest extends FormRequest
{

    use ResponseHandeler , DatesTrait;
 

    public function authorize()
    {
        return true;
    }

  
    public function rules()
    {
        $task_request = $this;
        $user_id =  Auth::user()->id;
        
        // $this->merge(['user_id' =>$user_id]);
        return [

         
            'task_body' => "required_without:id|max:500",
            'status'    => ['required_without:id', Rule::in(['in progress', 'completed', 'not started yet'])],
            'prioirty'  => ['required_without:id', Rule::in(['high', 'mid', 'low'])],
            'start_date' => $this->BasicDateValidationForTasks($task_request),
            'end_date'  =>  $this->BasicDateValidationForTasks($task_request),
            // 'user_id'   => 'required|exists:users,id',
            'id'        =>  "nullable|exists:tasks,id",





        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        return $this->validationfails($validator);
    }
}
