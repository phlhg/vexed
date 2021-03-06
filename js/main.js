/* © Philippe Hugo <info@phlhg.ch> */

document.addEventListener("DOMContentLoaded",function(){ App.init(); });

/* APP */

var App = {}

App.init = function(){
    App.client = new Client();
    App.site = new Site();
    App.ready = new Event("AppReady");
    document.dispatchEvent(App.ready);
}

App.onready = function(){
    document.dispatchEvent(new Event("AppReady"));
}

/* CONFIG */

var Config = {}

Config.get = function(id){
    return (s_config[id] ? s_config[id] : false);
}

/* CLIENT */
function Client(){
    this.id = -1;
    this.load();
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

Client.prototype.logout = function(){
    var msg = new PHNotification("Nicht angemeldet","Bitte melde dich erneut an",function(){
        App.site.load("/logout/?re="+window.location.pathname);
    });
    msg.time = 5;
    msg.show();
}

Client.prototype = Object.create(Client.prototype);
Client.prototype.constructor = Client;

/* SITE */

function Site(){
    this.init();
    this.setGlobalEvents();
}

Site.prototype.init = function(){
    setTimeout(function(){
        document.getElementsByTagName("body")[0].classList.remove("ph_loading");
        setTimeout(function(){
            document.getElementsByTagName("body")[0].classList.remove("ph_page_init");
        },1000);
    },400);

    setTimeout(function(){ App.site.showAppInfo(); },1000*10);
}

Site.prototype.load = function(href){
    if(href == location.pathname){ $("html, body").animate({scrollTop: 0},500); return true; }
    if(!href.startsWith("/") || href.startsWith("//")){ return false; }
    this.obscure(function(){
        window.location.href = href;
    }.bind(null,href));
    return true;
}

Site.prototype.back = function(){
    this.obscure(function(){  window.history.back(); });
}

Site.prototype.obscure = function(callback){
    document.getElementsByTagName("body")[0].classList.add("ph_loading");
    setTimeout(function(callback){
        callback();
        setTimeout(function(){    
            document.getElementsByTagName("body")[0].classList.remove("ph_loading");
        },3000);
    }.bind(null,callback),900);
}

Site.prototype.showAppInfo = function(){
    var lastinfo = (localStorage.getItem("appinfo") ? parseInt(localStorage.getItem("appinfo")) : 0);
    var isSafari = (/(iPad|iPhone|iPod)/gi).test(navigator.userAgent) && !(navigator.userAgent.indexOf('FxiOS') > -1) && !(navigator.userAgent.indexOf('CriOS') > -1);
    var isAndroid = (navigator.userAgent.indexOf('Mozilla/5.0') > -1 && navigator.userAgent.indexOf('Android ') > -1 && navigator.userAgent.indexOf('AppleWebKit') > -1);

    if(App.client.isWebApp() || (!isSafari && !isAndroid) || lastinfo+1000*60*60*24*7 > Date.now()){ return; }
    if(Config.get("logged_in") == false){ return; }

    text = 'Klicke auf <img src="/img/grafiken/webapp/ios_more.png"> und wähle dann <img src="/img/grafiken/webapp/ios_add.png"> aus.<br/>Und schon hast du die App immer mit dabei!';
    if(isAndroid){ text = 'Klicke auf <img src="/img/grafiken/webapp/and_more.png"> und wähle dann <i>"Zum Startbildschirm hinzfügen"</i> aus.<br/>Und schon hast du die App immer mit dabei!'; }

    msg = new PHNotification("VEXED als App",text);
    msg.callback = function(){ localStorage.setItem("appinfo",Date.now()); };
    msg.image = "/img/icons/dark_favicon.png";
    msg.time = 20;
    msg.show();
}

Site.prototype.setGlobalEvents = function(){
    document.querySelectorAll("link[data-href]").forEach(function(i){
        i.setAttribute("href",i.getAttribute("data-href"));
        i.removeAttribute("data-href");
    });

    window.onscroll = function(){ LazyLoader.onscroll(); }

    this.setEvents(document);
}

Site.prototype.setEvents = function(element){
    var site = this;

    element.querySelectorAll("img[data-lazyload]").forEach(function(i){ new LazyLoader(i); });
    window.onscroll();

    element.querySelectorAll("a[href]").forEach(function(i){
        if(i.getAttribute("href") == location.pathname){ i.classList.add("active"); }
        if(i.getAttribute("target") != "_blank"){
            i.onclick = function(e){
                if(site.load(i.getAttribute("href")))
                    e.preventDefault();
            }
        }
    });

    element.querySelectorAll(".ph_fs_button[data-rel][data-id]").forEach(function(i){
        if(i.classList.contains("fake")){ return; }
        new FSButton(i);
    });

    element.querySelectorAll(".ph_voter[data-vote][data-id]").forEach(function(i){
        new Voter(i);
    });

    element.querySelectorAll(".ph_pwc").forEach(function(i){
        new PWCreator(i);
    });

    element.querySelectorAll("form").forEach(function(i){
        new Form(i);
    });
}  

Site.prototype = Object.create(Site.prototype);
Site.prototype.constructor = Site;

function PHFeed(element){
    this.wrapper = $("html, body")[0];
    this.element = element;
    this.protect = true;
    this.loading = true;
    this.latest = -1;
    this.previous = -1;

    this.lastScrollHeight = 0;
    this.lastScrollTop = 0;
    this.loadingTimeout = null;

    this.newPostMsg = false;
    this.interval = null;

    this.init();
}

PHFeed.prototype.init = function(){
    $(this.wrapper).scrollTop(0);

    $(window).scroll(function(e){ this.onscroll(); }.bind(this));

    this.interval = setInterval(function(){ this.checkForUpdates(); }.bind(this),1000*30);

    this.load();
}

PHFeed.prototype.onscroll = function(){
    var offset = this.wrapper.clientHeight/3;
    if($(window).scrollTop() > offset && $(window).scrollTop() < $("html,body").prop("scrollHeight") - this.wrapper.clientHeight - offset){ this.protect = false; }
    if(this.protect){ return false; }
    this.lastScrollHeight = $("html,body").prop("scrollHeight");
    this.lastScrollTop = $(window).scrollTop();
    if(this.loading){ return false; }
    if($(window).scrollTop() <= offset){ return this.loadLatest() }
    if($(window).scrollTop() >= $("html,body").prop("scrollHeight") - this.wrapper.clientHeight - offset){ return this.loadPrevious(); }
    this.protect = false;
}

PHFeed.prototype.checkForUpdates = function(){
    PHAjax.GET("/ajax/f/feed_check/?last="+this.latest,function(data){
        if(data.value.new > 0 && !this.newPostMsg){
            var msg = new PHNotification("Neue Beiträge verfügbar","Hier klicken um diese zu laden.");
            msg.time = 5;
            msg.onclick = function(msg){ msg.hide(); this.loadLatest(); $("html,body").animate({scrollTop: 0},500); }.bind(this,msg);
            msg.show();
            this.newPostMsg = true;
        }
    }.bind(this));
}

PHFeed.prototype.restoreScroll = function(){
    $(window).scrollTop($("html,body").prop("scrollHeight")-this.lastScrollHeight+$(window).scrollTop());
}

PHFeed.prototype.load = function(){
    this.loading = true;
    PHAjax.GET("/ajax/f/feed_init/",function(data){
        if(data.value.feed.length > 0){ 
            this.addElements(data.value.feed);
            this.latest = data.value.feed[0].id;
            this.previous = data.value.feed[data.value.feed.length-1].id;
        }
        this.loading = false;
    }.bind(this));
}

PHFeed.prototype.loadPrevious = function(){
    this.loading = true;
    this.protect = true;
    PHAjax.GET("/ajax/f/feed_previous/?prev="+this.previous,function(data){
        if(data.value.feed.length > 0){ this.addElements(data.value.feed); }
        this.loading = false;
    }.bind(this));
}

PHFeed.prototype.loadLatest = function(){
    this.loading = true;
    this.protect = true;
    PHAjax.GET("/ajax/f/feed_latest/?last="+this.latest,function(data){
        if(data.value.feed.length > 0){
            this.addElements(data.value.feed);
            this.newPostMsg = false;
            this.restoreScroll();
        }
        this.loading = false;
    }.bind(this));
}

PHFeed.prototype.addElements = function(posts){
    var feed = this;
    posts.forEach(function(el){
        var post = $(el.html);
        var id = parseInt(el.id);
        App.site.setEvents($(post)[0]);
        if(id > feed.latest){
            feed.latest = id;
            $(feed.element).prepend(post);
        } else {
            feed.previous = id;
            $(feed.element).append(post);
        }
    });
}

PHFeed.prototype = Object.create(PHFeed.prototype);
PHFeed.prototype.constructor = PHFeed;

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
        this.reset();
        App.site.obscure(function(){
            this.form.submit();
        }.bind(this));
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

/* Lazy-Loader */

function LazyLoader(element){
    this.element = element;
    this.source = $(this.element).attr("data-lazyload");
    this.loaded = false;
    LazyLoader.list.push(this);
}

LazyLoader.list = []

LazyLoader.prototype.setEvents = function(){
    $(this.element).on("load",function(){
        $(this.element).attr("data-lazyload","");
    }.bind(this));
}

LazyLoader.prototype.check = function(){
    if(this.isVisible() && !this.loaded){ 
        LazyLoader.load(this.source);
    }
}

LazyLoader.prototype.load = function(){
    if(this.loaded){ return; }
    this.setEvents();
    $(this.element).attr("src",this.source);
    this.loaded = true;
}

LazyLoader.prototype.isVisible = function(){
    var viewTop = $(window).scrollTop(); 
    var viewHeight = $(window).height();
    var viewBottom = viewTop + viewHeight;

    var elTop = $(this.element).offset().top;
    var elBottom = elTop + $(this.element).height();

    if(!(elTop > viewBottom || elBottom < viewTop)){ return true; }
    return false;
}

LazyLoader.onscroll = function(){
    LazyLoader.list.forEach(function(el){
        el.check();
    });
}

LazyLoader.load = function(src){
    LazyLoader.list.filter(function(el){
        return el.source == src;
    }).forEach(function(el){
        el.load();
    });
}

LazyLoader.prototype = Object.create(LazyLoader.prototype);
LazyLoader.prototype.constructor = LazyLoader;

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

/* VOTER */

VoteState = {
    NEUTRAL: 0,
    UP: 1,
    DOWN: -1
}

function Voter(element){
    this.element = element;
    this.up = element.getElementsByClassName("up")[0];
    this.down = element.getElementsByClassName("down")[0];
    this.counter = element.getElementsByTagName("span")[0];
    this.vote = parseInt(element.getAttribute("data-vote") || 0);
    this.id = parseInt(element.getAttribute("data-id") || -1);
    this.setEvents();
    Voter.list.push(this);
}

Voter.list = [];

Voter.set = function(id,vote,votes){
    Voter.find(id).forEach(function(el){
        el.set(vote,votes);
    });
}

Voter.find = function(id){
    return Voter.list.filter(function(el){
        return (el.id == id);
    });
}

Voter.prototype.setEvents = function(){
    this.up.onclick = function(e){
        e.preventDefault();
        this.upVote();
    }.bind(this);

    this.down.onclick = function(e){
        e.preventDefault();
        this.downVote();
    }.bind(this);
}

Voter.prototype.set = function(vote,votes){
    this.vote = vote;
    this.element.setAttribute("data-vote",this.vote);
    this.counter.innerHTML = (votes >= 0 ? votes+"+" : "-"+Math.abs(votes));
}

Voter.prototype.upVote = function(){
    if(this.id < 0){ return false; }
    if(this.vote == VoteState.UP){ return Vote.neutral(this.id); }
    return Vote.up(this.id);
}

Voter.prototype.downVote = function(){
    if(this.id < 0){ return false; }
    if(this.vote == VoteState.DOWN){ return Vote.neutral(this.id); }
    return Vote.down(this.id);
}

Voter.prototype = Object.create(Voter.prototype);
Voter.prototype.constructor = Voter;

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

/* Notifications */

function PHNotification(title,content,callback){
    this.element = null;
    this.timeout = null;
    this.title = title || "";
    this.content = content || "";
    this.image = "";
    this.time = 2;
    this.el_title = null;
    this.el_content = null;
    this.onclick = function(){}
    this.callback = callback || function(){}
}

PHNotification.list = [];

PHNotification.next = function(){
    if(PHNotification.list.length > 0){
        PHNotification.list[0]._show();
    }
}

PHNotification.prototype.makeElement = function(){
    var html = '';
    html += '<div class="ph_notifi hidden'+(this.image != "" ? ' img' : '')+'">';
    html += '<span class="title">'+this.title+'</span>';
    html += '<p>'+this.content+'</p>';
    if(this.image != "") html += '<img src="'+this.image+'"/>';
    html += '</div>'
    var element = $(html);
    this.el_title = $(element).find(".title");
    this.el_content = $(element).find("p");
    $(element).click(function(){ this.onclick(); }.bind(this));
    return element;
}

PHNotification.prototype.show = function(){
    this.element = this.makeElement();
    if(PHNotification.list.length < 1){ this._show(); }
    PHNotification.list.push(this);
}

PHNotification.prototype._show = function(){
    $(".ph_notifi_wrapper").append(this.element);
    setTimeout(function(element){ $(element).removeClass("hidden"); }.bind(null,this.element),200);
    if(this.time > 0){ setTimeout(function(){ this.hide(); }.bind(this),this.time*1000); }
}

PHNotification.prototype.hide = function(){
    $(this.element).addClass("hidden");
    setTimeout(function(){ 
        $(this.element).remove(); 
        this.callback();
        PHNotification.list.shift();
        PHNotification.next();
    }.bind(this),400);
}

PHNotification.prototype.update = function(title,content){
    title = title ? title : "";
    content = content ? content : "";
    if(title != ""){ this.title = title; $(this.el_title).html(this.title); }
    if(content != ""){ this.content = content; $(this.el_content).html(this.content); }
}

PHNotification.create = function(title,content,time,image,callback){
    image = image || "";
    time = time || null;
    callback = callback || null;
    var msg = new PHNotification(title,content,callback);
    msg.image = image;
    if(time) msg.time = time;
    msg.show();
}

PHNotification.prototype = Object.create(PHNotification.prototype);
PHNotification.prototype.constructor = PHNotification;

/* AJAX */

PHAjaxRspn = {
    OK: 0,
    DENIED: 1,
    WARNING: 2,
    ERROR: 3
}

PHAjax = {}

PHAjax.GET = function(url,callback){
    callback = callback || function(){};
    var xhr = $.get(url,function(data){
        PHAjax.processResponse(data,callback); 
    })
    xhr.fail(function(){ 
        PHAjax.failed();
    });
}

PHAjax.POST = function(url,data,callback){
    callback = callback || function(){};
    data = data || {};
    var xhr = $.post(url,data,function(data){
        PHAjax.processResponse(data,callback); 
    })
    xhr.fail(function(){ 
        PHAjax.failed();
    });
}

PHAjax.failed = function(){
    PHNotification.create("","Fehler bei der Kommunikation mit dem Server");
}

PHAjax.error = function(data){
    PHNotification.create("Whoops",(data.info != "" ? data.info : "Etwas ist schief gegangen"));
}

PHAjax.warning = function (data){
    PHNotification.create("",data.info);
}

PHAjax.processResponse = function (data,callback){
    if(data.rspn === undefined){ return PHAjax.failed(); }
    if(data.rspn == PHAjaxRspn.OK){ return callback(data); }
    if(data.rspn == PHAjaxRspn.DENIED){ return App.client.logout(); } 
    if(data.rspn == PHAjaxRspn.WARNING){ return PHAjax.warning(data); }
    if(data.rspn == PHAjaxRspn.ERROR){ return PHAjax.error(data); }
    PHAjax.failed();
}

/* VOTE */

var Vote = {}

Vote.up = function(id,callback){
    var callback = callback || function(){};
    PHAjax.GET("/ajax/f/vote_up/?post="+id,function(data){
        Voter.set(id,data.value.vote,data.value.votes);
        callback(data);
    });
}

Vote.down = function(id,callback){
    var callback = callback || function(){};
    PHAjax.GET("/ajax/f/vote_down/?post="+id,function(data){
        Voter.set(id,data.value.vote,data.value.votes);
        callback(data);
    })
}

Vote.neutral = function(id,callback){
    var callback = callback || function(){};
    PHAjax.GET("/ajax/f/vote_neutral/?post="+id,function(data){
        Voter.set(id,data.value.vote,data.value.votes);
        callback(data);
    })
}

/* RELATION */

var Relation = {}

Relation.follow = function(id,callback){
    var callback = callback || function(){};
    PHAjax.GET("/ajax/f/rel_follow/?user="+id,function(data){
        FSButton.set(id,data.value.state);
        callback(data);
    })
    return true;
}

Relation.unfollow = function(id,callback){
    var callback = callback || function(){};
    PHAjax.GET("/ajax/f/rel_unfollow/?user="+id,function(data){
        FSButton.set(id,data.value.state);
        callback(data);
    })
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