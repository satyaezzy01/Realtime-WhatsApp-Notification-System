<?php
$host = "localhost";
$user = "root";  // Ganti jika berbeda
$pass = "";      // Ganti jika ada password
$db = "notifwa"; // Nama database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Tambah data ke database & kirim ke WhatsApp
if (isset($_POST['nama']) && isset($_POST['nilai'])) {
    $nama = $_POST['nama'];
    $nilai = $_POST['nilai'];

    $sql = "INSERT INTO data_realtime (nama, nilai) VALUES ('$nama', '$nilai')";
    if ($conn->query($sql) === TRUE) {
        // Kirim pesan ke WhatsApp via CallMeBot API
        $phone = "62**********"; // Ganti dengan nomor tujuan
        $apikey = "rename"; // API Key dari CallMeBot

        $message = urlencode("🔔 Data Baru Masuk!\nNama: $nama\nNilai: $nilai");
        $url = "https://api.callmebot.com/whatsapp.php?phone=$phone&text=$message&apikey=$apikey";
        
        // Kirim pesan ke WhatsApp
        file_get_contents($url);

        echo "<script>alert('Data berhasil ditambahkan & dikirim ke WhatsApp!');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}

// Ambil data dari database
$sql = "SELECT * FROM data_realtime ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime WhatsApp</title>
</head>
<body>
    <h2>Tambah Data & Kirim ke WhatsApp</h2>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="number" name="nilai" placeholder="Nilai" required>
        <button type="submit">Kirim</button>
    </form>

    <h3>📌 Data Realtime</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['nilai'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
