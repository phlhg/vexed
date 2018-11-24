document.addEventListener("DOMContentLoaded",function(){
    suggestions = document.querySelector(".ph_search_suggestions > .ph_suggestions");
    input = document.querySelector("input[name='q']");

    input.onkeyup = function(e){ search(e) };
    input.onfocus = function(e){ search(e) };

    function search(e){
        suggestions.innerHTML = "";
        var string = input.value;
        if(string.replace(/\s*/i,"") == ""){ return; }
        PHAjax.GET("/ajax/f/search/?string="+encodeURI(encodeURI(string)),function(data){
            data.value.results.forEach(el => {
                suggestions.innerHTML += el;
            });
            App.site.setEvents(suggestions);
        })
    }
});