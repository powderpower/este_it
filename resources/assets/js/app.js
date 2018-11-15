window.a = window.jQuery = require('jquery');
var token = document.head.querySelector('meta[name="csrf-token"]').content;

(function()
{
    /*
    a.post('/', {'_token':token, tags:[1,4], exclude:[6]}, 'json')
    .done( b =>
    {
        console.log(b);
    })
    .fail( b =>
    {
        console.log(b);
    });
    */
})();