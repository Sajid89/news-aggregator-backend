<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Article;
use App\Models\Source;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScrapeNews extends Command
{
    protected $signature = 'scrape:news';
    protected $description = 'Scrape news from various APIs';

    public function handle()
    {
        $client = new Client();

        // Scrape from NewsAPI
        $this->scrapeFromNewsAPI($client);

        // Scrape from The Guardian API
        $this->scrapeFromTheGuardian($client);

        // Scrape from BBC News using NewsAPI
        $this->scrapeFromBBCNews($client);

        $this->info('News articles from multiple sources fetched successfully!');
    }

    // Scrape from NewsAPI
    private function scrapeFromNewsAPI($client)
    {
        $response = $client->request('GET', 'https://newsapi.org/v2/top-headlines', [
            'query' => [
                'country' => 'us',
                'apiKey' => '610dcd36612341acbe7411d76f9d8498',
            ]
        ]);

        $newsData = json_decode($response->getBody(), true);
        $this->saveArticles($newsData, 'NewsAPI');
    }

    // Scrape from The Guardian API
    private function scrapeFromTheGuardian($client)
    {
        $response = $client->request('GET', 'https://content.guardianapis.com/search', [
            'query' => [
                'api-key' => '2ce72392-1f70-4531-bc46-2f4deb99a8cf',
                'show-fields' => 'headline,byline,bodyText,webUrl',
            ]
        ]);

        $newsData = json_decode($response->getBody(), true);
        $this->saveArticlesFromGuardian($newsData, 'The Guardian');
    }

    // Scrape from BBC News using NewsAPI
    private function scrapeFromBBCNews($client)
    {
        $response = $client->request('GET', 'https://newsapi.org/v2/top-headlines', [
            'query' => [
                'sources' => 'bbc-news',
                'apiKey' => '610dcd36612341acbe7411d76f9d8498',
            ]
        ]);

        $newsData = json_decode($response->getBody(), true);
        $this->saveArticles($newsData, 'BBC News');
    }

    // Common logic to save articles from any source
    private function saveArticles($newsData, $sourceName)
    {
        if (isset($newsData['articles'])) {
            foreach ($newsData['articles'] as $articleData) {

                // Check if the source already exists
                $source = Source::firstOrCreate(
                    ['name' => $sourceName],
                    ['api_source_name' => $sourceName, 'url' => $articleData['url']]
                );

                // Check if the category already exists (if provided)
                $categoryName = $articleData['category'] ?? 'General';
                $category = Category::firstOrCreate(['name' => $categoryName]);

                // Format the published date
                $publishedAt = $articleData['publishedAt'] ?? now();
                $formattedPublishedAt = Carbon::parse($publishedAt)->format('Y-m-d H:i:s');

                // Save the article in the database
                Article::create([
                    'title' => $articleData['title'],
                    'description' => $articleData['description'],
                    'content' => $articleData['content'] ?? '',
                    'url' => $articleData['url'],
                    'published_at' => $formattedPublishedAt,
                    'author' => $articleData['author'] ?? 'Unknown',
                    'source_id' => $source->id,
                    'category_id' => $category->id,
                ]);
            }
        }
    }

    // Logic to save articles from The Guardian API
    private function saveArticlesFromGuardian($newsData, $sourceName)
    {
        if (isset($newsData['response']['results'])) {
            foreach ($newsData['response']['results'] as $articleData) {

                // Check if the source already exists
                $source = Source::firstOrCreate(
                    ['name' => $sourceName],
                    ['api_source_name' => $sourceName, 'url' => $articleData['webUrl']]
                );

                // The Guardian does not provide a category, so default to 'General'
                $category = Category::firstOrCreate(['name' => 'General']);

                // Format the published date
                $publishedAt = $articleData['webPublicationDate'] ?? now();
                $formattedPublishedAt = Carbon::parse($publishedAt)->format('Y-m-d H:i:s');

                // Save the article in the database
                Article::create([
                    'title' => $articleData['fields']['headline'],
                    'description' => $articleData['fields']['headline'],
                    'content' => $articleData['fields']['bodyText'] ?? '',
                    'url' => $articleData['webUrl'],
                    'published_at' => $formattedPublishedAt,
                    'author' => $articleData['fields']['byline'] ?? 'Unknown',
                    'source_id' => $source->id,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
