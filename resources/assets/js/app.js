window.a = window.jQuery = require('jquery');
var token = document.head.querySelector('meta[name="csrf-token"]');

(function()
{
    console.log(a('body').length, 'ok');
})();