<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the original SQL dump in the workspace
        $sqlPath = base_path('../project_web/project_web/project_web.sql');

        if (!file_exists($sqlPath)) {
            $this->command->info("SQL file not found at $sqlPath — skipping import.");
            return;
        }

        $sql = file_get_contents($sqlPath);
        if (!$sql) {
            $this->command->info('SQL file empty or unreadable — skipping import.');
            return;
        }

        // Extract only INSERT statements and ignore CREATE TABLE (migrations handle that)
        preg_match_all('/INSERT INTO `([^`]+)`[^;]+;/is', $sql, $matches);
        
        if (empty($matches[0])) {
            $this->command->info('No INSERT statements found in SQL dump.');
            return;
        }

        DB::beginTransaction();
        try {
            // Temporarily disable foreign key checks for bulk inserts
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            foreach ($matches[0] as $insert) {
                DB::unprepared($insert);
            }
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::commit();
            $this->command->info('Imported ' . count($matches[0]) . ' INSERT statements from SQL dump.');
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('Failed to import SQL: ' . $e->getMessage());
        }
    }
}
