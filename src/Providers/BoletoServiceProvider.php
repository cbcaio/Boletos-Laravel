<?php

namespace CbCaio\Boletos\Providers;

use Illuminate\Support\ServiceProvider;

class BoletoServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = FALSE;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../../resources/config/boletos.php'
                => config_path('boletos.php'),
            ]
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/boletos.php', 'boletos'
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->registerBoletosManager();
    }

    private function registerBoletosManager()
    {
        $this->app->singleton('BoletoCEF', 'CbCaio\Boletos\Models\Boleto\CaixaEconomicaFederal');
    }

    private function registerBindings()
    {
        $this->app->bind('CbCaio\Boletos\Models\Banco\Contracts\BancoInterface',
                         'CbCaio\Boletos\Models\Banco\Base\Banco');

        $this->app->bind('CbCaio\Boletos\Models\Beneficiario\Contracts\BeneficiarioInterface',
                         'CbCaio\Boletos\Models\Beneficiario\Base\Beneficiario');

        $this->app->bind('CbCaio\Boletos\Models\Boleto\Contracts\BoletoInterface',
                         'CbCaio\Boletos\Models\Boleto\Base\Boleto');

        $this->app->bind('CbCaio\Boletos\Models\BoletoInfo\Contracts\BoletoInfoInterface',
                         'CbCaio\Boletos\Models\BoletoInfo\BoletoInfo');

        $this->app->bind('CbCaio\Boletos\Models\Pagador\Contracts\PagadorInterface',
                         'CbCaio\Boletos\Models\Pagador\Pagador');
    }

}