<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trivia;
use App\Models\Category;

class TriviaSeeder extends Seeder
{
    public function run()
    {
        // Science category (id 1)
        $scienceCategory = Category::find(1);
        $scienceQuestions = [
            [
                'question' => 'What is the chemical symbol for water?',
                'options' => ['H2O', 'O2', 'CO2', 'H2O2'],
                'correct_answer' => 'H2O',
                'category_id' => $scienceCategory->id,
            ],
            [
                'question' => 'What planet is known as the Red Planet?',
                'options' => ['Venus', 'Mars', 'Jupiter', 'Saturn'],
                'correct_answer' => 'Mars',
                'category_id' => $scienceCategory->id,
            ],
            [
                'question' => 'What is the hardest natural substance on Earth?',
                'options' => ['Gold', 'Iron', 'Diamond', 'Platinum'],
                'correct_answer' => 'Diamond',
                'category_id' => $scienceCategory->id,
            ],
            [
                'question' => 'What gas do plants absorb from the atmosphere for photosynthesis?',
                'options' => ['Oxygen', 'Carbon Dioxide', 'Nitrogen', 'Hydrogen'],
                'correct_answer' => 'Carbon Dioxide',
                'category_id' => $scienceCategory->id,
            ],
            [
                'question' => 'What is the chemical formula for methane?',
                'options' => ['CH4', 'CO2', 'H2O', 'NH3'],
                'correct_answer' => 'CH4',
                'category_id' => $scienceCategory->id,
            ]
        ];

        foreach ($scienceQuestions as $question) {
            Trivia::create($question);
        }

        // Math category (id 2)
        $mathCategory = Category::find(2);
        $mathQuestions = [
            [
                'question' => 'What is the square root of 64?',
                'options' => ['6', '8', '10', '12'],
                'correct_answer' => '8',
                'category_id' => $mathCategory->id,
            ],
            [
                'question' => 'What is 7 times 8?',
                'options' => ['56', '54', '58', '60'],
                'correct_answer' => '56',
                'category_id' => $mathCategory->id,
            ],
            [
                'question' => 'What is the value of Pi to two decimal places?',
                'options' => ['3.14', '3.16', '3.12', '3.18'],
                'correct_answer' => '3.14',
                'category_id' => $mathCategory->id,
            ],
            [
                'question' => 'What is the sum of 12 and 15?',
                'options' => ['27', '28', '30', '26'],
                'correct_answer' => '27',
                'category_id' => $mathCategory->id,
            ],
            [
                'question' => 'What is 25% of 200?',
                'options' => ['50', '40', '60', '30'],
                'correct_answer' => '50',
                'category_id' => $mathCategory->id,
            ]
        ];

        foreach ($mathQuestions as $question) {
            Trivia::create($question);
        }

        // General Knowledge category (id 3)
        $generalKnowledgeCategory = Category::find(3);
        $generalKnowledgeQuestions = [
            [
                'question' => 'Who was the first President of the United States?',
                'options' => ['George Washington', 'Thomas Jefferson', 'Abraham Lincoln', 'John Adams'],
                'correct_answer' => 'George Washington',
                'category_id' => $generalKnowledgeCategory->id,
            ],
            [
                'question' => 'What is the capital city of France?',
                'options' => ['Paris', 'London', 'Berlin', 'Madrid'],
                'correct_answer' => 'Paris',
                'category_id' => $generalKnowledgeCategory->id,
            ],
            [
                'question' => 'Who wrote the play Romeo and Juliet?',
                'options' => ['William Shakespeare', 'Charles Dickens', 'Jane Austen', 'Homer'],
                'correct_answer' => 'William Shakespeare',
                'category_id' => $generalKnowledgeCategory->id,
            ],
            [
                'question' => 'Which country is known as the Land of the Rising Sun?',
                'options' => ['Japan', 'China', 'Korea', 'Vietnam'],
                'correct_answer' => 'Japan',
                'category_id' => $generalKnowledgeCategory->id,
            ],
            [
                'question' => 'What is the tallest mountain in the world?',
                'options' => ['Mount Everest', 'K2', 'Kangchenjunga', 'Mount Fuji'],
                'correct_answer' => 'Mount Everest',
                'category_id' => $generalKnowledgeCategory->id,
            ]
        ];

        foreach ($generalKnowledgeQuestions as $question) {
            Trivia::create($question);
        }
    }
}
