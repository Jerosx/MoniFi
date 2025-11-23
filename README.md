
---

````md
<div align="center">

# 🟦 MONIFI  
### *Sistema de Gestión de Finanzas Personales*

**Backend PHP + MySQL · Arquitectura Modular · Seguridad y Escalabilidad**

</div>

---

## ✨ Descripción del Proyecto

**Monifi** es una plataforma diseñada para la gestión inteligente de cuentas personales.  
Su arquitectura está construida con un enfoque en **claridad, escalabilidad y buenas prácticas backend**, utilizando PHP nativo organizado en una estructura modular con controladores, modelos, vistas y metadatos de base de datos.

Monifi refleja un backend real listo para expandirse hacia movimientos financieros, reportes y más módulos avanzados.

---

## 🚀 Funcionalidades Principales

### 🔐 Autenticación Segura
- Validación de credenciales
- Manejo de sesión robusto
- Restricción de rutas privadas

### 🗂️ Gestión de Cuentas
- Crear, actualizar y eliminar cuentas
- Visualizar cuentas por usuario autenticado
- Manejo de presupuesto, nombre y estado por cuenta

### 🧱 Arquitectura Profesional
- Directorios limpios por responsabilidad
- Metaclases PHP para representar tablas/columnas
- Mini MVC desarrollado a mano
- Código mantenible y escalable

---

## 🛠️ Tecnologías Usadas

- **PHP 8+**
- **MySQL / MariaDB**
- **Bootstrap**
- **MVC Ligero**
- **Metadatos PHP**

---

## 📁 Estructura del Proyecto

```bash
MONIFI/
├── controllers/
│   └── process/
│       ├── accounts/
│       │   ├── create_account.php
│       │   ├── delete_account.php
│       │   ├── update_account.php
│       │   └── accounts_management.php
│       ├── process_login.php
│       ├── process_register_user.php
│       ├── close_session.php
│       ├── user_management.php
│       └── validate_exist_session.php
│
├── model/
│   ├── database/
│   │   ├── metadata/
│   │   ├── constantes.php
│   │   └── connection.php
│   ├── database.txt
│   └── ...
│
├── public/
│   ├── index.html
│   └── register_user.html
│
├── views/
│   ├── character/
│   ├── partials/
│   ├── style/
│   └── main.php
│
├── config.php
├── notas.txt
├── .env
├── .gitignore
└── README.md
````

---

## 🧭 Cómo Ejecutarlo

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/monifi.git
```

### 2️⃣ Configurar `config.php`

Define rutas y credenciales.

### 3️⃣ Importar la base de datos

Ubicada en:

```
/model/database/database.txt
```

### 4️⃣ Iniciar el servidor PHP

```bash
php -S localhost:8000
```

### 5️⃣ Abrir en el navegador

```
http://localhost:8000/public/index.html
```

---

## 👨‍💻 Aporte Personal

Este proyecto fue desarrollado por **Jeronimo Buitrago Serna** como una demostración de:

* Arquitectura backend limpia con PHP
* Diseño de sistemas escalables
* Buenas prácticas en autenticación y manejo de sesiones
* Modelado de base de datos y metadatos
* Organización profesional del código

Monifi es una muestra sólida de mis capacidades como **Backend / Full Stack Developer Junior**.

---

<div align="center">

✨ *Gracias por ver este proyecto*

</div>
```

---
