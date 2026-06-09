<x-layout>
    <div class="p-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        </div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang Pemilik Toko</h2>
            <h1 id="clock" class="text-2xl font-bold text-gray-800">--:--:--</h1>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <p class="text-yellow-900 font-semibold">Notifikasi Obat</p>
            <p class="text-sm text-yellow-800">
                Obat expired: <span class="font-semibold">{{ $expiredCount }}</span> |
                Hampir expired (<= 30 hari): <span class="font-semibold">{{ $nearExpiredCount }}</span>
            </p>
        </div>

        <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
            <div
                class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default sm:px-7.5 xl:col-span-8">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3 sm:flex-nowrap">
                    <div class="flex w-full flex-wrap gap-3 sm:gap-5">
                        <div class="flex min-w-47.5">
                            <span
                                class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-red-500">
                                <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-red-500"></span>
                            </span>
                            <div class="w-full">
                                <p class="font-semibold text-red-500">Pendapatan Bersih</p>
                                <p class="text-sm font-medium date_range">{{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
                            </div>
                        </div>
                        <div class="flex min-w-47.5">
                            <span
                                class="mr-2 mt-1 flex h-4 w-full max-w-4 items-center justify-center rounded-full border border-yellow-500">
                                <span class="block h-2.5 w-full max-w-2.5 rounded-full bg-yellow-500"></span>
                            </span>
                            <div class="w-full">
                                <p class="font-semibold text-yellow-500">Pendapatan Kotor</p>
                                <p class="text-sm font-medium date_range">{{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full max-w-xs justify-end">
                        <div class="inline-flex items-center rounded-md bg-gray-400 p-1.5">
                            <button
                                class="rounded px-3 py-1 text-xs font-medium text-black shadow-card hover:bg-white hover:shadow-card"
                                id="weekButton" onclick="applyFilter('week')">Minggu
                            </button>
                            <button
                                class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card"
                                id="monthButton" onclick="applyFilter('month')">Bulan
                            </button>
                            <button
                                class="rounded px-3 py-1 text-xs font-medium text-black hover:bg-white hover:shadow-card"
                                id="yearButton" onclick="applyFilter('year')">Tahun
                            </button>
                        </div>
                    </div>
                </div>

                <div class="chart-container" style="position: relative; height:60vh; width: 100%;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 mt-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">MENU</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @php
                    $menus = [
                        ['icon' => 'fa-solid fa-gauge-high', 'label' => 'Dashboard', 'route' => route('dashboard')],
                        ['icon' => 'fa-solid fa-pills', 'label' => 'Data Obat', 'route' => route('product')],
                        ['icon' => 'fa-solid fa-layer-group', 'label' => 'Kategori Obat', 'route' => route('category')],
                        ['icon' => 'fa-solid fa-truck-ramp-box', 'label' => 'Pembelian Obat', 'route' => route('pembelian')],
                        ['icon' => 'fa-solid fa-cash-register', 'label' => 'Penjualan Obat (Kasir)', 'route' => route('cashier')],
                        ['icon' => 'fa-solid fa-boxes-stacked', 'label' => 'Stok Obat', 'route' => route('stock')],
                        ['icon' => 'fa-solid fa-chart-line', 'label' => 'Laporan', 'route' => route('report.sales')],
                        ['icon' => 'fa-solid fa-user-gear', 'label' => 'Manajemen User', 'route' => route('user')],
                        ['icon' => 'fa-solid fa-gears', 'label' => 'Pengaturan Toko', 'route' => route('settings')],
                        ['icon' => 'fa-solid fa-database', 'label' => 'Backup Database', 'route' => route('backup')],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    @if ($menu['route'])
                        <a href="{{ $menu['route'] }}">
                            <div
                                class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg shadow hover:bg-gray-100 transition cursor-pointer">
                                <i class="{{ $menu['icon'] }} text-2xl text-red-600 mb-2"></i>
                                <span class="text-sm font-medium text-gray-700">{{ $menu['label'] }}</span>
                            </div>
                        </a>
                    @else
                        <div
                            class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg shadow cursor-not-allowed opacity-50">
                            <i class="{{ $menu['icon'] }} text-2xl text-red-600 mb-2"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $menu['label'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function updateClockWIB() {
            const nowUTC = new Date();

            const wib = new Date(nowUTC.getTime() + (7 * 60 * 60 * 1000));

            const hours = String(wib.getUTCHours()).padStart(2, '0');
            const minutes = String(wib.getUTCMinutes()).padStart(2, '0');
            const seconds = String(wib.getUTCSeconds()).padStart(2, '0');

            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClockWIB, 1000);
        updateClockWIB();

        const ctx = document.getElementById('myChart').getContext('2d');

        let myChart = new Chart(ctx, {
            type: 'bar', // Changed to bar chart
            data: {
                labels: @json($days),
                datasets: [{
                    label: 'Pendapatan Bersih',
                    data: @json($netProfit),
                    backgroundColor: 'rgba(239, 68, 68, 0.6)', // Bar color
                    borderColor: 'rgb(239, 68, 68)', // Border color
                    borderWidth: 1
                }, {
                    label: 'Pendapatan Kotor',
                    data: @json($incomeData),
                    backgroundColor: 'rgba(234, 179, 8, 0.6)', // Bar color
                    borderColor: 'rgb(234, 179, 8)', // Border color
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.formattedValue;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        function applyFilter(filter) {
            const startDate = getStartDate(filter);
            const endDate = getEndDate();
            setActiveButton(filter);

            fetch(`/filter?filter=${filter}&start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    myChart.data.labels = data.labels;
                    myChart.data.datasets[0].data = data.netprofit;
                    myChart.data.datasets[1].data = data.incomedata;
                    myChart.update();

                    document.querySelectorAll('.date_range').forEach(el => {
                        el.innerText = `${data.startDate} - ${data.endDate}`;
                    });
                });
        }

        function setActiveButton(active) {
            const buttons = {
                week: document.getElementById("weekButton"),
                month: document.getElementById("monthButton"),
                year: document.getElementById("yearButton")
            };

            for (const key in buttons) {
                if (buttons[key]) {
                    buttons[key].classList.remove("bg-white", "shadow-card");
                }
            }

            if (buttons[active]) {
                buttons[active].classList.add("bg-white", "shadow-card");
            }
        }

        function getStartDate(filter) {
            let today = new Date();
            if (filter === 'week') {
                today.setDate(today.getDate() - 7);
            } else if (filter === 'month') {
                today.setMonth(today.getMonth() - 1);
            } else if (filter === 'year') {
                today.setFullYear(today.getFullYear() - 1);
            }
            return today.toISOString().split('T')[0];
        }

        function getEndDate() {
            return new Date().toISOString().split('T')[0];
        }
    </script>
</x-layout>
