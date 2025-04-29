<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hr_employees')->truncate();

        $docuemntTypes = [

        ];
        foreach($docuemntTypes as $documentType) {
            DocumentType::create($documentType);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
