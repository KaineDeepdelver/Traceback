<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SearchService;

class SearchController extends Controller {

    private SearchService $searchService;

    public function __construct(SearchService $searchService) {

        $this -> searchService = $searchService;
    }
    
    public function index() {

        return view('search');
    }

    public function search(Request $request) {
        $validated = $request -> validate([
            'query' => ['required', 'string', 'max:2000'],
        ]);

        $query = $validated['query'];
        $result = $this -> searchService -> investigate($query);

        return response() -> json($result);
    }
}
