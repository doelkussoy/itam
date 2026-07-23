<?php
$dir = "e:\\SOURCE CODE\\itam\\resources\\views";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$files = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'index.blade.php')) {
        $files[] = $file->getPathname();
    }
}

foreach ($files as $path) {
    $content = file_get_contents($path);
    $original = $content;
    
    // Some files might have `class="d-flex" style="gap: 8px;"`
    // We want to replace it with `class="d-flex justify-content-center" style="gap: 8px;"`
    // Only in the context of the actions column, but usually `gap: 8px;` is used for actions.
    
    // Let's specifically target the td for actions.
    // Usually it's like `<div class="d-flex" style="gap: 8px;">`
    $content = str_replace('<div class="d-flex" style="gap: 8px;">', '<div class="d-flex justify-content-center" style="gap: 8px;">', $content);
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
    }
}
echo "Done\n";
