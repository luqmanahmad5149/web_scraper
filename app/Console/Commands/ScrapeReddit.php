<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ScrapeReddit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:posts {subreddit=malaysia}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape posts with images from a subreddit and save to JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sub     = $this->argument('subreddit');
        $after   = null;
        $results = [];

        // fetch up to 10 pages of results
        for ($i = 0; $i < 10; $i++) {
            $url = "https://www.reddit.com/r/{$sub}.json";

            // make http request r/{subreddit}
            $response = Http::withHeaders([
                'User-Agent' => 'laravel-reddit-scraper'
            ])->get($url, ['after' => $after]);

            // if the request fails, log and stop
            if (! $response->ok()) {
                $this->error("Failed to fetch page {$i}: " . $response->status());
                break;
            }

            $data  = $response->json('data');
            $after = $data['after'];

            // loop over each post returned
            foreach ($data['children'] as $child) {
                $post = $child['data'];
                
                // only keep posts that are images and have a valid URL
                if (
                    isset($post['post_hint']) && $post['post_hint'] === 'image'
                    && filter_var($post['url'], FILTER_VALIDATE_URL)
                ) {
                    $results[] = [
                        'post_title' => $post['title'],
                        'image_url'  => $post['url'],
                    ];
                }
            }

            // if there's no "after" token, it has reached the last page
            if (! $after) {
                break;
            }
        }

        // delete old file if it exists
        if (Storage::disk('public')->exists('reddit_posts.json')) {
            Storage::disk('public')->delete('reddit_posts.json');
        }

        // save new JSON
        Storage::disk('public')
               ->put('reddit_posts.json', json_encode($results, JSON_PRETTY_PRINT));

        $this->info("Saved " . count($results) . " posts to storage/app/public/reddit_posts.json");
    }
}
