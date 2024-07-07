<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.png">
    <title>Rock Paper Scissors</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Rock Paper Scissors</h1>
        <h5>Make your choice :</h5>
        <form method="post">
            <button type="submit" value="Scissors" name="user"><img src="scissors.jpg" alt="Scissors"></button>
            <button type="submit" value="Rock" name="user"><img src="rock.jpg" alt="Rock"></button>
            <button type="submit" value="Paper" name="user"><img src="paper.jpg" alt="Paper"></button>
        </form>

        <?php 
        // Declare bot
        $choices = array("Scissors", "Rock", "Paper");
        $bot_choice = $choices[array_rand($choices)];

        // Process Output
        if(isset($_POST["user"])) {
            $user_choice = $_POST["user"];
            $user_output = "<img src='{$user_choice}.jpg' alt='{$user_choice}'>";
            $choices = array("Scissors", "Rock", "Paper");
            $bot_choice = $choices[array_rand($choices)];
            $bot_output = "<img src='{$bot_choice}.jpg' alt='{$bot_choice}'>";

            echo "<div class='all_output'>";
            echo "<div class='user_output'>{$user_output}</div>";
            echo "<span class='vs'><b>VS</b></span>";
            echo "<div class='bot_output'>{$bot_output}</div>";
            echo "</div>";
      
            $result = "";
            if ($user_choice === $bot_choice) {
                $result = "<span class='draw'>Draw</span>";
            } elseif (
                ($user_choice === "Scissors" && $bot_choice === "Paper") ||
                ($user_choice === "Rock" && $bot_choice === "Scissors") ||
                ($user_choice === "Paper" && $bot_choice === "Rock")
            ) {
                $result = "<span class='win'>You Win</span>";
            } else {
                $result = "<span class='lose'>You Lose</span>";
            }

            echo "<div class='result'>{$result}</div>";
        }
        ?>
    </div>

    <div class="howto-container">
        <form method="post">
            <button class="howto" value="how" name="howto">How to play</button>
        </form>

        <?php
        if(isset($_POST["howto"])) {
            echo "<div class='howto-text'>";
            echo "Really? Just choose<br><img src='scissors.jpg' alt='Scissors'> or <img src='rock.jpg' alt='Rock'> or <img src='paper.jpg' alt='Paper'>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
