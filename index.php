<?php
declare(strict_types=1);
session_start();

/*
|--------------------------------------------------------------------------
| Mini PHP Challenge Page
|--------------------------------------------------------------------------
| Tek dosyalık bir challenge ekranı.
| Amaç: Kullanıcı adı için doğru challenge kodunu bulmak.
| Kod üretimi:
|   SHA256( username + secret + current_date )
| ve ilk 8 karakter bekleniyor.
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Europe/Istanbul');

$secret = 'SENIN_GIZLI_CHALLENGE_TUZUN_2026';
$today  = date('Y-m-d');

function clean(string $value): string
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

function generateChallengeCode(string $username, string $secret, string $date): string
{
    $base = strtolower(trim($username)) . '|' . $secret . '|' . $date;
    return strtoupper(substr(hash('sha256', $base), 0, 8));
}

function renderCard(string $title, string $content, string $type = 'default'): string
{
    $colors = [
        'default' => '#334155',
        'success' => '#16a34a',
        'error'   => '#dc2626',
        'info'    => '#2563eb',
        'warn'    => '#d97706',
    ];

    $border = $colors[$type] ?? $colors['default'];

    return "
        <div class='card' style='border-left: 5px solid {$border};'>
            <h3>{$title}</h3>
            <div>{$content}</div>
        </div>
    ";
}

$username = '';
$userCode = '';
$message  = '';
$hint     = '';

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action'] ?? 'check';
    $username = clean($_POST['username'] ?? '');
    $userCode = strtoupper(clean($_POST['usercode'] ?? ''));

    if ($username === '') {
        $message = renderCard('Hata', 'Lütfen bir kullanıcı adı gir.', 'error');
    } else {
        $realCode = generateChallengeCode($username, $secret, $today);

        if ($action === 'hint') {
            $_SESSION['attempts']++;
            $hint = "Kodun ilk 2 karakteri: <strong>" . substr($realCode, 0, 2) . "</strong><br>";
            $hint .= "Kod uzunluğu: <strong>" . strlen($realCode) . "</strong><br>";
            $hint .= "Bugünün tarihi challenge içine dahil edilir: <strong>{$today}</strong>";

            $message = renderCard('İpucu', $hint, 'info');
        }

        if ($action === 'check') {
            $_SESSION['attempts']++;

            if ($userCode === '') {
                $message = renderCard('Eksik Bilgi', 'Challenge kodunu girmedin.', 'warn');
            } elseif (!preg_match('/^[A-F0-9]{8}$/', $userCode)) {
                $message = renderCard('Geçersiz Format', 'Kod 8 karakter olmalı ve sadece A-F, 0-9 içermeli.', 'error');
            } elseif (hash_equals($realCode, $userCode)) {
                $message = renderCard(
                    'Başarılı 🎉',
                    "
                    <p>Tebrikler <strong>{$username}</strong>, challenge çözüldü.</p>
                    <p>Doğru kod: <strong>{$realCode}</strong></p>
                    <p>Toplam deneme: <strong>{$_SESSION['attempts']}</strong></p>
                    <hr>
                    <p>Bir sonraki geliştirme fikri:</p>
                    <ul>
                        <li>Leaderboard ekle</li>
                        <li>Session yerine database kullan</li>
                        <li>Zamana karşı yarış modu yap</li>
                    </ul>
                    ",
                    'success'
                );
            } else {
                $distance = 0;
                similar_text($realCode, $userCode, $similarity);

                $feedback = "Girdiğin kod yanlış.<br>";
                $feedback .= "Benzerlik oranı: <strong>" . number_format($similarity, 2) . "%</strong><br>";

                if (substr($realCode, 0, 1) === substr($userCode, 0, 1)) {
                    $feedback .= "İlk karakter doğru görünüyor.<br>";
                }

                if (substr($realCode, -1) === substr($userCode, -1)) {
                    $feedback .= "Son karakter doğru görünüyor.<br>";
                }

                $feedback .= "Toplam deneme: <strong>{$_SESSION['attempts']}</strong>";

                $message = renderCard('Yanlış Kod', $feedback, 'error');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Challenge</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #0f172a, #111827);
            color: #e5e7eb;
            min-height: 100vh;
        }

        .container {
            max-width: 760px;
            margin: 50px auto;
            padding: 24px;
        }

        .panel {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            padding: 28px;
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.35);
        }

        h1 {
            margin-top: 0;
            font-size: 32px;
            color: #f8fafc;
        }

        p {
            color: #cbd5e1;
            line-height: 1.6;
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            background: #1d4ed8;
            color: white;
            font-size: 13px;
            margin-bottom: 18px;
        }

        form {
            display: grid;
            gap: 14px;
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            color: #f1f5f9;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #334155;
            background: #0f172a;
            color: #f8fafc;
            outline: none;
            transition: 0.2s ease;
        }

        input[type="text"]:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.2);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        button {
            border: none;
            border-radius: 12px;
            padding: 13px 18px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.2s ease;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #334155;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .card {
            margin-top: 22px;
            background: rgba(255,255,255,0.04);
            padding: 18px;
            border-radius: 14px;
        }

        .card h3 {
            margin-top: 0;
            color: #f8fafc;
        }

        .footer {
            margin-top: 18px;
            font-size: 13px;
            color: #94a3b8;
        }

        code {
            background: rgba(255,255,255,0.08);
            padding: 3px 7px;
            border-radius: 6px;
        }

        .stats {
            margin-top: 18px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 12px 16px;
            min-width: 150px;
        }

        .stat-box strong {
            display: block;
            font-size: 20px;
            color: #fff;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="panel">
            <div class="badge">Mini CTF Style PHP Challenge</div>
            <h1>Challenge Sayfası</h1>
            <p>
                Kullanıcı adına göre günlük bir challenge kodu üretilir.
                Amaç, doğru <code>8 karakterlik</code> kodu bulmak.
            </p>

            <div class="stats">
                <div class="stat-box">
                    Bugün
                    <strong><?= htmlspecialchars($today) ?></strong>
                </div>
                <div class="stat-box">
                    Deneme
                    <strong><?= (int)$_SESSION['attempts'] ?></strong>
                </div>
                <div class="stat-box">
                    Format
                    <strong>A-F0-9</strong>
                </div>
            </div>

            <form method="POST" action="">
                <div>
                    <label for="username">Kullanıcı Adı</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="örnek: phantomtrader"
                        value="<?= htmlspecialchars($username) ?>"
                        maxlength="50"
                        required
                    >
                </div>

                <div>
                    <label for="usercode">Challenge Kodu</label>
                    <input
                        type="text"
                        id="usercode"
                        name="usercode"
                        placeholder="örnek: A1B2C3D4"
                        value="<?= htmlspecialchars($userCode) ?>"
                        maxlength="8"
                    >
                </div>

                <div class="actions">
                    <button class="btn-primary" type="submit" name="action" value="check">Kodu Kontrol Et</button>
                    <button class="btn-secondary" type="submit" name="action" value="hint">İpucu Ver</button>
                </div>
            </form>

            <?= $message ?>

            <div class="footer">
                İpucu: Bu challenge mantığını zorlaştırmak için JS obfuscation, rate limit, skor tablosu,
                sqlite kayıtları ve admin paneli ekleyebilirsin.
            </div>
        </div>
    </div>
</body>
</html>
