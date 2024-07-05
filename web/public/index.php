<?php
include '../app/vendor/autoload.php';
$foo = new App\Acme\Foo();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker <?php echo $foo->getName(); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center align all text within the container */
        }
        h1, h2, h3 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
        }
        ul li a {
            display: block;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        ul li a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Docker <?php echo $foo->getName(); ?></h1>
            <h2>Docker - Marcel Test</h2>
            <h3><a href="LastUpdate.php">Last Update</a></h3>
            <h4><a href="phpVersion.php">PHP Version Page</a></h4>
        </header>
        
        <nav>
            <h2>Choose a Game:</h2>
            <ul>
                <li><a href="HangMan.php">Hangman Game</a></li>
                <li><a href="DiceGame.php">Dice Game</a></li>
                <li><a href="Sudoku.php">Sudoku Game</a></li>
                <li><a href="PaperRocks.php">Paper-Rocks Game</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>