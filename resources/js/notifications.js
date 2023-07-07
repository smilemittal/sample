// $(document).ready(function () {
   

//     $(document).on('click', '.notification-text', function (e) {
//         e.preventDefault();
//         let url = $(this).attr('href');

//         let btn = $(this);
//         $.ajax({
//             url: url,
//             method: 'post',
//             data: {
//                 '_token': $('meta[name="csrf-token"]').attr('content'),
//             },
//             success: function (data) {
//                 $("#loader").addClass('hide');
//                 if (data.success == true) {
//                     $(".notification-sec").load(location.href + " .notification-sec>*");
//                     $(btn).addClass('is-read');
//                 } 
//             }
//         });
//     });
    
// });
