<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Console\Command;

class ImportTransactionData extends Command
{
    protected const FILE_NAME = "transactions";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import the transactions from json file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // read from file
        // loop
        // find by email
        // if not exist

        $path = storage_path() . "/json/".self::FILE_NAME.".json";
        $transactions = json_decode(file_get_contents($path), true);

        $transactions = $transactions["transactions"]??[];

        foreach($transactions as $transaction){
            try {
                $user = User::where('email', '=', $transaction['parentEmail'])->firstOrFail();
                Transaction::firstOrCreate(
                    ["parent_id"=>$transaction['parentIdentification']],[
                        'amount' => $transaction['paidAmount'],
                        'currency' => $transaction['Currency'],
                        'user_id' => $user->id,
                        'status_code' => $transaction['statusCode'],
                        'payment_date'=> date( 'Y-m-d', strtotime($transaction['paymentDate']) ),
                    ]
                );
            }catch (\Exception $exception){
                //todo log the failed inserts
            }
        }

        return 0;
    }
}
