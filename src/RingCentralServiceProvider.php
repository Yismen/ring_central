<?php

namespace Dainsys\RingCentral;

use Dainsys\Mailing\Mailing;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Dainsys\RingCentral\Services\CallsService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class RingCentralServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        \Dainsys\RingCentral\Models\Department::class => \Dainsys\RingCentral\Policies\DepartmentPolicy::class,
        \Dainsys\RingCentral\Models\Project::class => \Dainsys\RingCentral\Policies\ProjectPolicy::class,
        \Dainsys\RingCentral\Models\Site::class => \Dainsys\RingCentral\Policies\SitePolicy::class,
    ];

    public function boot()
    {
        Mailing::bind(__DIR__ . './Mail');

        Model::preventLazyLoading(true);

        $this->registerPolicies();
        $this->bootEvents();
        $this->bootPublishableAssets();
        $this->bootLoads();
        $this->bootBindings();

        RingCentral::setConnection('ring_central.connection');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/ring_central.php',
            'ring_central'
        );
    }

    protected function bootPublishableAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/ring_central.php' => config_path('ring_central.php')
        ], 'ring_central:config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/ring_central')
        ]);
    }

    protected function bootLoads()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ring_central');
    }

    protected function bootEvents()
    {
        Event::listen(\Illuminate\Mail\Events\MessageSent::class, \Dainsys\RingCentral\Listeners\RemoveMailAttachment::class);
    }

    protected function bootBindings()
    {
        // $this->app->bind(CallsService::class, CallsService::class);
    }
}
