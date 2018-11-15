(function()
{
    var data = {'_token':token, tags:[1,4], exclude:[6]};
    
    a.post('/', data, 'json')
    .done( b =>
    {
        console.log(b);
    })
    .fail( b =>
    {
        console.log(b);
    });
})();