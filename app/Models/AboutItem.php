<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutItem extends Model
{
    protected $fillable = ['name', 'role', 'description', 'image_path', 'order', 'facebook_url', 'instagram_url', 'google_url'];
}
