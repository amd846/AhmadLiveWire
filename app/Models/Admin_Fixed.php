<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin_Fixed extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'admin_fixed';

    protected $fillable = [
        'fixedMoney',       
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
   

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
   
}
