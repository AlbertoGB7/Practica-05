# Pràctica 05 - Social Authentication & Miscel·lània - Alberto González

## Enllaç al Projecte

Accedeix al projecte [aquí](https://agonzalez.cat/Practica05/):

## Usuari de Prova (Es admin)
- **Usuari**: Xavi
- **Correu**: xmartin@sapalomera.cat
- **Contrasenya**: P@ssw0rd

## Usuari normal:
- **Usuari**: Alberto
- **Correu**: alberto.gb222@gmail.com
- **Contrasenya**: P@ssw0rd

## Descripció
Aquest projecte és una aplicació web que permet als usuaris registrar-se i iniciar sessió. Els usuaris poden veure tots els articles creats quan no estan loguejats. Un cop inicien sessió, només poden veure els articles que ells mateixos han creat. A més, els usuaris tenen la capacitat d'inserir, modificar i eliminar els seus propis articles.

## Temàtica
La temàtica del projecte està inspirada en el videojoc **Clash of Clans**.

## Característiques

- **Registre i Inici de Sessió**: Els usuaris poden registrar-se i després iniciar sessió per accedir als seus articles.
- **Control d'Articles**: Els usuaris poden inserir, modificar i eliminar només els articles que ells han creat.
- **Seguretat**:
  - No es guarda la contrasenya en el formulari per motius de seguretat.
  - La contrasenya es guarda de forma encriptada.
  - La contrasenya ha de complir els següents requisits:
    - Un mínim de 8 caràcters.
    - Almenys una lletra majúscula.
    - Almenys un número.
    - Almenys un símbol.
- **Missatges amb Cookies**: S'han utilitzat cookies per mostrar missatges d'èxit en iniciar i tancar sessió.
- **Timeout de Sessió**: La sessió es tanca automàticament després de 40 minuts d'inactivitat.
- **Paginació**: S'ha implementat un sistema de paginació per a la visualització dels articles.
- **Selecció d'Articles per Pàgina**: Els usuaris poden escollir quants articles volen veure per pàgina (5, 10 o 15).

## Noves Funcionalitats Implementades (Part 5)

### **Recuperació/Canvi de Contrasenya**
1. **Recuperar Contrasenya**  
   - A la pantalla de login, es pot sol·licitar una nova contrasenya introduint un correu electrònic que existeixi a la base de dades.  
   - El sistema envia un token temporal al correu de l'usuari per validar la recuperació.  

2. **Canviar Contrasenya**  
   - Des de la sessió iniciada, es pot accedir al desplegable del navbar per canviar la contrasenya.  
   - Requereix introduir la contrasenya actual i repetir la nova contrasenya dues vegades.  

### **Ordenació d'Articles**
- S'ha implementat una funcionalitat per ordenar els articles de forma ascendent o descendent segons:
  - Data de creació.  
  - Ordre alfabètic.  

### **Remember Me**
- Opció al formulari de login per recordar la sessió de l'usuari.  
- Es genera un token segur que permet iniciar sessió automàticament.  

### **reCAPTCHA**
- Després de 3 intents fallits d'inici de sessió, es mostra un **reCAPTCHA** que l'usuari ha de completar per seguir intentant logar-se.  

### **Autenticació Social**
- Es permet l'autenticació amb **Google** i **GitHub** mitjançant OAuth i HybridAuth.    
- Els usuaris registrats amb mètodes socials no poden canviar la seva contrasenya però poden modificar altres dades del perfil.
- El nom d'usuari d'aquests usuaris es random, el poden canviar en el seu perfil.  

### **Editar Perfil Personal**
- Des del desplegable del navbar, els usuaris poden:  
  - Canviar el seu avatar (imatge de perfil).  
  - Modificar el seu nom d'usuari.  

### **Usuari Admin**
- Els administradors tenen accés a una secció especial per gestionar usuaris:  
  - Poden eliminar altres usuaris.  
  - Quan s'elimina un usuari, també s'eliminen els seus articles.  
  - **Justificació**: Els articles estan associats al perfil de l'usuari. Mantenir articles orfes podria generar inconsistències en la base de dades i confusió pels altres usuaris.  

### **Barra de Cerca**
- S'ha implementat una barra de cerca funcional per buscar articles basant-se en almenys un camp de les fitxes.  
- Les cerques realitzades s'emmagatzemen per reutilitzar-les.  
- Opció avançada amb AJAX per mostrar resultats en temps real.  

---

## Configuracions de Seguretat
### **Fitxer `.htaccess`**
S'han afegit regles per millorar la seguretat i personalització del projecte:

1. **Redirigir a error 301 personalitzat**  
   - Es mostra una pàgina amb una imatge quan es produeix un error 301.  
   ErrorDocument 301 /Imatges/error301.png
2. **Redirigir a error 404 personalitzat**  
   - Es mostra una pàgina amb una imatge quan es produeix un error 404.  
   ErrorDocument 301 /Imatges/error404.png
3. **Eliminar les www de l'URL**  
   - Força l'ús de la URL sense www redirigint automàticament. 
   RewriteEngine On
   RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
   RewriteRule ^ http://%1%{REQUEST_URI} [L,R=301]

## Gestió d'Errors
- S'han implementat missatges d'error i èxit detallats per proporcionar una millor retroalimentació a l'usuari.

## Navegació
- S'ha implementat una barra de navegació (navbar) en totes les vistes que permet als usuaris tancar sessió fàcilment.
- La barra de navegació també mostra el nom de l'usuari actualment connectat.

