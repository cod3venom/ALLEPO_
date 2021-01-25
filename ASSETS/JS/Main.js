var operator = 'operator.php';
var home = 'index.php';
var main = $('.Main_content');
var subContainer = $("#subContainer");
var limit = 6; var start=0; var operator = 'operator.php';  var action = 'inactive';
 
 
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
function GET(addr,param)
{
    var content =  $.ajax({
        url:addr, type:"GET",  cache:false, async:false,success:function(result)
        {
            console.log(result);
        },
    }).responseText;
    return content;
}
function TableEmpty()
{
    var $tbname = $('#tableList').children('option:selected').val();
    var $resp = Post(operator, 'truncate='+$tbname).trim();
    if($resp == 'cleaned')
    {
        $("#TableEmpty").css('background','transparent');
        $("#TableEmpty").html('<img src="ASSETS//IMG//check.png">');
    }
    else if($resp === 'error' || $resp === 'disallowed')
    {
        alert($resp);
    }
}
function Choose_Store(handler)
{
    Post(operator, "setStore="+handler);
    window.location.href = '?Customers';
}
function addCustomers_form()
{
    var $form = $('.Customers_add');
    if($form.css('display') == 'none')
    {
        $form.slideDown('slow');
    }
    else
    {
        $form.slideUp('slow');
    }
}
function addCustomer()
{
    document.getElementById("add_customer").addEventListener("submit", function(e){
        e.preventDefault()
        var $data = $("#add_customer").serialize();
        var $resp = Post(operator, $data)
        $resp = $resp.trim();
         if($resp == '1')
         {
            $("#add_customer > button[type='submit']").html('<img src="ASSETS//IMG//ok.png">');
         }
         setTimeout(function(){
            window.location.href = 'index.php?Customers';
         },1000);
      });
}
function addProducts_form()
{
    var $form = $('.Products_add');
    if($form.css('display') == 'none')
    {
        $form.slideDown('slow');
    }
    else
    {
        $form.slideUp('slow');
    }
}
function AddProduct($data)
{
    var $resp = Post(operator, $data);
    $resp = $resp.trim();
    if($resp == '1')
    {
        $("#add_products > button[type='submit']").html('<img src="ASSETS//IMG//ok.png">');
    }
    setTimeout(function(){
        window.location.href = '?Products';
     },100);
}
function ShowUserOptions()
{
    var $resp = Post(operator, 'TopuserOptions=');
    var $options = $('.top_user_options');
    if($options.css("display") == "none")
    {
        $('.top_user_options').slideDown('slow');
    }
    else
    {
        $('.top_user_options').slideUp('slow');
    }
    $('.top_user_options').html($resp);
}
function DeleteProduct($TARGET)
{
    var $resp = Post(operator,'product&delete='+$TARGET.id);
    $("#table_item_"+$TARGET.id).slideUp('slow');
}
function DeleteCustomer($TARGET)
{
    var $resp = Post(operator,'product&delete='+$TARGET.id);
    $("#table_item_"+$TARGET.id).slideUp('slow');
}
function DeleteStore($TARGET)
{
    var $resp = Post(operator, 'store&delete='+$TARGET);
    $("#StoreBox_"+$TARGET).slideUp('slow');
}
function Export($data)
{
    var $resp = Post(operator, $data);
    $resp = $resp.trim();
    Download($resp);
}
function Download($data)
{
    if($data !='')
    {
        var $fname = new Date().getTime() + '_export.txt';
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent($data));
        element.setAttribute('id','downloadClicker');
        element.setAttribute('download', $fname);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
    else
    {
        $(".export_info > span").html('BAZA JEST PUSTA');
    }
}
$(document).ready(function()
{
    $("#addstorebtn").click(function(){
        $fr = $('.add_store');
        if($fr.css('display') === 'none')
        {
            $fr.slideDown('slow');
        }
        else
        {
            $fr.slideUp('slow');
        }
    });
    $("#add_products").submit(function(e){
        e.preventDefault();
        var $data = $(this).serialize();
        AddProduct($data);
    });

    $("#exportF>select").change(function(){
        $param = $(this).children("option:selected").val();
        $("#exportF>input[name='param']").attr('value',$param);
    });
    $("#exportF").submit(function(e){
        e.preventDefault();
        var $data = $(this).serialize();
        Export($data);
    }); 
    $(".table_export_btn>button").click(function()
    {
        $exp = $('.export_settings');
        if($exp.css("display") === "block")
        {
            $exp.slideUp('slow');
        }
        else
        {
            $exp.slideDown('slow');
        }
    }); 
    $('.leftToggle').click(function(){
        $('.Sidebar').toggleClass('active');
    });  
    
});