<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BuilderMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Builder::macro('searchKey', function (array $attributes, $needle) {
            return $this->where(function (Builder $query) use ($attributes,$needle) {
                foreach ($attributes as $attribute) {
                    $query->orWhere($attribute, 'LIKE', "%{$needle}%");
                }
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
