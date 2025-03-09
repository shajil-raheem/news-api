<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\News;
use App\Models\Preference;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dateFrom' => ['nullable', 'date'],
            'dateTo' => ['nullable', 'date'],
            'source' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'keyword' => ['nullable', 'string'],
            'offset' => ['nullable', 'integer'],
            'limit' => ['nullable', 'integer'],
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $filterData = $request->all();
        $dateFrom = '' . trim($filterData['dateFrom'] ?? '');
        $dateTo = '' . trim($filterData['dateTo'] ?? '');
        $source = '' . trim($filterData['source'] ?? '');
        $category = '' . trim($filterData['category'] ?? '');
        $keyword = '' . trim($filterData['keyword'] ?? '');
        $offset = '' . trim($filterData['offset'] ?? '');
        $limit = '' . trim($filterData['limit'] ?? '') ?: 20;
        $where = [];
        if($dateFrom) {
            $where[['date', '>=', date('Y-m-d', strtotime($dateFrom))]];
        }
        if($dateTo) {
            $where[['date', '<=', date('Y-m-d', strtotime($dateTo))]];
        }
        if($source) {
            $where[['source', '=', $source]];
        }
        if($category) {
            $where[['category', '=', $category]];
        }
        $newsQuery = News::select([
            'source',
            'category',
            'author',
            'date',
            'title',
            'description',
            'content_url',
        ]);
        if(!empty($where)) {
            $newsQuery = $newsQuery->where($where);
        }
        if($keyword) {
            $newsQuery = $newsQuery->whereLike('description', '%' . $keyword . '%');
        }
        $data = $newsQuery->offset((int)$offset)
            ->limit($limit)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        for($i = 0; isset($data[$i]); $i++) {
            $data[$i]['contentUrl'] = $data[$i]['content_url'];
            unset($data[$i]['content_url']);
            $data[$i]['categoryName'] = config('news.categories.preset')[$data[$i]['category']];
            $data[$i]['sourceName'] = config('news.sources')[$data[$i]['source']];
        }
        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function personalized(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => ['nullable', 'integer'],
            'limit' => ['nullable', 'integer'],
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $arr = $request->all();
        $offset = trim($arr['offset'] ?? '') ?: 0;
        $limit = trim($arr['limit'] ?? '') ?: 20;
        $userId = Auth::user()->id;
        $preferencesRow = Preference::select([
                'categories',
                'authors',
                'sources',
            ])
            ->where('user_id', $userId)
            ->first();
        if(empty($preferencesRow)) {
            return response()->json([], 200);
        }
        $categories = json_decode($preferencesRow['categories'], true) ?: [];
        $authors = json_decode($preferencesRow['authors'], true) ?: [];
        $sources = json_decode($preferencesRow['sources'], true) ?: [];

        $newsQuery = News::select([
            'source',
            'category',
            'author',
            'date',
            'title',
            'description',
            'content_url',
        ]);
        if(!empty($categories)) {
            $newsQuery->whereIn('category', $categories);
        }
        if(!empty($authors)) {
            $newsQuery->whereIn('author', $authors);
        }
        if(!empty($sources)) {
            $newsQuery->whereIn('source', $sources);
        }
        $data = $newsQuery->offset((int)$offset)
            ->limit($limit)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        for($i = 0; isset($data[$i]); $i++) {
            $data[$i]['contentUrl'] = $data[$i]['content_url'];
            unset($data[$i]['content_url']);
            $data[$i]['categoryName'] = config('news.categories.preset')[$data[$i]['category']];
            $data[$i]['sourceName'] = config('news.sources')[$data[$i]['source']];
        }
        return response()->json([
            'data' => $data,
        ]);
    }
}
