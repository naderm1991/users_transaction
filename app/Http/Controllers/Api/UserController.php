<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $request_rules = [
            'amount_range'      => 'nullable|array|size:2',
            'amount_range.from' => 'required_with:amount_range',
            'amount_range.to' => 'required_with:amount_range',
            'date_range'      => 'nullable|array|size:2',
            'date_range.from' => 'required_with:date_range',
            'date_range.to' => 'required_with:date_range',
        ];

        $validator = Validator::make($request->all(),$request_rules );

        if ($validator->fails()){
            return response()->json([
                'status'=> "failed",
                "errors"=> $validator->errors()
           ]);
        }
        $filters=[];

        if($request->status_code){
            $filters[] = ["column"=>"status_code" , "operator"=>"=" , "value" => $request->status_code];
        }

        if($request->currency){
            $filters[] = ["column"=>"currency" , "operator"=>"=" , "value" => $request->currency];
        }

        if($request->amount_range){
            $filters[] = ["column"=>"amount" , "operator"=>"between" , "value" => $request->amount_range];
        }

        if($request->date_range){
            $filters[] = ["column"=>"payment_date" , "operator"=>"between" , "value" => $request->date_range];
        }

        if (empty($filters)){
            $users = User::with('transactions')->get();
            return response()->json([
                'users' => $users
            ]);
        }

        $transactions_filter = ['transactions' => function($query) use ($filters) {
            foreach ($filters as  $filter){
                if ($filter['operator']== "between")
                    $query->whereBetween($filter['column'], [$filter['value']['from'], $filter['value']['to']]);
                else
                    $query->where($filter['column'],$filter['operator'] ,$filter['value']);
            }
        }];

        $users = User::with($transactions_filter)->get();

        return response()->json([
            'status'=> "success",
            'users' => $users
        ]);
    }
}
