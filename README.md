# web_scraper
A simple Laravel app that scrapes image posts from Reddit for the Jobstore assessment.

## Prerequisites
- PHP 8.2+
- Composer
- Git

## Installation
1. Open your terminal and navigate to your development folder
2. Clone the repository:
    ```bash
    git clone https://github.com/luqmanahmad5149/web_scraper.git
    cd web_scraper
    ``` 
3. Install dependencies:
    ```bash
    composer install
    ```
4. Create and configure the environment file:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. Make storage public:
    ```bash
    php artisan storage:link
    ```
6. Scrape images from r/malaysia (default) or any subreddit:
    ```bash
    php artisan scrape:posts
    ```
7. Start the development server in a new terminal:
    ```bash
    php artisan serve
    ```
8. Open your browser and visit:
    http://localhost:8000/reddit-posts
9. Congrats! The JSON file containing the reddit posts is saved to storage/app/public/reddit_posts.json.

## References:
1. [Web Scraping With Laravel: A Step-By-Step Guide](https://brightdata.com/blog/web-data/web-scraping-with-laravel?utm_source=chatgpt.com)
2. [Reddit Developer API Documentation](https://www.reddit.com/dev/api/)