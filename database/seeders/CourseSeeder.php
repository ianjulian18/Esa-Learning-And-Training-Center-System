<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::create([
            'code' => 'CS-101',
            'title' => 'Pengenalan Budaya Perusahaan',
            'description' => 'Materi wajib untuk karyawan baru (Onboarding) mengenai visi misi dan budaya kerja di perusahaan.',
            'duration' => 120,
            'passing_grade' => 75,
            'status' => 'PUBLISHED'
        ]);

        Course::create([
            'code' => 'IT-201',
            'title' => 'Dasar Keamanan Informasi (Cybersecurity)',
            'description' => 'Panduan penting tentang cara menjaga keamanan data dan menghindari phising di lingkungan kantor.',
            'duration' => 60,
            'passing_grade' => 80,
            'status' => 'PUBLISHED'
        ]);

        Course::create([
            'code' => 'MGT-301',
            'title' => 'Leadership for New Managers',
            'description' => 'Pelatihan kepemimpinan tingkat dasar untuk supervisor dan manajer baru.',
            'duration' => 240,
            'passing_grade' => 85,
            'status' => 'DRAFT'
        ]);
    }
}
