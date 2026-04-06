<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $sections = [
           [
                'sectionName' => 'Section A',
                'year_level' => '1st Year',
                'department' => 'Computer Science',
            ],
            [
                'sectionName' => 'Section B',
                'year_level' => '2nd Year',
                'department' => 'Information Technology',
            ],
            [
                'sectionName' => 'Section C',
                'year_level' => '3rd Year',
                'department' => 'Software Engineering',
           ]
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
