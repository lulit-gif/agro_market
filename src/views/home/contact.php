<?php $pageClass = 'home'; require __DIR__ . '/../layout/header.php'; ?>

<div style="padding: 40px 0;">
  <div class="container" style="max-width: 800px;">
    <div style="text-align: center; margin-bottom: 32px;">
      <h1 style="margin-top: 0;">Contact Us</h1>
      <p style="font-size: 16px; color: var(--muted);">We’re here to help. Reach us using the details below.</p>
    </div>

    <div class="card" style="padding: 24px;">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div>
          <h3 style="margin: 0 0 8px;">Address</h3>
          <p style="margin: 0; color: var(--text-brown);">123 Market Street<br>Farm Valley, FV 12345</p>
        </div>
        <div>
          <h3 style="margin: 0 0 8px;">Phone</h3>
          <p style="margin: 0; color: var(--text-brown);">+1 (555) 123-4567</p>
        </div>
        <div>
          <h3 style="margin: 0 0 8px;">Email</h3>
          <p style="margin: 0; color: var(--text-brown);">hello@agromarket.com<br>support@agromarket.com</p>
        </div>
        <div>
          <h3 style="margin: 0 0 8px;">Hours</h3>
          <p style="margin: 0; color: var(--text-brown);">Mon–Fri: 9am–6pm<br>Sat: 10am–4pm<br>Sun: Closed</p>
        </div>
      </div>
    </div>

    <div style="margin-top: 24px; display:flex; gap:12px;">
      <a href="/products" class="btn btn-primary">Browse Products</a>
      <a href="/" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>
