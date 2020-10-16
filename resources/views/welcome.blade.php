@extends('_layouts.app')

@section('content')
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                    <h5 class="text-white op-7 mb-2">Aplikasi Keuangan Azura Labs</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-4">
				<div class="card">
					<div class="card-body pb-0">
                        <h2 class="fw-bold text-primary" id="countPemasukan">0</h2>
                        <p class="text-muted">Pemasukan Bulan Ini</p>
                        <div class="pull-in sparkline-fix">
                            <div id="lineChart"><canvas width="384" height="70" style="display: inline-block; width: 384.188px; height: 70px; vertical-align: top;"></canvas></div>
					    </div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body pb-0">
                        <h2 class="fw-bold text-danger" id="countPengeluaran">0</h2>
                        <p class="text-muted">Pengeluaran Bulan Ini</p>
                        <div class="pull-in sparkline-fix">
                            <div id="lineChart2"><canvas width="384" height="70" style="display: inline-block; width: 384.188px; height: 70px; vertical-align: top;"></canvas></div>
					    </div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card">
					<div class="card-body pb-0">
						<h2 class="fw-bold text-warning" id="countReimburse">0</h2>
						<p class="text-muted">Reimburse Bulan Ini</p>
						<div class="pull-in sparkline-fix">
						    <div id="lineChart3"><canvas width="384" height="70" style="display: inline-block; width: 384.188px; height: 70px; vertical-align: top;"></canvas></div>
						</div>
					</div>
				</div>
            </div>
            <div class="col-md-4">
                <div class="card card-danger bg-primary-gradient">
                    <div class="card-header">
						<div class="card-title curves-shadow">Total Pemasukan</div>
                        <div class="card-category">Bulan Ini</div>
                        <h1 class="mt-4" id="totalpemasukan">0</h1>
					</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary bg-primary-gradient">
                    <div class="card-header">
						<div class="card-title">Total Pengeluaran</div>
						<div class="card-category">Bulan Ini</div>
                        <h1 class="mt-4" id="totalpengeluaran">0</h1>
					</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-warning bg-primary-gradient">
                    <div class="card-header">
						<div class="card-title">Total Reimburse</div>
                            <div class="card-category">Bulan Ini</div>
                            <h1 class="mt-4" id="totalreimburse">0</h1>
					</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="atlantis/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="atlantis/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="atlantis/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Chart JS -->
	<script src="atlantis/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="atlantis/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<script>
        $.ajax({
            url: "{{ url('dashboard/countdata') }}",
            type: "get",
            success: function (response) {
                $('#countPemasukan').html(response.jmlPemasukan);
                var data7pemasukan = [];
                for (var i = response.last7Pemasukan.length - 1; i>= 0; i--) {
                    data7pemasukan.push(response.last7Pemasukan[i].qty);
                };

                $('#lineChart').sparkline(data7pemasukan, {
                    type: 'line',
                    height: '70',
                    width: '100%',
                    lineWidth: '2',
                    lineColor: '#177dff',
                    fillColor: 'rgba(23, 125, 255, 0.14)'
                });

                $('#countPengeluaran').html(response.jmlPengeluaran);
                var data7pengeluaran = [];
                for (var i = response.last7Pengeluaran.length - 1; i>=0; i--) {
                    data7pengeluaran.push(response.last7Pengeluaran[i].qty);
                };

                $('#lineChart2').sparkline(data7pengeluaran, {
                    type: 'line',
                    height: '70',
                    width: '100%',
                    lineWidth: '2',
                    lineColor: '#f3545d',
                    fillColor: 'rgba(243, 84, 93, .14)'
                });

                $('#countReimburse').html(response.jmlReimburse);
                var data7reimburse = [];
                for (var i = response.last7Reimburse.length - 1; i>=0; i--) {
                    data7reimburse.push(response.last7Reimburse[i].qty);
                };

                $('#lineChart3').sparkline(data7reimburse, {
                    type: 'line',
                    height: '70',
                    width: '100%',
                    lineWidth: '2',
                    lineColor: '#ffa534',
                    fillColor: 'rgba(255, 165, 52, .14)'
                });

                $('#totalpemasukan').html(response.totalPemasukan);
                $('#totalpengeluaran').html(response.totalPengeluaran);
                $('#totalreimburse').html(response.totalReimburse);
            }
        })       
	</script>
@endpush
