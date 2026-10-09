const form = document.getElementById('search-form');
const input = document.getElementById('query');
const result = document.getElementById('result');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    result.textContent = 'Traceback is receiving your query...';

    try {
        const response = await fetch('/search', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                )?.content ?? ''
            },
            body: JSON.stringify({
                query: input.value
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Search request failed.');
        }

        result.textContent = `${data.message} Query: ${data.query}`; 
    } catch (error) {
        result.textContent = error.message;
    }
});