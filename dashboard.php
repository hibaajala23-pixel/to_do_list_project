<?php
session_start();
require "db.php";

/*
|--------------------------------------------------------------------------
| SECURITY CHECK
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| FETCH TASKS FROM DATABASE
|--------------------------------------------------------------------------
*/

$sql = "SELECT t.*, c.nom_categorie AS categorie_nom
        FROM tasks t
        LEFT JOIN categories c ON t.categorie_id = c.id
        WHERE t.user_id = :user_id
        ORDER BY t.created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':user_id' => $user_id
]);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>TaskFlow — Dashboard</title>

  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />

  <style>
    /* ===== TON CSS ORIGINAL (inchangé) ===== */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy-deep:#0a1e35;
      --navy-mid:#0e3460;
      --teal-accent:#1a7fbe;
      --white:#fff;
      --off-white:#f7f9fc;
      --text-dark:#0f1923;
      --text-muted:#8a96a3;
      --border:#e2e8ef;
      --error:#e04545;
      --input-bg:#f4f7fa;
    }

    body {
      font-family:'DM Sans';
      background:var(--off-white);
      display:flex;
      justify-content:center;
      padding:40px 20px;
    }

    .dashboard { width:100%; max-width:800px; }

    .header-actions {
      display:flex;
      justify-content:space-between;
      margin-bottom:24px;
      flex-wrap:wrap;
    }

    .btn-add {
      background:linear-gradient(135deg,var(--navy-mid),var(--teal-accent));
      color:#fff;
      padding:12px 20px;
      border-radius:10px;
      text-decoration:none;
    }

    .btn-logout {
      background:#fff5f5;
      color:var(--error);
      padding:12px 18px;
      border-radius:10px;
      text-decoration:none;
    }

    .tasks-list { display:flex; flex-direction:column; gap:16px; }

    .task-card {
      background:#fff;
      border:1px solid var(--border);
      padding:20px;
      border-radius:14px;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }

    .task-title { font-weight:700; font-family:'Sora'; }

    .badge-cat {
      background:var(--input-bg);
      padding:4px 10px;
      border-radius:20px;
      font-size:12px;
    }

    .priority-high { color:red; }
    .priority-medium { color:orange; }
    .priority-low { color:green; }

    .btn-delete {
      background:#fff5f5;
      border:1px solid #f8d7da;
      padding:6px 12px;
      cursor:pointer;
    }
  </style>
</head>

<body>

<div class="dashboard">

  <div class="header-actions">

    <div>
      <h2>My Tasks</h2>
      <p>Welcome <b><?= htmlspecialchars($userName) ?></b> 👋</p>
    </div>

    <div>
      <a href="create_task.php" class="btn-add">+ New Task</a>
      <a href="logout.php" class="btn-logout">Logout</a>
    </div>

  </div>

  <!-- SEARCH -->
  <input type="text" oninput="filtrerTaches()" id="searchInput" placeholder="Search tasks..." style="width:100%;padding:12px;margin-bottom:20px;border-radius:10px;border:1px solid #ccc;">

  <!-- TASKS -->
  <div class="tasks-list" id="tasksContainer">

    <?php foreach ($tasks as $task): ?>

      <div class="task-card" id="task-<?= $task['id'] ?>">

        <div>

          <h3 class="task-title">
            <?= htmlspecialchars($task['titre']) ?>
          </h3>

          <div>
            <span class="badge-cat">
              <?= htmlspecialchars($task['categorie_nom'] ?? 'No category') ?>
            </span>

            <span>
              <?= $task['date_limite'] ?>
            </span>
          </div>

          <div>
            <b class="
              <?php
                if ($task['priorite'] == 'Haute') echo 'priority-high';
                elseif ($task['priorite'] == 'Moyenne') echo 'priority-medium';
                else echo 'priority-low';
              ?>
            ">
              <?= $task['priorite'] ?>
            </b>
          </div>

        </div>

        <button class="btn-delete"
          onclick="deleteTask('task-<?= $task['id'] ?>')">
          Delete
        </button>

      </div>

    <?php endforeach; ?>

  </div>

</div>

<script>

/* SEARCH */
function filtrerTaches() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const cards = document.querySelectorAll('.task-card');

  cards.forEach(card => {
    const title = card.querySelector('.task-title').innerText.toLowerCase();
    card.style.display = title.includes(query) ? 'flex' : 'none';
  });
}

/* DELETE (front only) */
function deleteTask(id) {
  document.getElementById(id)?.remove();
}

</script>

</body>
</html>