<?php

namespace App\Services\NewsCollectorService;
use App\Models\News;
use \Log;
use Illuminate\Support\Facades\Http;

class TheGuardianNewsCollector {

    /**
     * the callable function
     */
    public function run() {
        Log::info('The Guardian Collector : Start');
        try {
            $rawNewsArray = $this->getRawNewsFromAPI();
            Log::info('News imported');
            $newsArray = $this->getFormattedNewsArrayFromRawNewsArray($rawNewsArray);
            Log::info('Processed');
            unset($rawNewsArray); // to save memory
            if(!empty($newsArray)) { // 
                News::insert($newsArray);
            }
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
        $url = 'https://content.guardianapis.com/search';
        $params = http_build_query([
            'format' => 'json',
            'from-date' => date('Y-m-d'),
            'api-key' => env('THE_GUARDIAN_API_KEY'),
            'show-tags' => 'contributor',
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
        if(!isset($responseArray['response'])) {
            $message = $responseArray['message'] ?? 'Invalid Response';
            throw new Exception($url . ' ' . $message, 1);
        }
        return $responseArray['response']['results'] ?? [];
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
                'source' => 'the_guardian',
                'category' => config('news.categories.source_preset')[$value['sectionId']] ?? config('news.categories.default'),
                'author' => $value['tags'][0]['webTitle'] ?? 'Unknown',
                'date' => date('Y-m-d', strtotime($value['webPublicationDate'])),
                'title' => substr($value['webTitle'], 0, 200),
                'description' => substr($value['webTitle'], 0, 500),
                'content_url' => $value['webUrl'],
                'id_from_source' => substr($value['id'], 0, 500),
                'created_at' => now(),
            ];
            $newsArr[] = $newsRow;
        }
        return $newsArr;
    }

}