<?php

require 'db.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sql = "
SELECT tasks.*, users.email
FROM tasks
JOIN users ON tasks.user_id = users.id
WHERE date_limite = CURDATE()
AND reminder_sent = 0
";

$stmt = $pdo->query($sql);

$tasks = $stmt->fetchAll();

foreach($tasks as $task){

    $mail = new PHPMailer(true);

    try{

        // SMTP CONFIG
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // TON EMAIL
        $mail->Username = 'h.chrorou@gmail.com';

        // APP PASSWORD
        $mail->Password = 'nphx bouz qryd hakj';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // EXPEDITEUR
        $mail->setFrom('h.chrorou@gmail.com', 'TaskFlow');

        // DESTINATAIRE
        $mail->addAddress($task['email']);

        // EMAIL
        $mail->isHTML(true);

        $mail->Subject = 'Task Reminder';

        $mail->Body = "
            <h2>Task Reminder ⏰</h2>

            <p>
                Your task 
                <strong>{$task['titre']}</strong>
                is due today.
            </p>
        ";

        // ENVOI
        $mail->send();

        // UPDATE reminder_sent
        $update = $pdo->prepare("
            UPDATE tasks
            SET reminder_sent = 1
            WHERE id = ?
        ");

        $update->execute([$task['id']]);

        echo "Reminder sent for task: " . $task['titre'] . "<br>";

    } catch(Exception $e){

        echo "Error sending email: " . $mail->ErrorInfo . "<br>";
    }
}
?>