<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPostAuthors extends Command
{
    protected $signature = 'fix:post-authors';

    protected $description = 'Исправить авторов для постов без author_id';

    private $dleConnection;
    private $fixedCount = 0;
    private $notFoundCount = 0;

    public function handle()
    {
        $this->info('🔧 Исправление авторов постов...');
        $this->newLine();

        // Подключаемся к DLE
        config([
            'database.connections.dle_mysql' => [
                'driver' => 'mysql',
                'host' => 'mysql-dle',
                'port' => '3306',
                'database' => 'dle',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'options' => [
                    \PDO::ATTR_EMULATE_PREPARES => true,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                ],
            ]
        ]);

        $this->dleConnection = DB::connection('dle_mysql');

        // Получаем посты без авторов
        $postsWithoutAuthors = Post::whereNull('author_id')->get();

        $this->info("Найдено постов без авторов: {$postsWithoutAuthors->count()}");
        $this->newLine();

        if ($postsWithoutAuthors->isEmpty()) {
            $this->info('✅ Все посты уже имеют авторов!');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($postsWithoutAuthors->count());
        $progressBar->start();

        foreach ($postsWithoutAuthors as $post) {
            try {
                $this->fixPostAuthor($post);
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Ошибка для поста ID {$post->id}: " . $e->getMessage());
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info('✅ Готово!');
        $this->newLine();
        $this->line("  • Исправлено: <fg=green>{$this->fixedCount}</>");
        $this->line("  • Не найдено: <fg=yellow>{$this->notFoundCount}</>");

        return 0;
    }

    private function fixPostAuthor(Post $post)
    {
        // Ищем исходный пост в DLE по old_url
        $dlePost = $this->dleConnection
            ->table('dle_post')
            ->where('alt_name', $post->old_url)
            ->first();

        if (!$dlePost) {
            $this->notFoundCount++;
            return;
        }

        // Находим автора
        $authorId = $this->findAuthor($dlePost->autor);

        if ($authorId) {
            $post->author_id = $authorId;
            $post->save();
            $this->fixedCount++;
        } else {
            $this->notFoundCount++;
        }
    }

    private function findAuthor(string $dleUsername): ?int
    {
        // Ищем пользователя в DLE по username
        $dleUser = $this->dleConnection
            ->table('dle_users')
            ->where('name', $dleUsername)
            ->first();

        if (!$dleUser) {
            return null;
        }

        // Получаем полное имя
        $fullname = !empty($dleUser->fullname) ? $dleUser->fullname : $dleUser->name;

        // Создаем slug из fullname
        $slug = \Illuminate\Support\Str::slug($fullname);

        // Ищем пользователя в Laravel
        $user = User::where('slug', $slug)->first();

        return $user ? $user->id : null;
    }
}
