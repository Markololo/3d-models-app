<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <!-- TODO: include your CSS files here -->
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