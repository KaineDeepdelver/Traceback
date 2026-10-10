<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/search.css', 'resources/js/search.js'])
    <title>Traceback - Internet Research</title>
</head>
<body>
    <main>
        <h1>Traceback</h1>
        <p>The Internet, Investigated...</p>
        <p>Discover the meaning, origins, and sources behind anything.</p>

        <form id='search-form' action="{{ url('/search') }}" method="POST">
            <input 
            id='query' 
            name='query'
            type='text' 
            placeholder='Enter a word, phrase, or claim...'
            maxlength='2000'
            required
            >
            <button type='submit'>Investigate</button>
        </form>

        <div id='result' aria-live='polite'></div>
    </main>
</body>
</html>