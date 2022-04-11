<?php

include 'config.php';

$questions = range(1, 275);
removeQuestions($easyQuestions, $questions);
shuffle($questions);
$total = count($questions);

while (true) {
    clearScreen();
    $questionNumber = array_pop($questions);
    $index = $total - count($questions);
    [$questionNumber, $question] = getQuestion($questionNumber);
    showQuestion($questionNumber, $question, $total, $index);
    readAnswers($question);
}

function clearScreen()
{
    system('clear');
}

function getQuestion($questionNumber)
{
    $data = file_get_contents("questions/$questionNumber.json");
    return [
        $questionNumber,
        json_decode($data, JSON_OBJECT_AS_ARRAY)
    ];
}

function showQuestion($questionNumber, $question, $total, $index)
{
    $choices = $question['choices'];
    shuffleAssoc($choices);
    echo "($index / $total) " . $questionNumber . '. ' . $question['title'] . "\n\n";
    foreach ($choices as $letter => $choice) {
        echo "$letter: $choice\n";
    }

    echo "\n";
}

function readAnswers($question)
{
    do {
        $answered = true;
        $guesses = [];
        for ($i = 0; $i < count($question['answers']); $i++) {
            $guess = strtolower(readline());
            if ('s' === $guess) {
                return;
            }

            $guesses[] = $guess;
        }

        foreach ($guesses as $guess) {
            if (!in_array($guess, $question['answers'])) {
                $answered = false;
            }
        }

        if (!$answered) {
            echo "---\n";
        }

    } while (!$answered);
}

function removeQuestions($easyQuestions, &$questions)
{
    foreach ($easyQuestions as $easyQuestion) {
        unset($questions[$easyQuestion-1]);
    }
}

function shuffleAssoc(&$data) {
    $keys = array_keys($data);
    shuffle($keys);
    foreach($keys as $key) {
        $new[$key] = $data[$key];
    }

    $data = $new;
}
