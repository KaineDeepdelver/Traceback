<?php
namespace App\Services {

    class SearchService {
        
        public function investigate(string $query) : array {

            return [
                'query' => $query,
                'status' => 'pending',
                'sources' => [],
                'summary' => null,
            ];
        }
    }
}

?>