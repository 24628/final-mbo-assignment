<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'phone_number',
        'about',
        'image',
        'facebook',
        'linkedin',
        'contact_email',
        'twitter',
        'company',
        'cv',
        'comp_function',
    ];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(){

        return $this->belongsTo('App\User');

    }
}
