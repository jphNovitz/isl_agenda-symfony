
$('document').ready(function () {
    var clicked = 0;

    $('#menu').click(function () {
        $('#global').addClass('is-hidden');
        $('#lateral-group').addClass('lateral-show');
        $('#lateral-group').removeClass('lateral-hide');

        $('html').click(function (event) {

            if (event.target.id === 'global' || event.target.id === 'lateral-button') {
                ;
                $('#lateral-group').addClass('lateral-hide');
                $('#lateral-group').removeClass('lateral-show');
                $('#global').addClass('is-visible');
                $('#global').removeClass('is-hidden');
            }

        });
    });



    $('#dropD').click(function () 
    {
        
        $('.navbar-nav').addClass('shadow');
        if (clicked === 0) {
            $('.navbar-nav').addClass('slide-down');
            $('.navbar-nav').removeClass('slide-up');
            arrow_up_path_file = 'arrow_drop_up.svg';
            $(this).find('img').attr('src', arrow_up_path_file);
            clicked = 1;
            console.log(clicked);
        }
        else {
            $('.navbar-nav').addClass('slide-up');
            $('.navbar-nav').removeClass('slide-down');
            arrow_up_path_file = 'arrow_drop_down.svg';
            $(this).find('img').attr('src', arrow_up_path_file);
            clicked = 0;
              console.log(clicked);

        }

    });



});
