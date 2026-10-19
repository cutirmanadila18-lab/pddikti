<?php
// DASHBOARD PDDIKTI - PNL - VERSI GRAFIK LANGSUNG - NO DOWNLOAD
error_reporting(0);
ini_set('display_errors', 0);
?>
<!DOCTYPE html>
<html lang="id" class="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard PDDIKTI PNL</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
tailwind.config = { darkMode: 'class' }
</script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto p-4 md:p-6">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-xl md:text-3xl font-bold">Dashboard PDDIKTI - Politeknik Negeri Lhokseumawe</h1>
    <button onclick="document.documentElement.classList.toggle('dark')" class="px-3 py-1 bg-gray-800 text-white rounded">Dark/Light</button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Total Mahasiswa</p>
      <p id="totalMhs" class="text-2xl font-bold">-</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Total Prodi</p>
      <p id="totalProdi" class="text-2xl font-bold">-</p>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
      <p class="text-sm text-gray-500">Tahun Data</p>
      <p id="tahunData" class="text-2xl font-bold">-</p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-xl shadow mb-6">
    <h2 class="font-semibold mb-4">Grafik Mahasiswa per Tahun</h2>
    <canvas id="grafikUtama" height="120"></canvas>
  </div>

  <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
    <h2 class="font-semibold mb-2">Status</h2>
    <p id="status" class="text-sm text-gray-500">Memuat data dari api/ambil_data.php...</p>
    <div class="mt-4">
      <p class="text-xs text-gray-400">Download manual (klik, bukan otomatis):</p>
      <a href="./api/unduh_historis.php" class="text-blue-500 text-sm underline">Unduh Historis (CSV)</a>
    </div>
  </div>
</div>

<script>
async function init() {
  const statusEl = document.getElementById('status');
  try {
    const res = await fetch('./api/ambil_data.php?t=' + Date.now());
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const json = await res.json();
    statusEl.innerText = 'Data berhasil dimuat';
    
    // Coba baca struktur data yang ada
    let labels = json.labels || json.tahun || Object.keys(json);
    let values = json.values || json.jumlah || Object.values(json);
    
    // Fallback kalau struktur beda
    if (!Array.isArray(labels) || labels.length > 20) {
        labels = ['2020','2021','2022','2023','2024'];
        values = [120, 150, 180, 210, 240];
        statusEl.innerText += ' (pakai data dummy karena format api beda)';
    }

    document.getElementById('totalMhs').innerText = values.reduce((a,b)=>a+b,0);
    document.getElementById('totalProdi').innerText = labels.length;
    document.getElementById('tahunData').innerText = labels[labels.length-1] || '-';

    new Chart(document.getElementById('grafikUtama'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jumlah Mahasiswa',
          data: values,
          backgroundColor: 'rgba(59,130,246,0.6)',
          borderColor: 'rgba(59,130,246,1)',
          borderWidth: 1
        }]
      },
      options: { responsive: true, plugins: { legend: { display: false } } }
    });

  } catch (e) {
    statusEl.innerText = 'Gagal load: ' + e.message;
    statusEl.className = 'text-sm text-red-500';
  }
}
init();
</script>
</body>
</html>
