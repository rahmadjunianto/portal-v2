<?php

namespace App\Console\Commands;

use App\Models\KnowledgeBank;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Console\Command;

class ReseedChatbot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reseed-chatbot 
                            {--keep-existing : Keep existing data without truncating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reseed chatbot data (ServiceCategories, Services, KnowledgeBank) safely';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Starting chatbot data reseed...');
        $this->newLine();

        // Check if we should keep existing data
        $keepExisting = $this->option('keep-existing');

        if (!$keepExisting) {
            $this->info('📦 Truncating tables in correct order...');
            $this->newLine();

            // Disable foreign key checks for safe truncate
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate in reverse order (child to parent)
            $this->truncateTable(KnowledgeBank::class, 'knowledge_banks');
            $this->truncateTable(Service::class, 'services');
            $this->truncateTable(ServiceCategory::class, 'service_categories');

            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->newLine();
        } else {
            $this->warn('⚠️  Keeping existing data (no truncate)');
            $this->newLine();
        }

        // Run seeders
        $this->info('🌱 Running seeders...');
        $this->newLine();

        $this->call('db:seed', ['--class' => 'ServiceCategorySeeder']);
        $this->call('db:seed', ['--class' => 'ServiceSeeder']);
        $this->call('db:seed', ['--class' => 'KnowledgeBankSeeder']);

        $this->newLine();
        $this->info('✅ Chatbot data reseed completed successfully!');
        $this->newLine();

        // Show summary
        $this->table(
            ['Table', 'Count'],
            [
                ['service_categories', ServiceCategory::count()],
                ['services', Service::count()],
                ['knowledge_banks', KnowledgeBank::count()],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Truncate a table with output
     */
    private function truncateTable(string $model, string $tableName): void
    {
        $model::truncate();
        $this->line("   ✓ {$tableName} truncated");
    }
}
