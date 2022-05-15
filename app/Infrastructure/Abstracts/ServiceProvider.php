<?php

namespace App\Infrastructure\Abstracts;


use Illuminate\Support\Facades\Gate;
use ReflectionClass;

abstract class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var string Alias untuk memuat terjemahan dan tampilan
     */
    protected string $alias;

    /**
     * @var bool Set if will load commands
     */
    protected bool $hasCommands = false;

    /**
     * @var bool Set apakah akan memuat perintah
     */
    protected bool $hasMigrations = false;

    /**
     * @var bool Set apakah akan memuat terjemahan
     */
    protected bool $hasTranslations = false;

    /**
     * @var bool Set apakah akan memuat policies
     */
    protected bool $hasPolicies = false;

    /**
     * @var array Daftar Artisan commands
     */
    protected array $commands = [];

    /**
     * @var array Daftar providers untuk di load
     */
    protected array $providers = [];

    /**
     * @var array Daftar policies untuk di load
     */
    protected array $policies = [];

    /**
     * Boot diperlukan pendaftaran tampilan dan terjemahan.
     *
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerTranslations();
    }

    /**
     * Register the application's policies.
     *
     */
    public function registerPolicies()
    {
        if ($this->hasPolicies && config('register.policies')){
            foreach ($this->policies as $key => $value){
                Gate::policy($key, $value);
            }
        }
    }

    /**
     * Register domain custom Artisan commands.
     *
     */
    protected function registerCommands()
    {
        if ($this->commands && config('register.commands')){
            $this->commands($this->commands);
        }
    }

    /**
     * Register domain migrations.
     *
     */
    protected function registerMigrations()
    {
        if ($this->hasMigrations && config('register.migrations')){
            $this->loadMigrationsFrom($this->domainPath('Database/Migrations'));
        }
    }

    /**
     * Detects the domain base path so resources can be proper loaded on child classes.
     *
     * @param $append
     * @return bool|string
     */
    protected function domainPath($append = null): bool|string
    {
        $reflection = new ReflectionClass($this);

        $realPath = realpath(dirname($reflection->getFileName()) . '/../');

        if (!$append) {
            return $realPath;
        }

        return $realPath . '/' . $append;
    }

    /**
     * Register domain translations.
     */
    protected function registerTranslations()
    {
        if ($this->hasTranslations && config('register.translations')){
            $this->loadJsonTranslationsFrom($this->domainPath('Resources/Lang'));
        }
    }

    /**
     * Register Domain ServiceProviders.
     */
    public function register()
    {
       collect($this->providers)->each(fn($providerClass) => $this->app->register($providerClass));
    }
}
