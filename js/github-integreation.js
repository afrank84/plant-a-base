// github-integration.js

const clientId = 'YOUR_GITHUB_OAUTH_APP_CLIENT_ID';
const redirectUri = 'https://your-github-pages-url.com';
const scope = 'repo';

let accessToken = null;
let octokit = null;
let username = null;
let records = [];

// Function to handle login
function login() {
    const authUrl = `https://github.com/login/oauth/authorize?client_id=${clientId}&redirect_uri=${redirectUri}&scope=${scope}`;
    window.location.href = authUrl;
}

// Function to handle the OAuth callback
function handleCallback() {
    const code = new URLSearchParams(window.location.search).get('code');
    if (code) {
        // In a real application, you would exchange this code for an access token on your server
        // For this example, we'll assume we got the access token directly (which is not secure for real-world use)
        accessToken = 'SIMULATED_ACCESS_TOKEN';
        octokit = new Octokit({ auth: accessToken });
        fetchUserData();
    }
}

// Fetch user data and initialize the app
async function fetchUserData() {
    try {
        const { data } = await octokit.request('GET /user');
        username = data.login;
        document.getElementById('username').textContent = username;
        document.getElementById('loginButton').style.display = 'none';
        document.getElementById('userInfo').style.display = 'block';
        fetchRecords();
    } catch (error) {
        console.error('Error fetching user data:', error);
    }
}

// Fetch records from GitHub
async function fetchRecords() {
    try {
        const { data } = await octokit.request('GET /repos/{owner}/{repo}/contents/{path}', {
            owner: username,
            repo: 'plant-records',
            path: `${username}_plantrecords.json`,
        });
        records = JSON.parse(atob(data.content));
        displayRecords();
    } catch (error) {
        if (error.status === 404) {
            // File doesn't exist, create it
            records = [];
            saveRecords();
        } else {
            console.error('Error fetching records:', error);
        }
    }
}

// Save records to GitHub
async function saveRecords() {
    try {
        const content = btoa(JSON.stringify(records));
        await octokit.request('PUT /repos/{owner}/{repo}/contents/{path}', {
            owner: username,
            repo: 'plant-records',
            path: `${username}_plantrecords.json`,
            message: 'Update plant records',
            content: content,
            sha: await getFileSha(),
        });
        displayRecords();
    } catch (error) {
        console.error('Error saving records:', error);
    }
}

// Get the SHA of the existing file (if it exists)
async function getFileSha() {
    try {
        const { data } = await octokit.request('GET /repos/{owner}/{repo}/contents/{path}', {
            owner: username,
            repo: 'plant-records',
            path: `${username}_plantrecords.json`,
        });
        return data.sha;
    } catch (error) {
        if (error.status === 404) {
            return null;
        }
        throw error;
    }
}

// Display records in the table
function displayRecords() {
    const tableBody = document.getElementById('plant-records-body');
    tableBody.innerHTML = '';

    records.forEach((record, index) => {
        const row = `
            <tr>
                <td>${record.date}</td>
                <td>${record.event}</td>
                <td>${record.location}</td>
                <td>${record.notes}</td>
                <td>
                    <i class="bi bi-pencil-square text-primary action-btn edit-row" data-id="${index}"></i>
                    <i class="bi bi-trash text-danger action-btn delete-row" data-id="${index}"></i>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });

    attachEventListeners();
}

// Attach event listeners to edit and delete buttons
function attachEventListeners() {
    document.querySelectorAll('.edit-row').forEach(btn => {
        btn.addEventListener('click', editRecord);
    });

    document.querySelectorAll('.delete-row').forEach(btn => {
        btn.addEventListener('click', deleteRecord);
    });
}

// Edit record
function editRecord(event) {
    const id = event.target.getAttribute('data-id');
    const record = records[id];
    
    document.getElementById('recordId').value = id;
    document.getElementById('date').value = record.date;
    document.getElementById('event').value = record.event;
    document.getElementById('location').value = record.location;
    document.getElementById('notes').value = record.notes;

    const modal = new bootstrap.Modal(document.getElementById('recordModal'));
    modal.show();
}

// Delete record
function deleteRecord(event) {
    const id = event.target.getAttribute('data-id');
    if (confirm('Are you sure you want to delete this record?')) {
        records.splice(id, 1);
        saveRecords();
    }
}

// Save record
function saveRecord() {
    const id = document.getElementById('recordId').value;
    const record = {
        date: document.getElementById('date').value,
        event: document.getElementById('event').value,
        location: document.getElementById('location').value,
        notes: document.getElementById('notes').value
    };

    if (id === '') {
        records.push(record);
    } else {
        records[id] = record;
    }

    saveRecords();
    const modal = bootstrap.Modal.getInstance(document.getElementById('recordModal'));
    modal.hide();
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    handleCallback();

    document.getElementById('loginButton').addEventListener('click', login);
    document.getElementById('logoutButton').addEventListener('click', () => {
        accessToken = null;
        octokit = null;
        username = null;
        records = [];
        document.getElementById('loginButton').style.display = 'block';
        document.getElementById('userInfo').style.display = 'none';
        displayRecords();
    });

    document.getElementById('add-row').addEventListener('click', () => {
        document.getElementById('recordForm').reset();
        document.getElementById('recordId').value = '';
        const modal = new bootstrap.Modal(document.getElementById('recordModal'));
        modal.show();
    });

    document.getElementById('saveRecord').addEventListener('click', saveRecord);
});
