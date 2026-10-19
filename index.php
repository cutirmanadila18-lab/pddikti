
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

<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-6xl mx-auto">

        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">
            DASHBOARD PDDIKTI POLITEKNIK NEGERI LHOKSEUMAWE
        </h1>

        <!-- Ringkasan Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="text-gray-500 text-sm">Total Mahasiswa</h2>
                <p id="totalMahasiswa" class="text-2xl font-bold text-blue-600">
                    -
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="text-gray-500 text-sm">Jumlah Tahun Data</h2>
                <p id="jumlahTahun" class="text-2xl font-bold text-green-600">
                    -
                </p>
            </div>

            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="text-gray-500 text-sm">Status Data</h2>
                <p id="statusData" class="text-lg font-bold text-yellow-600">
                    Memuat...
                </p>
            </div>

        </div>

        <!-- Grafik Mahasiswa -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">

            <h2 class="text-xl font-bold mb-4 text-gray-800">
                Grafik Data Mahasiswa
            </h2>

            <div class="relative w-full" style="height: 350px;">
                <canvas id="grafik"></canvas>
            </div>

            <p id="status" class="text-center mt-4 text-gray-500">
                Loading data...
            </p>

        </div>

    </div>

    <script>
        async function loadData() {
            try {
                const res = await fetch('./api/ambil_data.php');

                if (!res.ok) {
                    throw new Error('Gagal mengambil data dari server.');
                }

                const data = await res.json();

                // Mengambil data untuk grafik
                const labels = data.labels || [
                    '2020', '2021', '2022', '2023', '2024'
                ];

                const values = data.values || [
                    100, 120, 150, 180, 200
                ];

                // Menghitung ringkasan
                const total = values.reduce(
                    (sum, value) => sum + Number(value), 0
                );

                document.getElementById('totalMahasiswa').textContent =
                    total.toLocaleString('id-ID');

                document.getElementById('jumlahTahun').textContent =
                    labels.length;

                document.getElementById('statusData').textContent =
                    'Berhasil dimuat';

                document.getElementById('status').textContent =
                    'Data grafik berhasil ditampilkan.';

                // Membuat grafik
                const ctx = document
                    .getElementById('grafik')
                    .getContext('2d');

                new Chart(ctx, {
                    type: 'bar',

                    data: {
                        labels: labels,

                        datasets: [{
                            label: 'Jumlah Mahasiswa',
                            data: values,
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(37, 99, 235, 1)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Mahasiswa'
                                }
                            },

                            x: {
                                title: {
                                    display: true,
                                    text: 'Tahun'
                                }
                            }
                        },

                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });

            } catch (error) {
                document.getElementById('statusData').textContent =
                    'Gagal dimuat';

                document.getElementById('status').textContent =
                    'Error: ' + error.message +
                    ' Pastikan api/ambil_data.php tersedia.';
            }
        }

        loadData();
    </script>

</body>
</html>

