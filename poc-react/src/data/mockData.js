// src/data/mockData.js

export const INITIAL_ITEMS = [
  { id: 'i1', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Burger Smash', station: 'cocina', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 2, estimated_time: 10, notes: ['Sin cebolla'] },
  { id: 'i2', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Patatas Bravas', station: 'cocina', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 1, estimated_time: 8, notes: [] },
  { id: 'i3', orderId: 'O-101', mesa: 'Mesa 12', producto: 'Ensalada César', station: 'barra', estado: 'pendiente', estado_timestamp: Date.now() - 1000 * 60 * 3, estimated_time: 5, notes: ['Salsa aparte'] },
  { id: 'i4', orderId: 'O-102', mesa: 'Mesa 04', producto: 'Pizza Margarita', station: 'horno', estado: 'horno', estado_timestamp: Date.now() - 1000 * 60 * 9, estimated_time: 8, notes: [] },
  { id: 'i5', orderId: 'O-103', mesa: 'Mesa 08', producto: 'Entrecot', station: 'cocina', estado: 'cocina', estado_timestamp: Date.now() - 1000 * 60 * 12, estimated_time: 15, notes: ['Punto menos'] },
  { id: 'i6', orderId: 'O-104', mesa: 'Mesa 02', producto: 'Gazpacho', station: 'barra', estado: 'emplatado', estado_timestamp: Date.now() - 1000 * 60 * 1, estimated_time: 2, notes: [] },
  { id: 'i7', orderId: 'O-104', mesa: 'Mesa 02', producto: 'Cerveza Artesanal', station: 'barra', estado: 'listo', estado_timestamp: Date.now() - 1000 * 60 * 15, estimated_time: 2, notes: [] }
];

export const generateMassiveData = () => {
  const items = [];
  const products = [
    { n: 'Burger Smash', s: 'cocina', e: 10 },
    { n: 'Patatas Bravas', s: 'cocina', e: 8 },
    { n: 'Ensalada César', s: 'barra', e: 5 },
    { n: 'Pizza Margarita', s: 'horno', e: 12 },
    { n: 'Pizza 4 Quesos', s: 'horno', e: 14 },
    { n: 'Entrecot', s: 'cocina', e: 15 },
    { n: 'Costillas BBQ', s: 'horno', e: 18 },
    { n: 'Gazpacho', s: 'barra', e: 3 },
    { n: 'Cerveza de Barril', s: 'barra', e: 2 },
    { n: 'Vino Tinto', s: 'barra', e: 2 },
    { n: 'Tarta Queso', s: 'barra', e: 4 }
  ];

  for (let t = 1; t <= 20; t++) {
    const numItems = Math.floor(Math.random() * 15) + 5;
    const orderId = `O-MASS-${t}`;
    const mesa = `Mesa ${t.toString().padStart(2, '0')}`;

    for (let i = 0; i < numItems; i++) {
        const prod = products[Math.floor(Math.random() * products.length)];
        let state = 'pendiente';
        const rand = Math.random();
        if (rand > 0.8) state = 'emplatado';
        else if (rand > 0.5) state = prod.s;

        const noteOptions = ['Urgente', 'Sin sal'];
        const randomNote = noteOptions[Math.floor(Math.random() * noteOptions.length)];
        const itemNotes = Math.random() > 0.85 ? [randomNote] : [];

        items.push({
            id: `m_i_${t}_${i}`,
            orderId,
            mesa,
            producto: prod.n,
            station: prod.s,
            estado: state,
            estado_timestamp: Date.now() - Math.floor(Math.random() * 25 * 60000),   
            estimated_time: prod.e,
            notes: itemNotes
        });
    }
  }
  return items;
};

export const WORKFLOW = {
  pendiente: (station) => station,
  barra: () => 'emplatado',
  cocina: () => 'emplatado',
  horno: () => 'emplatado',
  emplatado: () => 'listo'
};

export const STATION_COLORS = {
  barra: '#06b6d4',
  cocina: '#ef4444',
  horno: '#f97316'
};
