<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <script src="{{ asset('js/search.js') }}" defer></script>
    <title>Traceback - Internet Research</title>
</head>
<body>
    <main>
        <h1>Traceback</h1>
        <p>The Internet, Investigated...</p>
        <p>Discover the meaning, origins, and sources behind anything.</p>

        <form id='search-form'>
            <input 
            id='query' 
            name='query' 
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