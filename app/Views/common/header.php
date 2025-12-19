<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <!-- TODO: include your CSS files here -->

    <link
        rel="canonical"
        href="https://getbootstrap.com/docs/5.3/examples/dashboard/" />
    <script src="<?= APP_ASSETS_DIR_URL ?>/js/color-modes.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="language-switcher">
        <?php
        // Get current locale from global translator
        global $translator;
        $currentLocale = $translator->getLocale();
        $availableLocales = $translator->getAvailableLocales();
        ?>

        <?php foreach ($availableLocales as $locale): ?>
            <?php if ($locale !== $currentLocale): ?>
                <a href="?lang=<?= hs($locale) ?>" class="lang-link">
                    <?= $locale === 'en' ? 'English' : 'Français' ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <span class="current-lang">
            <?= $currentLocale === 'en' ? '🇬🇧 English' : '🇫🇷 Français' ?>
        </span>
    </div>
