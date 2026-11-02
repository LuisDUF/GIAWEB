<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            WeekDaySeeder::class,
            MuscleGroupSeeder::class,
            ExerciseSeeder::class,
            RoutineSeeder::class,
            RoutineDaySeeder::class,
            RoutineDayExerciseSeeder::class,
            UserRoutineSubscriptionSeeder::class,
            WorkoutSessionSeeder::class,
            SessionExerciseSeeder::class,
            SessionSetSeeder::class,
        ]);
    }
}
