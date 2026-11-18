new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Realisasi',
            data: [520,540,480,500,560,590,620,710,680,300,600,650],
            backgroundColor: '#1ba784'
        }]
    }
});

new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Realisasi','Sisa'],
        datasets: [{
            data: [90,10],
            backgroundColor: ['#0b7d6e','#dcdcdc']
        }]
    }
});


new Chart(document.getElementById('horizontalChart'), {
    type: 'bar',
    data: {
        labels: ['B1234AA','B9231BB','B8888CC','B2211DD'],
        datasets: [{
            data: [60,45,35,25],
            backgroundColor: '#1ba784'
        }]
    },
    options: {
        indexAxis: 'y'
    }
});

