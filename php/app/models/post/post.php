<?php

    namespace App\Models\Post;

    class Post {

        private $ps; 
        private $ms;

        public $id = -1;
        public $exists = false;
        public $user = -1;
        public $type = Type::UNDEFINED;
        public $media = [];
        public $text = "";
        public $date = 0;

        public function __construct($id){
            $this->ps = new \App\Models\Storage\Sql\PostService();
            $this->ms = new \App\Models\Storage\Sql\MediaService();
            $this->id = $id;
            $this->load();
        }

        public function load(){
            $data = $this->ps->get($this->id);
            if(!$data){ return false; }
            $this->exists = true;
            $this->user = intval($data["user"]);
            $this->type = intval($data["type"]);
            $this->media = ($this->type == Type::MEDIA ? $this->ms->ofPost($this->id) : []);
            $this->text = Self::format($data["description"]);
            $this->date = intval($data["date"]);
        }

        /* HTML */

        public function toHtmlBanner($showUser = false){
            $user = new \App\Models\Account\User($this->user);
            $html = '<div href="/p/'.$user->name.'/" class="ph_post_banner TEXT">
                '.(strlen($this->text) > 255 ? substr($this->text,0,255).'...' : $this->text).'
                <span class="meta">'.($showUser ? '<a href="/p/'.$user->name.'/">'.$user->displayName.'</a> | ' : '').'2+ | '.\Helpers\Date::beautify($this->date).'</span>
                </div>';
            return $html;
        }

        public function toHtmlFeed(){
            $user = new \App\Models\Account\User($this->user);
            $html = '<article class="ph_post">
                <div class="ph_post_media">'.(isset($this->media[0]) ? '<img src="/img/media/'.$this->media[0].'/"/>' : '').'</div>
                <div class="ph_post_info">
                    <div class="profile">
                        <a href="/p/'.$user->name.'/" class="pb" style="background-image: url(/img/pb/'.$user->id.'/);"></a>
                    </div>
                    <p class="description">'.$this->text.'</p>
                    <span class="meta">
                        <a class="p_link" href="/p/'.$user->name.'/">'.$user->displayName.'</a> | '.\Helpers\Date::beautify($this->date).' 
                    </span>
                    <div class="voting" style="opacity: 0.25" data-vote="0" data-id="'.$this->id.'"><!--
                        --><div class="actions down">-</div><!--
                        --><span>0+</span><!--
                        --><div class="actions up">+</div><!--
                    --></div>
                </div>
            </article>';
            return $html;
        }

        public static function format($text){
            $text = htmlspecialchars($text);
            $text = preg_replace('/@([\w\d]+)\b/im','<a href="/p/$1/">@$1</a>',$text);
            $text = preg_replace('/https?:\/\/(?>www\.)?([\w\d.]+\.[\w]{2,8})/im','<a target="_blank" href="http://$1">$0</a>',$text);
            $text = nl2br($text);
            return $text;
        }

        public static function byUser($id){
            $ps = new \App\Models\Storage\Sql\PostService();
            return $ps->byUser($id);
        }

    }

?>