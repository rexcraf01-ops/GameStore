# Sesión 4 · UX/UI en Figma · sistema de diseño Level Up Games

## Identidad

Fondo claro con detalles oscuros. El menú lateral y las pantallas de bienvenida van en azul noche; el contenido va sobre un lavanda muy claro con tarjetas blancas. El **morado** es el acento principal (botones, enlaces, cifras destacadas) y el **verde lima** es el acento de marca (la palabra «GAMES», etiquetas pequeñas y el botón del inicio). Sin sombras fuertes: las separaciones se hacen con espacio, bordes finos y tarjetas redondeadas.

## Variables (crea estos *styles* en Figma; coinciden con `public/css/levelup.css`)

*Los colores son aproximados a partir de las capturas. Confírmalos en Figma con el cuentagotas.*

| Nombre | Hex | Uso |
| :---- | :---- | :---- |
| Azul noche | `#14131F` | Menú lateral, barra del catálogo, tarjetas oscuras |
| Fondo | `#F4F4FA` | Fondo del contenido y de los inputs |
| Superficie | `#FFFFFF` | Tarjetas y tablas |
| Borde | `#E6E4F0` | Líneas y contornos |
| Texto | `#15141F` | Texto principal |
| Texto secundario | `#6B6A7F` | Etiquetas, ayudas y subtítulos |
| Morado principal | `#6F42E8` | Botón principal, ítem activo del menú, cifra destacada |
| Morado claro | `#EDE8FB` | Fondo de etiquetas, avisos y pasos |
| Morado medio | `#A98CEB` | Barras de gráficos y progreso |
| Lima | `#CDF56E` | Acento de marca: «GAMES», etiquetas pequeñas y botón del inicio |
| Éxito | texto `#1F6B3F` · fondo `#E3F4E8` | Entregado, confirmado, disponible |
| Advertencia | texto `#8A5A00` · fondo `#FDF0D5` | Pendiente, por verificar, stock bajo |
| Peligro | texto `#B42323` · fondo `#FDE8E8` | Agotado, reembolso, errores, eliminar |

*Los textos de estado van más oscuros que en las capturas para cumplir el contraste mínimo de 4,5:1.*

## Tipografía (máximo 2)

- **Inter** 400/500/600/700 → toda la interfaz: títulos, cifras, tablas, botones y la marca «LEVEL UP».
- Tamaños de referencia: título de página 28–32 px (700) · cifras grandes 32–40 px (700) · texto 14–15 px (400/500) · etiquetas pequeñas 11–12 px en mayúscula con espaciado (600).
- Se usa una sola familia; la jerarquía se logra con peso y tamaño.

## Reglas

- Tarjetas con esquinas de 16 px; botones de 10 px; buscadores, selectores y etiquetas en forma de píldora. Bordes de 1 px.
- Botón principal: relleno morado con texto blanco, un tono más oscuro al pasar el mouse. Botón secundario: contorno gris con fondo blanco. Botón peligroso: contorno rojo.
- La primera tarjeta de cifras de cada pantalla va rellena de morado para destacar el dato más importante.
- Cada pantalla de gestión lleva: ruta de navegación («Gestión / Panel»), título con una frase de ayuda, botones de acción arriba a la derecha, tarjetas de cifras y una tabla o lista.
- Las etiquetas de estado usan color + texto (nunca solo color).
- Elemento memorable del catálogo público: el inicio oscuro con el mando de videojuegos, el lema «Sube de nivel» y el botón lima. El resto queda sobrio.
- Contraste mínimo 4,5:1 y foco visible (contorno morado de 2 px) en todos los controles.

## Pantallas (cada una en 1440 px y 390 px)

*Las capturas están en 1440 px. Las versiones de 390 px quedan por diseñar.*

| # | Pantalla | Qué muestra | Estado |
| :---- | :---- | :---- | :---- |
| 1 | Login | Dos columnas: a la izquierda el lema «El siguiente nivel de tu tienda» con el mando; a la derecha el formulario con correo, contraseña (con «Mostrar»), «Mantener mi sesión iniciada», botón «Iniciar sesión», etiquetas de rol y un aviso para quien solo quiere ver el catálogo | Lista (1440) |
| 2 | Panel | «Tu tienda, de un vistazo»; selector de período y botón «+ Nuevo pedido»; 4 tarjetas de cifras; gráfico de ventas de la semana; prioridades de hoy; pedidos recientes; estado de los pedidos | Lista (1440) |
| 3 | Menú lateral | Marca LEVEL UP GAMES; ítems Panel, Pedidos (con contador), Pagos, Productos, Categorías, Clientes y Reportes; tarjeta de ayuda; usuario con cierre de sesión. El administrador ve todos los ítems; el vendedor/cajero ve Panel, Pedidos, Pagos, Productos y Clientes. En móvil es un panel deslizante | Lista (1440) |
| 4 | Listado de productos | «Productos e inventario»: cifras (activos, unidades, stock bajo, sin existencias); tabla con SKU, producto, categoría, precio, stock y estado; filtros, paginación y aviso de reposición | Lista (1440) |
| 5 | Formulario de producto | No aparece en las capturas. Propuesta: nombre, SKU, categoría, plataforma, precio, stock, stock mínimo y activo; botones «Guardar» y «Cancelar»; errores bajo cada campo | Pendiente |
| 6 | Detalle de pedido | «Pedido PED-1048»: aviso de pendiente de confirmación, productos, datos del cliente, actividad del pedido y tarjeta de pago; botones «Imprimir pedido» y «Confirmar pedido» | Lista (1440) |
| 7 | Modal de confirmación | No aparece en las capturas. Propuesta: ventana centrada con título, explicación corta, botón secundario «Volver» y botón rojo «Cancelar pedido» o «Eliminar» | Pendiente |
| 8 | Reportes | Selector de mes y botón «Exportar reporte»; 4 cifras; evolución de ventas; ventas por categoría; productos más vendidos; tarjeta oscura «Tu siguiente movimiento» | Lista (1440). Falta cambiar el selector de mes por un filtro de fechas (ver nota) |
| 9 | Mensajes de error | No aparece en las capturas. Propuesta: campo con borde y texto rojo, aviso de error arriba del formulario y página «No encontrado» | Pendiente |
| 10 | Menú público responsive | Catálogo: barra superior con acceso del equipo, inicio con mando, buscador, filtros por categoría y tarjetas con foto, precio y disponibilidad («Disponible», «Stock bajo», «Agotado») | Lista (1440). Falta 390 px |

**Pantallas adicionales que aparecen en las capturas:** Pedidos (listado con filtros), Nuevo pedido (3 pasos: productos y cliente, pago y entrega, confirmación), Pagos y caja, Categorías y Clientes.

**Nota sobre Reportes:** el proyecto ahora filtra los reportes por rango de fechas. Cambia el selector «Noviembre 2026» por dos campos **Desde** y **Hasta** y los botones rápidos **Hoy · 7 días · 30 días**.

## Componentes reutilizables

| Componente | Variantes | Cómo se ve |
| :---- | :---- | :---- |
| Botón | Principal (morado) · Secundario (contorno) · Peligro (contorno rojo) · Lima (inicio del catálogo) | Esquinas de 10 px; con ícono de flecha opcional |
| Input con error | Normal · Con búsqueda · Con error | Fondo lavanda; en error, borde y mensaje rojos debajo |
| Select | Normal · Filtro en píldora | Píldora con flecha, como «Estado: todos» |
| Tabla | Encabezado gris claro, filas con línea fina, paginación | Última columna con flecha para abrir el detalle |
| Tarjeta de cifra | Destacada (morado) · Normal (blanca) | Etiqueta, número grande y texto de apoyo |
| Etiqueta de estado | Pendiente, En preparación, Entregado, Por verificar, Confirmado, Reembolso, Disponible, Stock bajo, Agotado, Nuevo, Recurrente | Píldora con fondo suave y texto oscuro |
| Alerta | Aviso amarillo · Aviso morado claro | Franja con título, texto corto y enlace de acción |
| Modal | Confirmación · Peligro | Ventana centrada con fondo oscurecido |
| Gráfico y barra de progreso | Barras redondeadas · Barra horizontal | Morado oscuro para el dato actual, morados claros para el resto |
| Pasos (stepper) | 3 pasos | Paso activo en morado claro con número |

Conecta las pantallas con un *Prototype* (login → panel → pedidos → detalle). Exporta `docs/figma.pdf` y guarda el enlace.
