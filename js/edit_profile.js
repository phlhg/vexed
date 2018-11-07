document.addEventListener("DOMContentLoaded",function(){
    document.getElementById("pb_sel").onclick = function(){
        document.getElementById("p_pb").click();
    }

    document.getElementById("pbg_sel").onclick = function(){
        document.getElementById("p_pbg").click();
    }

    document.getElementById("p_pbg").onchange = function(e){
        var url = URL.createObjectURL(e.target.files[0]);
        document.querySelector(".ph_profile_bg > div").style.backgroundImage = 'url(' + url + ')';
    }

    document.getElementById("p_pb").onchange = function(e){
        var url = URL.createObjectURL(e.target.files[0]);
        document.querySelector(".ph_profileh_content .ph_pb_wrapper .pb").style.backgroundImage = 'url(' + url + ')';
    }

    document.querySelector(".ph_fs_button").onclick = function(e){
        document.querySelector("form").submit();
    }
    
});