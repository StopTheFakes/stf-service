var gliss = gliss || {};

gliss.action = (function ($) {

    this.actionHold = function(elm) {

        $(this).toggleClass('current');
        var task = $(this).parents('.task_item');
        var task_state = task.find('.task_state');
        if ( task.hasClass('suspended') ) {
            task.removeClass('suspended').addClass('current');
            task_state.text('Current');
        } else if( task.hasClass('current') ) {
            task.removeClass('current').addClass('suspended');
            task_state.text('On hold');
        }
        return false;

    };

    return this;

}).call(gliss.action || {},jQuery);