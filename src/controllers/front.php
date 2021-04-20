<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Page;
use Blog\Ctrl\utils;
use Blog\Models\PostModel;
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
        $model = new PostModel(NULL);
        $this->data = [];
        foreach ($model->getArticles() as $key => $value) {
            $value->url = Utils::titleToURI($value->titre);
            array_push($this->data, $value);
        }
    }

    protected function article($safedata)
    {
        $this->template = 'article';
        $article = new PostModel(["titre" => $safedata->uri[1]]);
        // $commentaires = new CommentModel($article->id);

        $this->data = [
            // "article"     => $article->getArticle(),
            // "commentaires"=> $commentaires->getComments()
            $article->getArticle()
        ];
        // die(var_dump($this->data));

        // $this->data = [$safedata];  


    }
}
