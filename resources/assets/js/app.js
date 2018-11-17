window.a = window.jQuery = require('jquery');
window.token = document.head.querySelector('meta[name="csrf-token"]').content;

(function()
{
    var bb = {
        tags:[],
        exclude:[],
        _token:token
    };
    
    a('body').on('click', '.clickable', function()
    {
        var b = a(this),
        c = {
            a: b.attr('c-act'),
            b: b.attr('opt'),
            c: b.attr('id')
        };
        switch(c.a)
        {
            case 'push-tag':
                var d = bb[c.b],
                e = {
                    a: b.html(),
                    b: function(){return (c.b == 'tags') ? 'primary' : 'danger'},
                    c: b.attr('pos'),
                    d: function(){return (this.c == 'pad') ? 'pool' : 'pad'},
                    e: function(){return (this.c == 'pad') ? 'tag-pool' : 'p-'+((c.b=='tags') ? 'tag' : 'exc')}
                },
                f = "<div id='"+c.c+"' c-act='"+c.a+"' opt='"+c.b+"' pos='"+e.d()+"' class='btn btn-"+e.b()+" mr-1 mt-1 clickable'>"+e.a+"</div>",
                g = '.tag-pad#'+c.b,
                h = a('h1#'+c.b);
                if(e.c == 'pad')
                {
                    if(d.includes(c.c)) return false;
                    d.push(c.c);
                }else
                {
                    d.splice(d.indexOf(c.c),1);
                }
                b.remove();
                a('.'+e.e()).append(f);
                !a(g).find('.btn').length ? h.addClass('hid') : h.removeClass('hid');
                break;
            case 'send-tags':
                a.post('/', bb, 'json')
                .done( b =>
                {
                    var c = a('#download'),
                    d = a('#errors'),
                    e = {
                        no_data: 'Нет данных',
                        not_exist: 'Файл не существует',
                    };
                    d.removeClass('hid');
                    c.attr('href', b.link ? '/download/'+b.link : '#');
                    b.link ? c.removeClass('hid') : c.addClass('hid');
                    d.find('.panel-body').html((b.stat == 'err')?e[b.message]:b.message.join('; '));
                })
                .fail( b =>
                {
                    alert('Что-то пошло не так...');
                });
                break;
            default:
                break;
        }
    });
})();