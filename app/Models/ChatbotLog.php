<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'question',
        'answer',
        'intent',
        'matched_product_id',
        'matched_faq_id',
        'confidence',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matchedProduct()
    {
        return $this->belongsTo(DataBarang::class, 'matched_product_id');
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'matched_faq_id');
    }
}
