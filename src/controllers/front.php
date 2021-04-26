<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Page;
use Blog\Ctrl\utils;
use Blog\Ctrl\Post;
use Blog\Models\CommentModel;

class Front extends Page
{


    // public function __construct(Array $uri){
    // }

    protected function home()
    {
        $this->template = "home";
        $this->current_page = "home";
        $this->data = []; //données du modele
    }

    protected function contact()
    {
        $this->template = "contact";
        $this->data = []; //données du modele

    }
    protected function articles()
    {
        $this->template = "blogListe";
        $articles = new Post("all");
        $this->data = [];
        foreach ($articles->getList() as $key => $value) {
            $value->url = Utils::titleToURI($value->titre);
            array_push($this->data, $value);
        }
    }

    protected function article($safedata)
    {
        $this->template = 'article';
        $article = new Post(["titre" => $safedata->uri[1]]);
        $commentaires = new Comments($article->getId());
       
        $this->data = [
            "article"     => $article->getAll(),
            "commentaires"=> $commentaires
            ->getCommentByArticle($article->getId())
        ];
        die($this);

    }
    protected function contactForm()
    {
        $this->template = 'contact';
    }
}
