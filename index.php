<?php
session_start();

$title = "PHP Quiz";
$questions = [
    [
        "question" => "PHP is primarily used for?",
        "option" => ["Web Development", "Mobile App Development", "Game Development"],
        "answer" => 0
    ],

    [
        "question" => "PHP works on which side of a web application?",
        "option" => [" Client-side", "Both sides ", "Server-side"],
        "answer" => 2
    ],

    [
        "question" => "Which PHP feature allows it to interact with databases?",
        "option" => [" DOMDocument", "PDO (PHP Data Objects)", "CSS styling"],
        "answer" => 1
    ]
];

if (!isset($_SESSION['leaderboard'])) {
    $_SESSION['leaderboard'] = [];
}

$score = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST['username']);
        $_SESSION['username'] = $username;
        $_SESSION['quiz_started'] = true; 
    }

    if (isset($_POST['submit_quiz']) && isset($_SESSION['username'])) {
        foreach ($questions as $index => $question) {
            if (isset($_POST['question' . $index]) && $_POST['question' . $index] == $question['answer']) {
                $score++;
            }
        }

        $username = $_SESSION['username'];
        $_SESSION['leaderboard'][] = [
            'name' => $username,
            'score' => $score
        ];

        echo "<h2>Your score: $score/" . count($questions) . "</h2>";
        echo "<a href='index.php'>Try Again?</a>";

        echo "<h2>Leaderboard:</h2>";
        echo "<ol>";
        foreach ($_SESSION['leaderboard'] as $entry) {
            echo "<li>" . htmlspecialchars($entry['name']) . ": " . $entry['score'] . "</li>";
        }
        echo "</ol>";

        unset($_SESSION['username']);
        unset($_SESSION['quiz_started']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<body>
    <?php if (!isset($_SESSION['quiz_started'])) : ?>
        <form action="" method="post">
            <h1>Sign In</h1>
            <p>Type your name:</p>
            <input type="text" name="username" required>
            <input type="submit" value="Start Quiz">
        </form>
    <?php else : ?>
        <h1><?php echo $title; ?></h1>
        <form action="" method="post">
            <?php foreach ($questions as $index => $question) : ?>
                <fieldset>
                    <legend><?php echo $question['question']; ?></legend>
                    <?php foreach ($question['option'] as $optionIndex => $option) : ?>
                        <label>
                            <input type="radio" name="question<?php echo $index; ?>" value="<?php echo $optionIndex; ?>" required>
                            <?php echo $option; ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>
            <input type="submit" name="submit_quiz" value="Submit Quiz">
        </form>
    <?php endif; ?>
</body>
</html>

