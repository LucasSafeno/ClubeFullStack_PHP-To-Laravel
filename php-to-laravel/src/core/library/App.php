<?php

namespace core\library;

use DI\Container;
use Dotenv\Dotenv;
use DI\ContainerBuilder;
use Spatie\Ignition\Ignition;

class App
{

    public readonly Container $container;
    public static function create(): self
    {
        return new self;
    } // create

    public function withErrorPage()
    {
        Ignition::make()
            ->register()
            ->shouldDisplayException(env('ENV') === 'development')
            ->setTheme('dark');


        return $this;
    }

    public function withContainer()
    {

        $builder = new ContainerBuilder();
        $this->container = $builder->build();

        return $this;
    }

    public function withEnvironmentVariables()
    {

        try {
            $dotenv = Dotenv::createImmutable(dirname(__FILE__, 3));
            $dotenv->load();
            return $this;
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }




    }

}
