<?php

namespace App\Services\Trivia;

use App\Models\User;
use App\Models\Trivia;
use Illuminate\Support\Str;
use App\Models\TriviaQuestion;
use Illuminate\Support\Facades\DB;
use App\Models\TriviaQuestionChoice;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Trivia\StoreTriviaRequest;

class CreateTriviaService implements BaseServiceInterface
{
    protected $request;
    public function __construct(StoreTriviaRequest $request)
    {
        $this->request = $request;
    }

    public function run()
    {
       
        DB::beginTransaction();
    
        try {
           $trivia = Trivia::create([
                "name" => $this->request["trivia_name"],
                "game_id" => $this->request["game_id"],
                "brand_id" => $this->request["brand_id"],
                "campaign_id" => $this->request["campaign_id"],
                "image_url" => $this->request["image_url"],
                "user_id" => $this->request->user()->id,
                "entry_fee" => $this->request['entry_fee'],
                "time_limit" => $this->request['time_limit'],
                "high_score_bonus" => $this->request['high_score_bonus'],
            ]); 
    
            DB::commit();
    
            return $trivia;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store trivia questions',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
