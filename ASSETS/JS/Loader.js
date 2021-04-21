var limit = 50; var start=0; var action = 'inactive';
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
               
            }
            else
            {
                $("#products_table").append(item);
                action = 'inactive';
            }
        }
    });
}

if(action == 'inactive')
{
    action = 'active';
    Load_PRODUCTS(limit,start);
    Load_CUSTOMERS(limit,start);
    Load_RECOMENDED(limit,start);
    Load_USERS(limit,start);
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
               }
               else
               {
                    $("#customers_table").append(item);
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


   function Load_RECOMENDED()
   {
       $.ajax({
           type:'POST', url:target, data:{'recomended':'','limit':limit,'start':start}, cahce:false,success:function(item)
           {
               console.log(item);
               if(item == '')
               {
                   action = 'active';
               }
               else
               {
                    $("#recomended_body").append(item);
                    action = 'inactive';
               }
           }
       }); 
   }
   

   $("#recomended_body").scroll(function(){
    if($(window).scrollTop() + $(window).height() > $("#recomended_body").height() && action == 'inactive')
    {
     action = 'active';
     start = start + limit;
     setTimeout(function(){
         Load_RECOMENDED(limit, start);
     }, 1000);
    }
   });

   function Load_USERS()
   {
       $.ajax({
           type:'POST', url:target, data:{'Users':'','limit':limit,'start':start}, cahce:false,success:function(item)
           {
               console.log(item);
               if(item == '')
               {
                   action = 'active';
               }
               else
               {
                    $("#all_users").append(item);
                    action = 'inactive';
               }
           }
       }); 
   }
   

   $("#all_users").scroll(function(){
    if($(window).scrollTop() + $(window).height() > $("#all_users").height() && action == 'inactive')
    {
     action = 'active';
     start = start + limit;
     setTimeout(function(){
        Load_USERS(limit, start);
     }, 1000);
    }
   });