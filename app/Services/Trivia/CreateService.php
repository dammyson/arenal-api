<?php

namespace App\Services\Trivia;

use App\Models\TriviaQuestion;
use App\Models\TriviaQuestionChoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\BaseServiceInterface;

class CreateService implements BaseServiceInterface
{
    protected $data;
    protected $user;

    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    public function run()
    {
        $user = User::find($this->user->id);
        DB::beginTransaction();
    
        try {
            $createdQuestions = [];
    
           
            foreach ($this->data as $questionData) {
                $question = TriviaQuestion::create([
                    'id' => Str::uuid(),
                    'question' => $questionData['question'],
                    'is_general' => $questionData['is_general'] ?? true,
                    'points' => $questionData['points'] ?? 0,
                    'duration' => $questionData['duration'] ?? 0,
                    'media_type' => $questionData['media_type'] ?? null,
                    'asset_url' => $questionData['asset_url'] ?? null,
                    'difficulty_level' => $questionData['difficulty_level'] ?? 'EASY',
                    'company_id' => $user->companies[0]->id,
                    'user_id' => $user->id,
                ]);
    
                $choices = [];
    
                foreach ($questionData['choices'] as $choiceData) {
                    $choice = TriviaQuestionChoice::create([
                        'id' => Str::uuid(),
                        'question_id' => $question->id,
                        'choice' => $choiceData['choice'],
                        'is_correct_choice' => $choiceData['is_correct_choice'],
                        'media_type' => $choiceData['media_type'] ?? null,
                        'asset_url' => $choiceData['asset_url'] ?? null,
                    ]);
                    $choices[] = $choice;
                }
    
                $question->setRelation('choices', collect($choices));
                $createdQuestions[] = $question;
            }
    
            DB::commit();
    
            return $createdQuestions;
        } catch (\Throwable $e) {
            DB::rollBack();
    
            return [
                'message' => 'Failed to store trivia questions',
                'error' => $e->getMessage()
            ];
        }
    }
    
}
