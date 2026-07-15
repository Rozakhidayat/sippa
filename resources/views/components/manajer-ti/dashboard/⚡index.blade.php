<?php

use Livewire\Component;
use App\Models\Historie;
use App\Models\Submission;

new class extends Component
{
    public function render()
    {
       /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $query */
        $query = Submission::query();

        $total = $query->count();

        // $completed = Submission::whereHas('step', function($q) {
        // $q->where('state_code', 'disetujui');
        // })->count();

        $completed = Submission::whereHas('develop_progres')
        ->whereDoesntHave('develop_progres', function($q) {
            $q->where('status', '!=', 'selesai');
        })->count();

        /** @var \Illuminate\Database\Eloquent\Builder<\App\Models\Submission> $query */
        $query = Submission::query();
        $rejected = $query->where('status', 'rejected')->count();
    
        $pending = $total - ($completed + $rejected);

        $status = [
            'total'     => $total,
            'completed' => $completed,
            'rejected'  => $rejected,
            'pending'   => $pending
        ];

        $statusDistribution = [
            [
                'name'  => 'Selesai Pengembangan',
                'value' => $completed
            ],
            [
                'name'  => 'Ditolak',
                'value' => $rejected
            ],
            [
                'name'  => 'Sedang Diproses',
                'value' => $pending 
            ],
        ];

        $recentActivities = Historie::query()
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        $recentSubmissions = Submission::query()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return $this->view([
            'stats' => $status,
            'statusDistribution' => $statusDistribution,
            'recentActivities' => $recentActivities,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }
};
?>

<div>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard SIPPA</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Pengajuan</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $stats['total'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Sedang Diproses</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clock-history text-warning"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $stats['pending'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-6">
                            <div class="card info-card customers-card ">
                                <div class="card-body">
                                    <h5 class="card-title">Selesai Pengembangan</h5>
                                    <div class="d-flex align-items-center ">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                            style="background: #e0f8e9">
                                            <i class="bi bi-check-circle text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $stats['completed'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-6">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Ditolak</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-x-circle text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $stats['rejected'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="card-body">
                                    <h5 class="card-title">Pengajuan Terbaru</h5>
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>No. Ticket</th>
                                                <th>Aplikasi</th>
                                                <th>Pemohon</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSubmissions as $rs)
                                            <tr>
                                                <td><span {{ $rs->no_ticket }}
                                                        class="text-primary fw-bold">{{
                                                        $rs->no_ticket }}</span></td>
                                                <td>{{ $rs->application_name }}</td>
                                                <td>{{ $rs->user->name }}</td>
                                                <td><span class="badge bg-{{ $rs->status_color }}">{{
                                                        $rs->status_label }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Distribusi Status</h5>
                            <div id="statusChart" style="min-height: 400px;" class="echart"></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Aktivitas Terakhir</h5>
                            <div class="activity">
                                @foreach($recentActivities as $activity)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $activity->created_at->diffForHumans(null, true) }}
                                    </div>
                                    <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                    <div class="activity-content">
                                        {{ $activity->user->name }} - {{ $activity->information }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener("livewire:navigated", () => {
                const chartDom = document.querySelector("#statusChart");
                if (chartDom) {
                    const myChart = echarts.init(chartDom);
                    myChart.setOption({
                        tooltip: { trigger: 'item' },
                        legend: { top: '5%', left: 'center' },
                        // Tambahkan palet warna di sini agar konsisten
                        color: ['#2eca6a', '#FF0000', '#FFD700'], 
                        series: [{
                            name: 'Status Pengajuan',
                            type: 'pie',
                            radius: ['40%', '70%'],
                            avoidLabelOverlap: false,
                            
                            itemStyle: {
                                borderRadius: 10,
                                borderColor: '#fff',
                                borderWidth: 2
                            },
                            label: { show: false, position: 'center' },
                            emphasis: {
                                label: {
                                    show: true,
                                    fontSize: '18',
                                    fontWeight: 'bold'
                                }
                            },
                            labelLine: { show: false },
                            data: @json($statusDistribution)
                        }]
                    });
                }
            });
        </script>
    </main>
</div>