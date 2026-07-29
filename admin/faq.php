<?php
session_start();
require_once __DIR__ . '/../app/db.php';

// proteksi login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   TAMBAH FAQ
========================= */
if (isset($_POST['add'])) {
    $question = trim($_POST['question']);
    $answer   = trim($_POST['answer']);

    if ($question && $answer) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
        $stmt->execute([$question, $answer]);
        header("Location: faq.php");
        exit;
    }
}

/* =========================
   UPDATE FAQ
========================= */
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $question = trim($_POST['question']);
    $answer   = trim($_POST['answer']);

    if ($question && $answer) {
        $stmt = $pdo->prepare("UPDATE faqs SET question=?, answer=? WHERE id=?");
        $stmt->execute([$question, $answer, $id]);
        header("Location: faq.php");
        exit;
    }
}

/* =========================
   DELETE FAQ
========================= */
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header("Location: faq.php");
    exit;
}

/* =========================
   AMBIL DATA
========================= */
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY id DESC")->fetchAll();

/* =========================
   EDIT MODE
========================= */
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin - FAQ</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --base: #F5E8D8;
            --primary: #F1E3C6;
            --soft: #E8D8C4;
            --text: #3F2E1F;
        }

        body {
            background: var(--base);
            color: var(--text);
        }
    </style>
</head>

<body>

<div class="container mx-auto px-4 py-10 max-w-5xl">

    <h1 class="text-2xl font-bold mb-6">Manage FAQ</h1>

    <!-- KEMBALI -->
    <div class="mb-4">
        <a href="dashboard.php"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
           bg-[var(--soft)] hover:bg-[var(--primary)]
           font-medium transition">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <form method="POST" class="space-y-4">
            <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

            <div>
                <label class="font-medium">Pertanyaan</label>
                <input
                    type="text"
                    name="question"
                    required
                    placeholder="Masukkan pertanyaan"
                    value="<?= htmlspecialchars($editData['question'] ?? '') ?>"
                    class="w-full border rounded-lg px-4 py-2 mt-1">
            </div>

            <div>
                <label class="font-medium">Jawaban</label>
                <textarea
                    name="answer"
                    rows="4"
                    required
                    placeholder="Masukkan jawaban"
                    class="w-full border rounded-lg px-4 py-2 mt-1"><?= htmlspecialchars($editData['answer'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-3">
                <button
                    name="<?= $editData ? 'update' : 'add' ?>"
                    class="px-6 py-2 rounded-lg bg-[var(--primary)] font-semibold hover:opacity-90">
                    <?= $editData ? 'Update' : 'Add' ?>
                </button>

                <?php if ($editData): ?>
                    <a href="faq.php" class="px-4 py-2 border rounded-lg">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-[var(--soft)]">
                <tr>
                    <th class="p-4 text-left">#</th>
                    <th class="p-4 text-left">Question</th>
                    <th class="p-4 text-left">Answer</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($faqs as $i => $f): ?>
                <tr class="border-t align-top">
                    <td class="p-4"><?= $i + 1 ?></td>
                    <td class="p-4 font-medium"><?= htmlspecialchars($f['question']) ?></td>
                    <td class="p-4"><?= nl2br(htmlspecialchars($f['answer'])) ?></td>
                    <td class="p-4 text-center space-x-2 whitespace-nowrap">
                        <a href="?edit=<?= $f['id'] ?>"
                           class="px-3 py-1 bg-yellow-400 rounded text-sm">Edit</a>

                        <a href="?delete=<?= $f['id'] ?>"
                           onclick="return confirm('Hapus FAQ ini?')"
                           class="px-3 py-1 bg-red-500 text-white rounded text-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
