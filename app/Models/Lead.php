<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
  use HasFactory;
  
  protected $fillable = [
    "first_name",
    "last_name",
    "full_name",
    "email",
    "phone",
    "alternate_phone",
    "address",
    "city",
    "requirement",
  ];
}
