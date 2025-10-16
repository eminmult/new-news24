<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDleData extends Command
{
    protected $signature = 'migrate:dle-categories';

    protected $description = 'Мигрировать категории из DLE в Laravel Filament';

    public function handle()
    {
        $this->info('========================================');
        $this->info('   Миграция категорий из DLE');
        $this->info('========================================');
        $this->newLine();

        try {
            $dle = DB::connection('dle_mysql');

            // Получаем все категории из DLE
            $dleCategories = $dle->table('dle_category')
                ->orderBy('id')
                ->get();

            $this->info("📁 Найдено категорий в DLE: {$dleCategories->count()}");
            $this->newLine();

            $bar = $this->output->createProgressBar($dleCategories->count());
            $bar->start();

            $migrated = 0;

            // Создаем все категории (без иерархии, как обычные категории)
            foreach ($dleCategories as $dleCat) {
                Category::updateOrCreate(
                    ['slug' => $dleCat->alt_name],
                    [
                        'name' => $dleCat->name,
                        'slug' => $dleCat->alt_name,
                        'description' => $dleCat->descr ?? null,
                        'order' => $dleCat->posi ?? 0,
                        'is_active' => true, // Статус "Активно!"
                        'show_in_menu' => false, // "Показать в меню" отключено
                        'color' => $this->generateCategoryColor(),
                    ]
                );

                $migrated++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("✓ Успешно мигрировано категорий: {$migrated}");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Ошибка миграции: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    protected function generateCategoryColor()
    {
        $colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Amber
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#EC4899', // Pink
            '#14B8A6', // Teal
            '#F97316', // Orange
            '#6366F1', // Indigo
            '#84CC16', // Lime
        ];

        return $colors[array_rand($colors)];
    }
}
