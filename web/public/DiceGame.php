<?php

// Function to roll the dice
function rollDice() {
    $die1 = rand(1, 6);
    $die2 = rand(1, 6);
    $sum = $die1 + $die2;
    return json_encode(['die1' => $die1, 'die2' => $die2, 'sum' => $sum]);
}

// Check if the request is via AJAX
if (isset($_POST['roll'])) {
    echo rollDice();
    exit;
}

// Include last user view (if needed)
include("LastUpdate.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interactive Dice Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        button {
            background-color: #007BFF; /* Blue */
            border: none;
            color: whitesmoke;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
        #result {
            margin-top: 20px;
            font-size: 1.2em;
        }
        .die {
            font-weight: bold;
            color: #ff6347; /* Tomato color */
        }
        .sum {
            font-weight: bold;
            color: #4682b4; /* Steel Blue color */
        }
    </style>
</head>
<body>
    <h1>Dice Game</h1>
    <button onclick="rollDice()">Roll the Dice</button>
    <div id="result"></div>

    <script>
        function rollDice() {
            console.log("Rolling dice..."); // Debugging log

            // Create an AJAX request to the server
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "roll_dice.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        console.log("Response received: " + xhr.responseText); // Debugging log
                        // Process the JSON response
                        const result = JSON.parse(xhr.responseText);
                        document.getElementById('result').innerHTML =
                            "First die result: <span class='die'>" + result.die1 + "</span><br>" +
                            "Second die result: <span class='die'>" + result.die2 + "</span><br>" +
                            "Sum of dice: <span class='sum'>" + result.sum + "</span>";
                    } else {
                        console.error("Error: " + xhr.status); // Debugging log
                        console.error("Response: " + xhr.responseText); // Debugging log
                    }
                }
            };

            // Send the request
            xhr.send("roll=1");
        }
    </script>
</body>
</html>
