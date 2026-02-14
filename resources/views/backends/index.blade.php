@extends('backends.master')

@section('page_title')
    Admin Dashboard
@endsection

@push('css')
    <style>
        .highcharts-credits {
            display: none !important;
        }
    </style>
@endpush

@section('contents')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{__('Welcome')}}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row gx-3 gy-3">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="small-box shadow p-3">
                        <div class="inner">
                            <h5>{{ __('Product') }}</h5>
                            <p>{{ $totalProducts }}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-cube"></i>
                        </div>
                    </div>
                </div>

                <!-- Customer Box -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="small-box shadow p-3">
                        <div class="inner">
                            <h5>{{ __('Customer') }}</h5>
                            <p>{{ $totalCustomers }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Booking Box -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="small-box shadow p-3">
                        <div class="inner">
                            <h5>{{ __('Total Booking') }}</h5>
                            <p>${{ $totalSales }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard"></i>
                        </div>
                    </div>
                </div>

                <!-- Today Booking Box -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="small-box shadow p-3">
                        <div class="inner">
                            <h5>{{ __('Today Booking') }}</h5>
                            <p>${{ number_format($todayTotalSales, 2) }}</p>
                        </div>
                        <div class="icon">
                            <i class="far fa-clipboard"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div id="chart_div" class="chart_div"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        const labels = {!! json_encode($labels) !!};
        const values = {!! json_encode($all_sell_values) !!}.map(Number);

        console.log('Labels:', labels);
        console.log('Values:', values);

        Highcharts.chart('chart_div', {
            chart: {
                type: 'spline'
            },
            title: {
                text: '{{ __('Sales Last 30 Days') }}',
                align: 'left'
            },
            xAxis: {
                categories: labels,
                lineColor: '#20CDEC'
            },
            yAxis: {
                title: {
                    text: '{{ __('Sales (USD)') }}'
                }
            },
            legend: {
                align: 'right',
                verticalAlign: 'top',
                floating: true,
                layout: 'vertical'
            },
            series: [{
                name: '{{ __('Daily Sales') }}',
                data: values,
                color: '#20CDEC',
                marker: {
                    enabled: true,
                    lineWidth: 1,
                    radius: 4,
                    lineColor: '#ff6600',
                    fillColor: '#ff6600',
                    states: {
                        hover: {
                            enabled: true,
                            lineWidth: 1,
                            radius: 5,
                            fillColor: '#ff6600',
                            lineColor: '#ff6600'
                        }
                    }
                }
            }],
            tooltip: {
                backgroundColor: '#FFFFFF',
                borderColor: '#FF5733',
                style: {
                    color: '#333333'
                }
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endpush
