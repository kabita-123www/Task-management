// (function ($) {
//     'use strict';
//     /*==================================================================
//         [ Daterangepicker ]*/
//     try {
//         $('.js-datepicker').daterangepicker({
//             "singleDatePicker": true,
//             "showDropdowns": true,
//             "autoUpdateInput": false,
//             locale: {
//                 format: 'YYYY/MM/DD'
//             },
//         });
    
//         var myCalendar = document.querySelector('.js-datepicker');
//         //  $('.js-datepicker');
//         var isClick = 0;
    
//         $(window).on('click',function(){
//             isClick = 0;
//         });
    
//         $(myCalendar).on('apply.daterangepicker',function(ev, picker){
//             isClick = 0;
//             $(this).val(picker.startDate.format('YYYY/MM/DD'));
    
//         });
    
//         $('.js-btn-calendar').on('click',function(e){
//             e.stopPropagation();
    
//             if(isClick === 1) isClick = 0;
//             else if(isClick === 0) isClick = 1;
    
//             if (isClick === 1) {
//                 myCalendar.focus();
//             }
//         });
    
//         $(myCalendar).on('click',function(e){
//             e.stopPropagation();
//             isClick = 1;
//         });
    
//         $('.daterangepicker').on('click',function(e){
//             e.stopPropagation();
//         });
    
    
//     } catch(er) {console.log(er);}
//     /*[ Select 2 Config ]
//         ===========================================================*/
    
//     try {
//         var selectSimple = $('.js-select-simple');
    
//         selectSimple.each(function () {
//             var that = $(this);
//             var selectBox = that.find('select');
//             var selectDropdown = that.find('.select-dropdown');
//             selectBox.select2({
//                 dropdownParent: selectDropdown
//             });
//         });
    
//     } catch (err) {
//         console.log(err);
//     }
    

// })(jQuery);
(function () {
    'use strict';

    /*==================================================================
        [ Daterangepicker ]
    ==================================================================*/
    try {
        var datepickerInputs = document.querySelectorAll('.js-datepicker');

        datepickerInputs.forEach(function (input) {
            var picker = new Datepicker(input, {
                autohide: true,
                format: 'yyyy/mm/dd'
            });

            input.addEventListener('changeDate', function (e) {
                this.value = e.detail.date ? picker.formatDate(e.detail.date, 'yyyy/mm/dd') : '';
            });
        });

        var myCalendar = document.querySelector('.js-datepicker');
        var isClick = 0;

        window.addEventListener('click', function () {
            isClick = 0;
        });

        myCalendar.addEventListener('changeDate', function (e) {
            isClick = 0;
        });

        document.querySelector('.js-btn-calendar').addEventListener('click', function (e) {
            e.stopPropagation();

            if (isClick === 1) isClick = 0;
            else if (isClick === 0) isClick = 1;

            if (isClick === 1) {
                myCalendar.focus();
            }
        });

        myCalendar.addEventListener('click', function (e) {
            e.stopPropagation();
            isClick = 1;
        });

        document.querySelector('.daterangepicker').addEventListener('click', function (e) {
            e.stopPropagation();
        });
    } catch (err) {
        console.log(err);
    }

    /*[ Select 2 Config ]
        ===========================================================*/
    try {
        var selectSimple = document.querySelectorAll('.js-select-simple');

        selectSimple.forEach(function (select) {
            var selectBox = select.querySelector('select');
            var selectDropdown = select.querySelector('.select-dropdown');

            new Choices(selectBox, {
                searchEnabled: false,
                itemSelectText: ''
            });
        });
    } catch (err) {
        console.log(err);
    }
})();
