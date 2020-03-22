<?php

namespace App\Console;

use App\Console\Commands\SchedulerDaemon;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use App\Models\Product;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      SchedulerDaemon::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      $schedule->call(function () {
        $products = Product::all(["url", "id"]);
        foreach ($products as $prd) {
          if($price = $this->fetchPrice($prd->url) ){
            $prd->prices()->create([
              "price" => $price,
              "currency" => "IDR"
            ]);
          }
        }
      })->everyMinute();
    }

    /**
     * method to fetch meta price from URL
     */
    protected function fetchPrice($url){
      #fetch the product's web content
      $res = file_get_contents($url);
  
      #extract the html head
      $res = substr($res, 0, strpos($res, "</head") );
  
      $res = array_filter(
        array_map(
          #cleanup each data
          function($v){return trim($v);},
  
          #split data by meta tag
          explode("<meta", $res )
        ),
  
        #pick only meta open graph prices
        function($v){return 0===strpos($v,'property="product:price:amount');}
      );
  
      foreach ($res as $v) {
        $s = substr($v, strpos($v, 'content="')+9);
        return substr($s, 0, strpos($s, '"'));
      }
      return null;
    }
}
