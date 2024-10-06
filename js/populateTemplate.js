// Fetch plant data from plants-data.json
async function fetchPlantData() {
  const response = await fetch('plants-data.json');
  const data = await response.json();
  return data.plants;
}

// Populate the database table
async function populateDatabase() {
  const plants = await fetchPlantData();
  const tableBody = document.querySelector('#plant-database tbody');
  
  plants.forEach(plant => {
    const row = tableBody.insertRow();
    row.innerHTML = `
      <td>${plant.parent}</td>
      <td>${plant.variety}</td>
      <td>${plant.type}</td>
      <td>${getGrowingSeason(plant.growingMonths)}</td>
    `;
    row.addEventListener('click', () => loadPlantDetails(plant));
  });
}

// Determine growing season based on months
function getGrowingSeason(months) {
  const seasons = [];
  if (months.some(m => m >= 3 && m <= 5)) seasons.push('Spring');
  if (months.some(m => m >= 6 && m <= 8)) seasons.push('Summer');
  if (months.some(m => m >= 9 && m <= 11)) seasons.push('Fall');
  if (months.some(m => m == 12 || m <= 2)) seasons.push('Winter');
  if (seasons.length === 4) return 'Year-round';
  return seasons.join(', ');
}

// Load plant details into template.html
function loadPlantDetails(plant) {
  // Store plant data in localStorage for transfer between pages
  localStorage.setItem('selectedPlant', JSON.stringify(plant));
  // Navigate to template.html
  window.location.href = 'template.html';
}

// Populate template.html with plant data
function populateTemplate() {
  const plantData = JSON.parse(localStorage.getItem('selectedPlant'));
  if (plantData) {
    document.getElementById('parentName').textContent = plantData.parent;
    document.getElementById('varietyName').textContent = plantData.variety;
    document.getElementById('plantType').textContent = plantData.type;
    // Populate other fields as needed
  }
}

// Event listeners
if (document.getElementById('plant-database')) {
  populateDatabase();
}

if (document.getElementById('parentName')) {
  populateTemplate();
}
