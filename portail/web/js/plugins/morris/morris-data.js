$(function() {
    // Area Chart
    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010 Q1',
            Loïc: 2666,
            Paul: null,
        }, {
            period: '2010 Q2',
            Loïc: 2778,
            Paul: 2294,
        }, {
            period: '2010 Q3',
            Loïc: 4912,
            Paul: 1969,
        }, {
            period: '2010 Q4',
            Loïc: 3767,
            Paul: 3597,
        }, {
            period: '2011 Q1',
            Loïc: 6810,
            Paul: 1914,
        }, {
            period: '2011 Q2',
            Loïc: 5670,
            Paul: 4293,
        }, {
            period: '2011 Q3',
            Loïc: 4820,
            Paul: 3795,
        }, {
            period: '2011 Q4',
            Loïc: 15073,
            Paul: 5967,
        }, {
            period: '2012 Q1',
            Loïc: 10687,
            Paul: 4460,
        }, {
            period: '2012 Q2',
            Loïc: 8432,
            Paul: 5713,
        }],
        xkey: 'period',
        ykeys: ['Loïc', 'Paul'],
        labels: ['Loïc', 'Paul'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });
});
