var operator = 'operator.php';
var home = 'index.php';
var $host = location.hostname;
var main = $('.Main_content');
var subContainer = $("#subContainer");
var customerSearchResult = $(".table-search-result");
var all_games = $("#all_games");
var list_users = $("#list_users");
var assign_selector = $("#assign_selector");
var assignmentForm = $("#assignment");
var limit = 6; var start=0; var operator = 'operator.php';  var action = 'inactive';

  
function SelectRecomendation(id)
{
  $flag = $('#'+id).attr('value');
  $target = $('#'+id);

  if($flag !== undefined)
  {
    if($flag === 'true')
    {
        $target.attr('value','false');
        Post(operator,'recomended&uncheck='+id);
        checkUncheck(id);
    }
    else
    {
        $target.attr('value','true');
        Post(operator,'recomended&check='+id);
        checkUncheck(id);    
    }
  }
}
function checkUncheck(id)
{
    var checker = $("#"+id+'>.selected');
    if(checker.css('display') === 'none')
    {
        checker.slideDown('slow');
    }
    else
    {
        checker.slideUp('slow');
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
function EditProduct($id,$title,$store,$email,$password,$image)
{
    $target = $("#edit_products");
    $('.Products_edit').slideDown('slow');
    $target.children('input[name="update_product"]').attr('value',$id);
    $target.children('input[name="title"]').attr('value',$title);
    $target.children('input[name="email"]').attr('value',$email);
    $target.children('input[name="password"]').attr('value',$password);
    $target.children('input[name="img"]').attr('value',$image);
    
}
$("#edit_products").submit(function(e){
    e.preventDefault();
    $data = $(this).serialize();
    Post(operator, $data);
    setTimeout(function(){
        window.location.href = '?Products';
     },100);
});
function add_recomended_form()
{
    $item = $('.add_item');
    if($item.css('display') === 'none')
    {
        $item.slideDown('slow');
    }
    else
    {
        $item.slideUp('slow');
    }
}
function addRecomended($data)
{
    Post(operator, $data);
    $('.add_item').slideUp('slow');
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
function BlockCustomer($id,$hash)
{
    $resp = Post(operator, 'block='+$hash);
    $("#"+$id).css('background-color','red');
    $("#"+$id).css('color','white');
}
function SearchCustomer($data)
{
    $resp = Post(operator, $data);
    customerSearchResult.html($resp);
}
function list_for_game_append()
{
    if(assign_selector.css("display") === "none")
    {
        assign_selector.css("display","flex");
    }
    else
    {
        assign_selector.slideUp("slow");
    }
    
    $people = Post(operator, 'assignment=users');
    $games = Post(operator, 'assignment=games');
    all_games.html('<option>Wybierz Gre</option>');
    all_games.append($games);
    list_users.html('<option>Wybierz Użytkownika</option>');
    list_users.append($people);
}
function Assignment($data)
{
    $resp = Post(operator,$data);
    //window.location.href = 'index.php?Users';
}
list_users.on('change',function(e){
    $value = $(this).find("option:selected").val();
    $('#assignment > input[name="customer"').attr("value",$value);
});
all_games.on('change',function(e){
    $value = $(this).find("option:selected").val();
    $('#assignment > input[name="product"').attr("value",$value);
});
function GenerateHash($email,$title)
{
    var $resp = Post(operator, "generate&email="+$email);
    var gen = "http://"+$host +"/ALLEPOV1/?activation&long="+$resp+"&title="+$title; 
    var input = document.createElement('input');
    input.setAttribute('value', gen);
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
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
function add_user()
{
    var $form = $('.add_users');
    if($form.css('display') == 'none')
    {
        $form.slideDown('slow');
    }
    else
    {
        $form.slideUp('slow');
    }
}
function EditUser($userid,$username, $email, $password)
{
    $target = $("#edit_user");
    $('.edit_users').slideDown('slow');
    $target.children('input[name="editUser"]').attr('value',$userid);
    $target.children('input[name="username"]').attr('value',$username);
    $target.children('input[name="email"]').attr('value',$email);
    
}
function Show_user_games(user,$currentUser)
{
    
    $resp = Post(operator, "targetGames="+user);
    $("#user_games").html("<option>Wybierz</option>");
    $("#user_games").append($resp);
    $('#deletGameF > input[name=username]').attr("value",user);
    $("#selectedUser").html($currentUser);
    $("#deletGameF").slideDown('slow');
}
$("#user_games").on('change',function(e){
    $value = $(this).find("option:selected").attr("id");
    $('#deletGameF > input[name=deletGame]').attr("value",$value);
    //$('#assignment > input[name="product"').attr("value",$value);
});

function UserSearch(data)
{
    $resp = Post(operator,data);
    $username = $('#searchUserF > input[name="username"]').val();
    $cards = Post(operator,"userCardSearch&username="+$username);
    if($resp === "")
    {
        alert("Niema takiego użytkownika");
    }
    else
    {
        $(".foundUsers").html($resp);
        $("#all_users").html($cards);
    }
}
function CloseSearchResult(target)
{
    target = $(target);
    target.slideUp('slow');
}
function DeleteGame(data)
{
    console.log(data);
    $resp = Post(operator, data);
}
function user_delete(userid)
{
     Post(operator, 'profile&delete='+userid);
     window.location.href = 'index.php?Users';
}
function user_block($hash)
{
    Post(operator, 'block='+$hash)
    window.location.href = 'index.php?Users';
}
function getRandomColor($target) 
{
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    $("#"+$target).css({
        'background':color
    });
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
    $("#add_recomended").submit(function(e){
        e.preventDefault();
        addRecomended($(this).serialize());
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
    
    $("#customers-search").submit(function(e){
        e.preventDefault();
        SearchCustomer($(this).serialize());
    });
    assignmentForm.submit(function(e)
    {
        e.preventDefault();
        Assignment($(this).serialize());
    });
    $("#deletGameF").submit(function(e){
        e.preventDefault();
        DeleteGame($(this).serialize());
    });
    $("#searchUserF").submit(function(e){
        e.preventDefault();
        UserSearch($(this).serialize());
    });
    
});