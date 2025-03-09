<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Preference;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * get preferences, available options
     */
    function index() {
        $userId = Auth::user()->id;
        $preferencesRow = Preference::select([
                'categories',
                'authors',
                'sources',
            ])
            ->where('user_id', $userId)
            ->first();
        $preferences = [
            'categories' => [],
            'authors' => [],
            'sources' => [],
        ];
        if(!empty($preferencesRow)) {
            $preferences = [
                'categories' => json_decode($preferencesRow['categories'], true),
                'authors' => json_decode($preferencesRow['authors'], true),
                'sources' => json_decode($preferencesRow['sources'], true),
            ];
        }
        
        $categoriesArr = config('news.categories.preset');
        $categories = [];
        foreach ($categoriesArr as $key => $value) {
            $categories[] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        $authorsDb = News::distinct()->select(['author'])->get()->toArray();
        $authors = [];
        foreach ($authorsDb as $row) {
            $author = str_ireplace([' and ', ', '], ',', $row['author']);
            $authors = [...$authors,...explode(',', $author)];
        }
        $sourcesArr = config('news.sources');
        $sources = [];
        foreach ($sourcesArr as $key => $value) {
            $sources[] = ['id' => $key, 'name' => $value];
        }
        return response()->json([
            'preferences' => $preferences,
            'values' => [
                'categories' => $categories,
                'authors' => $authors,
                'sources' => $sources,
            ]
        ]);
    }

    function upsert(Request $request) {
        $userId = Auth::user()->id;
        $requestData = $request->all();
        $requestData['authors'] = ($requestData['authors'] ?? '')? json_encode($requestData['authors']): '[]';
        $requestData['categories'] = ($requestData['categories'] ?? '')? json_encode($requestData['categories']): '[]';
        $requestData['sources'] = ($requestData['sources'] ?? '')? json_encode($requestData['sources']): '[]';

        try {
            Preference::upsert(
                [[
                    'user_id' => $userId,
                    'authors' => $requestData['authors'],
                    'categories' => $requestData['categories'],
                    'sources' => $requestData['sources']
                ]],
                ['user_id'],
                ['authors', 'categories', 'authors', 'sources']
            );
        } catch (Exception $e) {
            \Log::error($e->getTraceAsString());
            return response()->json(['errors' => 'Internal Error Occured'], 500);
        }
        return response()->json(['status' => 'success'], 200);
    }
}
