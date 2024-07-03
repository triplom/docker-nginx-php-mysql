<?php

require_once 'HangedMan.php';

session_start();

if (!isset($_SESSION['words'])) {
    $_SESSION['words'] = array();
}

$words = $_SESSION['words'];
$numwords = count($words);

function printPage($image, $guesstemplate, $guessed, $wrong, $script) {
    echo <<<ENDPAGE
<!DOCTYPE html>
<html>
<head>
    <title>Hangman</title>
</head>
<body>
<h1>Hangman Game</h1>
<br />
<pre>$image</pre>
<br />
<p><strong>Word to guess: $guesstemplate</strong></p>
<p>Letters used in guesses so far: $guessed</p>
<form method="post" action="$script">
    <input type="hidden" name="wrong" value="$wrong" />
    <input type="hidden" name="lettersguessed" value="$guessed" />
    <fieldset>
        <legend>Your next guess</legend>
        <input type="text" name="letter" autofocus />
        <input type="submit" value="Guess" />
    </fieldset>
</form>
<form method="post" action="$script">
    <fieldset>
        <legend>Add a new word</legend>
        <input type="text" name="newword" />
        <input type="submit" name="addword" value="Add Word" />
    </fieldset>
</form>
</body>
</html>
ENDPAGE;
}

function startGame() {
    global $words, $numwords, $hang;

    if ($numwords == 0) {
        echo "No words loaded yet. Add a word below.";
        return;
    }

    $which = rand(0, $numwords - 1);
    $word = $words[$which];
    $len = strlen($word);
    $guesstemplate = str_repeat('_ ', $len);
    $script = $_SERVER["PHP_SELF"];

    printPage($hang[0], $guesstemplate, "", 0, $script);
}

function killPlayer($word) {
    echo <<<ENDPAGE
<!DOCTYPE html>
<html>
<head>
    <title>Hangman</title>
</head>
<body>
<h1>You lost!</h1>
<p>The word you were trying to guess was <em>$word</em>.</p>
</body>
</html>
ENDPAGE;
}

function congratulateWinner($word) {
    echo <<<ENDPAGE
<!DOCTYPE html>
<html>
<head>
    <title>Hangman</title>
</head>
<body>
<h1>You win!</h1>
<p>Congratulations! You guessed that the word was <em>$word</em>.</p>
</body>
</html>
ENDPAGE;
}

function matchLetters($word, $guessedLetters) {
    if (is_null($word)) {
        return "";
    }

    $len = strlen($word);
    $guesstemplate = str_repeat("_ ", $len);

    for ($i = 0; $i < $len; $i++) {
        $ch = $word[$i];
        if (strstr($guessedLetters, $ch)) {
            $pos = 2 * $i;
            $guesstemplate[$pos] = $ch;
        }
    }

    return $guesstemplate;
}

function handleGuess() {
    global $words, $hang;

    $wrong = $_POST["wrong"];
    $lettersguessed = $_POST["lettersguessed"];
    $guess = $_POST["letter"];
    $letter = strtoupper($guess[0]);

    // Check if a new word is being added
    if (isset($_POST["addword"]) && !empty($_POST["newword"])) {
        $newword = strtoupper(trim($_POST["newword"]));
        if (!in_array($newword, $words)) {
            $_SESSION['words'][] = $newword;
            $words = $_SESSION['words'];
            $numwords = count($words);
            startGame();
            return;
        } else {
            echo "Word '$newword' is already in the list.";
            return;
        }
    }

    $which = rand(0, $words - 1);
    $word = $words[$which];

    if (!strstr($word, $letter)) {
        $wrong++;
    }

    $lettersguessed .= $letter;
    $guesstemplate = matchLetters($word, $lettersguessed);

    if (!strstr($guesstemplate, "_")) {
        congratulateWinner($word);
    } else if ($wrong >= 6) {
        killPlayer($word);
    } else {
        $script = $_SERVER["PHP_SELF"];
        printPage($hang[$wrong], $guesstemplate, $lettersguessed, $wrong, $script);
    }
}

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {
    handleGuess();
} else {
    startGame();
}

// Include last user view (if needed)
include("LastUpdate.php");

?>
