<?php

namespace App\Services\NewsCollectorService;
use App\Models\News;
use \Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NyTimesNewsCollector {

    /**
     * the callable function
     */
    public function run() {
        Log::info('Ny Times Collector : Start');
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
     * Get News from Source : The Guardian
     * @return array Raw News Data
     */
    private function getRawNewsFromAPI() : array {
        $url = 'https://api.nytimes.com/svc/topstories/v2/home.json';
        $params = http_build_query([
            'api-key' => env('NY_TIMES_API_KEY'),
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
        if(!isset($responseArray['results'])) {
            $message = $responseArray['message'] ?? 'Invalid Response';
            throw new Exception($url . ' Invalid Response', 1);
        }
        return $responseArray['results'] ?? [];
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
                'source' => 'ny_times',
                'category' => config('news.categories.source_preset')[$value['section']] ?? config('news.categories.default'),
                'author' => ltrim($value['byline'], ' By ') ?: 'Unknown', // because author is empty sometimes
                'date' => date('Y-m-d', strtotime($value['published_date'])),
                'title' => substr($value['title'], 0, 200),
                'description' => substr($value['abstract'], 0, 500),
                'content_url' => $value['url'],
                'id_from_source' => substr($value['uri'], 0, 500),
                'created_at' => now(),
            ];
            $newsArr[] = $newsRow;
        }
        return $newsArr;
    }

}