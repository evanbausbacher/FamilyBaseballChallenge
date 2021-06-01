/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import $ from 'jquery';


// $(".js-row").on('click', function(e){
//     e.preventDefault();
//     console.log("clicked row");
//     console.log(e.currentTarget)
//     var $standingsRow = $(e.currentTarget);
// });

var $table = $('.js-standings-table');

// $table.find('.js-expand-standings').on('click', function(e){
//     e.preventDefault();
//     // console.log('clicked icon updated');
    
//     // $(this).find('.material-icons')
//     if (e.target.innerHTML == 'expand_more'){
//         $(this).find('.material-icons')
//             .addClass('text-danger')
//             .text('expand_less');
    
    
    
//         }else{
//         $(this).find('.material-icons')
//             .removeClass('text-danger')
//             .text('expand_more');
//     }
    
//     var teamURL = $(this).data('url');
//     console.log(teamURL);
//     var $row = $(this).closest('tr');
//     console.log($row);
//     var index = $row.index();
//     console.log(index);
//     $.ajax({
//        url:  teamURL,
//        method: 'EXPAND',
//     }).then(function(data){
        

//         for (let index = 0; index < 4; index++){
            
//             var x = document.getElementById('standings');
//             console.log(x.rows.length);
//             // console.log('index of click' . x.val() - 1 )
//             var new_row = x.rows[1].cloneNode(true)
//             var len = x.rows.length;
//             new_row.cells[0].innerHTML = '&nbsp;&nbsp;&nbsp;' + data[index]['name'];
//             new_row.cells[1].innerHTML = data[index]['wins'];
//             new_row.cells[2].innerHTML = data[index]['losses'];
//             new_row.cells[3].innerHTML = data[index]['gamesPlayed'];
//             new_row.cells[4].innerHTML = "";
//             new_row.cells[5].innerHTML = "";
//             var indx = index-1;
//             new_row.insertBefore($('#standings tbody tr:nth('+indx+')'), new_row);
//             // x.appendChild(new_row);

//             //console.log(data[index]['name']);
//             //console.log(data[index]['wins']);
//         }
//         console.log(data);
//         console.log("i am done");
//     });
// });

// $table.find('tbody tr').on('click', function(){
//     console.log('row clicked updated');
// })

$('[data-toggle="toggle"]').on('click', function(e){
    e.preventDefault();
    if (e.target.innerHTML == 'expand_more') {
        $(e.target).addClass('text-danger');
        $(e.target).text('expand_less');
    } else {
        $(e.target).removeClass('text-danger')
        $(e.target).text('expand_more');
    }

	$(this).parents().nextUntil('.teams','.hide').toggle();
});
