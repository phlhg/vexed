
$(document).ready(function(){
    setTimeout(function(){
        $("body").removeClass("ph_loading");
    },200);

    $("a[href]").on("click touchstart",function(e){
        var href = $(this).attr("href");
        if(href.startsWith("/") && !href.startsWith("//")){
            e.preventDefault();
            $("body").addClass("ph_loading");
            setTimeout(function(href){
                window.location.href = href;
                setTimeout(function(){    
                    $("body").removeClass("ph_loading");
                },1000);
            }.bind(null,href),600);
        }
    });

    $("form").submit(function(e){
        var form = this;
        $("body").addClass("ph_loading");
        setTimeout(function(href){
            form.submit();
            setTimeout(function(){    
                $("body").removeClass("ph_loading");
            },1000);
        },600);
        return false;
    });
});

function structureCode(e,el,length,amount){
    amount = parseInt(amount);
    length = parseInt(length);
    var max = ((length+1)*amount)-1;
    el.setAttribute("maxlength",max);
    el.value = el.value.toUpperCase();
    el.value = el.value.replace(/ /g,"");
    el.value = el.value.replace(/[^A-Z\d]/g,"");
    var value = el.value;
    //by https://stackoverflow.com/questions/28779631/how-to-insert-space-after-four-characters-in-html-input
    var regex = new RegExp('[\\dA-Z]{1,'+length+'}','g')
    value = value.match(regex).join(" ");
    if(value){ el.value = value; }
}