$('.filter-btns').click(function() {

    var periodValue = '';
    switch($(this).attr('id')) {
        case 'days7':
            periodValue = 'last_7_days';
            break;
        case 'days21':
            periodValue = 'last_21_days';
            break;
        case 'months3':
            periodValue = 'last_3_months';
            break;
        case 'months12':
            periodValue = 'last_12_months';
            break;
    }

    $('#period').val(periodValue);
    $(this).closest("form").submit();
});