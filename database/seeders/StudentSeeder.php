<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Seed the students register.
     */
    public function run(): void
    {
        $students = [
            [
                'name' => 'Nimal Perera',
                'email' => 'nimal.perera@example.com',
                'phone' => '0771234567',
                'address' => '12 Temple Road, Nugegoda',
                'date_of_birth' => '2001-04-18',
            ],
            [
                'name' => 'Kavindi Fernando',
                'email' => 'kavindi.fernando@example.com',
                'phone' => '0712345678',
                'address' => '45 Galle Road, Colombo 03',
                'date_of_birth' => '2003-09-02',
            ],
            [
                'name' => 'Ruwan Jayasinghe',
                'email' => 'ruwan.jayasinghe@example.com',
                'phone' => '0763456789',
                'address' => '8 Lake Drive, Kandy',
                'date_of_birth' => '1999-12-11',
            ],
            [
                'name' => 'Ayesha Silva',
                'email' => 'ayesha.silva@example.com',
                'phone' => '0774567890',
                'address' => '23 Hill Street, Galle',
                'date_of_birth' => '2004-06-25',
            ],
            [
                'name' => 'Tharindu Bandara',
                'email' => 'tharindu.bandara@example.com',
                'phone' => '0725678901',
                'address' => '67 Station Road, Kurunegala',
                'date_of_birth' => '2000-01-30',
            ],
            [
                'name' => 'Ishara Wickramasinghe',
                'email' => 'ishara.wickramasinghe@example.com',
                'phone' => '0756789012',
                'address' => '5 Park Avenue, Negombo',
                'date_of_birth' => '2002-03-14',
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(['email' => $student['email']], $student);
        }
    }
}
