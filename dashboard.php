<?php
session_start();

require "db.php";

if (
    !isset($_SESSION['user_name']) ||
    !isset($_SESSION['user_id'])
) {

    header("Location: hibaindex.php");
    exit();
}

$userName = $_SESSION['user_name'];
$user_id  = $_SESSION['user_id'];

$sql = "SELECT t.*, c.nom_categorie AS categorie_nom
        FROM tasks t
        LEFT JOIN categories c
        ON t.categorie_id = c.id
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

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>TaskFlow — Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
/* ================= MOBILE RESPONSIVE ================= */

@media (max-width:768px){

    body{
        padding:20px 12px;
    }

    .dashboard{
        width:100%;
    }

    .header-actions{
        flex-direction:column;
        align-items:flex-start;
    }

    .buttons{
        width:100%;
        flex-direction:column;
    }

    .btn-add,
    .btn-logout{
        width:100%;
        text-align:center;
    }

    .task-card{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .task-actions{
        width:100%;
        display:flex;
        justify-content:space-between;
    }

    .btn-edit,
    .btn-delete{
        flex:1;
        text-align:center;
    }

    .task-meta{
        gap:8px;
    }

    .search-input{
        font-size:13px;
    }

    select.search-input{
        margin-bottom:0;
    }

}

/* ================= SMALL MOBILE ================= */

@media (max-width:480px){

    .header-actions h2{
        font-size:24px;
    }

    .task-title{
        font-size:16px;
    }

    .task-description{
        font-size:13px;
    }

    .task-card{
        padding:16px;
    }

}
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

:root{
    --navy-deep:#0a1e35;
    --navy-mid:#0e3460;
    --teal-accent:#1a7fbe;
    --white:#ffffff;
    --off-white:#f7f9fc;
    --text-dark:#0f1923;
    --text-muted:#8a96a3;
    --border:#e2e8ef;
    --error:#e04545;
    --input-bg:#f4f7fa;
}

body{
    font-family:'DM Sans',sans-serif;
    background:var(--off-white);
    padding:40px 20px;
    display:flex;
    justify-content:center;
}

.dashboard{
    width:100%;
    max-width:850px;
}

.header-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
    flex-wrap:wrap;
    gap:20px;
}

.header-actions h2{
    font-family:'Sora';
    font-size:30px;
    color:var(--navy-deep);
}

.welcome{
    margin-top:5px;
    color:var(--text-muted);
}

.welcome span{
    color:var(--teal-accent);
    font-weight:700;
}

.buttons{
    display:flex;
    gap:10px;
}

.btn-add{
    background:linear-gradient(135deg,var(--navy-mid),var(--teal-accent));
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    font-weight:700;
}

.btn-logout{
    background:#fff5f5;
    color:var(--error);
    text-decoration:none;
    padding:12px 18px;
    border-radius:10px;
    border:1px solid #f8d7da;
    font-weight:700;
}

.search-input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid var(--border);
    margin-bottom:25px;
    font-size:14px;
}

.tasks-list{
    display:flex;
    flex-direction:column;
    gap:16px;
}

.task-card{
    background:white;
    border:1px solid var(--border);
    border-radius:14px;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.task-title{
    font-family:'Sora';
    font-size:18px;
    margin-bottom:8px;
    color:var(--navy-deep);
}

.task-meta{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}

.badge-cat{
    background:var(--input-bg);
    color:var(--navy-mid);
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:700;
}

.priority-high{
    color:red;
    font-weight:700;
}

.priority-medium{
    color:orange;
    font-weight:700;
}

.priority-low{
    color:green;
    font-weight:700;
}

.date{
    font-size:13px;
    color:var(--text-muted);
}

/* ACTION BUTTONS */

.task-actions{
    display:flex;
    gap:10px;
    align-items:center;
}

.btn-edit{
    background:#eef6ff;
    color:var(--teal-accent);
    border:1px solid #cfe8ff;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-weight:700;
    transition:0.3s;
}

.btn-edit:hover{
    background:var(--teal-accent);
    color:white;
}

.btn-delete{
    background:#fff5f5;
    color:var(--error);
    border:1px solid #f8d7da;
    padding:8px 14px;
    border-radius:8px;
    cursor:pointer;
    font-weight:700;
    text-decoration:none;
    transition:0.3s;
}

.btn-delete:hover{
    background:var(--error);
    color:white;
}

.no-task{
    text-align:center;
    padding:30px;
    background:white;
    border-radius:12px;
    color:var(--text-muted);
    border:1px dashed var(--border);
}
/* TASK COMPLETED */
.task-card.completed{
    opacity:0.7;
    background:#fff0f0;
    border-color:#f5b5b5;
}

.task-card.completed .task-title{
    text-decoration: line-through;
    color:var(--error);
}
.task-card.completed .date{
    color: var(--error);
  }
.checkbox-complete{
    width:18px;
    height:18px;
    accent-color:var(--error);
    cursor:pointer;
}
.task-description{
    margin-top:6px;
    color:var(--text-muted);
    font-size:14px;
    line-height:1.5;
}
</style>
</head>

<body>

<div class="dashboard">

    <div class="header-actions">

        <div>

            <h2>My Tasks</h2>

            <p class="welcome">
                Welcome
                <span>
                    <?= htmlspecialchars($userName) ?>
                </span>
                👋
            </p>

        </div>

        <div class="buttons">

            <a href="create_task.html" class="btn-add">
                + New Task
            </a>

            <a href="logout.php" class="btn-logout">
                Logout
            </a>

        </div>

    </div>

    <input
        type="text"
        id="searchInput"
        class="search-input"
        placeholder="🔍 Search tasks..."
        oninput="filtrerTaches()"
    >
      <div style="display:flex; gap:15px; margin-bottom:25px;">

    <select id="categoryFilter"
            class="search-input"
            onchange="filtrerTaches()">

        <option value="">📁 All Categories</option>
        <option value="Work">📝 Work</option>
        <option value="Personal">🏠 Personal</option>
        <option value="Goals">🎯 Goals</option>
        <option value="Shopping">🛒 Shopping</option>

    </select>

    <select id="priorityFilter"
            class="search-input"
            onchange="filtrerTaches()">

        <option value="">🚦 All Priorities</option>
        <option value="Haute">🔴 High</option>
        <option value="Moyenne">🟡 Medium</option>
        <option value="Faible">🟢 Low</option>

    </select>

</div>
    <?php if(count($tasks) > 0): ?>

    <div class="tasks-list" id="tasksContainer">
<div id="noResult" class="no-task" style="display:none;">
    ❌ No task found.
</div>
        <?php foreach($tasks as $task): ?>

       <div class="task-card <?= $task['is_done'] == 1 ? 'completed' : '' ?>"
       data-category="<?=
        $task['categorie_id'] == 1 ? 'Work' :
        ($task['categorie_id'] == 2 ? 'Personal' :
        ($task['categorie_id'] == 3 ? 'Goals' : 'Shopping'))
     ?>"

     data-priority="<?= htmlspecialchars($task['priorite']) ?>">

    <input type="checkbox" class="checkbox-complete" onchange="toggleTask(this, <?= $task['id'] ?>)" <?= $task['is_done'] == 1 ? 'checked' : '' ?>>

            <div style="display:flex; gap:4px; align-items:flex-start;">  
                <h3 class="task-title">
                    <?= htmlspecialchars($task['titre']) ?>
                </h3>
                <p class="task-description">
                <?= htmlspecialchars($task['description']) ?>
                </p>
                <div class="task-meta">

<span class="badge-cat">

<?php
    switch($task['categorie_id']){

        case 1:
            echo '📝 Work';
            break;

        case 2:
            echo '🏠 Personal';
            break;

        case 3:
            echo '🎯 Goals';
            break;

        case 4:
            echo '🛒 Shopping';
            break;

        default:
            echo '📁 Unknown';
    }
?>

</span>

                    <span class="date">
                        📅 <?= htmlspecialchars($task['date_limite']) ?>
                    </span>

<span class="
<?php
    if($task['priorite'] == 'Haute'){
        echo 'priority-high';
    }
    elseif($task['priorite'] == 'Moyenne'){
        echo 'priority-medium';
    }
    else{
        echo 'priority-low';
    }
?>
">


<?php
    if($task['priorite'] == 'Haute'){
        echo '🔴';
    }
    elseif($task['priorite'] == 'Moyenne'){
        echo '🟡';
    }
    else{
        echo '🟢';
    }
?>

</span>

                </div>

            </div>

            <!-- ACTION BUTTONS -->

            <div class="task-actions">

                <a 
                    href="edit_task.php?id=<?= $task['id'] ?>" 
                    class="btn-edit"
                >
                    Edit
                </a>

                <a 
                    href="delete_task.php?id=<?= $task['id'] ?>" 
                    class="btn-delete"
                >
                    Delete
                </a>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php else: ?>

    <div id="noResult" class="no-task" style="display:none;">
         ❌ No tasks found.
    </div>

    <?php endif; ?>

</div>

<script>

function filtrerTaches(){

    const query = document
        .getElementById('searchInput')
        .value
        .toLowerCase();

    const selectedCategory =
        document.getElementById('categoryFilter').value;

    const selectedPriority =
        document.getElementById('priorityFilter').value;

    const cards = document.querySelectorAll('.task-card');

    let visibleCount = 0;

    cards.forEach(card => {

        const title = card
            .querySelector('.task-title')
            .innerText
            .toLowerCase();

        const category = card.dataset.category;

        const priority = card.dataset.priority;

        const matchSearch =
            title.includes(query);

        const matchCategory =
            selectedCategory === "" ||
            category === selectedCategory;

        const matchPriority =
            selectedPriority === "" ||
            priority === selectedPriority;

        if(
            matchSearch &&
            matchCategory &&
            matchPriority
        ){

            card.style.display = 'flex';
            visibleCount++;

        } else {

            card.style.display = 'none';
        }

    });

    const noResult =
        document.getElementById('noResult');

    if(visibleCount === 0){

        noResult.style.display = 'block';

    } else {

        noResult.style.display = 'none';
    }
}
function toggleTask(checkbox){

    const card = checkbox.closest('.task-card');

    if(checkbox.checked){
        card.classList.add('completed');
    } else {
        card.classList.remove('completed');
    }

}
</script>
<script>
function toggleTask(checkbox, taskId){

    const isDone = checkbox.checked ? 1 : 0;

    const card = checkbox.closest('.task-card');

    // effet visuel
    if(isDone){
        card.classList.add('completed');
    } else {
        card.classList.remove('completed');
    }

    // envoi vers PHP
    fetch('update_task_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${taskId}&is_done=${isDone}`
    });
}
</script>
</body>
</html> 