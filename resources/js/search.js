
const form = document.getElementById('search-form');
const input = document.getElementById('query');
const result = document.getElementById('result');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    result.textContent = 'Traceback is investigating...';

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).content
            },
            body: JSON.stringify({
                query: input.value
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Search request failed.');
        }

        result.replaceChildren();

        const section = document.createElement('section');
        section.className = 'research-result';

        const heading = document.createElement('h2');
        heading.textContent = 'Research Results';
        section.appendChild(heading);

        const query = document.createElement('p');
        query.textContent = `Query: ${data.query}`;
        section.appendChild(query);

        const status = document.createElement('p');
        status.textContent = `Status: ${data.status}`;
        section.appendChild(status);

        const summaryHeading = document.createElement('h3');
        summaryHeading.textContent = 'Summary';
        section.appendChild(summaryHeading);

        const summary = document.createElement('p');
        summary.textContent = data.summary ?? 'No summary available yet.';
        section.appendChild(summary);

        const sourcesHeading = document.createElement('h3');
        sourcesHeading.textContent = 'Sources';
        section.appendChild(sourcesHeading);

        const sources = document.createElement('ul');

        for (const source of data.sources ?? []) {
            const item = document.createElement('li');
            item.textContent = typeof source === 'string'
                ? source
                : JSON.stringify(source);
            sources.appendChild(item);
        }

        if (sources.children.length === 0) {
            const item = document.createElement('li');
            item.textContent = 'No sources found yet.';
            sources.appendChild(item);
        }

        section.appendChild(sources);
        result.appendChild(section);
    } catch (error) {
        result.textContent = error.message;
    }
});
