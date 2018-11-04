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

Client.prototype = Object.create(Client.prototype);
Client.prototype.constructor = Client;

/* SITE */

function Site(){
    this.loaded();
    this.menu = new Menu();
    this.setEvents();
}

Site.prototype.loaded = function(){
    setTimeout(function(){
        document.getElementsByTagName("body")[0].classList.remove("ph_loading");
        setTimeout(function(){
            document.getElementsByTagName("body")[0].classList.remove("ph_page_init");
        },1000);
    },400);
}

Site.prototype.load = function(href){
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

Site.prototype.setEvents = function(){
    var site = this;

    document.querySelectorAll("a[href]").forEach(function(el){
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
        new FSButton(el);
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

/* MENU */

function Menu(){
    this.body = document.getElementsByTagName("body")[0];
}

Menu.prototype.open = function(){
    this.body.classList.add("ph_menu_open");
}

Menu.prototype.close = function(){
    this.body.classList.remove("ph_menu_open");
}

Menu.prototype.toggle = function(){
    if(this.body.classList.contains("ph_menu_open")){
        this.close();
    } else {
        this.open();
    }
}

Menu.prototype = Object.create(Menu.prototype);
Menu.prototype.constructor = Menu;

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
    this.el_error.textContent = "";
    this.el_info.textContent = "";

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
        this.el_error.textContent = "Bitte fülle alle Felder korrekt aus";
        return false;
    }

    return true;
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