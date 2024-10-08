async function scrapeSheetData(url) {
    try {
        // Fetch the HTML content of the page
        const response = await fetch(url);
        const html = await response.text();

        // Create a temporary DOM element to parse the HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Find the table with the sheet data
        const table = doc.querySelector('.x-spreadsheet-sheet');

        if (!table) {
            throw new Error('Sheet table not found');
        }

        // Extract data from the table
        const rows = Array.from(table.querySelectorAll('.x-spreadsheet-sheet-row'));
        const data = rows.map(row => {
            const cells = Array.from(row.querySelectorAll('.x-spreadsheet-sheet-col'));
            return cells.map(cell => cell.textContent.trim());
        });

        // Assume the first row is headers
        const headers = data[0];
        const records = data.slice(1).map(row => {
            const record = {};
            headers.forEach((header, index) => {
                record[header] = row[index] || '';
            });
            return record;
        });

        return records;
    } catch (error) {
        console.error("Error scraping sheet data:", error);
        return [];
    }
}

async function updateTable() {
    const synologyShareLink = 'http://afrank84.quickconnect.to/d/s/10RFrIMfaMHE8uesoid8WzEHUqBDF4nD/ghMxg59KaUEI5EjWWK59Xz3u2-X_1hJ6-vLdA6b_wugs';
    const data = await scrapeSheetData(synologyShareLink);
    const tableBody = document.querySelector('#plant-records-table tbody');
    tableBody.innerHTML = ''; // Clear existing rows

    data.forEach(record => {
        const row = `
            <tr>
                <td>${record.Date || ''}</td>
                <td>${record.Event || ''}</td>
                <td>${record.Location || ''}</td>
                <td>${record.Notes || ''}</td>
                <td>
                    <i class="bi bi-pencil-square text-primary action-btn edit-row"></i>
                    <i class="bi bi-trash text-danger action-btn delete-row"></i>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', updateTable);
