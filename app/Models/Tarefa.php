<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'prazo', 'projeto_id', 'status', 'tag_id'];
   
     public function projeto(){
        return $this->belongsTo(Projeto::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tarefa_tag');
    }
}
