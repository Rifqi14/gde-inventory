// Chart.plugins.register({
//     id: 'showAllTooltips',
//     beforeRender: function (chart) {
//         // create an array of tooltips
//         // we can't use the chart tooltip because there is only one tooltip per chart
//         chart.pluginTooltips = [];
//         chart.config.data.datasets.forEach(function (dataset, i) {
//             chart.getDatasetMeta(i).data.forEach(function (sector, j) {
//                 chart.pluginTooltips.push(new Chart.Tooltip({
//                     _chart: chart.chart,
//                     _chartInstance: chart,
//                     _data: chart.data,
//                     _options: chart.options.tooltips,
//                     _active: [sector]
//                 }, chart));
//             });
//         });

//         // turn off normal tooltips
//         chart.options.tooltips.enabled = false;
//     },
//     afterDraw: function (chart, easing) {
//         // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
//         if (!chart.allTooltipsOnce) {
//             if (easing !== 1)
//                 return;
//             chart.allTooltipsOnce = true;
//         }

//         // turn on tooltips
//         chart.options.tooltips.enabled = true;
//         Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
//             tooltip.initialize();
//             tooltip.update();
//             // we don't actually need this since we are not animating tooltips
//             tooltip.pivot();
//             tooltip.transition(easing).draw();
//         });
//         chart.options.tooltips.enabled = false;
//     }
// });

$(function() {
    'use strict'

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var $visitorsChart = $('#visitors-chart')
    var dataTarget = $('#visitors-chart').attr("data-target")
    var target = dataTarget.split(',')
    var dataRealisasi = $('#visitors-chart').attr("data-realisasi")
    var realisasi = dataRealisasi.split(',')
    var dataMonth = $('#visitors-chart').attr("data-month")
    var chartMonth = dataMonth.split(',')
    var visitorsChart = new Chart($visitorsChart, {
        data: {
            labels: chartMonth,
            datasets: [{
                    type: 'line',
                    data: target,
                    backgroundColor: 'transparent',
                    borderColor: '#e5222a',
                    pointBorderColor: '#e5222a',
                    pointBackgroundColor: '#e5222a',
                    fill: false,
                    // pointHoverBackgroundColor: '#ff0000',
                    // pointHoverBorderColor    : '#ff0000'
                },
                {
                    type: 'bar',
                    data: realisasi,
                    backgroundColor: '#fccf00',
                    borderColor: '#fccf00',
                    pointBorderColor: '#fccf00',
                    pointBackgroundColor: '#fccf00',
                    fill: false,
                    // pointHoverBackgroundColor: '#ced4da',
                    // pointHoverBorderColor    : '#ced4da'
                }
            ]
        },
        options: {
            plugins: {
                showAllTooltips: false,
            },
            bezierCurve: false,
            elements: {
                line: {
                    tension: 0
                }
            },
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    mode: 'index',
                    intersect: true,
                    label: function(tooltipItem, data) {
                        return 'Rp ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },
                },
            },
            scales: {
                yAxes: [{
                    // display: false,
                    // gridLines: {
                    //     display: true,
                    //     lineWidth: '4px',
                    //     color: 'rgba(0, 0, 0, .2)',
                    //     zeroLineColor: 'transparent'
                    // },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200,
                        callback: function(value, index, values) {
                            if (parseInt(value) >= 1000) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            } else {
                                return 'Rp ' + value;
                            }
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    offset: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })

    //-------------
    //- Overview per tahun -
    //-------------

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }

    var mode = 'index'
    var intersect = true

    var $overviewTahun = $('#overview-per-tahun')
    var dataTarget = $('#overview-per-tahun').attr("data-target")
    var target = dataTarget.split(',')
    var dataRealisasi = $('#overview-per-tahun').attr("data-realisasi")
    var realisasi = dataRealisasi.split(',')
    var dataMonth = $('#overview-per-tahun').attr("data-month")
    var chartMonth = dataMonth.split(',')
    var overviewTahun = new Chart($overviewTahun, {
        data: {
            labels: chartMonth,
            datasets: [{
                type: 'line',
                data: target,
                backgroundColor: 'transparent',
                borderColor: '#e5222a',
                pointBorderColor: '#e5222a',
                pointBackgroundColor: '#e5222a',
                fill: false,
                // pointHoverBackgroundColor: '#ff0000',
                // pointHoverBorderColor    : '#ff0000'
            },
            {
                type: 'bar',
                data: realisasi,
                backgroundColor: '#fccf00',
                borderColor: '#fccf00',
                pointBorderColor: '#fccf00',
                pointBackgroundColor: '#fccf00',
                fill: false,
                // pointHoverBackgroundColor: '#ced4da',
                // pointHoverBorderColor    : '#ced4da'
            }
            ]
        },
        options: {
            plugins: {
                showAllTooltips: false,
            },
            bezierCurve: false,
            elements: {
                line: {
                    tension: 0
                }
            },
            maintainAspectRatio: false,
            tooltips: {
                mode: mode,
                intersect: intersect
            },
            hover: {
                mode: mode,
                intersect: intersect
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    mode: 'index',
                    intersect: true,
                    label: function (tooltipItem, data) {
                        return 'Rp ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    },
                },
            },
            scales: {
                yAxes: [{
                    // display: false,
                    // gridLines: {
                    //     display: true,
                    //     lineWidth: '4px',
                    //     color: 'rgba(0, 0, 0, .2)',
                    //     zeroLineColor: 'transparent'
                    // },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200,
                        callback: function (value, index, values) {
                            if (parseInt(value) >= 1000) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            } else {
                                return 'Rp ' + value;
                            }
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    offset: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            }
        }
    })

    //-------------
    //- end Overview per tahun -
    //-------------

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method

    var dataBayar = $('#pieChart1').attr("data-sudahbayar")
    var databelumBayar = $('#pieChart1').attr("data-belumbayar")
    var data_anggotasudahBayar = $('#pieChart1').attr("data-anggota_sudahbayar")
    var data_anggotabelumBayar = $('#pieChart1').attr("data-anggota_belumbayar")
    var pieChartCanvas1 = $('#pieChart1').get(0).getContext('2d')
    var pieData1 = {
        labels: [
            'Sudah Bayar',
            'Belum Bayar',
        ],
        datasets: [{
            label: ['Sudah Bayar', 'Belum Bayar'],
            data: [parseInt(dataBayar), parseInt(databelumBayar)],
            anggota: [parseInt(data_anggotasudahBayar), parseInt(data_anggotabelumBayar)],
            backgroundColor: ['#fccf00', '#e5222a'],
        }]
    }
    var pieOptions1 = {
        responsive: true,
        hover: {
            mode: 'label',
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                // display: false,
                // gridLines: {
                //     display: true,
                //     lineWidth: '4px',
                //     color: 'rgba(0, 0, 0, .2)',
                //     zeroLineColor: 'transparent'
                // },
                ticks: $.extend({
                    beginAtZero: true,
                    suggestedMax: 200,
                    callback: function(value, index, values) {
                        if (parseInt(value) >= 1000) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        } else {
                            return 'Rp ' + value;
                        }
                    }
                }, ticksStyle)
            }],
            xAxes: [{
                display: true,
                offset: true,
                gridLines: {
                    display: false
                },
                ticks: ticksStyle
            }]
        },
        tooltips: {
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index];
                    var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    var total = data.datasets[0].data[0] + data.datasets[0].data[1];
                    return ' ' + label + ' : ' + ' (' + (100 * val / total).toFixed(2) + '%)';
                },
                afterLabel: function(tooltipItem, data) {
                    var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    var value = val.toLocaleString('de-DE', {
                        minimumFractionDigits: 0
                    });
                    var anggota = data.datasets[tooltipItem.datasetIndex].anggota[tooltipItem.index];
                    return ' Rp. ' + value + "\n" + ' ' + anggota + ' anggota';
                }
            }
        }
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.

    var pieChart1 = new Chart(pieChartCanvas1, {
        type: 'bar',
        data: pieData1,
        options: pieOptions1,
        plugins: {
            showAllTooltips: false,   
        }
    })

    //-----------------
    //- END PIE CHART -
    //-----------------

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var dataBayar = $('#pieChart2').attr("data-sudahbayar")
    var databelumBayar = $('#pieChart2').attr("data-belumbayar")
    var data_anggotasudahBayar = $('#pieChart2').attr("data-anggota_sudahbayar")
    var data_anggotabelumBayar = $('#pieChart2').attr("data-anggota_belumbayar")
    var data_provinsisudahBayar = $('#pieChart2').attr("data-provinsi_sudahbayar")
    var data_provinsibelumBayar = $('#pieChart2').attr("data-provinsi_belumbayar")
    var pieChartCanvas2 = $('#pieChart2').get(0).getContext('2d')
    var pieData2 = {
        labels: [
            'Sudah Bayar',
            'Belum Bayar',
        ],
        datasets: [{
            label: ['Sudah Bayar', 'Belum Bayar'],
            data: [parseInt(dataBayar), parseInt(databelumBayar)],
            anggota: [parseInt(data_anggotasudahBayar), parseInt(data_anggotabelumBayar)],
            provinsi: [parseInt(data_provinsisudahBayar), parseInt(data_provinsibelumBayar)],
            backgroundColor: ['#fccf00', '#e5222a'],
        }]
    }
    var pieOptions2 = {
            responsive: true,
            hover: {
                mode: 'label',
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    // display: false,
                    // gridLines: {
                    //     display: true,
                    //     lineWidth: '4px',
                    //     color: 'rgba(0, 0, 0, .2)',
                    //     zeroLineColor: 'transparent'
                    // },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200,
                        callback: function(value, index, values) {
                            if (parseInt(value) >= 1000) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            } else {
                                return 'Rp ' + value;
                            }
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    offset: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            },
            tooltips: {
                enabled: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.labels[tooltipItem.index];
                        var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var total = data.datasets[0].data[0] + data.datasets[0].data[1];
                        return ' ' + label + ' : ' + ' (' + (100 * val / total).toFixed(2) + '%)';
                    },
                    afterLabel: function(tooltipItem, data) {
                        var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var value = val.toLocaleString('de-DE', {
                            minimumFractionDigits: 0
                        });
                        var anggota = data.datasets[tooltipItem.datasetIndex].anggota[tooltipItem.index];
                        var provinsi = data.datasets[tooltipItem.datasetIndex].provinsi[tooltipItem.index];
                        return ' Rp. ' + value + "\n" + ' ' + anggota + ' anggota' + "\n" + ' ' + provinsi + ' provinsi';
                    }
                }
            }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
    var pieChart2 = new Chart(pieChartCanvas2, {
        type: 'bar',
        data: pieData2,
        options: pieOptions2
    })

    //-----------------
    //- END PIE CHART -
    //-----------------

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var dataBayar = $('#pieChart3').attr('data-sudahbayar')
    var databelumBayar = $('#pieChart3').attr('data-belumBayar')
    var data_anggotasudahBayar = $('#pieChart3').attr("data-anggota_sudahbayar")
    var data_anggotabelumBayar = $('#pieChart3').attr("data-anggota_belumbayar")
    var data_kotasudahBayar = $('#pieChart3').attr("data-kota_sudahbayar")
    var data_kotabelumBayar = $('#pieChart3').attr("data-kota_belumbayar")
    var pieChartCanvas3 = $('#pieChart3').get(0).getContext('2d')
    var pieData3 = {
        labels: [
            'Sudah Bayar',
            'Belum Bayar',
        ],
        datasets: [{
            label: ['Sudah Bayar', 'Belum Bayar'],
            data: [parseInt(dataBayar), parseInt(databelumBayar)],
            anggota: [parseInt(data_anggotasudahBayar), parseInt(data_anggotabelumBayar)],
            // provinsi: [12, 11],
            kota: [parseInt(data_kotasudahBayar), parseInt(data_kotabelumBayar)],
            backgroundColor: ['#fccf00', '#e5222a'],
        }]
    }
    var pieOptions3 = {
            responsive: true,
            hover: {
                mode: 'label',
            },
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    // display: false,
                    // gridLines: {
                    //     display: true,
                    //     lineWidth: '4px',
                    //     color: 'rgba(0, 0, 0, .2)',
                    //     zeroLineColor: 'transparent'
                    // },
                    ticks: $.extend({
                        beginAtZero: true,
                        suggestedMax: 200,
                        callback: function(value, index, values) {
                            if (parseInt(value) >= 1000) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            } else {
                                return 'Rp ' + value;
                            }
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display: true,
                    offset: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }]
            },
            tooltips: {
                enabled: true,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var label = data.labels[tooltipItem.index];
                        var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var total = data.datasets[0].data[0] + data.datasets[0].data[1];
                        return ' ' + label + ' : ' + ' (' + (100 * val / total).toFixed(2) + '%)';
                    },
                    afterLabel: function(tooltipItem, data) {
                        var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var value = val.toLocaleString('de-DE', {
                            minimumFractionDigits: 0
                        });
                        var anggota = data.datasets[tooltipItem.datasetIndex].anggota[tooltipItem.index];
                        var kota = data.datasets[tooltipItem.datasetIndex].kota[tooltipItem.index];
                        return ' Rp. ' + value + "\n" + ' ' + anggota + ' anggota' + "\n" + ' ' + kota + ' kab/kota';
                    }
                }
            }
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
    var pieChart3 = new Chart(pieChartCanvas3, {
        type: 'bar',
        data: pieData3,
        options: pieOptions3
    })

    //-----------------
    //- END PIE CHART -
    //-----------------
})