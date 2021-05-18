<?php

namespace Blog\Ctrl;

use Blog\Ctrl\Page;
use Blog\Ctrl\utils;
use Blog\Ctrl\Post;
use Blog\Ctrl\Comments;

class Front extends Page
{


    protected function home()
    {
        $this->template = "home";
        $this->current_page = "home";
        $this->data = []; //données du modele
    }

    protected function contact()
    {
        $this->template = "contact";
        $this->current_page = "contact";
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

        if ($safedata->method === "POST") {
            //  die(var_dump($safedata));   
            try {
                $newComment = new Comments($safedata);
                $newComment->save($safedata);
            } catch (\Throwable $th) {
                //throw $th;
                $this->template ="page500";
                $this->status = 500;
                $this->data = ["msg"=>$th];
                return;
            }
        }

        $this->template = 'article';
        $article = new Post(["titre" => $safedata->uri[1]]);
        $commentaires = new Comments(["id_article" => $article->getId()]);
        $this->data = [
            "article"     => $article->getAll(),
            "commentaires" => $commentaires->getCommentByArticle()
                
        ];
        // die(var_dump($this->data));
    }

    protected function contactForm()
    {
        $this->template = 'contact';
    }
}
