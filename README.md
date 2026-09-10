
# Carrito de la compra con Microservicios

El objetivo principal de este proyecto es proporcionar una experiencia práctica para aprender cómo los microservicios funcionan en un entorno real y cómo se comunican de distintas maneras según la necesidad.

Este proyecto es un carrito construido utilizando una arquitectura de microservicios. La plataforma permite a los usuarios navegar por productos, agregar artículos a su carrito de compras y recibir notificaciones de confirmación. Cada funcionalidad principal está implementada como un microservicio independiente, asegurando una alta escalabilidad y mantenibilidad del sistema.

El proyecto utiliza RabbitMQ para la comunicación asincrona entre microservicios.

Támbien se trabaja el uso de comunicación interna mediante APIS, para la comunicación sincrona entre servicios.

## Tecnologías Usadas
- Lenguaje: PHP v8.1
- Framework Symfony v6.4
- Bases de Datos: MySQL
- Mensajería: RabbitMQ
- Caché / Idempotencia / Locks: Redis
- Contenerización: Docker, Docker Compose
- Otros: Ngrock, Nginx, PHPUnit
## Authors

- [@PortilloDev](https://github.com/PortilloDev)
- [Pefil de linkedin](https://www.linkedin.com/mynetwork/discovery-see-all/?usecase=PEOPLE_FOLLOWS&followMember=ivan-portillo-perez)
- [blog](https://notasweb.me/entrada/rabbitmq-y-microservicios/)
## Deployment

### Para desplegar este proyecto, ejecute

Clone el repositorio en su máquina local:
```
git clone https://github.com/PortilloDev/microservicios-php.git

cd microservicios-php
```

Ejecute el siguiente comando para iniciar todos los servicios utilizando el Makefile:

```
 make start-all
````

Configurar cada microservicio excepto rabbitmq, hacer paso a paso en cada microservicio

```bash
sf messenger:setup-transports
```
Configuración de RabbitMQ

Acceda a la interfaz de administración de RabbitMQ a través de la URL http://localhost:15672 utilizando las siguientes credenciales:
- user: guest
- pass: guest
Navegue a la sección Admin, Vaya a Virtual Hosts. Cree un nuevo host virtual con el nombre: ecommerce_service.

### Infraestructura compartida (RabbitMQ y Redis)

RabbitMQ y Redis son **infraestructura global** del sistema, no de cada microservicio:

- **RabbitMQ** (`ecommerce-ms-rabbitmq-service`): un único broker con el vhost `ecommerce_service`. Cada servicio publica/consume en su propio exchange y sus propias colas, por lo que el aislamiento es lógico, no físico.
- **Redis** (`ecommerce-ms-redis-service`, puerto host `6380` → interno `6379`): una única instancia compartida por `payments` y `esim`. Cada servicio usa un **prefijo de claves propio** (`payments:` y `esim:`) para no acoplarse. Se usa para:
  - Idempotencia (no cobrar dos veces, no aprovisionar dos eSIM para el mismo pedido).
  - Locks distribuidos durante el cobro/aprovisionamiento (evita duplicados si se escala el consumidor).
  - Caché de estado a corto plazo y caché de aplicación (`cache.app`).

Para un despliegue real de alto volumen, la alternativa "purista" sería un Redis por servicio; en este laboratorio una instancia global con prefijos evita multiplicar contenedores manteniendo el desacoplamiento lógico, igual que RabbitMQ.

---
### Consumir mensajes
Para poder consumir los mensajes dentro de application, inventory, mailer, carts, products, payments y esim, ejecutar sf messenger:consume dentro del contenedor de cada servicio
```
sf messenger:consume async_register_application -vvv (registrar usuarios)
sf messenger:consume async_add_inventory -vvv (añadir producto nuevo como inventario)
sf messenger:consume async_update_inventory -vvv (actualizar inventario)
sf messenger:consume async_stock_out_mailer -vvv (consumir mensajes de stock out)
sf messenger:consume async_application_mailer -vvv (email de bienvenida a usuarios)
sf messenger:consume async_checkout_payment -vvv (cobrar un checkout; payments)
sf messenger:consume async_payment_result -vvv (actualizar estado del carrito; carts)
sf messenger:consume async_payment_succeeded -vvv (aprovisionar eSIM; esim)
sf messenger:consume async_esim_provisioned -vvv (email con la eSIM; mailer)
sf messenger:consume failed -vvv (mensajes fallidos)

```


## Documentation

### Casos de Uso

Registro de Usuarios Los usuarios pueden registrarse en la plataforma proporcionando su nombre y correo electrónico. Una vez registrados, reciben un correo de confirmación para verificar su cuenta.

Gestión de Productos Los administradores pueden añadir, actualizar o eliminar productos en el catálogo, así como consultar el inventario disponible. Los productos se muestran en el frontend para que los usuarios los naveguen.

Carrito de Compras Los usuarios pueden agregar productos a su carrito, ajustar las cantidades y ver el total actualizado en tiempo real. El carrito se guarda en la sesión del usuario y se puede acceder desde cualquier dispositivo.

Proceso de Pedido Después de revisar el carrito, los usuarios pueden proceder al pago. Al cerrar el carrito (`carts`) se calcula el importe y se publica un evento `CheckoutRequested` en RabbitMQ. El servicio `payments` lo consume de forma asíncrona, crea un PaymentIntent contra el proveedor (fake con semántica Stripe) y publica `PaymentSucceeded` o `PaymentFailed`. Según el resultado, `carts` actualiza el estado del pedido y `esim` aprovisiona una tarjeta eSIM cuando el pago es correcto.

Aprovisionamiento eSIM Cuando el pago se confirma, el servicio `esim` genera la tarjeta (ICCID, IMSI, MSISDN y código de activación LPA) a través de un proveedor simulado y publica `EsimProvisioned`, que `mailer` consume para enviar la eSIM al usuario.

Notificaciones El sistema envía notificaciones por correo electrónico a los usuarios para confirmar sus acciones, como el registro de cuenta, la realización de pedidos o la entrega de una eSIM.

### APIS
#### Register Service
Create a new user
- POST http://localhost:8011/api/register
````
{
    "name": "John Doe",
    "email" : "john.doe@notasweb.com"
}
````
---
#### Application Service
Finde user. Internal request
- GET http://localhost:8007/api/internal/user?email=email@email.com

---
#### Product Service
Create a new product
- POST http://localhost:8004/api/product
````
{
    "name" : "Zapatillas Running",
    "price" : 45,
    "description" : "zapatillas para correr",
}
````
---
Find product. Internal request
- GET http://localhost:8004/api/internal/product/id
---
#### Inventory Service
Create or update product quantity in inventory
- POST http://localhost:8006/api/inventory
````
{
    "productId": "uuid",
    "quantity": 45
}
````
Find product quantity in inventory, internal service
- GET http://localhost:8006/api/internal/inventory/:productId

---
List all products in inventory
- GET http://localhost:8006/api/inventory

---
#### Cart Service
Add new product to cart
- POST http://localhost:8002/api/cart/add-product
````
{
    "productId" : "uuid",
    "userEmail" : "john.doe@notasweb.com"
    "quantity" : 1
}
````
---
Close cart
- PATCH http://localhost:8002/api/cart/:cartId/close

---
Current cart information opened by user
- GET http://localhost:8002/api/cart/:userEmail

---
#### Payment Service (payments)
Charge a checkout. Publishes `CheckoutRequested` to RabbitMQ (asynchronous)
- POST http://localhost:8008/api/payment/charge
````
{
    "orderId": "uuid",
    "userEmail": "john.doe@notasweb.com",
    "amount": 4500,
    "currency": "EUR",
    "paymentMethod": "tok_visa"
}
````
`amount` va en unidades menores (céntimos), igual que Stripe. `paymentMethod` acepta tokens de prueba:
`tok_visa`, `tok_mastercard`, `tok_amex` (éxito), `tok_chargeDeclined`, `tok_insufficientFunds`, `tok_expiredCard` (fallo).

---
Find payment by id
- GET http://localhost:8008/api/payment/:id

---
Find payment by order. Internal request
- GET http://localhost:8008/api/internal/payment/order/:orderId

---
List payments
- GET http://localhost:8008/api/payment

---
Refund a succeeded payment. Internal request
- POST http://localhost:8008/api/internal/payment/:id/refund
````
{
    "amount": 4500
}
````
`amount` es opcional; si se omite se reembolsa el total.

---
#### eSIM Service (esim)
Find eSIM by id
- GET http://localhost:8009/api/esim/:id

---
Find eSIM by order. Internal request
- GET http://localhost:8009/api/internal/esim/order/:orderId

---
List eSIMs
- GET http://localhost:8009/api/esim

---
Provision an eSIM manually (internal/testing). Normally triggered by `PaymentSucceeded`
- POST http://localhost:8009/api/internal/esim/provision
````
{
    "orderId": "uuid",
    "userEmail": "john.doe@notasweb.com",
    "plan": "default"
}
````
Planes disponibles: `default` (Global 5GB/30 días), `global_1gb`, `europe_10gb`.

---
### Flujo de eventos (RabbitMQ)

```
carts ──CheckoutRequested──▶ [checkout_events_exchange / checkout_payment_queue] ──▶ payments
payments ──PaymentSucceeded──▶ [payment_events_exchange / payment_succeeded] ──┬──▶ esim  ──EsimProvisioned──▶ [esim_events_exchange] ──▶ mailer
                                                                              └──▶ carts (actualiza el pedido a "paid")
payments ──PaymentFailed────▶ [payment_events_exchange / payment_failed] ──────▶ carts (actualiza el pedido a "payment_failed")
```

### Puertos

| Servicio | Web  | MySQL |
|----------|------|-------|
| carts    | 8002 | 10200 |
| products | 8004 | 10400 |
| inventory| 8006 | 10600 |
| application | 8007 | 10700 |
| register | 8011 | 10100 |
| payments | 8008 | 10800 |
| esim     | 8009 | 10900 |
| mailer   | -    | 10500 |
| rabbitmq | 15672/5672 | - |
| redis    | 6380 | - |
