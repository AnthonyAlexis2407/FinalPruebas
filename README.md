# 🍿 AppLaravel: Sistema Integral de Gestión para Snacks y Comida

AppLaravel es una solución completa diseñada para negocios de snacks, comida rápida y venta de productos en general, con un enfoque en la escalabilidad multi-sede y el control detallado de inventarios y ventas.

---

## 🌟 Módulos y Funcionalidades Principales

### 1. 🏬 Arquitectura Multi-Sede (Aislamiento Total)

Ideal para franquicias o negocios con múltiples locales físicos.

- **Aislamiento por Local**: Cada sede gestiona su propia disponibilidad de productos y stock. Un snack disponible en el "Local A" no afectará el inventario del "Local B".
- **Cambio de Contexto Dinámico**: Los administradores pueden supervisar diferentes locales desde el dashboard. Al cambiar de local, se utiliza re-autenticación de seguridad para garantizar la integridad de las operaciones.
- **Identidad del Local**: Configuración personalizada de branding y colores para cada sede.
- **Seguridad de Roles**:
    - **Admin**: Supervisión total de todas las sedes y gestión de personal.
    - **Cajero**: Vinculado exclusivamente a su local asignado para operaciones de venta.

### 2. 🍔 Inventario y Gestión de Productos

Optimizado para el manejo de porciones, combos y presentaciones variadas.

- **Categorías Dinámicas**: Clasificación de productos (Bebidas, Snacks, Comida Caliente, Postres, etc.).
- **Presentaciones y Porciones**: Permite definir diferentes formatos de venta (Ej: Pequeño, Mediano, Grande o por Peso/Volumen). El sistema filtra las presentaciones válidas según la categoría del producto.
- **Abastecimiento de Stock**: Registro ágil de ingreso de insumos y productos terminados.
- **Recetas de Productos (Combos)**: Funcionalidad clave para definir componentes de un plato o combo. Al vender un "Mega Combo", el sistema descuenta automáticamente del stock cada uno de sus componentes individuales.

### 3. � Punto de Venta (POS) de Alta Velocidad

Diseñado para una atención rápida y eficiente.

- **Búsqueda Inteligente**: Motor que localiza productos instantáneamente ignorando tildes o errores comunes (Ej: "hamburguesa" se encuentra escribiendo "hamburguesa").
- **Flujo de Pago Flexible**: Procesamiento de ventas con múltiples métodos de pago (Efectivo, Yape/Plin, Tarjeta).
- **Emisión de Comprobantes**:
    - **Ticket**: Formato de boleta rápida para impresoras térmicas.
    - **A4**: Formato detallado para pedidos grandes o coordinación logística.

### 4. 👥 Seguridad y Colaboradores

Control de acceso granular para proteger la operación.

- **Protección de Datos**: Cada acción crítica está restringida por roles y sede.
- **Confirmación de Seguridad**: Eliminación de registros sensibles mediante verificación de contraseña de administrador en tiempo real.

### 5. � Reportes y Analítica

Información crítica para el crecimiento del negocio.

- **Cierre de Caja Diario**: Resumen de ingresos y movimientos del día.
- **Reporte de Ventas Mensual**: Seguimiento de los productos más vendidos y rendimiento por local.

---

## 🛠️ Tecnologías y Arquitectura

- **Core**: Laravel 11.x (PHP 8.2+)
- **Base de Datos**: MySQL con filtrado automático mediante `Eloquent Scopes`.
- **Frontend**: Blade + AdminLTE 3 + JS para formularios reactivos.
- **Lógica Multi-Tenancy**: Trait `BelongsToStore` que automatiza la seguridad de datos por local.

---

## ⚙️ Instalación y Configuración

1. **Clonar el repositorio**.
2. **Instalar dependencias**: `composer install` y `npm install`.
3. **Configurar el entorno**: Renombrar `.env.example` a `.env` y configurar DB.
4. **Migraciones**: `php artisan migrate`.
5. **Carga Inicial**:
    - Administrador: `php seed_admin.php`
    - Configurar Local 1: `php assign_default_store.php`
    - Añadir Segundo Local: `php create_second_store.php`

---

## 🚀 Mejoras Recientes

- ✅ **Persistencia de Local para Admin**: Corrección en el flujo de re-autenticación al rotar entre locales.
- ✅ **Búsqueda Avanzada en POS**: Optimización del motor de búsqueda para catálogos amplios de comida y snacks.
- ✅ **Gestión de Stock por Porción**: Mejoras en la precisión del inventario para presentaciones variables.
- ✅ **Uniformidad Visual**: Estandarización de precios y formatos de moneda.
