# 🏗️ Arquitectura y Flujo de KitchenSync KDS (Alpine Edition)

Este diagrama explica cómo viajan los datos y cómo reacciona la interfaz sin necesidad de un Virtual DOM pesado, usando el patrón de **Estado Global + Componentes Ligeros**.

```mermaid
flowchart TD
    classDef bg fill:#1e293b,stroke:#334155,stroke-width:2px,color:#f8fafc,rx:8px,ry:8px;
    classDef core fill:#4f46e5,stroke:#3730a3,stroke-width:3px,color:#fff,rx:12px,ry:12px;
    classDef userAction fill:#ea580c,stroke:#c2410c,stroke-width:2px,color:#fff,rx:8px,ry:8px;
    classDef network fill:#059669,stroke:#047857,stroke-width:2px,color:#fff,rx:8px,ry:8px;

    %% -----------------------------------------
    %% BLOQUE 1: BACKEND (El origen de la verdad)
    %% -----------------------------------------
    subgraph BACKEND ["🏢 BACKEND (PHP + SQLite)"]
        direction LR
        DB[(🗄️ Base de Datos\nSQLite)]:::bg
        SSE[📡 kds_api.php\n(Server-Sent Events)]:::network
        POST[📩 update_comanda.php\n(Guarda Cambios)]:::bg
        
        DB -. "Notifica cambios" .-> SSE
        POST ==> "Guarda en BD" ==> DB
    end

    %% -----------------------------------------
    %% BLOQUE 2: EL CEREBRO DE LA APP (Alpine.js)
    %% -----------------------------------------
    subgraph FRONTEND ["🧠 FRONTEND (Alpine.js)"]
        direction TB
        STORE{"📦 Alpine.store('kds')\nEstado Global (items, online)"}:::core
        GETTERS("⚙️ Getters (Lógica)\nAgrupa por mesa/producto"):::bg
        
        STORE === "Se actualiza y\nrecalcula" ===> GETTERS
    end

    %% -----------------------------------------
    %% BLOQUE 3: LA INTERFAZ VISUAL (PHP)
    %% -----------------------------------------
    subgraph UI ["🖥️ INTERFAZ DE USUARIO (PHP Components)"]
        direction LR
        KANBAN["📋 Columnas Kanban\n(kanban_column.php)"]:::bg
        CARD["🃏 Tarjeta de Comanda\n(kanban_card.php)"]:::userAction
        MESAS["🪑 Vista de Mesas\n(mesas_view.php)"]:::bg
        
        KANBAN -. "Contiene" .-> CARD
    end

    %% =========================================
    %% LAS CONEXIONES (EL FLUJO REAL)
    %% =========================================
    
    %% 1. El servidor envía datos al cerebro
    SSE =="1️⃣ Empuja datos\nen tiempo real"==> STORE
    
    %% 2. El cerebro alimenta la interfaz
    GETTERS =="2️⃣ Inyecta datos\nfiltrados"==> KANBAN
    STORE =="2️⃣ Inyecta datos"==> MESAS
    
    %% 3. El usuario interactúa
    CARD =="3️⃣ Long Press\n(Cambio de estado)"==> STORE
    MESAS =="3️⃣ Marchar Pase\n(Click)"==> STORE
    
    %% 4. El cerebro hace el fetch
    STORE =="4️⃣ Actualiza UI al instante\ny envía fetch() POST"==> POST
```

## 🗝️ Conceptos Clave de este Flujo

### 1. El "Cerebro" Central (`Alpine.store`)
A diferencia de React, donde pasas *props* hacia abajo, aquí los componentes (`kanban_card.php`) "beben" directamente de un almacén global. 
- **Ventaja:** No hay "Prop Drilling" (pasar datos por 5 niveles).
- **Control:** Si cambias algo en el store, **todo** lo que use esa variable en el HTML se actualiza al instante.

### 2. Getters vs `useMemo`
En el diagrama, verás el nodo **GETTERS**. En `store.js`, tenemos `get groupedColumns()`. 
- En React usarías un `useMemo` para agrupar los platos. 
- En Alpine, simplemente defines una función con `get`. Alpine es lo suficientemente inteligente para saber que si `items` cambia, debe re-ejecutar el getter.

### 3. Actualización Optimista
Cuando pulsas una comanda:
1.  **UI:** Alpine cambia el color de la carta *antes* de que el servidor responda (Acción: `advanceState`).
2.  **Red:** Se envía el `fetch()`.
3.  **Error:** Si la red falla, el store hace un "Rollback" (vuelve al estado anterior). 
Esto hace que la aplicación se sienta instantánea, como si fuera nativa.

### 4. Ciclo de Vida del Timer
Cada carta (`kanbanCard.js`) no tiene su propio `setInterval`. 
- Tenemos **un solo reloj** en el Store (`lastUpdate`). 
- Todas las cartas escuchan ese valor global para recalcular sus minutos/segundos. 
- **Resultado:** Menos consumo de CPU y todos los relojes están sincronizados al milisegundo.

---

### 🚀 ¿Qué te gustaría profundizar?
- **El flujo de datos SSE:** Cómo el backend "empuja" la información.
- **La lógica del Store:** Cómo gestionamos los Toasts y el "Deshacer".
- **Los Componentes Alpine.data:** Cómo aislamos la lógica del Long Press.