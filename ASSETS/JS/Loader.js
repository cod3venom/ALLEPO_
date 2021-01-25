var limit = 14; var start=0; var action = 'inactive';
var target = 'operator.php';
function Load_PRODUCTS()
{
    $.ajax({
            type:'POST', url:target, data:{'products':'','limit':limit,'start':start}, cahce:false,success:function(item)
            {
            console.log(item);
            if(item == '')
            {
                action = 'active';
                $("#Loader").slideUp('slow');
            }
            else
            {
                $("#Loader").slideDown('slow');
               $("#products_table").append(item);
                action = 'inactive';
            }
        }
    });
}

if(action == 'inactive')
{
    action = 'active';
    Load_CUSTOMERS(limit,start);
    Load_PRODUCTS(limit,start);
}
$('#products_table').scroll(function(){
    if($(window).scrollTop() + $(window).height() > $("#products_table").height() && action == 'inactive')
    {
     action = 'active';
     start = start + limit;
     setTimeout(function(){
        Load_PRODUCTS(limit, start);
     }, 1000);
    }
   });

   function Load_CUSTOMERS()
   {
       $.ajax({
           type:'POST', url:target, data:{'customers':'','limit':limit,'start':start}, cahce:false,success:function(item)
           {
               console.log(item);
               if(item == '')
               {
                   action = 'active';
                   $("#Loader").css('display','none');
               }
               else
               {
                    $("#Loader").slideDown('slow');
                    $("#customers_table").append(item);
                    var maxWidth = $(window).width();
                    
                    action = 'inactive';
               }
           }
       }); 
   }
   

   $('#customers_table').scroll(function(){
    if($(window).scrollTop() + $(window).height() > $("#customers_table").height() && action == 'inactive')
    {
     action = 'active';
     start = start + limit;
     setTimeout(function(){
        Load_CUSTOMERS(limit, start);
     }, 1000);
    }
   });


 