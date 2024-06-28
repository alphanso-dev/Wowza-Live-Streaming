// Movies Slider start

$(document).ready(function($) {
// $('body').on('change', '#content', function(){
    carouselLoad()
});

function carouselLoad() {
    $('#content .loop').owlCarousel({
        center: false,
        items: 2,
        loop: false,
        margin: 10,
        nav : true,
        dots : false,
        responsive: {
            315: {
                items: 2
            },
            420: {
                items: 2
            },
            575: {
                items: 3
            },
            768 : {
                items: 4
            },
            992 : {
                items: 5
            },
            1200 : {
                items: 6
            },
            1400 : {
                items: 7
            }
        }
    });
}


// Movies Slider close
// lesthen 992px navbar start
$('body').on('change','#togle-btn',function(event){
    $('#mega-menu').show()
    // $('#myspace').prop("checked", false);
    // $('#browse').prop("checked", false);
});

$('body').on('click','#close-menu',function(){
    $('#mega-menu').hide()
    // $('#profile-menu').hide()
    // $('#browse-menu').hide()

    $('#togle-btn').prop("checked", false);
    // $('#myspace').prop("checked", false);
    // $('#browse').prop("checked", false);
});

$('body').on('click','section',function(){
    $('#mega-menu').hide()
});
// lesthen 992px navbar close

// active navigation start
// toastr.options = {
//     "progressBar": false,
//     "positionClass": "toast-top-left",
// }
// active navigation close


$(function(){
    var today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate());
    $('.datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format:'yyyy-mm-dd',
        maxDate: today
    });
});


$('body').on('click','.controler-pp',function(){
    if($(this).attr('id') == 'play'){
        $('#pause').show();
        $('#play').hide();
    }else{
        $('#pause').hide();
        $('#play').show();
    }
});

// VIDEO SECTION JS by KISHAN
// $('.vid-car').owlCarousel({
// center: false,
// loop: false,
// margin: 15,
// video:true,
// nav : true,
// dots : false,
// responsive: {
//     315: {
//         items: 1
//     },
//     420: {
//         items: 1
//     },
//     575: {
//         items: 2
//     },
//     768 : {
//         items: 3
//     },
//     992 : {
//         items: 4
//     },
//     1200 : {
//         items: 4
//     },
//     1400 : {
//         items: 5
//     }
// }
// });
// VIDEO SECTION JS by KISHAN
// video-sec

/*======================== Kishan Code ================================*/
// Add to Playlist
$(document).ready(function(){
    $('body').on('click','.newPlaylists',function(){
        $(".createPlaylist").show(300);
        $(".playlists").hide(300);
        $(this).hide();
        $(this).parent().parent().parent().find('#playlistModalTitle').css({
            'top': '30px'
        });
        $(this).parent().parent().parent().find('#playlistModalTitle').text('Add New Playlist');
    });
    $('body').on('click','.cancCreaPlaylists',function(){
        $(".createPlaylist").hide(300);
        $(".playlists").show(300);

        $(this).parent().parent().parent().parent().parent().find('#newPlaylist').show();
        $(this).parent().parent().parent().parent().parent().parent().find('#playlistModalTitle').css({
            'top': '25px'
        });
        $(this).parent().parent().parent().parent().parent().parent().find('#playlistModalTitle').text('Add To Playlist')
        // $("#newPlaylist").css({
        //     'opacity': '1',
        //     'cursor':'pointer'
        // });
        
        // $("#playlistModalTitle").css({
        //     'top': '25px'
        // });
    });
});
$(document).ready(function(){
    $('body').on('click','body',function(){
        $('#PlayOptiOpened').hide();
    });

    $('#playlistOptions').click( function(e) {
        e.preventDefault(); // stops link from making page jump to the top
        e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
        $('#PlayOptiOpened').toggle();
    });

    $('#PlayOptiOpened').click( function(e) {
        e.stopPropagation(); // when you click within the content area, it stops the page from seeing it as clicking the body too 
    });
    $('body').click( function() {
        $('#PlayOptiOpened').hide(); 
    });
    
    // Edit Playlist
    $('body').on('click','.edit-playlist',function(){

        if ($(this).data('type') == 'cancle'){
            $('#playlist-title-hide').show();
            $('#inline-forms-hide').hide();
            $('#playlist-name').val($('#playlist-title-hide').text());
        }else{
            $('#playlist-title-hide').hide();
            $('#inline-forms-hide').show();
        }
    });
});
// Add to Playlist
/*======================== Kishan Code ================================*/


