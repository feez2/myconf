<?php

namespace App\Providers;

use App\Models\Conference;
use App\Models\Paper;
use App\Models\Proceedings;
use App\Models\ProgramBook;
use App\Policies\ProgramBookPolicy;
use App\Policies\ProceedingsPolicy;
use App\Policies\ConferencePolicy;
use App\Policies\PaperPolicy;
use App\Policies\DecisionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Conference::class => ConferencePolicy::class,
        Paper::class => PaperPolicy::class,
        Conference::class => DecisionPolicy::class,
        Proceedings::class => ProceedingsPolicy::class,
        ProgramBook::class => ProgramBookPolicy::class,
        // DatabaseNotification::class => NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define an admin gate
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Define a conference chair gate
        Gate::define('conference-chair', function ($user, $conference) {
            return $user->role === 'admin' ||
                   $conference->chairs()->where('user_id', $user->id)->exists();
        });

        // Define a proceedings management gate
        Gate::define('manageProceedings', function ($user, $conference = null) {
            if ($user->role === 'admin') {
                return true;
            }
            if (!$conference || $conference === Conference::class) {
                return false;
            }
            return $user->isProgramChair($conference) || $user->isAreaChair($conference);
        });

        // Define a program chair gate
        Gate::define('program-chair', function ($user, $conference) {
            return $user->role === 'admin' ||
                   $conference->programChairs()->where('user_id', $user->id)->exists();
        });

        // Define a program book management gate
        Gate::define('manageProgramBook', function ($user, $conference) {
            return $user->role === 'admin' ||
                   $user->isProgramChair($conference) ||
                   $user->isAreaChair($conference);
        });
    }
}
