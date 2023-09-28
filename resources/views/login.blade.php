




  <div class="hlp-login-and-signup">
    <div class="hlp-form__header">
      <h3>Sign in</h3>
      <p>Please enter your username and password below.</p>
    </div>

    <form method="post" class="hlp-form" id="login-form" action="/admin/login">
      <label class="hlp-input-label" for="username">Email</label>
      <input id="username" name="email" type="text" value="{{ old('email') }}" required class="input" placeholder="Email / Username">

      <div class="error">
        <span data-error="This field is required"></span>
      </div>

      <label class="hlp-input-label" for="password">Password</label>
      <input id="password" name="password" type="password" value="" required class="input" placeholder="Password">

      <div class="error">
        <span data-error="This field is required"></span>
      </div>

      <button
        class="primary-button full-width g-recaptcha"
        data-callback="supLoginSubmit">
        Sign in
      </button>

      <script type="text/javascript">
        function supLoginSubmit(token) {
          document.getElementById("login-form").submit();
        }
      </script>

      @csrf
    </form>

    <div class="hlp-divider">
      <span class="divider-line"></span>
      <span class="divider-text">or</span>
      <span class="divider-line"></span>
    </div>


    <div class="hlp-form__footer">
      <div class="hlp-divider">
        <span class="divider-line"></span>
      </div>

     
    </div>
  </div>

