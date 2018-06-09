(function ($) {

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


});