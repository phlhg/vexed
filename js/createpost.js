document.addEventListener("DOMContentLoaded",function(){

    var img_wrapper = document.getElementsByClassName("ph_post_media")[0];
    var prev_img = document.getElementById("post_image_prev");
    var action_open = document.getElementById("post_img_open");
    var action_delete = document.getElementById("post_img_delete");
    var file_input = document.getElementById("post_image");
    var textarea = document.getElementsByClassName("desc_text")[0];
    var char_counter = document.getElementById("char_counter");

    action_open.onclick = function(e){
        file_input.click();
    }

    action_delete.onclick = function(e){
        img_wrapper.classList.add("empty");
        prev_img.setAttribute("src","");
        file_input.value = "";
    }

    textarea.onkeyup = function(e){
        this.value = this.value.slice(0,255);
        char_counter.innerText = (this.value.length)+"/255";
    }

    textarea.onkeypress = function(e){
        this.value = this.value.slice(0,255);
        char_counter.innerText = (this.value.length)+"/255";
    }

    file_input.onchange = function(e){
        type = e.target.files[0].type.split("/");
        if(type[0] != "image"){ return false; }
        if(type[1] != "jpg" && type[1] != "jpeg" && type[1] != "png" && type[1] != "gif"){ return false; }
        console.log(e.target.files[0]);
        var url = URL.createObjectURL(e.target.files[0]);
        prev_img.setAttribute("src",url);
        img_wrapper.classList.remove("empty");
    }

});