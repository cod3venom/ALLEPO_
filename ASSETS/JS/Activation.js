$(document).ready(function(){
    //$('.whiteContainer').slideDown('slow');
    $('.whiteContainer').toggleClass('centered');
    $("#activationF").submit(function(e){
        e.preventDefault();
        $("#actbtn>img").attr("src","ASSETS//IMG//activationLoad.gif");

        setTimeout(() => {
            Activate($(this).serialize());
        }, 1000);
    });
});
var $op = 'index.php';

function Activate($DATA)
{
    var $resp = Post($op, $DATA);
    var $cont = $('.activationContainer');
    var $actf = $('#activationF');
    var $ctf = $('.ContactUs');
    var $notf = $('.notFound');
    var $activ = $('.Activated');
    var $recom = $('.recomended_activation');
    var $instructor = $('.instruction');
    var $instruct = $('.instructor');
    
    $resp = $resp.trim();
    if($resp === 'Contact' || $resp === 'Blocked')
    {
        $actf.css('display','none');
        $ctf.slideDown('slow');
        $instruct.css('display','none');
        $instructor.css({
            'border':'none'
        });
    }
    if($resp === 'NOTFOUND')
    {
        $actf.slideUp('slow');
        $notf.slideDown('slow');
        $instruct.css('display','none');
        $instructor.css({
            'border':'none'
        });
    }
    if($resp === 'Activated')
    {
        $actf.css('display','none');
        $instruct.css('display','none');
        $activ.slideDown('slow');
        $six = Post('operator.php', 'recomended&activation=');
        $recom.html($six);
        $instructor.css({
            'border':'none'
        });
        
    }
    
}
function Post(addr,param)
{
    var content =  $.ajax({
        url:addr, type:"POST", data:param, cache:false, async:false,success:function(result)
        {
            console.log(result);
        },
    }).responseText;
    return content;
}