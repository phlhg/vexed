
$(document).ready(function(){
    setTimeout(function(){
        $("body").removeClass("ph_loading");
        setTimeout(function(){
            $("body").removeClass("ph_page_init");
        },1000);
    },400);

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
            }.bind(null,href),900);
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
        },900);
        return false;
    });

    $(".ph_fs_button[data-rel][data-id]").click(function(e){
        var btn = this;
        var rel = parseInt($(this).attr("data-rel"));
        var id = parseInt($(this).attr("data-id"));
        if([0,2].indexOf(rel) == -1 || id < 1){ return false; }
        if(rel == 0){
            $.get("/ajax/f/rel_follow/?user="+id,function(data){
                if(data.rspn == 0){
                    $(".ph_fs_button[data-id='"+id+"']").attr("data-rel",data.value.state);
                }
            });
        } else {
            $.get("/ajax/f/rel_unfollow/?user="+id,function(data){
                if(data.rspn == 0){
                    $(".ph_fs_button[data-id='"+id+"']").attr("data-rel",data.value.state);
                }
            });
        }
    });

    $(".ph_mham").click(function(){
        $("body").toggleClass("ph_menu_open");
    });

    $(".ph_menu_container .ph_menu_bg").click(function(){
        $("body").removeClass("ph_menu_open");
    });

    $(".ph_fi_submit, .ph_inline_submit").click(function(){
        // .valid() by https://stackoverflow.com/questions/6658937/how-to-check-if-a-form-is-valid-programmatically-using-jquery-validation-plugin
        var form = $(this).closest("form");
        var error = $(form).find(".ph_form_error");
        $(error).text("");
        $(form).find("input").removeClass("invalid");
        if($(form)[0].checkValidity()){
            $(form).submit();
        } else {
            $(form).find("input").each(function(){
                if(!$(this)[0].checkValidity()){
                    $(this).addClass("invalid");
                }
            });
            $(error).text("Bitte fülle das Formular korrekt aus");
        }
    })
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