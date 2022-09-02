<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(UserRequest $request): \Illuminate\Http\JsonResponse
    {
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
