<?php

namespace App\Providers;

use Doctrine\Common\Cache\SQLite3Cache;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use SQLite3;
use Tmdb\ApiToken;
use Tmdb\Client as TmdbClient;
use Tmdb\Helper\ImageHelper;
use Tmdb\Repository\ConfigurationRepository;
use Tmdb\Repository\DiscoverRepository;
use Tmdb\Repository\GenreRepository;
use Tmdb\Repository\MovieRepository;
use Tmdb\Repository\PeopleRepository;
use Tmdb\Repository\SearchRepository;
use Tmdb\Repository\TvRepository;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        URL::forceRootUrl(env('APP_URL'));

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        $this->app->singleton(ClientInterface::class, static function () {
            return new Client();
        });

        $this->app->singleton(TmdbClient::class, static function () {
            $token = new ApiToken(env('TMDB_KEY'));
            return new TmdbClient($token, [
                'cache' => [
                    'handler' => new SQLite3Cache(new SQLite3(database_path('database.sqlite')), 'tmdb_cache'),
                ],
            ]);
        });

        $this->app->singleton(MovieRepository::class, static function () {
            return new MovieRepository(app(TmdbClient::class));
        });

        $this->app->singleton(SearchRepository::class, static function () {
            return new SearchRepository(app(TmdbClient::class));
        });

        $this->app->singleton(TvRepository::class, static function () {
            return new TvRepository(app(TmdbClient::class));
        });

        $this->app->singleton(PeopleRepository::class, static function () {
            return new PeopleRepository(app(TmdbClient::class));
        });

        $this->app->singleton(GenreRepository::class, static function () {
            return new GenreRepository(app(TmdbClient::class));
        });

        $this->app->singleton(DiscoverRepository::class, static function () {
            return new DiscoverRepository(app(TmdbClient::class));
        });

        $this->app->singleton(ConfigurationRepository::class, static function () {
            $config = new ConfigurationRepository(app(TmdbClient::class));
            return $config->load();
        });

        $this->app->singleton(ImageHelper::class, static function () {
            return new ImageHelper(app(ConfigurationRepository::class));
        });

    }
}
