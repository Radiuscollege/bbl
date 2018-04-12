<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\User;
use App\Student;
use App\Cohort;
use App\Module;
use Carbon\Carbon;

class DataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            $cohort = Cohort::create([
                'name' => $faker->numerify('*######') . ' 2015-2016 APO',
            ]);

            $user = User::create([
                'id' => $faker->numerify('D######'),
                'name' => $faker->firstName . ' the ' . $faker->lastName,
                'email' => $faker->email,
                'type' => 'student',
                'remember_token' => null,
            ]);
            $student = Student::create([
                'user_id' => $user->id,
                'cohort_id' => $cohort->id,
                'first_name' => $faker->firstName,
                'prefix' => 'the',
                'last_name' => $faker->lastName,
                'started_on' => Carbon::now()->subYear()->toDateString(),
                'graduated' => false,
            ]);

            $module = Module::create([
                'name' => $faker->sentence($nbWords = 2, $variableNbWords = true),
                'sub_description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'long_description' => $faker->sentence($nbWords = 12, $variableNbWords = true),
                'week_duration' => $faker->numberBetween($min = 2, $max = 12),
            ]);

            DB::table('cohort_module')->insert([
                'cohort_id' => $cohort->id,
                'module_id' => $module->id,
            ]);

            DB::table('student_modules')->insert([
                'student_id' => $student->id,
                'module_id' => $module->id,
                'mark' => null, //randomFloat($nbMaxDecimals = 1, $min = 0, $max = 10),
                'approved_by' => null,
                'begin_date' => Carbon::now()->subDays($index + 7)->toDateString(),
                'finish_date' => null,
            ]);
        }
    }
}
