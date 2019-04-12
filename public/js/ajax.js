var ajax = {

    init: function() {
        $('.block-comment').on('click', ajax.blockAndUnblock);
        $('.block-user').on('click', ajax.userBlockAndUnblock);
        $('.block-blog-review').on('click', ajax.reviewBlockAndUnblock);
    },

    blockAndUnblock: function(event) {
        event.preventDefault();
        ajax.targetBlocked = event.currentTarget;

        targetBlockedXHR = $.ajax({
            'method'     : 'GET',
            'url'        : ajax.targetBlocked.href,
            'dataType'   : 'json',
            'contentType': 'application/json'
        })

        targetBlockedXHR.done(ajax.displayPadlock);
    },

    userBlockAndUnblock: function(event) {
        event.preventDefault();
        ajax.targetBlocked = event.currentTarget;

        targetBlockedXHR = $.ajax({
            'method'     : 'GET',
            'url'        : ajax.targetBlocked.href,
            'dataType'   : 'json',
            'contentType': 'application/json'
        })

        targetBlockedXHR.done(ajax.displayPadlock);
    },

    reviewBlockAndUnblock: function(event) {
        console.log('test');
        event.preventDefault();
        ajax.targetBlocked = event.currentTarget;

        targetBlockedXHR = $.ajax({
            'method'     : 'GET',
            'url'        : ajax.targetBlocked.href,
            'dataType'   : 'json',
            'contentType': 'application/json'
        })

        targetBlockedXHR.done(ajax.displayPadlock);
    },

    displayPadlock: function(data) {
        
        console.log(data);
        if($(ajax.targetBlocked).hasClass('btn-outline-success')) {

            $(ajax.targetBlocked).toggleClass('d-none');
            $(ajax.targetBlocked).next('.btn-outline-danger').toggleClass('d-none');

        } else {

            $(ajax.targetBlocked).toggleClass('d-none');
            $(ajax.targetBlocked).siblings('.btn-outline-success').toggleClass('d-none');
        }

        $(ajax.targetBlocked).parents('.activated').toggleClass('bg-dark text-light');

    }
}

$(ajax.init)