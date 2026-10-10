import { marked } from 'marked';
import DOMPurify from 'dompurify';

const form = document.getElementById('search-form');
const input = document.getElementById('query');
const resultBox = document.getElementById('result');
const button = form.querySelector('button');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    resultBox.className = '';
    resultBox.textContent = 'Traceback is investigating...';
    button.disabled = true;

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
        console.log('Search response:', data);

        if (!response.ok) {
            throw new Error(data.message || 'Search request failed.');
        }

        resultBox.replaceChildren();

        const section = document.createElement('section');
        section.className = 'research-resultBox';

        const heading = document.createElement('h2');
        heading.textContent = `Research: ${data.query}`;
        section.appendChild(heading);

        const status = document.createElement('p');
        status.className = 'resultBox-status';
        status.textContent = `Status: ${data.status}`;
        section.appendChild(status);

        const summaryHeading = document.createElement('h3');
        summaryHeading.textContent = 'What we found: ';
        section.appendChild(summaryHeading);

        const summary = document.createElement('div');
        const markdown = (data.summary ?? '').replaceAll('\\*', '*');

        summary.innerHTML = DOMPurify.sanitize(marked.parse(markdown));
        section.appendChild(summary);

        const sourcesHeading = document.createElement('h3');
        sourcesHeading.textContent = 'Sources';
        section.appendChild(sourcesHeading);

        const sourcesList = document.createElement('ul');

        for (const source of data.sources ?? []) {
            const item = document.createElement('li');
            const link = document.createElement('a');
            const url = new URL(source.url);

            link.href = url.href;
            link.textContent = source.title;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            
            item.appendChild(link);
            sourcesList.appendChild(item);
        }

        section.appendChild(sourcesList);
        resultBox.appendChild(section);
    } catch (error) {
        resultBox.className = 'error';
        resultBox.textContent = error.message;
    } finally {
        button.disabled = false;
    }
});
