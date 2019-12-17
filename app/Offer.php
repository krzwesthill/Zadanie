<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offer';
    public $timestamps = false;
    protected $fillable = ['kod_produktu', 'ilosc', 'rok_produkcji', 'cena'];

    public $rules = [

    	'kod_produktu'  => 'required',
    	'ilosc'			=> 'numeric|min:0|not_in:0',
    	'rok_produkcji'	=> 'numeric',
    	'cena'			=> 'numeric|min:0|not_in:0'
    ];

}
