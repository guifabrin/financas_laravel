const { bind } = require('lodash');

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
global.$ = global.jQuery = require('jquery');

require('./bootstrap');
require('./tagsinput');
$('.btn-iframe').on('click', (event) => {
    const elModal = $('#modal')[0] || $('#modal', parent.document)[0];
    const $elModal = $(elModal)
    const $elIframe = $elModal.find('iframe');
    $elIframe[0].style.height = "0px";
    const $elLoading = $('.loading')[0] || $('.loading', parent.document)[0]
    $elLoading.style.display = "block"
    $elIframe.on('load', () => {
        $elModal.modal('show');
        setTimeout(() => {
            const $elH2 = $elIframe.contents().find('h2');
            $elModal.find('.modal-title').text($elH2.text());
            $elIframe[0].style.height = ($elIframe.contents().find('body').height() + 40) + "px";
            $elLoading.style.display = "none";
        }, 300)
    });
    const strUrl = $(event.target.closest('.btn')).attr('href');
    var url = new URL(strUrl);
    url.searchParams.set('iframe', true)
    $elIframe.attr('src', url);
})

$('form').submit((event) => {
    if (event.target.dataset.message) {
        return confirm(event.target.dataset.message);
    }
    return true;
})
function bindNotification() {
    $('.notification').on('click', (event) => {
        const form = $(event.currentTarget.closest('form'))
        var url = form.attr('action');
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function (d) {
                $('#notifications').replaceWith($(d))
                bindNotification();
            }
        });
    })
}

function bindCaptcha() {
    $('.captcha').on('click', (event) => {
        value = prompt('Who is this captcha?')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        const form = $(event.currentTarget.closest('form'))
        var url = form.attr('action');
        $.ajax({
            url,
            type: 'POST',
            dataType: 'text',
            data: {
                value
            },
            success: function (d) {
                $('#captchas').replaceWith($(d))
                bindCaptcha();
            }
        });
    })
}
setInterval(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/captchas',
        type: 'GET',
        dataType: 'text',
        success: function (d) {
            $('#captchas').replaceWith($(d))
            bindCaptcha();
        }
    });
}, 15000)
setInterval(() => {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/notifications',
        type: 'GET',
        dataType: 'text',
        success: function (d) {
            $('#notifications').replaceWith($(d))
            bindNotification();
        }
    });
}, 15000)
bindCaptcha();
bindNotification();