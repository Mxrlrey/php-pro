<h2>Login</h2>

<?php if (!empty($erros['login'])): ?>
    <p style="color: red;"><?php echo htmlspecialchars($erros['login']); ?></p>
<?php endif; ?>

<form action="/login" method="post">
    <div>
        <label for="email">Email</label>
        <input
            type="text"
            id="email"
            name="email"
            value="<?php echo htmlspecialchars($email ?? ''); ?>"
        >

        <?php if (!empty($erros['email'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($erros['email']); ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="password">Senha</label>
        <input type="password" id="password" name="password">

        <?php if (!empty($erros['password'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($erros['password']); ?></p>
        <?php endif; ?>
    </div>

    <button type="submit">Login</button>
</form>
