<?php
$baseDir = '/home/u188832786/domains/yupiii.cl/public_html';
$target = $baseDir . '/storage/app/public';
$link = $baseDir . '/storage';  // Cambiado para que apunte directamente a /storage

echo "Target path: " . $target . "\n";
echo "Link path: " . $link . "\n";

// Eliminar el enlace existente si existe
if (file_exists($link)) {
    unlink($link);
    echo "Existing link removed\n";
}

// Asegurarse de que el directorio target existe
if (!file_exists($target)) {
    mkdir($target, 0775, true);
    echo "Target directory created\n";
}

// Intentar crear el enlace simbólico
$success = symlink($target, $link);

if ($success) {
    echo "Symlink created successfully\n";
    // Establecer permisos
    system('chmod -R 775 ' . $target);
    system('chmod -R 775 ' . $link);
    echo "Permissions set\n";
} else {
    echo "Failed to create symlink, trying alternative method...\n";
    // Método alternativo: copiar archivos
    if (!file_exists($link)) {
        mkdir($link, 0775, true);
        echo "Storage directory created\n";
    }
    system('cp -r ' . $target . '/* ' . $link . '/');
    system('chmod -R 775 ' . $link);
    echo "Files copied and permissions set\n";
}

// Verificar el resultado
if (file_exists($link)) {
    echo "Storage link/directory exists and is accessible\n";
    if (is_link($link)) {
        echo "It's a symbolic link\n";
    } else {
        echo "It's a directory with copied files\n";
    }
} else {
    echo "WARNING: Storage link/directory was not created successfully\n";
}

echo "Process completed\n";