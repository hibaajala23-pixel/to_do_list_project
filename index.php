<?php
require_once __DIR__ . '/includes/session.php';

// Si déjà connecté → rediriger directement au dashboard
if (isLoggedIn()) {
    header('Location: /taskflow/dashboard/index.php');
    exit;
}

// Génère le token CSRF dès le chargement de la page
$csrfToken = csrf_token();

// Récupérer erreurs et anciennes valeurs après redirect
$regErrors  = $_SESSION['reg_error'] ?? [];
$regOld     = $_SESSION['reg_old']   ?? [];
$showReg    = isset($_GET['view']) && $_GET['view'] === 'register';
unset($_SESSION['reg_error'], $_SESSION['reg_old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=1440" />
<title>TaskFlow — Authentication</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
  .page { display: flex; width: 1440px; min-height: 100vh; margin: 0 auto; }

  /* ── Panneau gauche ── */
  .panel-left { flex: 0 0 780px; background: linear-gradient(145deg, #003354 0%, #005a92 35%, #00a3e0 100%); padding: 64px 72px; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden; }
  .panel-left::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(ellipse 80% 60% at 20% 10%, rgba(255,255,255,0.1) 0%, transparent 60%), radial-gradient(ellipse 60% 80% at 80% 80%, rgba(0,0,0,0.2) 0%, transparent 60%); pointer-events: none; }
  .left-content { position: relative; z-index: 1; }
  .brand { display: flex; align-items: center; gap: 14px; margin-bottom: 40px; }
  .brand-logo-img { width: 52px; height: 52px; border-radius: 12px; object-fit: cover; background: #fff; padding: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
  .brand-name { font-size: 26px; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
  .headline { font-size: 42px; font-weight: 800; color: #fff; line-height: 1.18; letter-spacing: -1px; max-width: 520px; }

  /* ── Panneau droit ── */
  .panel-right { flex: 1; background: #fff; display: flex; align-items: center; justify-content: center; padding: 60px 72px; }
  .form-box { width: 100%; max-width: 440px; }
  .form-title { font-size: 32px; font-weight: 700; color: #111827; letter-spacing: -0.6px; margin-bottom: 8px; }
  .form-sub { font-size: 15px; color: #6b7280; font-weight: 400; margin-bottom: 36px; line-height: 1.5; }

  /* ── Bouton social ── */
  .btn-social { width: 100%; height: 52px; background: #fff; border: 1.5px solid #e5e7eb; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 12px; font-family: 'Inter', sans-serif; font-size: 15px; font-weight: 500; color: #111827; cursor: pointer; transition: background 0.15s, border-color 0.15s; margin-bottom: 14px; }
  .btn-social:hover { background: #f9fafb; border-color: #d1d5db; }
  .divider { display: flex; align-items: center; gap: 14px; margin: 28px 0; }
  .divider-line { flex: 1; height: 1px; background: #e5e7eb; }
  .divider-text { font-size: 13px; font-weight: 400; color: #9ca3af; }

  /* ── Champs ── */
  .field { margin-bottom: 20px; }
  .field-label { display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px; }
  .input-wrap { position: relative; }
  .input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9ca3af; display: flex; align-items: center; }
  .field input { width: 100%; height: 52px; padding: 0 16px 0 46px; font-family: 'Inter', sans-serif; font-size: 15px; color: #111827; border: 1.5px solid #e5e7eb; border-radius: 10px; outline: none; transition: 0.2s; }
  .field input:focus { border-color: #005a92; box-shadow: 0 0 0 3px rgba(0,90,146,0.1); }
  .field input.input-error { border-color: #dc2626; }

  /* ── Bouton principal ── */
  .btn-primary { width: 100%; height: 52px; background: #005a92; color: #fff; font-size: 16px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer; transition: 0.18s; margin-bottom: 28px; font-family: 'Inter', sans-serif; }
  .btn-primary:hover { background: #003354; }
  .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

  .form-footer { text-align: center; font-size: 14px; color: #6b7280; }
  .form-footer a { color: #005a92; font-weight: 500; text-decoration: none; cursor: pointer; }
  .form-footer a:hover { text-decoration: underline; }

  /* ── Barre de force mot de passe ── */
  .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
  .strength-seg { flex: 1; height: 4px; border-radius: 2px; background: #e5e7eb; transition: background 0.3s; }

  /* ── Alertes ── */
  .alert { padding: 14px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; display: none; }
  .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }
  .alert-error   { background: #fef2f2; border: 1px solid #fca5a5; color: #dc2626; }

  .field-error { color: #dc2626; font-size: 13px; margin-top: 6px; display: none; }

  #register-view { display: none; }
</style>
</head>
<body>
<div class="page">

  <!-- ── Panneau gauche ── -->
  <aside class="panel-left">
    <div class="left-content">
      <div class="brand">
        <img src="logo_taskflow.jpeg" alt="TaskFlow Logo" class="brand-logo-img">
        <span class="brand-name">TaskFlow</span>
      </div>
      <h1 class="headline">Organize your work,<br/>amplify your productivity</h1>
    </div>
  </aside>

  <!-- ── Panneau droit ── -->
  <main class="panel-right">
    <div class="form-box">

      <!-- ════════ VUE CONNEXION ════════ -->
      <div id="login-view">
        <h2 class="form-title">Welcome back</h2>
        <p class="form-sub">Please enter your details to sign in</p>

        <div id="loginAlert" class="alert"></div>

        <a href="/taskflow/auth/google_login.php" class="btn-social" style="text-decoration:none;">
          <svg width="20" height="20" viewBox="0 0 18 18" fill="none"><path d="M17.64 9.2c0-.638-.057-1.252-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908C16.658 14.075 17.64 11.767 17.64 9.2z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/><path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
          Continue with Google
        </a>

        <div class="divider">
          <div class="divider-line"></div>
          <span class="divider-text">Or continue with email</span>
          <div class="divider-line"></div>
        </div>

        <form id="loginForm">
          <!-- Token CSRF injecté par PHP -->
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

          <div class="field">
            <label class="field-label">Email Address</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <input type="email" name="email" id="login-email" placeholder="you@example.com" required />
            </div>
          </div>

          <div class="field">
            <label class="field-label">Password</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input type="password" name="password" id="login-password" placeholder="Enter your password" required />
            </div>
          </div>

          <button type="submit" class="btn-primary" id="loginBtn">Login</button>
        </form>

        <p class="form-footer">Don't have an account? <a onclick="toggleView('register')">Sign up</a></p>
      </div>

      <!-- ════════ VUE INSCRIPTION ════════ -->
      <div id="register-view">
        <h2 class="form-title">Create an account</h2>
        <p class="form-sub">Sign up to get started with TaskFlow</p>

        <?php if (!empty($regErrors['global'])): ?>
        <div class="alert alert-error" style="display:block"><?= htmlspecialchars($regErrors['global']) ?></div>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="/taskflow/auth/register.php">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

          <div class="field">
            <label class="field-label">Full Name</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a8.38 8.38 0 0 1 13 0"/></svg>
              </span>
              <input type="text" name="full_name" id="reg-name" placeholder="John Doe"
                value="<?= htmlspecialchars($regOld['full_name'] ?? '') ?>"
                class="<?= !empty($regErrors['full_name']) ? 'input-error' : '' ?>" />
            </div>
            <?php if (!empty($regErrors['full_name'])): ?>
            <p class="field-error" style="display:block"><?= htmlspecialchars($regErrors['full_name']) ?></p>
            <?php else: ?>
            <p class="field-error" id="nameError"></p>
            <?php endif; ?>
          </div>

          <div class="field">
            <label class="field-label">Email Address</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <input type="email" name="email" id="reg-email" placeholder="you@example.com"
                value="<?= htmlspecialchars($regOld['email'] ?? '') ?>"
                class="<?= !empty($regErrors['email']) ? 'input-error' : '' ?>" />
            </div>
            <?php if (!empty($regErrors['email'])): ?>
            <p class="field-error" style="display:block"><?= htmlspecialchars($regErrors['email']) ?></p>
            <?php else: ?>
            <p class="field-error" id="emailError"></p>
            <?php endif; ?>
          </div>

          <div class="field">
            <label class="field-label">Password</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              </span>
              <input type="password" name="password" id="reg-password" placeholder="Min. 8 characters"
                class="<?= !empty($regErrors['password']) ? 'input-error' : '' ?>" />
            </div>
            <div class="strength-bar">
              <div class="strength-seg" id="seg1"></div>
              <div class="strength-seg" id="seg2"></div>
              <div class="strength-seg" id="seg3"></div>
            </div>
            <?php if (!empty($regErrors['password'])): ?>
            <p class="field-error" style="display:block"><?= htmlspecialchars($regErrors['password']) ?></p>
            <?php else: ?>
            <p class="field-error" id="passwordError"></p>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn-primary" id="registerBtn">Create Account</button>
        </form>

        <p class="form-footer">Already have an account? <a onclick="toggleView('login')">Log in</a></p>
      </div>

    </div>
  </main>
</div>

<script>
// ── Afficher la bonne vue au chargement ───────────────────────────────────────
const urlParams = new URLSearchParams(window.location.search);
const initView  = urlParams.get('view') === 'register' ? 'register' : 'login';
toggleView(initView);

// ── Basculer entre les vues ───────────────────────────────────────────────────
function toggleView(view) {
  document.getElementById('login-view').style.display    = view === 'login'    ? 'block' : 'none';
  document.getElementById('register-view').style.display = view === 'register' ? 'block' : 'none';
}

// ── Barre de force du mot de passe ───────────────────────────────────────────
document.getElementById('reg-password').addEventListener('input', (e) => {
  const val  = e.target.value;
  const segs = [document.getElementById('seg1'), document.getElementById('seg2'), document.getElementById('seg3')];
  let score  = val.length >= 8 ? (/[A-Z]/.test(val) && /[0-9]/.test(val) ? 3 : 2) : (val.length > 0 ? 1 : 0);
  segs.forEach((s, i) => {
    s.style.background = i < score
      ? (score === 1 ? '#ef4444' : score === 2 ? '#f59e0b' : '#10b981')
      : '#e5e7eb';
  });
});

// ── Connexion via fetch (pas de rechargement) ─────────────────────────────────
function showAlert(id, message, type = 'error') {
  const el = document.getElementById(id);
  el.textContent = message;
  el.className = 'alert alert-' + type;
  el.style.display = 'block';
}

document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('loginBtn');
  btn.disabled = true;
  btn.textContent = 'Connexion…';
  try {
    const res  = await fetch('/taskflow/auth/login.php', { method: 'POST', body: new FormData(e.target) });
    const data = await res.json();
    if (data.success) {
      showAlert('loginAlert', data.message, 'success');
      setTimeout(() => window.location.href = data.redirect, 600);
    } else {
      showAlert('loginAlert', data.message || 'Identifiants incorrects.', 'error');
      btn.disabled = false;
      btn.textContent = 'Login';
    }
  } catch {
    showAlert('loginAlert', 'Erreur réseau. Réessayez.', 'error');
    btn.disabled = false;
    btn.textContent = 'Login';
  }
});

// ── Inscription : soumission normale (déclenche le prompt mot de passe) ───────
// Le formulaire soumet directement vers register.php via method="POST" action="..."
// Pas de fetch ici → le navigateur détecte le champ password et propose de sauvegarder
</script>
</body>
</html>
