<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Napa\R19\Sms;

class SmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send messages to customers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $from_datetime = now();
        if (8 <= intval(date('H', strtotime($from_datetime))) && 18 >= intval(date('H', strtotime($from_datetime)))) {
            $to_datetime = now()->addMinutes(30);
            $today = date_format($from_datetime, 'y-m-d');
//        $to_date = date_format($to_datetime, 'y-m-d');
            $from_time = date_format($from_datetime, 'H:i:s');
            $to_time = date_format($to_datetime, 'H:i:s');
            $customer = Customer::orderby('date')->orderby('time')->where('date', $today)->whereBetween('time', [$from_time, $to_time])->get();
            if ($customer->count() > 0) {
                foreach ($customer as $value)
                    if ($value['status'] == 0) {
                        if ($value['language'] == 0)
                            $message = "Assalomu alaykum {$value['name']}! Siz bugun soat {$value['time']} da EuroStom klinikasiga navbatga yozilgansiz! Iltimos, o'z vaqtida yetib kelishingizni so'raymiz!";
                        if ($value['language'] == 1)
                            $message = "Здравствуйте, уважаемый {$value['name']}!. Напоминаем, что сегодня в {$value['time']} Вы записаны в клинику EuroStom. Просим Вас прийти вовремя.";
                        $value['status'] = 1;
                        $value->save();
                        Sms::send($value['phone'], $message);
                    }
            }
        }
    }
}
