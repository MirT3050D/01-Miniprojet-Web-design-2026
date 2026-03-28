<?php
// login page markup only.
?>
<title>Backoffice login - Iran Info</title>
<meta name="description" content="Secure backoffice access for the Iran conflict information site.">

<link rel="stylesheet" href="../assets/css/login.css">

<main class="backoffice-login">
    <section class="login-card">
        <div class="brand">
            <span class="brand-mark">IA</span>
            <div class="brand-text">
                <h1>Iran Conflict Archive</h1>
                <p>Backoffice access</p>
            </div>
        </div>

        <form class="login-form" action="../inc/login.php" method="post">
            <label for="identifier">Email or username</label>
            <input
                id="identifier"
                name="identifier"
                type="text"
                autocomplete="username"
                value="admin"
                placeholder="analyst@archive.org"
                required
            >

            <label for="password">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                value="admin123"
                placeholder="Enter your password"
                required
            >

            <div class="form-row">
                <label class="checkbox">
                    <input type="checkbox" name="remember" value="1">
                    <span>Keep me signed in</span>
                </label>
                <a class="link" href="#">Forgot password?</a>
            </div>

            <button type="submit">Sign in</button>
        </form>

        <p class="login-note">
            Restricted area for editors and analysts. All sessions are logged.
        </p>
    </section>

    <aside class="login-panel">
        <div class="panel-inner">
            <h2>Operational Brief</h2>
            <p>
                Curated timelines, verified sources, and situational briefs on the
                Iran conflict. Use the backoffice to publish updates and manage
                sensitive materials.
            </p>

            <div class="panel-stats">
                <div>
                    <span class="stat-label">Coverage</span>
                    <span class="stat-value">2018 - Present</span>
                </div>
                <div>
                    <span class="stat-label">Sources</span>
                    <span class="stat-value">Field + OSINT</span>
                </div>
                <div>
                    <span class="stat-label">Last update</span>
                    <span class="stat-value">Today, 06:30 UTC</span>
                </div>
            </div>

            <div class="panel-alert">
                <span class="alert-tag">Priority</span>
                <p>
                    Verify all entries against primary sources before publishing.
                </p>
            </div>
        </div>
    </aside>
</main>
