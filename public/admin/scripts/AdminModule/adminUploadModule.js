$(document).ready(function ()
{
    var startDate = new Date('01/01/2012');
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    var currentDate = new Date();
    ToEndDate.setDate(ToEndDate.getDate() + 365);
    $('#from').datepicker({
        format: 'dd/mm/yyyy',
//        weekStart: 7,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true
    })
            .on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#to').datepicker('setStartDate', startDate);
            });
    $('#to')
            .datepicker({
                format: 'dd/mm/yyyy',
//                weekStart: 7,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true
            })
            .on('changeDate', function (selected) {
                FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#from').datepicker('setEndDate', FromEndDate);
            });
});
