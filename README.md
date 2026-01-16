[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/nMSQJoxi)
# **Programa gestió d'inventari** 

# Descripcio del projecte
## Funcionalitat
Aquest projecte és bàsicament un sistema de control d’inventari, pensat per a magatzems o botigues que necessiten gestionar i tenir un control precís de tot el seu estoc. L’aplicació permet registrar, consultar i actualitzar els productes disponibles, facilitant així el seguiment de les existències, l’entrada i sortida de productes i l’organització general de l’inventari.
## Backend
Al backend he desenvolupat una API en forat json. Els models utilitzats són: Proveïdor, Client, Comanda, Usuari i Producte, així com la classe DetallComanda, que serveix per relacionar els productes amb cada comanda.

Els controladors tenen com a funció principal la gestió del CRUD (crear, llegir, actualitzar i eliminar). A través d’aquests, es poden afegir, eliminar, editar i actualitzar les diferents entitats del sistema. A més, també s’encarreguen del control de l’inventari: quan entra un producte, aquest s’afegeix a l’estoc, i quan surt, es descompta de l’inventari, garantint així un seguiment correcte de les existències.

## Frontend
Per al desenvolupament del frontend he utilitzat una estructura basada en components i pages. Dins de la carpeta pages s’hi troba la lògica corresponent a Proveïdor, Client, Comanda, Usuari i Producte, mentre que a la carpeta components s’han definit elements reutilitzables com el Layout, el Modal (per reutilitzar-lo en diferents parts de l’aplicació) i el PrivateRoute per al control d’accés.

Pel que fa als estils, s’ha utilitzat Bootstrap.

# Esquemas
## Diagrama de classes
<img width="648" height="861" alt="Captura de pantalla 2025-12-23 130113" src="https://github.com/user-attachments/assets/a5d51388-c287-497e-90a0-2a20317e48a5" />

<br>

## Esquema de la bbdd
<img width="1110" height="1121" alt="image" src="https://github.com/user-attachments/assets/21e3c110-cd59-4768-9bec-45724523c117" />

# Posar el projecte en producció

Per posar el projecte en funcionament, cal iniciar tant el backend com el frontend.

## Backend
Per engegar el backend, hem d’anar a la carpeta **app** i executar la següent comanda:

```bash
./vendor/bin/sail up -d
```

## Frontend

Per engegar el frontend, hem d’anar a la carpeta **frontend** i executar la següent comanda:

```bash
npm start
```
