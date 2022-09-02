<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ImportUserData extends Command
{
    protected const FILE_NAME = "users";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import users from json file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //todo :
        // 1- read the data from the json file
        // 2- loop and insert into the DB

        $path = storage_path() . "/json/".self::FILE_NAME.".json";
        $users = json_decode(file_get_contents($path), true);
        $users = $users["users"]??[];

        foreach($users as $user){
            User::firstOrCreate(
                ["uid"=>$user['id']],[
                    'balance' => $user['balance'],
                    'currency' => $user['currency'],
                    'email' => $user['email'],
                    'created_at'=> date( 'Y-m-d', strtotime($user['created_at']) )
                ]
            );
        }

        return 0;
    }
}
