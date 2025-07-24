<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\StoreBrandBadges;
use App\Http\Requests\User\BrandStoreRequest;
use App\Models\Badge;

class StoreBrandBadgesService implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreBrandBadges $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();
        $data = $this->request->validated();

        $createdBadges = [];

        foreach ($data['badges'] as $badgeData) {
            $badge = Badge::create([
                ...$badgeData,
                'user_id' => $user->id,
            ]);

            $createdBadges[] = $badge;
        }

        return $createdBadges;
    }
}