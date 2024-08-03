
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
- Contenerización: Docker, Docker Compose
- Otros: Ngrock, Nginx, PHPUnit
## Authors

- [@PortilloDev](https://github.com/PortilloDev)
- [Pefil de linkedin](https://www.linkedin.com/mynetwork/discovery-see-all/?usecase=PEOPLE_FOLLOWS&followMember=ivan-portillo-perez)
- [blog](https://notasweb.me/entrada/rabbitmq-y-microservicios/)
## Deployment

To deploy this project run

```bash
  make start-all
```

Dependencies already installed in each microservice

```bash
 composer require symfony/serializer-pack symfony/orm-pack symfony/security-bundle symfony/expression-language guzzlehttp/guzzle monolog symfony/ampq-messenger symfony/validator doctrine/orm symfony/serializer symfony/property-access symfony/uid

 composer require symfony/maker-bundle --dev 
```

To consume the messages
```bash
sf messenger:consume async_register_application
```

Ngrock url
```
http://localhost:4050
```
## Documentation

### Casos de Uso

Registro de Usuarios Los usuarios pueden registrarse en la plataforma proporcionando su nombre y correo electrónico. Una vez registrados, reciben un correo de confirmación para verificar su cuenta.

Gestión de Productos Los administradores pueden añadir, actualizar o eliminar productos en el catálogo, así como consultar el inventario disponible. Los productos se muestran en el frontend para que los usuarios los naveguen.

Carrito de Compras Los usuarios pueden agregar productos a su carrito, ajustar las cantidades y ver el total actualizado en tiempo real. El carrito se guarda en la sesión del usuario y se puede acceder desde cualquier dispositivo.

Proceso de Pedido Después de revisar el carrito, los usuarios pueden proceder al pago y realizar un pedido. El sistema verifica la disponibilidad de inventario y confirma el pedido, enviando una notificación por correo electrónico.

Notificaciones El sistema envía notificaciones por correo electrónico a los usuarios para confirmar sus acciones, como el registro de cuenta o la realización de pedidos.

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
- POST http://localhost:8004/api/inventory
````
{
    "productId": "uuid",
    "quantity": 45
}
````
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
