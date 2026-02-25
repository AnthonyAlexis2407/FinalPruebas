# 👟 AppLaravel: Sistema Integral de Gestión para Zapaterías

AppLaravel es una solución completa diseñada para el sector retail (calzado y afines), con un enfoque en la escalabilidad multi-sede y el control granular de inventarios y ventas.

---

## 🌟 Módulos y Funcionalidades Principales

### 1. 🏬 Arquitectura Multi-Sede (Aislamiento Total)

Diseñado para negocios que operan en múltiples ubicaciones geográficas.

- **Aislamiento por Tienda**: Cada sede tiene su propio ecosistema de datos. Un producto creado en la "Tienda A" no es visible en la "Tienda B" a menos que se configure explícitamente.
- **Cambio de Contexto Dinámico**: Los administradores disponen de un selector de sede en el dashboard. Al cambiar de sede, el sistema utiliza re-autenticación de seguridad para asegurar que el usuario mantenga permisos válidos en el nuevo contexto.
- **Identidad Visual**: Cada sede puede tener su propio color primario y branding configurado en la base de datos.
- **Seguridad de Roles**:
    - **Admin**: Acceso total, capacidad de saltar entre sedes y gestionar usuarios.
    - **Cajero**: Bloqueado a una única sede asignada. No puede ver datos de otras tiendas.

### 2. 📦 Inventario y Gestión de Productos

Más allá de un simple registro de productos, el sistema maneja variantes complejas.

- **Categorías Inteligentes**: Los productos se organizan por categorías (Zapatillas, Sandalias, etc.).
- **Tallas y Presentaciones**: El sistema permite definir números de calzado (tallas). Estas se filtran por categoría para que, al ingresar stock de "Zapatillas", solo se muestren tallas relevantes.
- **Ingreso de Stock**: Módulo intuitivo para añadir existencias por talla y presentación.
- **Recetas de Productos**: Funcionalidad avanzada para definir "ingredientes" o componentes. Ideal para productos que se venden en combos o que requieren ensamblaje, afectando el stock de múltiples ítems simultáneamente.

### 3. 🛒 Punto de Venta (POS) Optimizado

Interfaz de alta velocidad para atención al cliente.

- **Búsqueda Avanzada**: Motor de búsqueda que ignora acentos y caracteres especiales (Ej: "mocasín" se encuentra escribiendo "mocasin").
- **Flujo de Pago**: Selección rápida de productos, cálculo automático de totales y gestión de métodos de pago (Efectivo, Tarjeta, etc.).
- **Facturación Dual**:
    - **Ticket**: Formato tradicional de 80mm para impresoras térmicas.
    - **A4 (PDF)**: Formato detallado para facturación administrativa.

### 4. 👥 Gestión de Usuarios y Seguridad

Control total sobre quién accede a la información.

- **Control de Acceso**: Middleware personalizado que verifica el rol y la sede activa en cada petición.
- **Borrado Protegido**: Para eliminar un usuario, el administrador debe confirmar su propia contraseña, evitando eliminaciones accidentales o malintencionadas.

### 5. 📊 Inteligencia de Negocios

Módulo de reportes para toma de decisiones.

- **Ventas Diarias**: Resumen detallado de la operación del día actual.
- **Reportes Mensuales**: Análisis de tendencias y volumen de ventas mes a mes.
- **Filtros por Sede**: Los reportes se adaptan automáticamente a la sede activa para un análisis preciso.

---

## 🛠️ Tecnologías y Arquitectura

- **Core**: Laravel 11.x (PHP 8.2+)
- **Base de Datos**: MySQL con uso extensivo de `Eloquent Global Scopes` para el manejo de `store_id`.
- **Frontend**: Blade Templates + AdminLTE 3 + Vanilla JavaScript.
- **Seguridad**: Autenticación nativa de Laravel extendida con persistencia de sesión de sede (`active_store_id`).
- **Traits**: Uso del trait `BelongsToStore` en los modelos para automatizar el filtrado de datos por tienda.

---

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio**:
    ```bash
    git clone [url-del-repo]
    cd AppLaravel
    ```
2. **Instalar dependencias**:
    ```bash
    composer install
    npm install && npm run build
    ```
3. **Configurar el entorno**:
   Copiar `.env.example` a `.env` y configurar la base de datos.
4. **Migraciones y Datos Iniciales**:
    ```bash
    php artisan migrate
    ```
5. **Puesta en Marcha Rápida (Scripts Especiales)**:
    - Crear Admin: `php seed_admin.php`
    - Configurar Sede Principal: `php assign_default_store.php`
    - Crear Segunda Sede: `php create_second_store.php`

---

## � Mejoras Implementadas Recientemente

- ✅ **Optimización de Cambio de Tienda**: Corregido el error de re-autenticación que revertía al admin a la tienda 1.
- ✅ **Búsqueda POS Accent-Insensitive**: Mejora crítica en la experiencia del cajero.
- ✅ **Validación de Inventario**: Refuerzo en la lógica de `updateOrCreate` para stocks por talla.
- ✅ **Estandarización de Divisa**: Unificación de la presentación visual a "S/." en toda la plataforma.
