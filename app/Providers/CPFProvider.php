<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CPFProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (class_exists(\Faker\Factory::class) && class_exists(\Faker\Generator::class)) {
            $this->app->extend(\Faker\Generator::class, function() {
                $faker = \Faker\Factory::create();
                $faker->addProvider(new class($faker) extends \Faker\Provider\Base {
                    public function cpf(): int
                    {
                        $n = $this->generator->numberBetween(100000000, 999999999);
                        $n .= $this->verifierDigit($n);
                        $n .= $this->verifierDigit($n);
                        return $n;
                    }
                    private function verifierDigit($digits): int
                    {
                        $numbers = array_map('intval', str_split($digits));
                        $weights = range(count($numbers) + 1, 2);

                        $sum = 0;
                        foreach ($numbers as $i => $number) {
                            $sum += $number * $weights[$i];
                        }

                        $mod = $sum % 11;
                        return $mod < 2 ? 0 : 11 - $mod;
                    }
                });
                return $faker;
            });
        }
    }
}
