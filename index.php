<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard PDDIKTI - PNL</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen p-6">
<div class="max-w-6xl mx-auto">
<h1 class="text-2xl font-bold text-center mb-6 dark:text-white">DASHBOARD PDDIKTI POLITEKNIK NEGERI LHOKSEUMAWE</h1>
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<canvas id="grafik"></canvas>
</div>
<div id="status" class="text-center mt-4 text-gray-500">Loading data...</div>
</div>

<script>
async function loadData() {
  try {
    const res = await fetch('./api/ambil_data.php');
    const data = await res.json();
    document.getElementById('status').innerText = 'Data loaded: ' + JSON.stringify(data).substring(0,100);
    
    // Contoh grafik - sesuaikan dengan struktur data kamu
    const ctx = document.getElementById('grafik').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels || ['2020','2021','2022','2023','2024'],
        datasets: [{
          label: 'Mahasiswa',
          data: data.values || [100,120,150,180,200],
          backgroundColor: 'rgba(59,130,246,0.5)'
        }]
      }
    });
  } catch(e) {
    document.getElementById('status').innerText = 'Error: ' + e.message + ' - cek api/ambil_data.php';
  }
}
loadData();
</script>
</body>
</html>
