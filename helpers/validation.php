<?php
/**
 * Validation Helper Functions
 */

/**
 * Validate email address
 */
function validate_email(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * Minimum 8 characters, at least one uppercase, one lowercase, one number, one special character
 */
function validate_password(string $password): array {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule";
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une lettre minuscule";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un chiffre";
    }
    if (!preg_match('/[@$!%*?&#]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un caractère spécial (@$!%*?&#)";
    }
    
    return $errors;
}

/**
 * Validate phone number (French format)
 */
function validate_phone(string $phone): bool {
    return preg_match('/^0[1-9][0-9]{8}$/', str_replace(' ', '', $phone));
}

/**
 * Validate ISBN (10 or 13 digits)
 */
function validate_isbn(string $isbn): bool {
    $isbn = str_replace(['-', ' '], '', $isbn);
    return preg_match('/^(97[89])?\d{9}[\dX]$/', $isbn);
}

/**
 * Validate required fields
 */
function validate_required(array $fields, array $data): array {
    $errors = [];
    
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            $errors[$field] = "Ce champ est obligatoire";
        }
    }
    
    return $errors;
}

/**
 * Validate file upload (image)
 */
function validate_image(array $file): array {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Erreur lors du téléchargement du fichier";
        return $errors;
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = "La taille du fichier ne doit pas dépasser " . (MAX_FILE_SIZE / 1024 / 1024) . " Mo";
    }
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        $errors[] = "Le fichier doit être une image (JPEG, PNG ou GIF)";
    }
    
    return $errors;
}

/**
 * Validate date format
 */
function validate_date(string $date, string $format = 'Y-m-d'): bool {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}