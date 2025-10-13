<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'OLAY', 'slug' => 'olay', 'color' => '#fc0067', 'order' => 1],
            ['name' => 'İNCƏSƏNƏT', 'slug' => 'incesenet', 'color' => '#8b5cf6', 'order' => 2],
            ['name' => 'WOW', 'slug' => 'wow', 'color' => '#f59e0b', 'order' => 3],
            ['name' => 'MÜSAHİBƏ', 'slug' => 'musahibe', 'color' => '#10b981', 'order' => 4],
            ['name' => 'VİDEO/FOTO', 'slug' => 'video-foto', 'color' => '#3b82f6', 'order' => 5],
            ['name' => 'ARI', 'slug' => 'ari', 'color' => '#ef4444', 'order' => 6],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Authors
        $authors = [
            ['name' => 'Elxan Məmmədov', 'slug' => 'elxan-memmedov'],
            ['name' => 'Aysel Həsənova', 'slug' => 'aysel-hesenova'],
            ['name' => 'Rəşad Əliyev', 'slug' => 'reshad-eliyev'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }

        // Create Tags
        $tags = [
            ['name' => 'Konsert', 'slug' => 'konsert'],
            ['name' => 'Film', 'slug' => 'film'],
            ['name' => 'Musiqi', 'slug' => 'musiqi'],
            ['name' => 'Teatr', 'slug' => 'teatr'],
            ['name' => 'İdman', 'slug' => 'idman'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Create Posts
        $categoryIds = Category::pluck('id')->toArray();
        $authorIds = Author::pluck('id')->toArray();
        $tagIds = Tag::pluck('id')->toArray();

        $posts = [
            [
                'title' => 'Bakıda böyük konsert keçiriləcək',
                'excerpt' => 'Paytaxtın mərkəzində dünya şöhrətli müğənnilərin iştirakı ilə konsert təşkil olunacaq.',
                'content' => '<p>Bakı şəhərinin mərkəzində keçiriləcək konsertdə bir sıra məşhur müğənnilər çıxış edəcək. Tədbir axşam saatlarında başlayacaq və gecə yarısına qədər davam edəcək.</p><p>Konsert proqramında milli və dünya musiqisindən nümunələr səslənəcək. Biletlər artıq satışa çıxarılıb.</p>',
                'is_featured' => true,
                'views' => 1250,
            ],
            [
                'title' => 'Yeni Azərbaycan filmi premyeraya hazırlaşır',
                'excerpt' => 'Yerli kinostudiyada çəkilən yeni film bu ay premyera olacaq.',
                'content' => '<p>Azərbaycan kinosunda yeni bir hadisə gözlənilir. Tanınmış rejissorun yeni filmi bu ay premyera olacaq.</p><p>Film müasir dövrümüzün aktual problemlərindən bəhs edir.</p>',
                'is_featured' => true,
                'views' => 980,
            ],
            [
                'title' => 'Məşhur aktrisa yeni rola hazırlaşır',
                'excerpt' => 'Tanınmış aktrisa yeni serialda rol alıb və çəkilişlərə başlayıb.',
                'content' => '<p>Azərbaycanlı aktrisa yeni televiziya serialında baş rolda çəkiləcək. Serial bu il nümayiş olunacaq.</p>',
                'is_featured' => false,
                'views' => 756,
            ],
            [
                'title' => 'Bakıda yeni teatr açılır',
                'excerpt' => 'Paytaxtda müasir texnologiyalarla təchiz olunmuş yeni teatr binası istifadəyə veriləcək.',
                'content' => '<p>Bakı şəhərində yeni teatr binası açılacaq. Teatr ən müasir texnologiyalarla təchiz edilib və 500 yerlik zala malikdir.</p>',
                'is_featured' => true,
                'views' => 1100,
            ],
            [
                'title' => 'Milli komanda növbəti oyuna hazırlaşır',
                'excerpt' => 'Azərbaycan milli komandası növbəti beynəlxalq matça hazırlaşır.',
                'content' => '<p>Milli futbol komandamız növbəti məsul qarşılaşmaya hazırlaşır. Oyun gələn həftə keçiriləcək.</p>',
                'is_featured' => false,
                'views' => 890,
            ],
            [
                'title' => 'Yeni musiqi festivası elan olundu',
                'excerpt' => 'Bu yay paytaxtda böyük musiqi festivası keçiriləcək.',
                'content' => '<p>Yay aylarında Bakıda beynəlxalq musiqi festivası təşkil olunacaq. Festivalda çoxlu ölkələrdən qonaqlar iştirak edəcək.</p>',
                'is_featured' => false,
                'views' => 645,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'author_id' => $authorIds[array_rand($authorIds)],
                'views' => $postData['views'],
                'read_time' => rand(3, 10),
                'is_featured' => $postData['is_featured'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(0, 30)),
            ]);

            // Attach random tags
            $randomTagIds = array_rand(array_flip($tagIds), min(rand(1, 3), count($tagIds)));
            $post->tags()->attach(is_array($randomTagIds) ? $randomTagIds : [$randomTagIds]);
        }
    }
}
