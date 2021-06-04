/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
global.$ = global.jQuery = require('jquery');

require('./bootstrap');
require('./tagsinput');

$('.btn-iframe').on('click', (event) => {
    const $elModal = $('#modal');
    const $elIframe = $elModal.find('iframe');
    $elIframe.on('load', () => {
        const $elH2 = $elIframe.contents().find('h2');
        $elModal.find('.modal-title').text($elH2.text());
        $elIframe[0].style.height = $elIframe.contents().find('body').height() + "px";
    });
    $elModal.on("hidden.bs.modal", function () {
        document.location.reload(true);
    })
    $elIframe.attr('src', $(event.target).attr('href'));
    $elModal.modal('show')
})
