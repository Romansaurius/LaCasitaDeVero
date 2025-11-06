# Desplegar en Render

## Pasos:

### 1. Sube tu código a GitHub
```bash
cd LaCasitaDeVero
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/LaCasitaDeVero.git
git push -u origin main
```

### 2. En Render.com
1. Ve a https://render.com y crea una cuenta
2. Click en "New +" → "Web Service"
3. Conecta tu repositorio de GitHub
4. Configura:
   - **Name**: lacasitadevero
   - **Environment**: PHP
   - **Build Command**: `composer install --no-dev || true`
   - **Start Command**: `php -S 0.0.0.0:$PORT -t .`

### 3. Variables de Entorno
En Render, ve a "Environment" y agrega:
- `DB_HOST`: shuttle.proxy.rlwy.net
- `DB_PORT`: 21840
- `DB_NAME`: LaCasitaDeVero
- `DB_USER`: root
- `DB_PASS`: anJkMDnhTJoXaMDjgYFpfmkMBUskRZFu

### 4. Despliega
Click en "Create Web Service"

### 5. Crea la base de datos
Una vez desplegado, accede a:
`https://tu-app.onrender.com/crear_base_datos.php`

¡Listo! Tu app estará en: `https://lacasitadevero.onrender.com`
