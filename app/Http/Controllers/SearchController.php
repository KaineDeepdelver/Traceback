<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller {
    
    public function index() {

        return view('search');
    }

    public function search(Request $request) {
        $request -> validate([
            'query' => ['required', 'string', 'max:2000'],
        ]);

        $query = $request -> input('query');

        return response() -> json([
            'query' => $query,
            'message' => 'Traceback received your research query.',
        ]);
    }
}
