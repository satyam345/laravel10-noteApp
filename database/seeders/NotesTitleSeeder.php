<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotesTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 200; $i++) {
            $data[] = ['title' => 'Note ' . $i];
        }

        // Update the title column for 200 rows in the notes table
        foreach ($data as $index => $row) {
            DB::table('notes')
                ->where('id', $index + 1) // Assuming 'id' starts at 1
                ->update($row);
        }
    }
}
