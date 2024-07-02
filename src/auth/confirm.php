<?php
include '../database/connection.php';

session_start();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE confirmation_token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE users SET confirmed = 1, confirmation_token = NULL WHERE id = :id");
            $stmt->execute(['id' => $user['id']]);

            header("Location: ./login.php?lang=$lang&verified=true");
            exit();
        } else {
            header("Location: ./login.php?error=invalid_token&lang=$lang");
            exit();
        }
    } catch (PDOException $e) {
        error_log('Database Error: ' . $e->getMessage(), 0);
        header("Location: ./login.php?error=db_error&lang=$lang");
        exit();
    }
} else {
    header("Location: ./login.php?error=token_not_provided&lang=$lang");
    exit();
}
?>
