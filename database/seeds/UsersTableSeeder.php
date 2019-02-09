<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 10)
            ->create()
            ->each(function ($user) {
                $user->companies()->saveMany(
                    factory(App\Models\Company::class, 10)->create(['user_id' => $user->id])->each(function($company) use ($user) {
                        $company->jobs()->save(factory(App\Models\Job::class)->make([
                            'user_id' => $user->id,
                            'company_id' => $company->id,
                        ]));
                    })
                );
        });
    }
}
