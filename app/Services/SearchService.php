<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SearchService {

    public function investigate(string $query): array {

        $apiKey = config('services.openai.key');

        if (!$apiKey) {
            throw new RuntimeException(
                'no openai key was found in yout .env file'
            );
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(90)
            ->post('https://api.openai.com/v1/responses', [

                'model' => config('services.openai.model'),
                'tools' => [
                    ['type' => 'web_search'],
                ],
                'tool_choice' => 'required',
                'input' => "
                    Use plain text headings.
                    Do not use Markdown, backslashes, inline links, or a sources section.
                    The app will display sources separately.


                    Research the origin and history of this word, slang term, or phrase:
                    {$query}

                    Explain what it means, where it likely originated, and how its use
                    changed over time. Identify the earliest documented use you can find.

                    Be careful with attribution. Do not claim you found the first person
                    who said or invented the phrase unless reliable historical evidence
                    supports that exact claim. Distinguish an earliest recorded example
                    from the actual inventor. If the origin is disputed or uncertain,
                    say so plainly.

                    Prefer dictionaries, archives, scholarly sources, and reputable
                    publications. Include citations to the web sources you used.
                    ",
            ])
            ->throw()
            ->json();

        logger()->info('OpenAI response output', [
            'output' => $response['output'] ?? $response,
        ]);

        $summary = '';
        $sources = [];

        foreach ($response['output'] ?? [] as $outputItem) {
            if (($outputItem['type'] ?? null) !== 'message') {
                continue;
            }

            foreach ($outputItem['content'] ?? [] as $contentItem) {
                if (($contentItem['type'] ?? null) === 'output_text') {
                    $summary .= $contentItem['text'] ?? '';
                }

                foreach ($contentItem['annotations'] ?? [] as $annotation) {
                    if (($annotation['type'] ?? null) !== 'url_citation') {
                        continue;
                    }

                    $url = $annotation['url'] ?? null;

                    if (is_string($url)) {
                        $sources[$url] = [
                            'title' => $annotation['title'] ?? 'Open source',
                            'url' => $url,
                        ];
                    }
                }
            }
        }

        $sources = array_values($sources);
        if (trim($summary) === '') {
            throw new RuntimeException('the ai service returned without answers');
        }

        return [
            'query' => $query,
            'status' => count($sources) > 0 ? 'complete' : 'insufficent_evidence',
            'summary' => trim($summary),
            'sources' => $sources,
        ];
    }
}
