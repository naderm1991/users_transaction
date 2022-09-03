<?php

namespace App\Http\Controllers\Api;

use App\Filters\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserRequest $request,UserFilter $userFilters): \Illuminate\Http\JsonResponse
    {
        $userFilters = $userFilters->filters;
        if (empty($userFilters)){
            $users = User::with('transactions')->get();
            return response()->json([
                'status'=> "success",
                'users' => $users
            ]);
        }

        $transactions_filter = ['transactions' => function($query) use ($userFilters) {
            foreach ($userFilters as  $filter){
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
