# 2FA
PHP Test - Second factor authentication (2FA)

Problema
Tenemos la necesidad de añadir un segundo factor de autenticación para verificar la identidad de los usuarios a la hora de realizar algunas acciones delicadas en nuestras plataformas web y mobile.

Se ha decidido que dicha verificación se hará a través del teléfono móvil del propio usuario.

El segundo factor de autenticación consta en generar un código de verificación que será enviado al teléfono móvil del usuario logado en la web. Cuando el usuario reciba el código de verificación en su teléfono, deberá introducirlo en la web para confirmar su identidad. Si el código introducido es correcto, el usuario podrá proceder con la acción deseada. En caso contrario, el usuario deberá pedir un nuevo código de verificación o abandonar el portal.
¿Qué necesitamos?
Se requiere de una API REST con dos endpoints:
Dado un número de teléfono se deberá generar y enviar el código de verificación a un teléfono móvil.
Dado el id del recurso creado en (1) y el código de verificación, se devuelve el número de teléfono y si el teléfono ha sido verificado correctamente o no.
Funcionalidad mínima
El código generado tiene que ser un código alfanumérico de 4 dígitos. Por ejemplo A1F6.
La verificación tiene una fecha de expiración máxima de 5 minutos.
Para que la verificación sea válida, el código enviado en (2) debe coincidir estrictamente con el código de verificación generado previamente en (1).
Un código de verificación puede ser usado solamente una vez (código de 1 solo intento).
¡Funcionalidad ninja!
Debe haber un código máster que permita autenticar cualquier número de teléfono. Buscar una buena opción ;)
Notas
Los números de teléfono son números válidos en España. Por ejemplo 667 313 244
Para esta prueba, el envío del código de verificación al teléfono móvil puede ser falseado de manera que se podría devolver como respuesta en el primer endpoint.
¿Qué se debe hacer y entregar?
Diseño y documentación de la API REST.
Código en Symfony 3.4+
Circuito funcional por interfaz web (mínima) y CLI utilizando la API.
Investigar y proporcionar un servicio externo para el envío del SMS para una futura implementación.
Guión para configurar y ejecutar funcionalmente la prueba.
¿Qué se valorará?
Calidad del diseño de la API REST: diseño de las URLs, parámetros, métodos, recursos, errores, formato de respuesta, etc.
Programación SOLID y código DDD: clean code, entidades, value objects, repositorios, servicios, lógica de negocio en dominio, etc.
Cobertura del código: tests funcionales y de la API.
Buena solución para el código master de verificación.
Defensa de la opción/opciones del servicio externo para el envío del SMS: investigación, propuestas según necesidades, soluciones de pago, gratuitas, etc.
