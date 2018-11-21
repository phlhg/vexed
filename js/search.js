document.addEventListener("DOMContentLoaded",function(){
    suggestions = document.querySelector(".ph_search_suggestions > .ph_suggestions");
    input = document.querySelector("input[name='q']");

    input.onkeyup = function(e){ search(e) };
    input.onfocus = function(e){ search(e) };

    function search(e){
        suggestions.innerHTML = "";
        var string = input.value;
        if(string.replace(/\s*/i,"") != ""){
            $.get("/ajax/f/search/?string="+encodeURI(encodeURI(string)),function(data){
                if(data.rspn == 0){
                    data.value.results.forEach(el => {
                        suggestions.innerHTML += el;
                    });
                    suggestions.querySelectorAll("a[href]").forEach(function(el){
                        if(el.getAttribute("href") == location.pathname){ el.classList.add("active"); }
                        el.onclick = function(e){
                            e.preventDefault();
                            if(App.site.load(el.getAttribute("href")))
                                e.preventDefault();
                        }
                    });
                }
            });
        }
    }

    /*input.onblur = function(){
        setTimeout(function(){
            suggestions.innerHTML = "";
        },1000);
    }*/
});