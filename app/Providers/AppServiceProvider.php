<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestCheckin;
use App\Models\GuestPublic;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\SouvenirDeposit;
use App\Models\TitipKehadiran;
use App\Models\User;
use App\Observers\ActivityObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::observe(ActivityObserver::class);
        Guest::observe(ActivityObserver::class);
        GuestCheckin::observe(ActivityObserver::class);
        GuestPublic::observe(ActivityObserver::class);
        Kategori::observe(ActivityObserver::class);
        Role::observe(ActivityObserver::class);
        SouvenirDeposit::observe(ActivityObserver::class);
        TitipKehadiran::observe(ActivityObserver::class);
        User::observe(ActivityObserver::class);

        // if (app()->environment('local')) {
        //     URL::forceScheme('https');
        // }

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $events = Event::get(["*"]);

                if (!session()->has('active_event_id')) {
                    $activeEvent = $events->where('is_active', "1")->first();
                    if ($activeEvent) {
                        session(['active_event_id' => $activeEvent->id]);
                    }
                }

                $view->with('events', $events);
            }
        });
    }
}
