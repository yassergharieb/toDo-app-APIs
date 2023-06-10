<?php 

namespace App\Http\traits;

trait  DatesTrait {
      
      
     public function BasicDateValidationForTasks ($request) {

        return [
            'required_without:id', 'date_format:Y-m-d H:i:s', 'after_or_equal:today' , 'unique:tasks,start_date,'  . $request->id

        ];
     }
   

}








?>