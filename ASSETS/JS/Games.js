var limit = 12; var start=0; var action = 'inactive';
var target = 'operator.php';

function Load_Games()
{
    $.ajax({
            type:'POST', url:target, data:{'games':'','limit':limit,'start':start}, cahce:false,success:function(item)
            {
            console.log(item);
            if(item == '')
            {
                action = 'active';
               
            }
            else
            {
                $("#all_games").append(item);
                action = 'inactive';
            }
        }
    });
}

if(action == 'inactive')
{
    action = 'active';
    Load_Games(limit,start);
    
}
$('#all_games').scroll(function(){
    if($(window).scrollTop() + $(window).height() > $("#all_games").height() && action == 'inactive')
    {
     action = 'active';
     start = start + limit;
     setTimeout(function(){
        Load_Games(limit, start);
     }, 1000);
    }
   });;