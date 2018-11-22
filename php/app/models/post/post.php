<?php

    namespace App\Models\Post;

    class Post {

        private $ps; 
        private $ms;
        private $vs;
        private $cs;

        public $id = -1;
        public $exists = false;
        public $user = -1;
        public $type = Type::UNDEFINED;
        public $media = [];
        public $text = "";
        public $text_nf = "";
        public $votes = 0;
        public $upvotes = [];
        public $clientVote = 0;
        public $comments = [];
        public $date = 0;

        public function __construct($id){
            $this->ps = new \App\Models\Storage\Sql\PostService();
            $this->ms = new \App\Models\Storage\Sql\MediaService();
            $this->vs = new \App\Models\Storage\Sql\VoteService();
            $this->cs = new \App\Models\Storage\Sql\CommentService();
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
            $this->text_nf = $data["description"];
            $this->text = Self::format($data["description"]);
            $this->votes = $this->vs->getVotes($this->id);
            $this->upvotes = $this->vs->getUpVotes($this->id);
            $this->clientVote = $this->vs->getClient($this->id);
            $this->comments = $this->cs->byPost($this->id);
            $this->date = intval($data["date"]);
        }

        /* HTML */

        public function toHtmlBanner($showUser = false){
            if($this->type == Type::MEDIA && isset($this->media[0])){ return $this->htmlMediaBanner($showUser); }
            return $this->htmlTextBanner($showUser);
        }

        private function htmlTextBanner($showUser){
            $user = new \App\Models\Account\User($this->user);
            $html = '<a href="/post/'.$this->id.'/" class="ph_post_banner TEXT">
                <span class="text">'.Self::format(strlen($this->text_nf) > 255 ? substr($this->text_nf,0,255).'...' : $this->text_nf,false).'</span>
                <span class="meta">
                    '.($showUser ? '<strong>
                        <img class="inline_pb" src="/img/pb/'.$user->id.'/?tiny" />'.$user->displayName.'
                    </strong> | ' : '').\App\Models\Post\Vote::format($this->votes).' | '.count($this->comments).' <i class="material-icons">chat_bubble_outline</i> | '.\Helpers\Date::beautify($this->date).'</span>
                </a>';
            return $html;
        }

        private function htmlMediaBanner($showUser){
            $user = new \App\Models\Account\User($this->user);
            $media = (object) $this->ms->get($this->media[0]);
            $html = '<a href="/post/'.$this->id.'/" class="ph_post_banner MEDIA"><!--
                --><img width="'.$media->width.'" height="'.$media->height.'" src="/img/media/'.$media->id.'/?tiny" data-lazyload="/img/media/'.$media->id.'/"/><!--
                --><span class="meta">
                    <span class="text">'.Self::format(strlen($this->text_nf) > 255 ? substr($this->text_nf,0,255).'...' : $this->text_nf,false).'</span>'.
                    ($showUser ? '<strong>
                        <img class="inline_pb" src="/img/pb/'.$user->id.'/?tiny" />'.$user->displayName.'
                    </strong> | ' : '')
                    .\App\Models\Post\Vote::format($this->votes).' | '.count($this->comments).' <i class="material-icons">chat_bubble_outline</i> | '.\Helpers\Date::beautify($this->date).'</span><!--
                --></a>';
            return $html;
        }

        public function toHtmlFeed(){
            $user = new \App\Models\Account\User($this->user);
            if(isset($this->media[0])){ $media = (object) $this->ms->get($this->media[0]); } 
            $html = '<article class="ph_post">
                <a class="ph_post_link" href="/post/'.$this->id.'/"></a>
                <div class="ph_post_media" '.(isset($this->media[0]) ? 'style="padding-bottom: '.(($media->height/$media->width)*100).'%;"' : '').'>'.(isset($this->media[0]) ? '<img src="/img/media/'.$this->media[0].'/?tiny" data-lazyload="/img/media/'.$this->media[0].'/"/>' : '').'</div>
                <div class="ph_post_info">
                    <div class="profile">
                        <a href="/p/'.$user->name.'/" class="pb" ><img src="/img/pb/'.$user->id.'/?tiny" data-lazyload="/img/pb/'.$user->id.'/" /></a>
                    </div>
                    <p class="description">'.$this->text.'</p>
                    <span class="meta">
                        <a class="p_link" href="/p/'.$user->name.'/">'.$user->displayName.'</a> | '.count($this->comments).' <i class="material-icons">chat_bubble_outline</i> | '.\Helpers\Date::beautify($this->date).' 
                    </span>
                    <div class="voting ph_voter" data-vote="'.$this->clientVote.'" data-id="'.$this->id.'"><!--
                        --><div class="actions down">-</div><!--
                        --><span>'.\App\Models\Post\Vote::format($this->votes).'</span><!--
                        --><div class="actions up">+</div><!--
                    --></div>
                </div>
            </article>';
            return $html;
        }

        /* VOTING */

        public function upVote(){
            if(!$this->vs->set($this->id,\App\Models\Post\Vote::UP)){ return false; }
            $this->clientVote = \App\Models\Post\Vote::UP;
            $this->votes = $this->vs->getVotes($this->id);
            return true;
        }

        public function downVote(){
            if(!$this->vs->set($this->id,\App\Models\Post\Vote::DOWN)){ return false; }
            $this->clientVote = \App\Models\Post\Vote::DOWN;
            $this->votes = $this->vs->getVotes($this->id);
            return true;
        }

        public function unVote(){
            if(!$this->vs->delete($this->id,\App\Models\Post\Vote::NEUTRAL)){ return false; }
            $this->clientVote = \App\Models\Post\Vote::NEUTRAL;
            $this->votes = $this->vs->getVotes($this->id);
            return true;
        }

        public function delete(){
            if(!(__CLIENT()->id == $this->user || __CLIENT()->admin == true)){ return false; }
            //DELETE Votes
            $this->vs->deleteFromPost($this->id);
            //DELETE Media
            if(isset($this->media[0])){ 
                $media = new \App\Models\Media\Media($this->media[0]);
                $media->delete();
            }
            //DELETE Post
            $this->ps->delete($this->id);
            return true;
        }

        public static function format($text,$link=true){
            $text = htmlspecialchars($text);
            if($link){
                $text = preg_replace('/https?:\/\/(?:www\.)?([\w\d.]+\.[\w]{2,8}(?:\/[\w\d]+)*\??(?:&?[\w\d]+\=?(?:[\w\d]+)?)*)\b/im','<a target="_blank" href="http://$1">$0</a>',$text);
                $text = preg_replace('/\@([\w\d]+)\b/im','<a href="/p/$1/">@$1</a>',$text);
            }
            $text = nl2br($text);
            return $text;
        }

        public static function byUser($id){
            $ps = new \App\Models\Storage\Sql\PostService();
            return $ps->byUser($id);
        }

    }

?>