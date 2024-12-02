<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;
    
    protected $fillable = ['nome', 'descricao', 'user_id'];

    public function user(){
       return $this->belongsTo(User::class, 'user_id');
    }

    public function projetoTarefas(){
      return $this->hasMany(Tarefa::class, 'projeto_id', 'id');
     }
}
