<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MeiliSearch\Client;

class ConfigureMeilisearchAzerbaijani extends Command
{
    protected $signature = 'meilisearch:configure-azerbaijani';
    protected $description = 'Configure Meilisearch for Azerbaijani language specifics';

    public function handle()
    {
        $this->info('Configuring Meilisearch for Azerbaijani language...');

        $client = new Client(
            config('scout.meilisearch.host'),
            config('scout.meilisearch.key')
        );

        $index = $client->index('posts');

        // Настраиваем синонимы для азербайджанских букв
        $synonyms = [
            // Ə → A, E
            'əliyev' => ['aliyev', 'eliyev'],
            'əli' => ['ali', 'eli'],
            'əhmad' => ['ahmad', 'ehmad'],
            'əziz' => ['aziz', 'eziz'],
            'əsgər' => ['asger', 'esger'],
            'ərəb' => ['arab', 'ereb'],
            'əsər' => ['asar', 'eser'],
            'əmək' => ['amak', 'emek'],
            'əhval' => ['ahval', 'ehval'],

            // Ö → O
            'gözəl' => ['gozel', 'guzel'],
            'ölkə' => ['olke'],
            'böyük' => ['boyuk', 'buyuk'],
            'kömək' => ['komek'],
            'öz' => ['oz'],
            'ölüm' => ['olum'],

            // Ü → U
            'üzü' => ['uzu'],
            'üç' => ['uch', 'uc'],
            'gün' => ['gun'],
            'üst' => ['ust'],
            'türk' => ['turk'],
            'üzgü' => ['uzgu'],

            // I → I (маленькая i без точки)
            'ılıq' => ['iliq'],
            'qırx' => ['qirx'],

            // Ğ → G, GH
            'dağ' => ['dag', 'dagh'],
            'qarabağ' => ['qarabag', 'qarabagh', 'karabag', 'karabakh'],
            'bağ' => ['bag', 'bagh'],
            'ığdır' => ['igdir'],
            'ağ' => ['ag', 'agh'],

            // Ş → SH, S
            'şah' => ['shah', 'sah'],
            'şəhər' => ['sheher', 'seher', 'shaher', 'saher'],
            'şəkil' => ['shekil', 'sekil'],
            'məşhur' => ['meshur', 'mashhur'],
            'bakı' => ['baki', 'baku'],
            'türkiyə' => ['turkiye', 'turkey'],

            // Ç → CH, C
            'çox' => ['chox', 'cox'],
            'uçuş' => ['uchush', 'ucus'],
            'azərbayçan' => ['azerbaycan', 'azerbaijan', 'azerbaijian'],
            'çay' => ['chay', 'cay'],
            'çiçək' => ['chichek', 'cicek'],
        ];

        try {
            $index->updateSynonyms($synonyms);
            $this->info('✅ Synonyms configured successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error configuring synonyms: ' . $e->getMessage());
            return 1;
        }

        // Настраиваем stop words (стоп-слова)
        $stopWords = [
            'və', 'ki', 'bu', 'o', 'bir', 'üçün', 'ilə', 'da', 'də',
            'və', 'ki', 'bu', 'o', 'bir', 'ucun', 'ile', 'da', 'de', // латинские варианты
        ];

        try {
            $index->updateStopWords($stopWords);
            $this->info('✅ Stop words configured successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error configuring stop words: ' . $e->getMessage());
            return 1;
        }

        // Настраиваем дополнительные параметры
        try {
            $index->updateSettings([
                'pagination' => [
                    'maxTotalHits' => 10000,
                ],
                'separatorTokens' => ['-', '_'],
            ]);
            $this->info('✅ Additional settings configured successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Error configuring settings: ' . $e->getMessage());
            return 1;
        }

        $this->info('');
        $this->info('🎉 Meilisearch successfully configured for Azerbaijani language!');
        $this->info('');
        $this->info('Configured features:');
        $this->info('  ✓ Synonyms for Ə, Ö, Ü, I, Ğ, Ş, Ç characters');
        $this->info('  ✓ Stop words for common Azerbaijani words');
        $this->info('  ✓ Token separators for compound words');

        return 0;
    }
}
