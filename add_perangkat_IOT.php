<?php
session_start();
require_once 'config.php';

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['userRole'] ?? null;

if (!$userId || $userRole !== 'Admin') {
    die("Unauthorized: You must be an administrator to add IoT devices.");
}

$message = '';
$messageType = '';

if (isset($_POST['add_device'])) {
    $serialNum = $_POST['serialNum'] ?? '';
    $noUnit = $_POST['noUnit'] ?? '';

    if (empty($serialNum) || empty($noUnit)) {
        $message = "Serial Number and Unit Number cannot be empty.";
        $messageType = 'error';
    } else {
        //Membedakan apakah Sensor (SN1) or Aktuator (SN2)
        $isSensor = str_starts_with($serialNum, 'SN1');
        $isAktuator = str_starts_with($serialNum, 'SN2');

        if (!$isSensor && !$isAktuator) {
            $message = "Serial Number harus diawali dengan 'SN1' untuk Sensor atau 'SN2' untuk Aktuator.";
            $messageType = 'error';
        } else {
            //Start a transaction
            sqlsrv_begin_transaction($conn);

            try {
                //Insert PerangkatIOT table
                $sql_perangkat_iot = "INSERT INTO PerangkatIOT (serialNum, noUnit, tglPasang) VALUES (?, ?, GETDATE())";
                $params_perangkat_iot = array($serialNum, $noUnit);
                $stmt_perangkat_iot = sqlsrv_query($conn, $sql_perangkat_iot, $params_perangkat_iot);

                if ($stmt_perangkat_iot === false) {
                    throw new Exception("SQL Server Error during PerangkatIOT insertion.");
                }

                //Conditional insert Sensor / Aktuator table
                if ($isSensor) {
                    $sql_sensor = "INSERT INTO Sensor (serialNumSen) VALUES (?)";
                    $params_sensor = array($serialNum);
                    $stmt_sensor = sqlsrv_query($conn, $sql_sensor, $params_sensor);

                    if ($stmt_sensor === false) {
                        throw new Exception(print_r(sqlsrv_errors(), true));
                    }
                } elseif ($isAktuator) {
                    $sql_aktuator = "INSERT INTO Aktuator (serialNumAkt, statusAkt) VALUES (?, 'OFF')";
                    $params_aktuator = array($serialNum);
                    $stmt_aktuator = sqlsrv_query($conn, $sql_aktuator, $params_aktuator);

                    if ($stmt_aktuator === false) {
                        throw new Exception(print_r(sqlsrv_errors(), true));
                    }
                }

                //If all inserts are successful, commit the transaction
                sqlsrv_commit($conn);
                $message = "Perangkat IoT '{$serialNum}' berhasil ditambahkan ke unit '{$noUnit}' sebagai " . ($isSensor ? "Sensor" : "Aktuator") . ".";
                $messageType = 'success';
                //Clear form fields on success
                $serialNum = '';
                $noUnit = '';

            } catch (Exception $e) {
                //If any error occurred, rollback the transaction
                sqlsrv_rollback($conn);
                $errors = sqlsrv_errors();
                $message = "Error adding device: Terjadi kesalahan dalam input. Silakan coba lagi.";
                $messageType = 'error';
            }
        }
    }
} else {
    $serialNum = '';
    $noUnit = '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tambah Perangkat IoT Baru</title>
    <link href="https://fonts.googleapis.com/css?family=Offside&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Offside', cursive;
            background-color: rgb(208, 208, 208);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgb(1, 138, 56);
        }
        .header h2 {
            color: rgb(1, 138, 56);
            margin: 0;
            font-size: 1.8rem;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            font-family: 'Offside', cursive;
            transition: background-color 0.3s ease, filter 0.3s ease;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-submit {
            background-color: rgb(1, 138, 56);
            color: white;
        }
        .btn-submit:hover {
            filter: brightness(1.1);
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .btn-cancel:hover {
            filter: brightness(1.1);
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Tambah Perangkat IoT</h2>
            </div>

        <?php if (!empty($message)): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="serialNum">Serial Number:</label>
                <input type="text" id="serialNum" name="serialNum" value="<?= htmlspecialchars($serialNum) ?>" required>
            </div>

            <div class="form-group">
                <label for="noUnit">No Unit:</label>
                <input type="text" id="noUnit" name="noUnit" value="<?= htmlspecialchars($noUnit) ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" name="add_device" class="btn btn-submit">Tambah Perangkat</button>
                <button type="button" class="btn btn-cancel" onclick="window.location.href='edit_perangkat_IOT.php'">Batal</button>
            </div>
        </form>
    </div>
</body>
</html>