<?php
session_start();

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

$filename = 'notes.txt';

if (!file_exists($filename)) {
    file_put_contents($filename, '');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['note']) && !empty(trim($_POST['note'])) && isset($_POST['token']) && hash_equals($_SESSION['token'], $_POST['token'])) {
        $note = trim($_POST['note']) . "\n";
        file_put_contents($filename, $note, FILE_APPEND);
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Regenerate token
    }
}

$notes = file_get_contents($filename);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes</title>
    <style>
        body {
            font-family: 'Cursive', Arial, sans-serif;
            background-color: #000;
            color: #ff69b4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 90%;
            height: 100px;
            font-size: 1em;
            padding: 10px;
            border: 2px solid #ff69b4;
            border-radius: 5px;
            background-color: #000;
            color: #ff69b4;
            margin-bottom: 10px;
        }
        button {
            font-size: 1em;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            background-color: #ff69b4;
            color: #000;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            background-color: #fff;
            color: #ff69b4;
            transform: scale(1.1);
            box-shadow: 0 0 15px #ff69b4;
        }
        pre {
            text-align: left;
            margin: 20px auto;
            width: 80%;
            background-color: #111;
            color: #ff69b4;
            padding: 10px;
            border-radius: 5px;
            overflow: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notes</h1>
        <form method="post">
            <textarea name="note" placeholder="Write your note here..." required></textarea>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <br>
            <button type="submit">Add Note</button>
        </form>
        <h2>Your Notes:</h2>
        <pre><?php echo htmlspecialchars($notes); ?></pre>
    </div>
</body>
</html>
