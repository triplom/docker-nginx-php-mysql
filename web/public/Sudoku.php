<?php

session_start();

// Initialize Sudoku grid
if (!isset($_SESSION['sudoku'])) {
    $_SESSION['sudoku'] = array(
        array(5, 3, 0, 0, 7, 0, 0, 0, 0),
        array(6, 0, 0, 1, 9, 5, 0, 0, 0),
        array(0, 9, 8, 0, 0, 0, 0, 6, 0),
        array(8, 0, 0, 0, 6, 0, 0, 0, 3),
        array(4, 0, 0, 8, 0, 3, 0, 0, 1),
        array(7, 0, 0, 0, 2, 0, 0, 0, 6),
        array(0, 6, 0, 0, 0, 0, 2, 8, 0),
        array(0, 0, 0, 4, 1, 9, 0, 0, 5),
        array(0, 0, 0, 0, 8, 0, 0, 7, 9)
    );
}

// Track number of attempts
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $row = $_POST['row'];
        $col = $_POST['col'];
        $num = $_POST['number'];

        // Validate input (only accept numbers 1-9)
        if ($num >= 1 && $num <= 9) {
            // Check if the number is valid in the Sudoku grid
            if (isValid($_SESSION['sudoku'], $row, $col, $num)) {
                $_SESSION['sudoku'][$row][$col] = $num;
            } else {
                // Invalid move
                echo '<script>alert("Invalid number for this cell!")</script>';
            }
            $_SESSION['attempts']++;
        }
    } elseif (isset($_POST['check_solution'])) {
        // Check if the puzzle is solved correctly
        if (isSudokuSolved($_SESSION['sudoku'])) {
            echo '<script>alert("Congratulations! Puzzle solved.")</script>';
        } else {
            echo '<script>alert("Not all cells are filled correctly. Keep trying!")</script>';
        }
    }
}

// Function to check if the Sudoku puzzle is solved
function isSudokuSolved($sudoku) {
    foreach ($sudoku as $row) {
        foreach ($row as $cell) {
            if ($cell == 0) {
                return false; // There are still empty cells
            }
        }
    }
    return true; // All cells are filled
}

// Function to validate if a number can be placed at a given position
function isValid($sudoku, $row, $col, $num) {
    // Check row
    for ($i = 0; $i < 9; $i++) {
        if ($sudoku[$row][$i] == $num) {
            return false;
        }
    }
    // Check column
    for ($i = 0; $i < 9; $i++) {
        if ($sudoku[$i][$col] == $num) {
            return false;
        }
    }
    // Check box
    $box_row = floor($row / 3) * 3;
    $box_col = floor($col / 3) * 3;
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($sudoku[$box_row + $i][$box_col + $j] == $num) {
                return false;
            }
        }
    }
    return true;
}

// Include last user view (if needed)
include("LastUpdate.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="Sudoku" href="favicon.png" type="image/x-icon"/>
    <title>Sudoku Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            margin: 20px auto;
        }
        td {
            border: 1px solid #666;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #333;
            background-color: #fff;
        }
        .input-cell {
            background-color: #f0f0f0;
        }
        input[type="number"] {
            width: 25px;
            height: 25px;
            font-size: 14px;
            text-align: center;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        input[name="submit"] {
            background-color: #ffc107; /* Yellow */
            color: #fff;
        }
        input[name="check_solution"] {
            background-color: #f44336; /* Red */
            color: #fff;
        }
        input[type="submit"]:hover {
            opacity: 0.8;
        }
        p {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>Sudoku Game</h2>
    <form method="post">
        <table>
            <?php for ($i = 0; $i < 9; $i++) : ?>
                <tr>
                    <?php for ($j = 0; $j < 9; $j++) : ?>
                        <?php if ($_SESSION['sudoku'][$i][$j] == 0) : ?>
                            <td class="input-cell">
                                <input type="number" name="number" min="1" max="9" required>
                                <input type="hidden" name="row" value="<?= $i ?>">
                                <input type="hidden" name="col" value="<?= $j ?>">
                            </td>
                        <?php else : ?>
                            <td><?= $_SESSION['sudoku'][$i][$j] ?></td>
                        <?php endif; ?>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <input type="submit" name="submit" value="Submit Number">
        <input type="submit" name="check_solution" value="Check Solution">
    </form>
    <p>Attempts made: <?= $_SESSION['attempts'] ?></p>
</body>
</html>