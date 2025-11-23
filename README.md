```md
# 🟦 Monifi — Sistema de Gestión de Finanzas Personales  
### *Backend PHP + MySQL | Arquitectura modular | Seguridad y escalabilidad*

Monifi es una plataforma diseñada para la **gestión inteligente de cuentas personales**, construida con un enfoque en arquitectura limpia, escalabilidad y buenas prácticas backend.  
El sistema implementa autenticación segura, separación de responsabilidades y un modelo basado en metadatos para mapear la base de datos de manera clara y profesional.

---

## 🚀 Características Principales

### 🔐 Autenticación Segura
- Manejo de sesión robusto.
- Validación estricta de usuario.
- Protección ante accesos no autorizados.

### 🗂️ Gestión de Cuentas
- Consulta de cuentas asociadas al usuario autenticado.
- Acceso a presupuesto, nombre, estado y demás información relevante.
- Estructura lista para expandirse a movimientos, reportes y más.

### 🧱 Arquitectura Profesional
- Uso de **metaclases PHP** para representar tablas y columnas.
- Separación clara entre configuración, conexión, lógica y vistas.
- Mantenimiento fácil y modificaciones rápidas gracias al modelo de constantes.

### 🛡️ Buenas Prácticas Aplicadas
- Queries preparadas.
- Rutas absolutas centralizadas.
- Código modular y mantenible.
- Organización basada en un mini MVC.

---

## 🛠️ Tecnologías Usadas
- **PHP 8+**
- **MySQL / MariaDB**
- **Bootstrap**
- **MVC ligero + metadatos**

---

## 📁 Estructura del Proyecto
```
..

````

---

## 📊 Base de Datos
La base de datos se organiza en torno a:

- **Usuarios**
- **Cuentas**
- **Estados**
- (Opcional) Movimientos financieros

Toda la estructura se maneja mediante metaclases en PHP, facilitando mantenibilidad y coherencia entre backend y BD.

---

## 🧪 Funcionalidades Implementadas

...

---

## 🧭 Cómo Ejecutarlo

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/MoniFi
````

### 2. Configurar `config.php`

Ajusta credenciales, rutas y constantes del sistema.

### 3. Importar el archivo SQL

Dentro de la carpeta `/database/`.

### 4. Iniciar el servidor PHP

```bash
php -S localhost:8000
```

### 5. Abrir en el navegador

```
http://localhost:8000
```

---

## 🤝 Aporte Personal al Proyecto

Este proyecto fue desarrollado por **Jeronimo Buitrago Serna** como una muestra de:

* Dominio de conceptos backend con PHP.
* Diseño de arquitecturas claras y escalables.
* Uso de principios de código limpio.
* Modelado profesional de bases de datos.
* Creación de sistemas reales aplicables en empresas.

Monifi demuestra mi capacidad para construir sistemas backend robustos, organizados y preparados para escalar.

---
