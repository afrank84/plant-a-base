// Add this to your js/crud_events.js file or create a new js file and include it in your HTML

async function fetchCSVData(url) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.text();
        return parseCSV(data);
    } catch (error) {
        console.error("There was a problem fetching the CSV data:", error);
        return [];
    }
}

function parseCSV(str) {
    const arr = str.split('\n').map(row => row.split(','));
    const headers = arr[0];
    const rows = arr.slice(1);
    return rows.filter(row => row.length === headers.length).map(row => {
        const obj = {};
        headers.forEach((header, index) => {
            obj[header.trim()] = row[index].trim();
        });
        return obj;
    });
}

async function updateTable() {
    const synologyShareLink = 'http://afrank84.quickconnect.to/d/s/10RFrIMfaMHE8uesoid8WzEHUqBDF4nD/ghMxg59KaUEI5EjWWK59Xz3u2-X_1hJ6-vLdA6b_wugs';
    const data = await fetchCSVData(synologyShareLink);
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
