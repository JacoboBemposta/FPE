<div align="center">

# 🎓 RedFPE — Ecosistema Digital de Formación Profesional para el Empleo

**Conectando Academias, Docentes y Alumnos en toda España a través de la tecnología.**

[![Web Oficial](https://img.shields.io/badge/🌐_Web_Oficial-redfpe.es-4361ee?style=for-the-badge)](https://redfpe.es)
[![Laravel](https://img.shields.io/badge/Laravel-11.47-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Seguridad](https://img.shields.io/badge/Security-Hardened_&_GDPR-10b981?style=for-the-badge)](https://redfpe.es/privacidad)

---

*Desarrollada por educadores para educadores. RedFPE elimina la fragmentación del sector de la Formación Profesional para el Empleo (FPE), ofreciendo una plataforma centralizada, transparente y sin intermediarios para la gestión académica y la búsqueda de talento docente.*

</div>

---

## 🚀 ¿Qué es RedFPE?

El sector de los **Certificados de Profesionalidad y la Formación Profesional para el Empleo (FPE)** en España se enfrentaba tradicionalmente a procesos lentos y desconectados: los centros de formación tenían dificultades para localizar docentes acreditados para módulos específicos, mientras que los formadores carecían de un canal especializado donde visibilizar su perfil y disponibilidad.

**RedFPE** transforma esta realidad en un **ecosistema colaborativo 360°** donde cada actor educativo cuenta con herramientas diseñadas a medida:

```
   ┌──────────────────────┐         Candidaturas / CV          ┌──────────────────────┐
   │      👨‍🏫 DOCENTES      │ ────────────────────────────────► │     🏛️ ACADEMIAS      │
   │  Acreditan cursos y  │ ◄──────────────────────────────── │  Publican cursos FPE │
   │  buscan vacantes     │       Contacto / Selección         │  y gestionan alumnos │
   └──────────────────────┘                                    └──────────────────────┘
              ▲                                                           ▲
              │                    Catálogo Oficial SEPE                  │
              │                 (Familias · Módulos · UFs)                │
              └───────────────────────────┬───────────────────────────────┘
                                          │
                                          ▼
                               ┌──────────────────────┐
                               │      🎓 ALUMNOS       │
                               │  Exploran oferta y   │
                               │  solicitan plaza     │
                               └──────────────────────┘
```

---

## 🌟 Pilares de la Plataforma

### 🏛️ 1. Panel para Academias y Centros de Formación
- **Asignación del Catálogo Oficial**: Selección ágil de Certificados de Profesionalidad organizados por **Familias Profesionales**, con desglose automático de **Módulos Formativos (MF)** y **Unidades Formativas (UF)**.
- **Planificación de Convocatorias**: Configuración de fechas de inicio/fin, municipio, provincia y calendario de exámenes (`Examen 0` y `Examen Final`) por cada unidad formativa.
- **Buscador de Talento Docente**: Acceso directo a profesores que han acreditado los certificados requeridos, con filtros por provincia, municipio, código/nombre de curso y mensajería integrada.
- **Gestión Académica y Libro de Calificaciones**:
  - Alta y edición de alumnado y asignación de docente responsable por curso.
  - Registro interactivo de calificaciones por Unidad Formativa y Módulo con cálculo automático de promedios y estados de superación.
  - Histórico y **archivado inteligente de cursos** finalizados con restauración en un clic.

### 👨‍🏫 2. Panel para Docentes y Formadores
- **Portafolio Docente Especializado**: Configuración de los Certificados de Profesionalidad que el formador está habilitado para impartir, indicando su disponibilidad geográfica.
- **Radar de Oportunidades Laborales**: Buscador avanzado de cursos próximos a iniciar en academias de toda España, con filtros por:
  - Nivel de cualificación profesional (**Nivel 1, 2 y 3**).
  - Estado de vacante (*Con docente asignado* / *Sin docente asignado*).
  - Código oficial, nombre del certificado, provincia y municipio.
- **Postulación Directa con Adjuntos**: Envío de candidatura y Currículum Vitae (`PDF`, `DOC/DOCX`, imagen) directamente al centro formativo, con indicador visual en tiempo real (`CV Enviado`) para evitar duplicidades.

### 🎓 3. Portal para Alumnos
- **Explorador de Oferta Formativa**: Buscador público e intuitivo de cursos activos filtrados por Familia Profesional, centro educativo, provincia y localidad.
- **Transparencia y Contacto Directo**: Visualización del programa formativo completo de cada academia y formulario de solicitud de información directo con el centro.

### 🛡️ 4. Panel de Administración y Control Global
- **Gestión Curricular Completa**: CRUD jerárquico de Familias Profesionales, Certificados de Profesionalidad (con horas y nivel de cualificación), Módulos y Unidades Formativas.
- **Control Dinámico de Monetización**: Interruptor global en tiempo real para activar o desactivar el sistema de suscripciones en toda la plataforma sin necesidad de tocar código.
- **Analítica de Comunicaciones**: Panel estadístico con métricas y gráficos de actividad de correos enviados entre docentes, academias y alumnos, tasas de entrega y auditoría por contexto.

---

## 🔒 Seguridad y Arquitectura Técnica

RedFPE está construida sobre **Laravel 11.47** y **PHP 8.4**, siguiendo estrictos estándares de seguridad y rendimiento:

| Área | Implementación en RedFPE |
| :--- | :--- |
| **Autenticación Híbrida** | Login tradicional con verificación de email + **Google OAuth 2.0** (`Laravel Socialite`) con selector modal de rol en el primer acceso. |
| **Control de Acceso (RBAC & Anti-IDOR)** | Middleware dedicado (`CheckRole`) y validación estricta de propiedad en cada controlador para garantizar que ninguna academia o docente pueda modificar recursos ajenos. |
| **Cabeceras HTTP Blindadas** | Middleware global `SecurityHeaders` con políticas estrictas de `Content-Security-Policy (CSP)`, `HSTS`, `X-Content-Type-Options`, `X-Frame-Options` y `Referrer-Policy`. |
| **Comunicaciones Seguras** | Motor de envíos auditado (`EnviaEmails`) sobre `Laravel Mail` con validación cruzada de destinatarios (prevención de *Open Relay*) y registro trazable en base de datos. |
| **Cumplimiento GDPR / RGPD** | Derecho al olvido integrado: eliminación permanente e irreversible de cuenta y datos asociados en cascada mediante doble confirmación y verificación de contraseña. |
| **Pasarela de Pagos** | Integración con **Stripe Checkout** y sincronización mediante **Webhooks firmados** para la gestión automatizada de suscripciones premium. |

---

## 🛠️ Stack Tecnológico

- **Backend**: PHP 8.4 · Laravel 11.47
- **Base de Datos**: MySQL / MariaDB (Eloquent ORM + Query Builder optimizado)
- **Frontend**: Blade Templates · Bootstrap 5.3 · SASS · Vite · FontAwesome 6 · SweetAlert2
- **Servicios Externos**:
  - **Google Cloud OAuth 2.0** (`laravel/socialite`)
  - **Stripe API & Webhooks** (`stripe/stripe-php`)
- **SEO & Rendimiento**: Generación dinámica de `sitemap.xml` y `robots.txt`, compatible con caché completa de configuración, rutas y vistas (`artisan optimize`).

---

## ⚙️ Instalación y Despliegue

### Requisitos previos
- **PHP** >= 8.2 (Recomendado **PHP 8.4**) con extensiones `openssl`, `pdo_mysql`, `mbstring`, `curl`, `xml`, `zip`.
- **Composer** 2.x
- **Node.js** >= 18.x & NPM
- **MySQL** >= 8.0 / MariaDB >= 10.6

### Pasos para entorno local

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/JacoboBemposta/FPE.git
   cd FPE
   ```

2. **Instalar dependencias PHP y JS**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configura en tu archivo `.env` las credenciales de base de datos (`DB_*`), correo (`MAIL_*`), Google OAuth (`GOOGLE_CLIENT_*`) y Stripe (`STRIPE_*`).

4. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

5. **Optimización para Producción (SiteGround / Hosting)**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 📄 Licencia y Autoría

Proyecto desarrollado y mantenido por **Jacobo Bemposta** para **[RedFPE (redfpe.es)](https://redfpe.es)**.  
Todos los derechos reservados.
