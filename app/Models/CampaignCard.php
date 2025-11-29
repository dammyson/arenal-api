<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CampaignCard extends Model
{
    use HasUuids;
    protected $fillable = ['campaign_id', 'title', 'description', 'image_url', 'link_text'];


}
