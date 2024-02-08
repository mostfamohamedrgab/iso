<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use Faker\Generator as Faker;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            for ($i = 1; $i <= 10; $i++) {
                Task::create([
                    'title' => "Task $i of Project {$project->id}",
                    'description' => "Description for Task $i",
                    'order' => $i,
                    'project_id' => $project->id,
                    // Add other fields as necessary
                ]);
            }
        }
    }
}
