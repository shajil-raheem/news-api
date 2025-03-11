<?php

namespace App\Services\NewsCollectorService;
use App\Models\News;
use \Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OpenNewsCollector {

    /**
     * the callable function
     */
    public function run() {
        Log::info('News Api Collector : Start');
        try {
            $rawNewsArray = $this->getRawNewsFromAPI();
            Log::info('News imported');
            $newsArray = $this->getFormattedNewsArrayFromRawNewsArray($rawNewsArray);
            Log::info('Processed');
            unset($rawNewsArray); // to save memory
            if(!empty($newsArray)) { // 
                News::insert($newsArray);
            }
            Cache::forget('authors');
        } catch(Exception $e) {
            Log::error('Error : '.$e->getMessage());
            return false;
        }
        Log::info('News stored');
        return true;
    }

    /**
     * Get News from Source : Open News
     * @return array Raw News Data
     */
    private function getRawNewsFromAPI() : array {
        $url = 'https://newsapi.org/v2/everything';
        $params = http_build_query([
            'q' => 'top-news-bbc',
            'from' => date('Y-m-d'),
            'to' => date('Y-m-d'),
            'sortBy' => 'popularity',
            'apiKey' => env('OPEN_NEWS_API_KEY'),
        ]);
        $url .= '?' . $params;
        $response = Http::get($url);
        if($response?->status() !== 200) {
            throw new Exception($url . ': Response Code ' . $response?->status(), 1);
        }
        if(!trim($response->body())) {
            throw new Exception($url . ' Empty Response', 1);
        }
        $responseArray = json_decode(trim($response->body()), true);
        if(empty($responseArray)) {
            throw new Exception($url . ' Invalid Response', 1);
        }
        if(!isset($responseArray['articles'])) {
            $message = $responseArray['message'] ?? 'Invalid Response';
            throw new Exception($url . ' ' . $message, 1);
        }
        return $responseArray['articles'] ?? [];
    }

    /**
     * Convert result news array to DB::insert() array format
     * @param array raw news array
     * @return array  2D array ready to insert
     */
    private function getFormattedNewsArrayFromRawNewsArray(&$rawArr) {
        $newsArr = [];
        if(empty($rawArr)) {
            return [];
        }
        $i = 0;
        foreach ($rawArr as $value) {
            $newsRow = [
                'source' => 'open_news',
                'category' => config('news.categories.default'),
                'author' => $value['author'] ?: 'Unknown', // because author is empty sometimes
                'date' => date('Y-m-d', strtotime($value['publishedAt'])),
                'title' => substr($value['title'], 0, 200),
                'description' => substr($value['description'], 0, 500),
                'content_url' => $value['url'],
                'id_from_source' => str_replace('https://www.androidauthority.com/', '', $value['url']),
                'created_at' => now(),
            ];
            $newsArr[] = $newsRow;
        }
        return $newsArr;
    }

}