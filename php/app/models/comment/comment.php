<?php 

    namespace App\Models\Comment;

    class Comment {

        private $cs;

        public $id = -1;
        public $post = -1;
        public $user = -1;
        public $content = -1;
        public $time = 0;

        public function __construct($id){
            $this->cs = new \App\Models\Storage\Sql\CommentService();
            $this->id = $id;
            $this->load();
        }

        public function load(){
            $data = $this->cs->get($this->id);
            if(!$data){ return; }
            $this->post = intval($data["post"]);
            $this->user = intval($data["user"]);
            $this->content = \App\Models\Post\Post::format($data["content"]);
            $this->content_nf = $data["content"];
            $this->time = intval($data["time"]);
        }

        public function toHtml(){
            $user = new \App\Models\Account\User($this->user);
            $html = '';
            $html .= '<article class="ph_comment">';
            $html .= '<div class="profile">';
            $html .= '    <a href="/p/'.$user->name.'/" class="pb" ><img src="/img/pb/'.$user->id.'/?tiny" data-lazyload="/img/pb/'.$user->id.'/" /></a>';
            $html .= '</div>';
            $html .= '<p class="description">'.$this->content.'</p>';
            $html .= '<span class="meta">';
            $html .= '    <a class="p_link" href="/p/'.$user->name.'/">'.$user->displayName.'</a> | '.\Helpers\Date::beautify($this->time);
            $html .= '</span>';
            $html .= '</article>';
            return $html;
        }
    }

?>