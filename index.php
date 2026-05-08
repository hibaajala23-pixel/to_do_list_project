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

  html, body {
    height: 100%;
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
  }

  .page {
    display: flex;
    width: 1440px;
    min-height: 100vh;
    margin: 0 auto;
  }

  .panel-left {
    flex: 0 0 780px;
    background: linear-gradient(145deg, #2d1fa3 0%, #3b29c4 35%, #3730a3 60%, #1e1b6e 100%);
    padding: 64px 72px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
  }

  .panel-left::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      radial-gradient(ellipse 80% 60% at 20% 10%, rgba(99,80,220,0.45) 0%, transparent 60%),
      radial-gradient(ellipse 60% 80% at 80% 80%, rgba(30,20,100,0.6) 0%, transparent 60%);
    pointer-events: none;
  }

  .left-content {
    position: relative;
    z-index: 1;
  }

  .brand {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 64px;
  }

  .brand-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255,255,255,0.2);
  }

  .brand-icon svg {
    width: 28px;
    height: 28px;
    fill: none;
    stroke: #fff;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .brand-name {
    font-size: 26px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.3px;
  }

  .headline {
    font-size: 42px;
    font-weight: 800;
    color: #fff;
    line-height: 1.18;
    letter-spacing: -1px;
    margin-bottom: 20px;
    max-width: 520px;
  }

  .tagline {
    font-size: 17px;
    font-weight: 400;
    color: rgba(255,255,255,0.65);
    line-height: 1.6;
    margin-bottom: 56px;
    max-width: 460px;
  }

  .features {
    display: flex;
    flex-direction: column;
    gap: 28px;
  }

  .feature {
    display: flex;
    align-items: flex-start;
    gap: 18px;
  }

  .feature-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
  }

  .feature-icon svg {
    width: 17px;
    height: 17px;
    fill: none;
    stroke: #fff;
    stroke-width: 2.2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .feature-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 4px;
  }

  .feature-desc {
    font-size: 14px;
    font-weight: 400;
    color: rgba(255,255,255,0.55);
    line-height: 1.5;
  }

  .panel-right {
    flex: 1;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 72px;
  }

  .form-box {
    width: 100%;
    max-width: 440px;
  }

  .form-title {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.6px;
    margin-bottom: 8px;
  }

  .form-sub {
    font-size: 15px;
    color: #6b7280;
    font-weight: 400;
    margin-bottom: 36px;
    line-height: 1.5;
  }

  .btn-social {
    width: 100%;
    height: 52px;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 500;
    color: #111827;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
    margin-bottom: 14px;
  }
  .btn-social:last-of-type { margin-bottom: 0; }
  .btn-social:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 28px 0;
  }
  .divider-line { flex: 1; height: 1px; background: #e5e7eb; }
  .divider-text { font-size: 13px; font-weight: 400; color: #9ca3af; white-space: nowrap; }

  .field { margin-bottom: 20px; }

  .field-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
  }

  .input-wrap { position: relative; }

  .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: #9ca3af;
    display: flex;
    align-items: center;
  }
  .input-icon svg {
    width: 17px;
    height: 17px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .field input {
    width: 100%;
    height: 52px;
    padding: 0 16px 0 46px;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #111827;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    -webkit-appearance: none;
  }
  .field input::placeholder { color: #9ca3af; }
  .field input:hover { border-color: #d1d5db; }
  .field input:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
  }
  .field input.invalid {
    border-color: #dc2626;
    background: #fef2f2;
  }
  .field input.invalid:focus {
    box-shadow: 0 0 0 3px rgba(220,38,38,0.12);
  }

  .field-error {
    color: #dc2626;
    font-size: 13px;
    margin-top: 6px;
    display: none;
    font-weight: 500;
  }

  .row-flex {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
  }

  .checkbox-label {
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    user-select: none;
  }
  .checkbox-label input[type="checkbox"] {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border: 1.5px solid #d1d5db;
    border-radius: 5px;
    cursor: pointer;
    flex-shrink: 0;
    position: relative;
    transition: border-color 0.15s, background 0.15s;
    background: #fff;
  }
  .checkbox-label input[type="checkbox"]:checked {
    background: #4f46e5;
    border-color: #4f46e5;
  }
  .checkbox-label input[type="checkbox"]:checked::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 5px;
    width: 5px;
    height: 9px;
    border: 2px solid #fff;
    border-top: none;
    border-left: none;
    transform: rotate(42deg);
  }
  .checkbox-text {
    font-size: 14px;
    font-weight: 400;
    color: #374151;
  }

  .forgot-link {
    font-size: 14px;
    font-weight: 500;
    color: #4f46e5;
    text-decoration: none;
    transition: color 0.15s;
  }
  .forgot-link:hover { color: #3730a3; text-decoration: underline; }

  .btn-primary {
    width: 100%;
    height: 52px;
    background: #4338ca;
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    letter-spacing: 0.1px;
    transition: background 0.18s, transform 0.12s;
    margin-bottom: 28px;
  }
  .btn-primary:hover { background: #3730a3; }
  .btn-primary:active { transform: scale(0.987); }

  .form-footer {
    text-align: center;
    font-size: 14px;
    color: #6b7280;
  }
  .form-footer a {
    color: #4f46e5;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.15s;
    cursor: pointer;
  }
  .form-footer a:hover { color: #3730a3; text-decoration: underline; }

  .strength-bar { display: flex; gap: 4px; margin-top: 8px; }
  .strength-seg { flex: 1; height: 4px; border-radius: 2px; background: #e5e7eb; transition: 0.3s; }

  .alert-success {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 24px;
    display: none;
    font-size: 14px;
    font-weight: 500;
  }

  #register-view { display: none; }
</style>
</head>
<body>
<div class="page">

  <aside class="panel-left">
    <div class="left-content">
      <div class="brand">
        <div class="brand-icon">
          <svg viewBox="0 0 28 28">
            <rect x="4" y="4" width="20" height="20" rx="4"/>
            <polyline points="8,14 12,18 20,10"/>
          </svg>
        </div>
        <span class="brand-name">TaskFlow</span>
      </div>
      <h1 class="headline">Organize your work,<br/>amplify your productivity</h1>
      <p class="tagline">Streamline your tasks and collaborate seamlessly with your team.</p>
      <div class="features">
        <div class="feature">
          <div class="feature-icon">
            <svg viewBox="0 0 17 17"><polyline points="3,9 6,13 14,5"/></svg>
          </div>
          <div>
            <div class="feature-title">Smart Task Management</div>
            <div class="feature-desc">Organize, prioritize, and track all your tasks in one place</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon">
            <svg viewBox="0 0 17 17"><polyline points="3,9 6,13 14,5"/></svg>
          </div>
          <div>
            <div class="feature-title">Team Collaboration</div>
            <div class="feature-desc">Work together efficiently with real-time updates</div>
          </div>
        </div>
        <div class="feature">
          <div class="feature-icon">
            <svg viewBox="0 0 17 17"><polyline points="3,9 6,13 14,5"/></svg>
          </div>
          <div>
            <div class="feature-title">Progress Tracking</div>
            <div class="feature-desc">Visualize your productivity with insightful analytics</div>
          </div>
        </div>
      </div>
    </div>
  </aside>

  <main class="panel-right">
    <div class="form-box">
      
      <div id="login-view">
        <h2 class="form-title">Welcome back</h2>
        <p class="form-sub">Please enter your details to sign in</p>

        <button type="button" class="btn-social">
          <svg width="20" height="20" viewBox="0 0 18 18" fill="none">
            <path d="M17.64 9.2c0-.638-.057-1.252-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908C16.658 14.075 17.64 11.767 17.64 9.2z" fill="#4285F4"/>
            <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
            <path d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z" fill="#FBBC05"/>
            <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.163 6.656 3.58 9 3.58z" fill="#EA4335"/>
          </svg>
          Continue with Google
        </button>

        <button type="button" class="btn-social">
          <svg width="20" height="20" viewBox="0 0 18 18" fill="#111827">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0C4.03 0 0 4.03 0 9c0 3.98 2.58 7.35 6.16 8.54.45.08.61-.2.61-.44v-1.55c-2.5.54-3.03-1.21-3.03-1.21-.41-1.04-1-1.32-1-1.32-.82-.56.06-.55.06-.55.9.06 1.38.93 1.38.93.8 1.37 2.1.97 2.62.74.08-.58.31-.97.57-1.2-2-.22-4.1-1-4.1-4.45 0-.98.35-1.79.93-2.42-.09-.23-.4-1.14.09-2.38 0 0 .76-.24 2.49.93a8.6 8.6 0 0 1 4.54 0c1.73-1.17 2.49-.93 2.49-.93.49 1.24.18 2.15.09 2.38.58.63.93 1.44.93 2.42 0 3.46-2.1 4.22-4.1 4.44.32.28.61.83.61 1.67v2.48c0 .24.16.52.62.43A9 9 0 0 0 18 9c0-4.97-4.03-9-9-9z"/>
          </svg>
          Continue with GitHub
        </button>

        <div class="divider">
          <div class="divider-line"></div>
          <span class="divider-text">Or continue with email</span>
          <div class="divider-line"></div>
        </div>

        <form onsubmit="return false;">
          <div class="field">
            <label class="field-label" for="login-email">Email Address</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 17 17"><rect x="1.5" y="3.5" width="14" height="10" rx="2"/><polyline points="1.5,4.5 8.5,10 15.5,4.5"/></svg>
              </span>
              <input type="email" id="login-email" placeholder="you@example.com" autocomplete="email" />
            </div>
          </div>

          <div class="field">
            <label class="field-label" for="login-password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 17 17"><rect x="3" y="7.5" width="11" height="7.5" rx="2"/><path d="M5.5 7.5V5.5a3 3 0 0 1 6 0v2"/></svg>
              </span>
              <input type="password" id="login-password" placeholder="Enter your password" autocomplete="current-password" />
            </div>
          </div>

          <div class="row-flex">
            <label class="checkbox-label">
              <input type="checkbox" id="remember" />
              <span class="checkbox-text">Remember me</span>
            </label>
            <a href="#" class="forgot-link">Forgot Password?</a>
          </div>

          <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="form-footer">Don't have an account? <a onclick="toggleView('register')">Sign up</a></p>
      </div>

      <div id="register-view">
        <h2 class="form-title">Create an account</h2>
        <p class="form-sub">Sign up to get started with TaskFlow</p>

        <div id="successAlert" class="alert-success">Account successfully created! You can now log in.</div>

        <form id="registerForm">
          <div class="field">
            <label class="field-label" for="reg-name">Full Name</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 16 16"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
              </span>
              <input type="text" id="reg-name" placeholder="John Doe" />
            </div>
            <p class="field-error" id="nameError">Full name is required.</p>
          </div>

          <div class="field">
            <label class="field-label" for="reg-email">Email Address</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 17 17"><rect x="1.5" y="3.5" width="14" height="10" rx="2"/><polyline points="1.5,4.5 8.5,10 15.5,4.5"/></svg>
              </span>
              <input type="email" id="reg-email" placeholder="you@example.com" />
            </div>
            <p class="field-error" id="emailError">Please enter a valid email address.</p>
          </div>

          <div class="field">
            <label class="field-label" for="reg-password">Password</label>
            <div class="input-wrap">
              <span class="input-icon">
                <svg viewBox="0 0 17 17"><rect x="3" y="7.5" width="11" height="7.5" rx="2"/><path d="M5.5 7.5V5.5a3 3 0 0 1 6 0v2"/></svg>
              </span>
              <input type="password" id="reg-password" placeholder="Min. 8 characters" />
            </div>
            <div class="strength-bar">
              <div class="strength-seg" id="seg1"></div>
              <div class="strength-seg" id="seg2"></div>
              <div class="strength-seg" id="seg3"></div>
            </div>
            <p class="field-error" id="passError">Password must be at least 8 characters.</p>
          </div>

          <button type="submit" class="btn-primary">Create Account</button>
        </form>

        <p class="form-footer">Already have an account? <a onclick="toggleView('login')">Log in</a></p>
      </div>

    </div>
  </main>

</div>

<script>
  function toggleView(view) {
    document.getElementById('login-view').style.display = view === 'login' ? 'block' : 'none';
    document.getElementById('register-view').style.display = view === 'register' ? 'block' : 'none';
    document.getElementById('successAlert').style.display = 'none';
    document.getElementById('registerForm').style.opacity = '1';
    
    document.querySelectorAll('#registerForm input').forEach(input => {
      input.value = '';
      input.classList.remove('invalid');
      input.disabled = false;
    });
    
    document.querySelectorAll('.field-error').forEach(err => err.style.display = 'none');
    document.querySelectorAll('.strength-seg').forEach(seg => seg.style.background = '#e5e7eb');
    document.querySelector('#registerForm button').disabled = false;
  }

  const regPassword = document.getElementById('reg-password');
  regPassword.addEventListener('input', (e) => {
    const val = e.target.value;
    const segs = [document.getElementById('seg1'), document.getElementById('seg2'), document.getElementById('seg3')];
    let score = 0;
    
    if (val.length > 0) score = 1;
    if (val.length >= 8) score = 2;
    if (val.length >= 8 && /[A-Z]/.test(val) && /[0-9]/.test(val)) score = 3;

    segs.forEach((s, i) => {
      if (i < score) {
        s.style.background = score === 1 ? '#ef4444' : score === 2 ? '#f59e0b' : '#10b981';
      } else {
        s.style.background = '#e5e7eb';
      }
    });
  });

  document.getElementById('registerForm').addEventListener('submit', (e) => {
    e.preventDefault();
    let isValid = true;

    const name = document.getElementById('reg-name');
    const email = document.getElementById('reg-email');
    const password = document.getElementById('reg-password');

    if (name.value.trim() === '') {
      document.getElementById('nameError').style.display = 'block';
      name.classList.add('invalid');
      isValid = false;
    } else {
      document.getElementById('nameError').style.display = 'none';
      name.classList.remove('invalid');
    }

    if (!email.value.includes('@') || !email.value.includes('.')) {
      document.getElementById('emailError').style.display = 'block';
      email.classList.add('invalid');
      isValid = false;
    } else {
      document.getElementById('emailError').style.display = 'none';
      email.classList.remove('invalid');
    }

    if (password.value.length < 8) {
      document.getElementById('passError').style.display = 'block';
      password.classList.add('invalid');
      isValid = false;
    } else {
      document.getElementById('passError').style.display = 'none';
      password.classList.remove('invalid');
    }

    if (isValid) {
      document.getElementById('successAlert').style.display = 'block';
      const form = document.getElementById('registerForm');
      form.style.opacity = '0.5';
      form.querySelectorAll('input, button').forEach(el => el.disabled = true);
    }
  });
</script>
</body>
</html>