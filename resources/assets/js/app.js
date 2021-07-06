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
    document.querySelector('.loading').style.display = "block"
    $elIframe.on('load', () => {
        $elModal.modal('show');
        setTimeout(() => {
            const $elH2 = $elIframe.contents().find('h2');
            $elModal.find('.modal-title').text($elH2.text());
            $elIframe[0].style.height = ($elIframe.contents().find('body').height() + 40) + "px";
            document.querySelector('.loading').style.display = "none";
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