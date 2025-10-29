<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateLlmTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'llm:generate {--limit=200 : Number of posts to include}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate llm.txt file for AI bots indexing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating llm.txt for AI bots...');

        $limit = (int) $this->option('limit');

        // Получаем последние опубликованные посты
        $posts = Post::with('categories')
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        if ($posts->isEmpty()) {
            $this->error('No published posts found!');
            return 1;
        }

        // Формируем содержимое llm.txt
        $content = $this->generateContent($posts);

        // Сохраняем в public/llm.txt
        $filePath = public_path('llm.txt');
        File::put($filePath, $content);

        $this->info("✓ Generated llm.txt with {$posts->count()} posts");
        $this->info("✓ File saved to: {$filePath}");
        $this->info("✓ Accessible at: https://news24.az/llm.txt");

        return 0;
    }

    /**
     * Generate llm.txt content
     */
    protected function generateContent($posts): string
    {
        $content = "# LLM documentation for news24.az\n\n";
        $content .= "## About\n";
        $content .= "news24.az - Azərbaycanın xəbər saytı. Azərbaycanda və dünyada baş verən son xəbərlər.\n\n";
        $content .= "## URLs\n";

        foreach ($posts as $post) {
            // Получаем первую категорию для формирования URL
            $category = $post->categories->first();

            if (!$category) {
                continue;
            }

            // Формируем URL
            $url = "/{$category->slug}/{$post->slug}";

            // Заголовок с суффиксом сайта
            $title = $post->title . ' - Azərbaycanın xəbər saytı';

            // Описание (excerpt или начало контента)
            $description = $post->excerpt
                ? strip_tags($post->excerpt)
                : $this->truncateHtml($post->content, 150);

            // Очищаем description от лишних символов
            $description = $this->cleanDescription($description);

            // Добавляем запись
            $content .= "- [{$title}]({$url}) - {$description}\n";
        }

        return $content;
    }

    /**
     * Truncate HTML content
     */
    protected function truncateHtml($html, $length = 150): string
    {
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length);
            // Обрезаем до последнего полного слова
            $text = mb_substr($text, 0, mb_strrpos($text, ' '));
            $text .= '...';
        }

        return $text;
    }

    /**
     * Clean description text
     */
    protected function cleanDescription($text): string
    {
        // Убираем переносы строк
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);

        // Убираем множественные пробелы
        $text = preg_replace('/\s+/', ' ', $text);

        // Убираем пробелы в начале и конце
        $text = trim($text);

        return $text;
    }
}
