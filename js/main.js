/* © Philippe Hugo <info@phlhg.ch> */

document.addEventListener("DOMContentLoaded",function(){ App.init(); });

/* APP */

var App = {}

App.init = function(){
    App.client = new Client();
    App.site = new Site();
}

/* CONFIG */

var Config = {}

Config.get = function(id){
    return (s_config[id] ? s_config[id] : false);
}

/* CLIENT */
function Client(){
    this.id = -1;
}

Client.prototype.load = function(){
    this.id = Config.get("client_id");
}

Client.prototype.isWebApp = function(){
    if(window.navigator.standalone === true || window.matchMedia('(display-mode: standalone)').matches === true){
        return true;
    } else {
        return false;
    }
}

Client.prototype = Object.create(Client.prototype);
Client.prototype.constructor = Client;

/* SITE */

function Site(){
    this.init();
    this.setEvents();
}

Site.prototype.init = function(){
    setTimeout(function(){
        document.getElementsByTagName("body")[0].classList.remove("ph_loading");
        setTimeout(function(){
            document.getElementsByTagName("body")[0].classList.remove("ph_page_init");
        },1000);
    },400);

    setTimeout(function(){ App.site.showWebAppInfo(); },1000*15);
}

Site.prototype.load = function(href){
    if(href == location.pathname){ return true; }
    if(!href.startsWith("/") || href.startsWith("//")){ return false; }
    this.obscure(function(){
        window.location.href = href;
    }.bind(null,href));
    return true;
}

Site.prototype.obscure = function(callback){
    document.getElementsByTagName("body")[0].classList.add("ph_loading");
    setTimeout(function(callback){
        callback();
        setTimeout(function(){    
            document.getElementsByTagName("body")[0].classList.remove("ph_loading");
        },1000);
    }.bind(null,callback),900);
}

Site.prototype.showWebAppInfo = function(){
    var lastinfo = (localStorage.getItem("appinfo") ? parseInt(localStorage.getItem("appinfo")) : 0);
    var isSafari = (/(iPad|iPhone|iPod)/gi).test(navigator.userAgent) && !(navigator.userAgent.indexOf('FxiOS') > -1) && !(navigator.userAgent.indexOf('CriOS') > -1);
    var isAndroid = (navigator.userAgent.indexOf('Mozilla/5.0') > -1 && navigator.userAgent.indexOf('Android ') > -1 && navigator.userAgent.indexOf('AppleWebKit') > -1);

    if(App.client.isWebApp() || (!isSafari && !isAndroid) || lastinfo+1000*60*60*24*7 > Date.now()){ return; }
    if(Config.get("logged_in") == false){ return; }

    text = 'Klicke auf <img src="/img/grafiken/webapp/ios_more.png"> und wähle dann <img src="/img/grafiken/webapp/ios_add.png"> aus<br/>Und schon hast du die App immer mit dabei!';
    if(isAndroid){
        text = 'Klicke auf <img src="/img/grafiken/webapp/and_more.png"> und wähle dann <i>"Zum Startbildschirm hinzfügen"</i> aus<br/>Und schon hast du die App immer mit dabei!';
    }

    el = $('<div class="ph_webapp_info_wrapper hidden"><div class="ph_webapp_info"><span class="close"><i class="material-icons">close</i></span><span class="title">Die VEXED-App</span>'+text+'</div></div>')
    
    $(el).find(".close").click(function(el){
        $(el).addClass("hidden");
        setTimeout(function(){ $(el).remove(); },400);
    }.bind(null,el));

    setTimeout(function(el){
        $(el).addClass("hidden");
        setTimeout(function(){ $(el).remove(); },400);
    }.bind(null,el),1000*15);

    $("body").prepend(el);
    setTimeout(function(el){ 
        $(el).removeClass("hidden");
        localStorage.setItem("appinfo",Date.now());
    }.bind(null,el),300);
}

Site.prototype.setEvents = function(){
    var site = this;

    images = document.querySelectorAll("img[data-lazyload]");

    window.onscroll = function(images){
        var viewTop = $(window).scrollTop(); 
        var viewHeight = $(window).height();
        var viewBottom = viewTop + viewHeight;

        var offset = 0;

        images.forEach(function(el){
            var src = el.getAttribute("data-lazyload")
            if(!el.hasAttribute("data-lazyloadinit")){
                var elTop = $(el).offset().top;
                var elBottom = elTop + $(el).height();
        
                if(!(elTop > viewBottom-offset || elBottom < viewTop+offset)){
                    document.querySelectorAll("img[data-lazyload='"+src+"']").forEach(function(el){
                        el.setAttribute("data-lazyloadinit","true")
                        el.onload = function(e){ setTimeout(function(){ this.setAttribute("data-lazyload",""); }.bind(this),200); }
                        el.setAttribute("src",src);
                    });
                }
            }
        });
    
    }.bind(null,images);
    
    window.onscroll();

    document.querySelectorAll("a[href]").forEach(function(el){
        if(el.getAttribute("href") == location.pathname){ el.classList.add("active"); }
        el.onclick = function(e){
            if(site.load(el.getAttribute("href")))
                e.preventDefault();
        }
    });

    document.querySelectorAll(".ph_mham").forEach(function(el){
        el.onclick = function(){ App.site.menu.toggle(); }
    });

    document.querySelectorAll(".ph_menu_container .ph_menu_bg").forEach(function(el){
        el.onclick = function(){ App.site.menu.close(); }
    });

    document.querySelectorAll(".ph_fs_button[data-rel][data-id]").forEach(function(el){
        if(!el.classList.contains("fake")){ new FSButton(el); }
    });

    document.querySelectorAll(".ph_pwc").forEach(function(el){
        new PWCreator(el);
    });

    document.querySelectorAll("form").forEach(function(el){
        new Form(el);
    });
}  

Site.prototype = Object.create(Site.prototype);
Site.prototype.constructor = Site;

/* FORM */

function Form(element){
    this.form = element;
    this.el_error = this.form.getElementsByClassName("ph_form_error")[0];
    this.el_info = this.form.getElementsByClassName("ph_form_info")[0];
    this.valid = false;
    this.setEvents();
    Form.list.push(this);
}

Form.list = [];

Form.prototype.setEvents = function(){
    var form = this;

    this.form.onsubmit = function(e){
        e.preventDefault();
        form.send();
    }

    this.form.querySelectorAll(".ph_fi_submit, .ph_inline_submit").forEach(function(el){
        el.onclick = function(e){
            form.send();
            return false;
        }
    });
}

Form.prototype.send = function(){
    if(this.validate()){
        App.site.obscure(function(){
            this.form.submit();
        }.bind(this));
        this.reset();
    }
}

Form.prototype.reset = function(){
    this.valid = false;
}

Form.prototype.validate = function(){
    var form = this;
    this.error("");

    error = "";
    this.valid = true;
    this.form.querySelectorAll("input").forEach(function(el){
        el.classList.remove("invalid");
        if(!el.checkValidity()){
            form.valid = false;
            el.classList.add("invalid");
        }
    });

    if(!this.valid){
        this.error("Bitte fülle alle Felder korrekt aus");
        return false;
    }

    return true;
}

Form.prototype.error = function(msg){
    if(this.el_error){
        this.el_error.textContent = msg;
    }
}

Form.prototype = Object.create(Form.prototype);
Form.prototype.constructor = Form;

/* FS-BUTTON */

FState = {
    STRANGER: 0,
    REQUESTED: 1,
    FOLLOWING: 2,
    ME: 3,
}

function FSButton(element){
    this.element = element;
    this.state = parseInt(element.getAttribute("data-rel") || 0);
    this.id = parseInt(element.getAttribute("data-id") || -1);
    this.setEvents();
    FSButton.list.push(this);
}

FSButton.list = [];

FSButton.set = function(id,state){
    FSButton.find(id).forEach(function(el){
        el.set(state);
    });
}

FSButton.find = function(id){
    return FSButton.list.filter(function(el){
        return (el.id == id);
    });
}

FSButton.prototype.setEvents = function(){

    this.element.onclick = function(e){
        e.preventDefault();
        this.clicked();
    }.bind(this);
}

FSButton.prototype.set = function(state){
    this.state = state;
    this.element.setAttribute("data-rel",this.state);
}

FSButton.prototype.clicked = function(){
    if([FState.STRANGER,FState.FOLLOWING].indexOf(parseInt(this.state)) == -1 || this.id < 1){ return false; }
    if(this.state == FState.STRANGER){ return Relation.follow(this.id);  }
    if(this.state == FState.FOLLOWING){ return Relation.unfollow(this.id); }
    return false;
}

FSButton.prototype = Object.create(FSButton.prototype);
FSButton.prototype.constructor = FSButton;

/* PASSWORD CREATOR */

function PWCreator(element){
    this.element = element;
    this.element.innerHTML += '<span class="indicator"><span class="i"></span></span>';
    this.indicator = this.element.getElementsByClassName("i")[0];
    this.input = this.element.getElementsByTagName("input")[0];
    this.check = (this.input.hasAttribute("data-check") ? document.getElementById(this.input.getAttribute("data-check")) : false);
    this.error = this.element.closest("form").getElementsByClassName("ph_form_error")[0];
    this.setEvents();
    PWCreator.list.push(this);
}

PWCreator.list = [];

PWCreator.prototype.setEvents = function (){
    var c = this;
    this.input.onkeyup = function(c){ c.typed(); }.bind(null,c);
}

PWCreator.progress = function(pw){
    var prog = 0;
    var regex = [
        /\d/gi,//digits
        /[a-z]/g, //chars
        /[^a-z0-9]/gi, //specialchars
        /[A-Z]/g, //big chars
        /.{5,}/g //longer than 5
    ];
    regex.forEach(function(el){
        if(el.test(pw)){ prog++; }
    });
    return prog/regex.length;
}

PWCreator.prototype.setIndicator = function(val){
    this.indicator.style.width = Math.round(val*100)+"%";
    this.indicator.style.backgroundColor = "rgb("+Math.min(255,Math.max(0,380-val*300))+","+Math.min(255,val*300)+",0)";
}

PWCreator.prototype.typed = function(){
    this.setIndicator(PWCreator.progress(this.input.value));
    if(!this.check || !this.error){ return; }
    if(this.check.value == "" || this.input.value == ""){ this.error.textContent = ""; return; }
    if(this.check.value == this.input.value){ this.error.textContent = ""; return; }
    this.error.textContent = "Passwörter entsprechen sich nicht";
}

PWCreator.prototype = Object.create(PWCreator.prototype);
PWCreator.prototype.constructor = PWCreator;

/* RELATION */

var Relation = {}

Relation.follow = function(id,callback){
    var callback = callback || function(){};
    $.get("/ajax/f/rel_follow/?user="+id,function(data){
        if(data.rspn == 0){
            FSButton.set(id,data.value.state);
            callback(data);
        }
    });
    return true;
}

Relation.unfollow = function(id,callback){
    var callback = callback || function(){};
    $.get("/ajax/f/rel_unfollow/?user="+id,function(data){
        if(data.rspn == 0){
            FSButton.set(id,data.value.state);
            callback(data);
        }
    });
    return true;
}

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