<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MigrateDleAuthors extends Command
{
    protected $signature = 'migrate:dle-authors';

    protected $description = 'Мигрировать авторов из DLE в Laravel Filament';

    public function handle()
    {
        $this->info('========================================');
        $this->info('   Миграция авторов из DLE');
        $this->info('========================================');
        $this->newLine();

        try {
            $dle = DB::connection('dle_mysql');

            // Получаем только администраторов из DLE (group_id = 1)
            $dleUsers = $dle->table('dle_users')
                ->where('user_group', 1) // Только администраторы
                ->orderBy('user_id')
                ->get();

            $this->info("👤 Найдено пользователей в DLE: {$dleUsers->count()}");
            $this->newLine();

            $bar = $this->output->createProgressBar($dleUsers->count());
            $bar->start();

            $migrated = 0;
            $skipped = 0;

            foreach ($dleUsers as $dleUser) {
                // Проверяем, есть ли уже такой email
                $existingUser = User::where('email', $dleUser->email)->first();

                if ($existingUser) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Используем fullname если заполнено, иначе name
                $displayName = !empty($dleUser->fullname) ? $dleUser->fullname : $dleUser->name;

                // Создаем автора (всем даем роль "author")
                User::create([
                    'name' => $displayName,
                    'slug' => $this->generateUniqueSlug($displayName),
                    'email' => $dleUser->email,
                    'password' => Hash::make(Str::random(16)), // Случайный пароль
                    'role' => 'author', // Роль "Авторы"
                    'bio' => $dleUser->info ?? null,
                    'avatar' => $dleUser->foto ? $this->processAvatar($dleUser->foto) : null,
                    'is_active' => true,
                ]);

                $migrated++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("✓ Успешно мигрировано авторов: {$migrated}");

            if ($skipped > 0) {
                $this->warn("⚠ Пропущено (уже существуют): {$skipped}");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Ошибка миграции: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    protected function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function processAvatar($foto)
    {
        if (empty($foto)) {
            return null;
        }

        // Если это дефолтная аватарка - не сохраняем
        if (str_contains($foto, 'noavatar.png')) {
            return null;
        }

        // Если путь начинается с uploads/, формируем полный URL
        if (strpos($foto, 'uploads/') === 0) {
            return 'http://178.63.72.226:8083/' . $foto;
        }

        // Если это просто имя файла, добавляем путь
        if (!str_contains($foto, '/')) {
            return 'http://178.63.72.226:8083/uploads/fotos/' . $foto;
        }

        return $foto;
    }
}
