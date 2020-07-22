<?php

class Staff_model extends Crud_model
{   
    public function __construct()
    {
        parent::__construct();
        $this->table = 'staffs';
    }
}