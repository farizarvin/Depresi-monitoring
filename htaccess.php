<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Jika request bukan ke file/folder yang ada langsung di root
    RewriteCond %{REQUEST_URI} !^/public/
    
    # Redirect ke folder public
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
# Blokir akses ke file sensitif
<FilesMatch "^\.env|composer\.(json|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
# Blokir akses ke folder sensitif
<DirectoryMatch "^/.*(app|bootstrap|config|database|resources|routes|storage|tests|vendor)/">
    Order allow,deny
    Deny from all
</DirectoryMatch>