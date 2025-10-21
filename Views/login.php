<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="Public/img/icons/safari-icone1.jpeg" />
  <title>Connexion • Safari</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/feather-icons"></script>
  <style>
    :root {
      --primary: #0066CC;
      --primary-dark: #0052A3;
      --text: #1F2937;
      --muted: #6B7280;
      --bg: #F9FAFB;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      width: 100%;
      max-width: 450px;
    }

    .login-card {
      background: white;
      border-radius: 20px;
      padding: 48px 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .logo {
      font-size: 36px;
      font-weight: 800;
      color: var(--primary);
      margin-bottom: 8px;
      letter-spacing: -1px;
    }

    .logo-tag {
      font-size: 13px;
      color: var(--muted);
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .login-title {
      font-size: 24px;
      font-weight: 700;
      color: var(--text);
      margin: 32px 0 8px;
    }

    .login-subtitle {
      font-size: 14px;
      color: var(--muted);
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--muted);
    }

    .input-icon svg {
      width: 20px;
      height: 20px;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px 14px 48px;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      font-size: 15px;
      font-family: inherit;
      color: var(--text);
      transition: all 0.2s;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(0, 102, 204, 0.1);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }

    .checkbox-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: var(--text);
      cursor: pointer;
    }

    .checkbox-label input {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }

    .forgot-link {
      font-size: 14px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 102, 204, 0.4);
    }

    .btn-login svg {
      width: 20px;
      height: 20px;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin: 32px 0;
    }

    .divider-line {
      flex: 1;
      height: 1px;
      background: #E5E7EB;
    }

    .divider-text {
      font-size: 13px;
      color: var(--muted);
      font-weight: 500;
    }

    .demo-info {
      background: #EFF6FF;
      border: 1px solid #DBEAFE;
      border-radius: 12px;
      padding: 16px;
      margin-top: 24px;
    }

    .demo-info-title {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 12px;
    }

    .demo-info-title svg {
      width: 18px;
      height: 18px;
    }

    .demo-credentials {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .demo-credential {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      padding: 8px 12px;
      background: white;
      border-radius: 8px;
    }

    .demo-credential-label {
      color: var(--muted);
      font-weight: 600;
    }

    .demo-credential-value {
      color: var(--text);
      font-weight: 600;
      font-family: 'Courier New', monospace;
    }

    .login-footer {
      text-align: center;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid #E5E7EB;
      font-size: 13px;
      color: var(--muted);
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 32px 24px;
      }

      .login-title {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="logo">SAFARI</div>
        <div class="logo-tag">Smart Mobility</div>
        <h1 class="login-title">Bienvenue</h1>
        <p class="login-subtitle">Connectez-vous pour accéder au système</p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
      <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 8px; color: #991b1b;">
          <i data-feather="alert-circle" style="width: 20px; height: 20px;"></i>
          <span style="font-size: 14px; font-weight: 500;"><?php echo e($_SESSION['error']); ?></span>
        </div>
      </div>
      <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
      <div style="background: #dcfce7; border-left: 4px solid #22c55e; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 8px; color: #166534;">
          <i data-feather="check-circle" style="width: 20px; height: 20px;"></i>
          <span style="font-size: 14px; font-weight: 500;"><?php echo e($_SESSION['success']); ?></span>
        </div>
      </div>
      <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <form method="POST" action="<?php echo BASE_URL; ?>/login" class="login-form">
        <?php echo csrfField(); ?>
        
        <div class="form-group">
          <label class="form-label">Adresse email</label>
          <div class="input-wrapper">
            <div class="input-icon">
              <i data-feather="mail"></i>
            </div>
            <input 
              type="email" 
              name="email"
              class="form-input" 
              placeholder="votre@email.com"
              required
              value="<?php echo e($_SESSION['old_email'] ?? ''); ?>"
              autofocus
            >
          </div>
        </div>
        <?php unset($_SESSION['old_email']); ?>

        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <div class="input-wrapper">
            <div class="input-icon">
              <i data-feather="lock"></i>
            </div>
            <input 
              type="password" 
              name="mot_de_passe"
              class="form-input" 
              placeholder="••••••••"
              required
            >
          </div>
        </div>

        <div class="form-options">
          <label class="checkbox-label">
            <input type="checkbox" name="remember_me">
            <span>Se souvenir de moi</span>
          </label>
          <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-login">
          <i data-feather="log-in"></i>
          Se connecter
        </button>
      </form>

      <div class="login-footer">
        © 2024 Dare-Dare. Tous droits réservés.
      </div>
    </div>
  </div>
  <script>
    // Initialiser Feather Icons
    feather.replace();
  </script>
</body>
</html>
