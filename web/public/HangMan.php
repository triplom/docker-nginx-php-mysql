<?php

require_once 'HangedMan.php';

session_start();

if (!isset($_SESSION['words'])) {
    $_SESSION['words'] = array();
}

$words = $_SESSION['words'];
$numwords = count($words);

function printPage($image, $guesstemplate, $guessed, $wrong, $script, $message = "", $word = "") {
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
<p>Guessed letters: $guessed</p>
<p>$message</p>
<form method="post" action="$script">
    <input type="hidden" name="wrong" value="$wrong" />
    <input type="hidden" name="lettersguessed" value="$guessed" />
    <input type="hidden" name="word" value="$word" />
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

function startGame($message = "") {
    global $words, $numwords, $hang;

    if ($numwords == 0) {
        $script = $_SERVER["PHP_SELF"];
        printPage("", "", "", 0, $script, "No words loaded yet. Add a word below.");
        return;
    }

    $which = rand(0, $numwords - 1);
    $word = $words[$which];
    $len = strlen($word);
    $guesstemplate = str_repeat('_ ', $len);
    $script = $_SERVER["PHP_SELF"];

    printPage($hang[0], $guesstemplate, "", 0, $script, $message, $word);
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

    $wrong = isset($_POST["wrong"]) ? $_POST["wrong"] : 0;
    $lettersguessed = isset($_POST["lettersguessed"]) ? $_POST["lettersguessed"] : "";
    $guess = isset($_POST["letter"]) ? $_POST["letter"] : "";
    $word = isset($_POST["word"]) ? $_POST["word"] : "";

    // Check if a new word is being added
    if (isset($_POST["addword"]) && !empty($_POST["newword"])) {
        $newword = strtoupper(trim($_POST["newword"]));
        if (strlen($newword) > 3) {
            if (!in_array($newword, $words)) {
                $_SESSION['words'][] = $newword;
                $words = $_SESSION['words'];
                $numwords = count($words);

                // Save the new word to words.txt
                file_put_contents('./words.txt', $newword . PHP_EOL, FILE_APPEND);

                startGame("New word '$newword' added.");
                return;
            } else {
                startGame("Word '$newword' is already in the list.");
                return;
            }
        } else {
            startGame("Word must be longer than 3 letters.");
            return;
        }
    }

    if ($guess === "") {
        startGame("Please enter a letter to guess.");
        return;
    }

    $letter = strtoupper($guess[0]);

    if (strstr($lettersguessed, $letter)) {
        $script = $_SERVER["PHP_SELF"];
        $guesstemplate = matchLetters($word, $lettersguessed);
        printPage($hang[$wrong], $guesstemplate, $lettersguessed, $wrong, $script, "You already guessed the letter '$letter'.", $word);
        return;
    }

    $lettersguessed .= $letter;

    if (!strstr($word, $letter)) {
        $wrong++;
    }

    $guesstemplate = matchLetters($word, $lettersguessed);

    if (!strstr($guesstemplate, "_")) {
        congratulateWinner($word);

        // Save the guessed word to words.txt
        file_put_contents('words.txt', $word . PHP_EOL, FILE_APPEND);
    } else if ($wrong >= 6) {
        killPlayer($word);
    } else {
        $script = $_SERVER["PHP_SELF"];
        printPage($hang[$wrong], $guesstemplate, $lettersguessed, $wrong, $script, "", $word);
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="HangMan" href="favicon.png" type="image/x-icon"/>
</head>
<body></body>
</html>