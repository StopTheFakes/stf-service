$(document).ready(function(){


    // $("html").niceScroll({
    //     scrollspeed: 80,
    //     mousescrollstep: 60,
    //     cursorcolor: "#3a81d4",
    //     smoothscroll: true,
    //     zindex: 120,
    //     cursorwidth: "8px",
    //     cursorborderradius: "10px",
    //     cursorborder: "none",
    // });

    $(".top-bar__notify-dropdown").niceScroll(".top-bar__notify-dropdown_wrap",{
        scrollspeed: 20,
        mousescrollstep: 50,
        cursorcolor: "#2581d9",
        //smoothscroll: true,
        zindex: 120,
        cursorwidth: "10px",
        cursorborderradius: "0px",
        cursorborder: "none",
        cursorfixedheight: 40,
        background: "#f0f2f3",
        autohidemode: false
    });

    $(".advansed_list").niceScroll(".flist",{
        scrollspeed: 60,
        mousescrollstep: 20,
        cursorcolor: "#2581d9",
        smoothscroll: true,
        zindex: 120,
        cursorwidth: "10px",
        cursorborderradius: "0px",
        cursorborder: "none",
        cursorfixedheight: 40,
        background: "#f0f2f3",
        autohidemode: false
    });

    var sliders = $("body").find(".images_slider");
    
    sliders.each(function() {
        $(this).slick({
            slidesToShow: 5,
            draggable: true,
            autoplay: false,
            arrows: true,
            dots: false,
            responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        });

    });

    $(".decline_alert_form").submit(function() {
        var ref = $(this).find(".reasons input");
        var validcheckbox = false;
        $(ref).each(function() {
            if ( $(this).prop('checked') == true ) {
                validcheckbox = true;
                return false;
            }
        });
        if (!validcheckbox) {
            $(this).find(".reasons").addClass('nochecked');
        } else {
            $(this).find(".reasons").removeClass('nochecked');
            $(".signal_control_params").slideToggle();
            $(this).parents('.signal_control_params').siblings('.signal_control').hide();
            $(this).parents('.signal_item').find('.clockdiv').hide();
            $(this).parents('.signal_item').find('.signal_top_bar').append('<img src="img/icon663.png" class="signal_received" alt="Icon">');
            $(this).parents('.signal_item').removeClass('processed_type').addClass('cancelled_type');
            $(this).parents('.signal_item').append('<div class="signal_description">'+$(this).find('.reason_text').val()+'</div>');
            var num1 = parseInt($('.show_processed span:nth-child(1)').text());
            var num2 = parseInt($('.show_cancelled span:nth-child(1)').text());
            $('.show_processed span:nth-child(1)').text(num1 - 1);
            $('.show_cancelled span:nth-child(1)').text(num2 + 1);
        }
        return false;
    });


    $('.signal_collapse').on("click", function(e) {
        e.preventDefault();
        $(this).parents('.signal_item').removeClass('processed_type').addClass('accepted_type');
        $(this).parents('.signal_item').find('.clockdiv').hide();
        $(this).parents('.signal_item').find('.signal_top_bar').append('<img src="img/icon652.png" class="signal_received" alt="Icon">');
        $(this).parents('.signal_item').find('.signal_control').hide();
        var num1 = parseInt($('.show_processed span:nth-child(1)').text());
        var num2 = parseInt($('.show_accepted span:nth-child(1)').text());
        $('.show_processed span:nth-child(1)').text(num1 - 1);
        $('.show_accepted span:nth-child(1)').text(num2 + 1);
    });

    $('.button_type.accept').on("click", function(e) {
        e.preventDefault();
        var task = $(this).parents('.task_signal');
        $(this).parents('.task_tab').next().find('.task_signals').append(task);
    });

    var decline_task;
    $('.button_type.decline').on("click", function(e) {
        e.preventDefault();
        decline_task = $(this).parents('.task_signal');
        $(this).parents('.task_item_block').find('.signal_control_params').slideToggle();
    });


    $('.send_offender_btn').on("click", function(e) {
        e.preventDefault();
        decline_task = $(this).addClass('deactive').siblings('.button_text').show();
    });

    $(".decline_alert_form2").submit(function() {
        var ref = $(this).find(".reasons input");
        var validcheckbox = false;
        $(ref).each(function() {
            if ( $(this).prop('checked') == true ) {
                validcheckbox = true;
                return false;
            }
        });
        if (!validcheckbox) {
            $(this).find(".reasons").addClass('nochecked');
        } else {
            $(this).find(".reasons").removeClass('nochecked');
            $(".signal_control_params").slideToggle();
            $(this).parents('.task_item_block').find('.task_tab[data-task-id="3"] .task_signals').append(decline_task);
        }
        return false;
    });



    $(".reasons input").change(function() {
        if($(this).prop('checked') == true) {
            $(this).parents('.reasons').removeClass('nochecked');
        }
    });

    $(".demo-form").submit(function() {
        var ref = $(this).find("[required]");
        var validforma = true;
        $(ref).each(function() {
            if ( $(this).val() == '' ) {
                validforma = false;
                $(this).focus();
                return false;
            }
        });

        if (validforma == true) {
          var th = $(this);
          $.ajax({
            type: "POST",
            url: "/mail.php",
            data: th.serialize()
            }).done(function() {
                setTimeout(function() {
                    th.trigger("reset");
                    $(".remodal-close").click();
                    $("#modal_thank").remodal().open();
                }, 1000);
            });
        }

        return false;
    });

    // change_menu_top();
    // $(window).resize(function() {
    //     change_menu_top();
    // });

    // function change_menu_top() {
    //     var top_bar_height = $('.main-page').offset().top;
    //     $('.demo-menu').css({ top: top_bar_height });
    // }

    $('.input_container input').on("focus", function() {
        $(this).parent().addClass('active');
    });

    $('.input_container input').on("blur", function() {
        $(this).parent().removeClass('active');
    });

    if($('select').length) {
        $('select').select2();
    }

    if($('.fancybox').length) {
    	$('.fancybox').fancybox();
    }

    if($('.table-sortable').length) {
        $('.table-sortable').tablesorter();
    }

    $('body').click(function(e){
        if(!$(e.target).parents('.open').length && !$(e.target).hasClass('open')) {
            $('.open').removeClass('open');
            $('.main-page').removeClass('dark');
        }
    });
    
    $('.user-menu').click(function(e){
        e.preventDefault();
        $(this).toggleClass('open');
        $('.user-menu-dropdown').toggleClass('open');
    });

    $('.show_current').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.task_item').hide();
        tab.find('.current').show();
    });

    $('.show_hold').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.task_item').hide();
        tab.find('.suspended').show();
    });

    $('.show_drafts').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.task_item').hide();
        tab.find('.draft').show();
    });

    $('.show_all').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.task_item').show();
    });

    $('.show_online_tupe').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.online_type').show();
    });

    $('.show_offline_tupe').on("click", function(e) {
        e.preventDefault();
        var tab = $(this).parents('.identification_tab_item');
        tab.find('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        tab.find('.offline_type').show();
    });

    $('.show_processed').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.signal_item').hide();
        $('.processed_type').show();
    });

    $('.show_accepted').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.signal_item').hide();
        $('.accepted_type').show();
    });

    $('.show_cancelled').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.signal_item').hide();
        $('.cancelled_type').show();
    });

    $('.show_all_signals').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.signal_item').show();
    });

    $('.show_accepted_alerts').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.table-my-signal tbody tr').hide();
        $('.accepted_alerts_type').show();
    });

    $('.show_accepted_alerts').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.table-my-signal tbody tr').hide();
        $('.accepted_alerts_type').show();
    });

    $('.show_declined_alerts').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.table-my-signal tbody tr').hide();
        $('.declined_alerts_type').show();
    });

    $('.show_processed_alerts').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.table-my-signal tbody tr').hide();
        $('.processed_alerts_type').show();
    });

    $('.show_all_alerts').on("click", function(e) {
        e.preventDefault();
        $('.mini-nav a').removeClass('active');
        $(this).addClass('active');

        $('.table-my-signal tbody tr').show();
    });

    $('.pay_1').on("click", function(e) {
        e.preventDefault();
        $('[name=pay_sum]').val(50);
    });
    
    $('.pay_2').on("click", function(e) {
        e.preventDefault();
        $('[name=pay_sum]').val(100);
    });

    $('.pay_3').on("click", function(e) {
        e.preventDefault();
        $('[name=pay_sum]').val(1);
    });

    $('.payout_sum').on("click", function(e) {
        e.preventDefault();
        var pay_sum = parseInt($('[name=pay_sum]').val());
        if (pay_sum > 0) {
            var sum = parseInt($('.check').text());
            $('.check').text(sum + pay_sum);
        }
    });

    $('input[type=text]').on('change invalid', function() {
        var textfield = $(this).get(0);
        textfield.setCustomValidity('');
        
        if (!textfield.validity.valid) {
          textfield.setCustomValidity('Fill this field');  
        }
    });

    $('input[type=email]').on('change invalid', function() {
        var textfield = $(this).get(0);
        textfield.setCustomValidity('');
        
        if (!textfield.validity.valid) {
          textfield.setCustomValidity('Fill this field');  
        }
    });

    // $('.task_dublicate').on("click", function(e) {
    //     e.preventDefault();
    //     var task = $(this).parents('.task_item');
    //     task.clone(true).insertAfter(task);
    // });

    $('.select-coutry').change(function(e){
        e.preventDefault();
        if($(this).val() !== 'null') {
            $('.country-h').val($(this).val()).trigger('change');
            window.VueBus.$emit('change-country', $(this).val());
            //$('.select-city').prop('disabled', false);
        }

        else {
            $('.select-city').prop('disabled', true);
        }

        var get_land_id = $('.select-coutry option:selected').attr('data-land-id');
        if(get_land_id) {
            $('.select-city').removeClass('show');
            $('.select-city[data-land-id='+get_land_id+']').addClass('show');
        }
    });

    var input_index = 0;

    $('.checkbox-item').each(function(){
        $(this).find('input').attr('id',input_index);
        $(this).find('label').attr('for',input_index);
        input_index++;
    });

    function check_pass() {
        $('.current-pass-input, .new-pass-input').change(function(){
            if($('.new-pass-input').val() !== $('.current-pass-input').val() && $('.new-pass-input').val() !== '' && $('.current-pass-input').val() !== '') {
                $('.pass-error-notice').fadeIn(0);
                $('.new-pass-input').addClass('input-error');
            }

            else {
                $('.pass-error-notice').fadeOut(0);
                $('.new-pass-input').removeClass('input-error');
            }
        });
    }

    check_pass();

    $('input[type=reset]').click(function() {
        $('.pass-error-notice').fadeOut(0);
        $('.new-pass-input').removeClass('input-error');
    });


    $('.demo-menu-trigger').click(function(e) {
        e.preventDefault();
        $('.demo-menu-trigger').toggleClass('open');
        $('.demo-menu').toggleClass('open');
        $('.main-page').toggleClass('dark');
    });

    $('.demo-menu__group-title').click(function(e) {
        e.preventDefault();
        $(this).toggleClass('show');
        $(this).next('ul').slideToggle();
        $(this).parents('.demo-menu__group').siblings().find('.demo-menu__group-title').removeClass('show');
        $(this).parents('.demo-menu__group').siblings().find('ul').slideUp();
    });

    var demo_menu_links = $(".demo-menu").find("a");
    $(demo_menu_links).each(function(){
        if ($(this).attr('href') == page_uri ) {
            $(this).addClass('active');
        }
    });


    $('.request-go').click(function(e) {
        e.preventDefault();
        $(this).siblings('.clockdiv').fadeIn();
        $(this).fadeOut();
    });

    if($(".chat-main").length) {
        $(".chat-main").niceScroll({
            cursorcolor:"#009FF3",
            nativeparentscrolling: false
        });
    }


 
    $('.chat-form').submit(function(e){
        e.preventDefault();
         
        if ($(this).hasClass('executor_chat')){
            var avatar_file_name = 'avatar-woman-sm.png';
        } else {
            var avatar_file_name = 'avatar-man-sm.png';
        }
        var get_text = $(this).find('textarea').val();
        
        if($(this).find('.chat-main').find('div:last-child').attr('class') != 'chat-second-user') {
            $(this).find('.chat-main').append('<div class="chat-second-user"><div class="chat-second-user__photo"><img src="img/'+avatar_file_name+'" alt="author"></div><div class="chat-second-user__list-message"><div class="message-item-gray">'+get_text+'</div></div></div>');
            $(this).find('textarea').val('');
        }

        else {
            $(this).find('.chat-main > div:last-child').find('.chat-second-user__list-message').append('<div class="message-item-gray">'+get_text+'</div>');
            $(this).find('textarea').val('');
        }
        setTimeout(function() {
            $('.chat-main').getNiceScroll().doScrollPos(0,$(this).find('.chat-main').scrollTop = 9999);
        }, 300);
    });

    $('.chat-nav a').click(function(e){
        e.preventDefault();
        var get_id = $(this).attr('data-chat-id');
        $('.chat-nav a').removeClass('active');
        $(this).addClass('active');
        $('.chat-item').removeClass('show');
        $('.chat-item[data-chat-id='+get_id+']').addClass('show');
    });

    $('.state_bar a').click(function(e){
        e.preventDefault();
        var get_id = $(this).attr('data-bar-id');
        $('.state_bar a').removeClass('active');
        $(this).addClass('active');
        $('.state_bar_item').removeClass('show');
        $('.state_bar_item[data-bar-id='+get_id+']').addClass('show');
    });

    $('.catalog-nav a').on("click", function(e) {
        e.preventDefault();
        var get_id = $(this).attr('data-catalog-id');
        $('.catalog-nav a').removeClass('active');
        $(this).addClass('active');
        $('.catalog_tab_item').removeClass('show');
        $('.catalog_tab_item[data-chat-id='+get_id+']').addClass('show');
    });

    $('.task_footer_nav a').on("click", function(e) {
        e.preventDefault();
        var get_id = $(this).attr('data-task-id');
        $('.task_footer_nav a').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.task_item').find('.task_tab:not([data-task-id='+get_id+'])').slideUp();
        $(this).parents('.task_item').find('.task_tab[data-task-id='+get_id+']').slideToggle();
    });

    $('.identification-nav a').on("click", function(e) {
        e.preventDefault();
        var get_id = $(this).attr('data-ident-id');
        $('.identification-nav a').removeClass('active');
        $(this).addClass('active');
        $('.identification_tab_item').removeClass('show');
        $('.identification_tab_item[data-ident-id='+get_id+']').addClass('show');
    });

    $('.btn-collapse').click(function(e){
        e.preventDefault();
        if(!$(this).hasClass('active')) {
            $(this).addClass('active').text('Minimize a window');
            $('.task__ins--collapse').addClass('show');
        } else {
            $(this).removeClass('active').text('maximize a window');
            $('.task__ins--collapse').removeClass('show');
        }
    });

    $('.refuse_request, .disable_decline').click(function(e){
        e.preventDefault();
        $(".signal_control_params").slideToggle();
    });

    $('.reason_text').keyup(function(){
      var Value = $(this).val();
      if (Value.length > 49) {
        $(this).siblings('.confirm_refuse').removeClass('disable_click');
      } else {
        $('.confirm_refuse').addClass('disable_click');
      }
    });


    $(".city").select2().on("change", formatTags);
    function formatTags(e) {
        var res = $(this).val();
        var result = res.toString().replace(/,/g, ', ');
        window.VueBus.$emit('change-city', res);
        $(this).next().find('.select2-selection--multiple').text(result);
    }


    var sels = $('.inputs-row--geo').find(".city");
    $(sels).each(function() {
        if ( $(this).val() ) {
            var res = $(this).val();
            var result = res.toString().replace(/,/g, ', ');
            console.log(result);
            $(this).next().find('.select2-selection--multiple').text(result);
        }
    });


    $('.advantages').click(function(e){
        e.preventDefault();
        $('.advansed_params_block').slideToggle();
    });

    $('.delete_file').click(function(e){
        e.preventDefault();
        $(this).parents('.item').hide();
    });

    $('.textarea-item2').on("click", 'a', function(e) {
        e.preventDefault();
        $(this).parents('.flex_row').hide();
    });
    
    $('.sent_to_offender_btn').click(function(e){
        e.preventDefault();
        $(this).addClass('recently').removeAttribute('data-remodal-target');
    });

    function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        return {
            'total': t,
            'hours': hours,
            'minutes': minutes
        };
    }

    function initializeClock(id, endtime) {
        var hoursSpan = id.find('.hours');
        var minutesSpan = id.find('.minutes');

        function updateClock() {
            var t = getTimeRemaining(endtime);

            hoursSpan[0].innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan[0].innerHTML = ('0' + t.minutes).slice(-2);

            if (t.total <= 0) {
              clearInterval(timeinterval);
          }
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    var deadline = new Date(Date.parse(new Date()) + 1 * 2 * 60 * 60 * 1000);
    var ref = $("body").find(".clockdiv");
    
    $(ref).each(function(){
        initializeClock($(this), deadline);
    });




    function getTimeRemaining2(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        return {
            'total': t,
            'hours': hours,
            'minutes': minutes
        };
    }

    function initializeClock2(id, endtime) {
        var hoursSpan = id.find('.hours');
        var minutesSpan = id.find('.minutes');

        function updateClock2() {
            var t = getTimeRemaining2(endtime);

            hoursSpan[0].innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan[0].innerHTML = ('0' + t.minutes).slice(-2);

            if (t.total <= 0) {
              clearInterval(timeinterval);
          }
        }

        updateClock2();
        var timeinterval = setInterval(updateClock2, 1000);
    }

    var deadline2 = new Date(Date.parse(new Date()) + 1 * 15 * 60 * 60 * 1000);
    var clocksimple = $("body").find(".clocksimple");
    
    $(clocksimple).each(function(){
        initializeClock2($(this), deadline2);
    });





    $("#range1").change(function () {
        $("#result_item_1").val(this.value);
    }).change();

    $("#range2").change(function () {
        $("#result_item_2").val(this.value);
    }).change();

    $("#range3").change(function () {
        $("#result_item_3").val(this.value);
    }).change();

    $("#result_item_1").change(function () {
        var elem = document.getElementById("result_item_1");
        $("#range1").val(elem.value);
    });

    $("#result_item_2").change(function () {
        var elem = document.getElementById("result_item_2");
        $("#range2").val(elem.value);
    });

    $("#result_item_3").change(function () {
        var elem = document.getElementById("result_item_3");
        $("#range3").val(elem.value);
    });


    // $(".checkbox_parent > input").change(function () {
    //     if(this.checked) {
    //         $(this).siblings('ul').find('input').prop("checked", true);
    //     }
    // });

    // $(".flist input").change(function () {
    //     if(this.checked) {
    //         $(this).siblings('ul').find('input').prop("checked", true);
    //     }
    // });
    //
    // $(".flist input").change(function () {
    //     if(!this.checked) {
    //         $(this).parents('ul').siblings('input').prop("checked", false);
    //     }
    // });


    $('input[type=radio][name=look]').change(function() {
        if (this.value == 'online') {
            $('.fix_items').find('.checkitem')[0].classList.add('disable');
            $('.fixed_results').find('.result_item')[0].classList.add('disable');
            $('.fixed_line').find('.fixed_line_wrap')[0].classList.add('disable');
            $('.fix_items').find('.checkitem')[1].classList.add('disable');
            $('.fixed_results').find('.result_item')[1].classList.add('disable');
            $('.fixed_line').find('.fixed_line_wrap')[1].classList.add('disable');
            $('.fix_items').find('.checkitem')[2].classList.remove('disable');
            $('.fixed_results').find('.result_item')[2].classList.remove('disable');
            $('.fixed_line').find('.fixed_line_wrap')[2].classList.remove('disable');
        }
        else if (this.value == 'offline') {
            $('.fix_items').find('.checkitem')[0].classList.remove('disable');
            $('.fixed_results').find('.result_item')[0].classList.remove('disable');
            $('.fixed_line').find('.fixed_line_wrap')[0].classList.remove('disable');
            $('.fix_items').find('.checkitem')[1].classList.remove('disable');
            $('.fixed_results').find('.result_item')[1].classList.remove('disable');
            $('.fixed_line').find('.fixed_line_wrap')[1].classList.remove('disable');
            $('.fix_items').find('.checkitem')[2].classList.add('disable');
            $('.fixed_results').find('.result_item')[2].classList.add('disable');
            $('.fixed_line').find('.fixed_line_wrap')[2].classList.add('disable');
        }
    });
    
    var task_sliders = $("body").find(".task_signal");
    
    task_sliders.each(function() {
        $(this).slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            infinite: false,
            variableWidth: true,
            draggable: true,
            autoplay: false,
            arrows: false,
            dots: false,
            responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        });

    });


});

