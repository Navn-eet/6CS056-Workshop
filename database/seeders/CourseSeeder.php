<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Seed the course catalogue.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Full-Stack Web Development',
                'description' => 'HTML, CSS, JavaScript and PHP through to Laravel, with a deployed project at the end.',
                'duration' => 24,
                'fee' => 75000.00,
                'difficulty' => 'Hard',
                'is_active' => true,
            ],
            [
                'name' => 'Data Science with Python',
                'description' => 'Data cleaning, pandas, visualisation and introductory machine learning on real datasets.',
                'duration' => 20,
                'fee' => 68000.00,
                'difficulty' => 'Medium',
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Fundamentals',
                'description' => 'Core cloud concepts, virtual machines, storage and the shared responsibility model.',
                'duration' => 8,
                'fee' => 32000.00,
                'difficulty' => 'Easy',
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'User research, wireframing and prototyping in Figma, finishing with a tested interface.',
                'duration' => 12,
                'fee' => 45000.00,
                'difficulty' => 'Medium',
                'is_active' => true,
            ],
            [
                'name' => 'Cyber Security Essentials',
                'description' => 'Threat models, network hardening, secure coding practice and incident response basics.',
                'duration' => 16,
                'fee' => 55000.00,
                'difficulty' => 'Hard',
                'is_active' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Search, social and email campaigns with analytics-driven reporting.',
                'duration' => 6,
                'fee' => 25000.00,
                'difficulty' => 'Easy',
                'is_active' => false,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(['name' => $course['name']], $course);
        }
    }
}
