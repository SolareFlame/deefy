<?php

namespace iutnc\deefy\action;

abstract class Action {

    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    public function __construct(){

        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    public function execute() : string {
        if($this->http_method == "POST"){
            return $this->executePost();
        } else {
            return $this->executeGet();
        }
    }

    //fonction pour POST
    abstract  public function executePost() : string;

    //fonction pour GET
    abstract  public function executeGet() : string;


    public function __invoke(): string
    {
        return $this->execute();
    }
}