<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Create account - PupNest</title>
  <meta name="description" content="Create a PupNest account" />
  <meta name="keywords" content="signup, register, account" />
  <?php require 'includes/head.php' ?>
</head>

<body>
<?php
  $defaultReturn = 'index.php';
  $incomingReturn = isset($_GET['return']) ? $_GET['return'] : '';
  $refererPath = '';
  if (isset($_SERVER['HTTP_REFERER'])) {
    $refererPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? '';
    $refererQuery = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY) ?? '';
    if ($refererPath !== '') {
      $refererPath = ltrim($refererPath, '/');
      $refererPath = $refererPath . ($refererQuery ? ('?' . $refererQuery) : '');
    }
  }
  $returnTo = $incomingReturn !== '' ? $incomingReturn : ($refererPath !== '' ? $refererPath : $defaultReturn);
  if (preg_match('#^https?://#i', $returnTo)) { $returnTo = $defaultReturn; }
  foreach (['login.php','signup.php'] as $p) { if (str_ends_with($returnTo, $p)) { $returnTo = $defaultReturn; break; } }
?>
<?php require 'includes/header.php' ?>

<main class="main">
  <section class="auth section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="auth-card shadow-lg rounded-4 overflow-hidden">
            <div class="row g-0">
              <div class="col-md-6 d-none d-md-block auth-hero position-relative">
                <img src="assets/img/golden-retriever/Angel/12.jpg" alt="Happy dog" class="w-100 h-100 object-fit-cover" />
              </div>
              <div class="col-md-6 bg-white p-4 p-md-5">
                <div class="d-flex align-items-center mb-4 gap-3">
                  <img src="assets/img/logo/logonew.jpg" alt="PupNest" style="width:38px;height:38px;border-radius:8px;object-fit:cover" />
                  <h3 class="m-0">Create your account</h3>
                </div>

                <form class="row gy-3" action="signup-submit.php" method="post" novalidate>
                  <input type="hidden" name="return" value="<?php echo htmlspecialchars($returnTo); ?>" />
                  <div class="col-12">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Your full name" required />
                  </div>
                  <div class="col-12">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required />
                  </div>
                  <div class="col-12">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create a password" required />
                  </div>
                  <div class="col-12 d-grid mt-2">
                    <button class="btn btn-primary py-2" type="submit">Create Account</button>
                  </div>
                </form>

                <div class="text-center my-3 text-muted">or continue with</div>

                <div class="d-grid gap-2">
                  <a href="#" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2"><i class="bi bi-google"></i> Continue with Google</a>
                </div>

                <div class="text-center my-3 text-muted">or</div>

                <div class="d-grid">
                  <a href="<?php echo htmlspecialchars($returnTo); ?>" class="btn btn-outline-secondary">Continue as guest</a>
                </div>

                <p class="mt-4 mb-0 text-center text-muted">Already have an account? <a href="login.php?return=<?php echo urlencode($returnTo); ?>">Sign in</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require 'includes/footer.php' ?>
<?php require 'includes/footerscripts.php' ?>

<?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
<!-- Signup Success Modal -->
<div class="modal fade" id="signupSuccessModal" tabindex="-1" aria-labelledby="signupSuccessLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signupSuccessLabel">Signup successful</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Thank you for creating an account with PupNest<?php echo isset($_GET['name']) ? ', ' . htmlspecialchars($_GET['name']) : '';?>. We\'ve sent a confirmation email to your inbox.</p>
      </div>
      <div class="modal-footer">
        <a href="<?php echo htmlspecialchars($returnTo); ?>" class="btn btn-primary">Continue</a>
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
 </div>
<script>
  (function(){
    var el = document.getElementById('signupSuccessModal');
    if (el && typeof bootstrap !== 'undefined') {
      var m = new bootstrap.Modal(el);
      m.show();
    }
  })();
</script>
<?php endif; ?>

<style>
  .auth-card{background:#fff}
  .auth-hero img{object-fit:cover}
  @media (max-width: 767.98px){
    .auth-card{border-radius: 12px}
  }
</style>

</body>
</html>


