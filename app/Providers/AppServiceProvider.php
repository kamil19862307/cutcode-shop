<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if(app()->isProduction()){
//            Отправка сообщения о длительности общего количества запросов
//            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {
//
//                logger()
//                    ->channel('telegram')
//                    ->debug('whenQueryingForLongerThan:' . $connection->query()->toSql());
//
//            });

            DB::listen(function ($query){

                if($query->time > 100){
                    logger()
                        ->channel('telegram')
                        ->debug('Query longer than 1s:' . $query->sql, $query->bindings);
                }

            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function (){

                    logger()
                        ->channel('telegram')
                        ->debug('whenQueryingForLongerThan:' . request()->url());

                }
            );
        }
    }
}
