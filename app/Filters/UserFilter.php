<?php

namespace App\Filters;

use Illuminate\Http\Request;

class UserFilter
{
    public array $filters = [];

    public function __construct(Request $request){

        if($request->status_code){
            $this->filters[] = ["column"=>"status_code" , "operator"=>"=" , "value" => $request->status_code];
        }

        if($request->currency){
            $this->filters[] = ["column"=>"currency" , "operator"=>"=" , "value" => $request->currency];
        }

        if($request->amount_range){
            $this->filters[] = ["column"=>"amount" , "operator"=>"between" , "value" => $request->amount_range];
        }

        if($request->date_range){
            $this->filters[] = ["column"=>"payment_date" , "operator"=>"between" , "value" => $request->date_range];
        }
    }

}
