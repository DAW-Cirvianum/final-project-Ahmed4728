import { useEffect, useState, useMemo, useCallback } from "react";
import { Button, Table, Spinner, Alert } from "react-bootstrap";
import { API_URL } from '../../config';

function OrdersList() {
  const [orders, setOrders] = useState([]);
  const [clients, setClients] = useState([]);
  const [proveidors, setProveidors] = useState([]);
  const [products, setProducts] = useState([]);

  

  const [orderFilters, setOrderFilters] = useState({ tipus: '', dateFrom: '', dateTo: '' });
  const [orderFilterErrors, setOrderFilterErrors] = useState({});

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [editingOrder, setEditingOrder] = useState(null);
  const [showProductsModal, setShowProductsModal] = useState(false);
  const [viewedOrderProducts, setViewedOrderProducts] = useState([]);
  const [viewingOrder, setViewingOrder] = useState(null);

  const token = localStorage.getItem("token");

  const headers = {
    Accept: "application/json",
    Authorization: token ? `Bearer ${token}` : undefined,
  };


  const fetchOrders = useCallback(async () => {
    const h = { Accept: "application/json", Authorization: token ? `Bearer ${token}` : undefined };
    const res = await fetch(`${API_URL}/comandes`, { headers: h });
    const json = await res.json();
    setOrders(json.dades || []);
  }, [token]);

  const fetchClients = useCallback(async () => {
    const h = { Accept: "application/json", Authorization: token ? `Bearer ${token}` : undefined };
    const res = await fetch(`${API_URL}/clients`, { headers: h });
    const json = await res.json();
    setClients(json.dades || []);
  }, [token]);

  const fetchProveidors = useCallback(async () => {
    const h = { Accept: "application/json", Authorization: token ? `Bearer ${token}` : undefined };
    const res = await fetch(`${API_URL}/proveidors`, { headers: h });
    const json = await res.json();
    setProveidors(json.dades || []);
  }, [token]);

  const fetchProducts = useCallback(async () => {
    const h = { Accept: "application/json", Authorization: token ? `Bearer ${token}` : undefined };
    const res = await fetch(`${API_URL}/productes`, { headers: h });
    const json = await res.json();
    setProducts(json.dades || []);
  }, [token]);

  const validateOrderFilters = (f) => {
    const errs = {};
    if (f.dateFrom && isNaN(Date.parse(f.dateFrom))) errs.message = "Data d'inici no vàlida";
    if (f.dateTo && isNaN(Date.parse(f.dateTo))) errs.message = "Data final no vàlida";
    if (!errs.message && f.dateFrom && f.dateTo && new Date(f.dateFrom) > new Date(f.dateTo)) errs.message = "Data d'inici no pot ser posterior a la data final";
    setOrderFilterErrors(errs);
    return Object.keys(errs).length === 0;
  };

  const resetOrderFilters = () => {
    setOrderFilters({ tipus: '', dateFrom: '', dateTo: '' });
    setOrderFilterErrors({});
  };

  

  const filteredOrders = useMemo(() => {
    let list = orders || [];
    if (orderFilters.tipus) list = list.filter(o => o.tipus === orderFilters.tipus);
    if (orderFilters.dateFrom) {
      const from = new Date(orderFilters.dateFrom);
      list = list.filter(o => new Date(o.data) >= from);
    }
    if (orderFilters.dateTo) {
      const to = new Date(orderFilters.dateTo);
      list = list.filter(o => new Date(o.data) <= to);
    }
    return list;
  }, [orders, orderFilters]);

  useEffect(() => {
    Promise.all([
      fetchOrders(),
      fetchClients(),
      fetchProveidors(),
      fetchProducts(),
    ])
      .catch(err => setError(err.message))
      .finally(() => setLoading(false));
  }, [fetchOrders, fetchClients, fetchProveidors, fetchProducts]);


  const openCreateModal = () => {
    setEditingOrder({
      data: new Date().toISOString().slice(0, 10),
      tipus: "",
      client_id: "",
      proveidor_id: "",
      detalls: [],
    });
    setShowModal(true);
  };




  const handleSave = async () => {
    if (!token) return alert("No autenticat");

    const payload = {
      data: editingOrder.data,
      tipus: editingOrder.tipus,
      client_id: editingOrder.client_id || null,
      proveidor_id: editingOrder.proveidor_id || null,
      detalls: editingOrder.detalls.map(d => ({
        producte_id: d.producte_id,
        quantitat: d.quantitat,
      })),
    };

    const res = await fetch(`${API_URL}/comandes`, {
      method: "POST",
      headers: { ...headers, "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const json = await res.json();
    if (!res.ok || json.exit === false) return alert(json.missatge);

    setOrders([...orders, json.dades]);
    setShowModal(false);
  };

  /* ---------------- DELETE ---------------- */

  const handleDelete = async (id) => {
    if (!window.confirm("Eliminar comanda?")) return;

    const res = await fetch(`${API_URL}/comandes/${id}`, {
      method: "DELETE",
      headers,
    });

    const json = await res.json();
    if (!res.ok) return alert(json.missatge);

    setOrders(orders.filter(o => o.id !== id));
  };

const viewProducts = async (id) => {
  const order = orders.find(o => o.id === Number(id));

  const normalizeDetalls = (detalls) =>
    (detalls || []).map(d => ({
      producte_id: d.producte_id ?? d.producte?.id,
      nom: d.nom ?? d.producte?.nom,
      quantitat: d.quantitat,
    }));

  const existingRaw = order?.detallsComanda ?? order?.detalls_comanda;
  if (existingRaw && existingRaw.length) {
    setViewedOrderProducts(normalizeDetalls(existingRaw));
    setViewingOrder(order);
    setShowProductsModal(true);
    return;
  }

  try {
    const res = await fetch(`${API_URL}/comandes/${id}`, { headers });
    if (!res.ok) throw new Error("Error carregant la comanda");

    const json = await res.json();
    const comanda = json.dades ?? json;
    const fetchedRaw = comanda?.detallsComanda ?? comanda?.detalls_comanda ?? [];

    setViewedOrderProducts(normalizeDetalls(fetchedRaw));
    setViewingOrder(comanda ?? null);
    setShowProductsModal(true);
  } catch (err) {
    alert("Error carregant productes: " + err.message);
  }
};


  /* ---------------- RENDER ---------------- */

  if (loading) return <Spinner className="mt-5" />;
  if (error) return <Alert variant="danger">{error}</Alert>;

  return (
    <div className="mt-4">
      <h2>Comandes</h2>
      <Button onClick={openCreateModal}>Crear Comanda</Button>

      <div className="mt-3 p-3 border rounded">
        <h6>Filtrar comandes</h6>
        <div className="d-flex gap-2 flex-wrap align-items-end">
          <div>
            <label className="form-label">Tipus</label>
            <select
              className="form-control"
              value={orderFilters.tipus}
              onChange={e => {
                const next = { ...orderFilters, tipus: e.target.value };
                setOrderFilters(next);
                validateOrderFilters(next);
              }}
            >
              <option value="">Tots</option>
              <option value="entrada">Entrada</option>
              <option value="sortida">Sortida</option>
            </select>
          </div>

          <div>
            <label className="form-label">Data des</label>
            <input
              type="date"
              className="form-control"
              value={orderFilters.dateFrom}
              onChange={e => {
                const next = { ...orderFilters, dateFrom: e.target.value };
                setOrderFilters(next);
                validateOrderFilters(next);
              }}
            />
          </div>

          <div>
            <label className="form-label">Data fins</label>
            <input
              type="date"
              className="form-control"
              value={orderFilters.dateTo}
              onChange={e => {
                const next = { ...orderFilters, dateTo: e.target.value };
                setOrderFilters(next);
                validateOrderFilters(next);
              }}
            />
          </div>

          <div>
            <label className="form-label">&nbsp;</label>
            <div>
              <Button variant="secondary" onClick={resetOrderFilters}>Reset</Button>
            </div>
          </div>
        </div>
        {orderFilterErrors.message && <div className="text-danger mt-2">{orderFilterErrors.message}</div>}
      </div>

      

      <Table striped bordered className="mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Proveïdor</th>
            <th>Tipus</th>
            <th>Data</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {filteredOrders.map(o => (
            <tr key={o.id}>
              <td>{o.id}</td>
              <td>{o.client?.nom || "-"}</td>
              <td>{o.proveidor?.nom || "-"}</td>
              <td>{o.tipus}</td>
              <td>{new Date(o.data).toLocaleDateString()}</td>
              <td className="d-flex gap-2">
                <Button size="sm" variant="primary" onClick={() => viewProducts(o.id)}>
                  Ver productos
                </Button>
                <Button size="sm" variant="danger" onClick={() => handleDelete(o.id)}>
                  Eliminar
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

{showModal && editingOrder && (
  <div className="modal show d-block" tabIndex="-1">
    <div className="modal-dialog modal-lg">
      <div className="modal-content">

        {}
        <div className="modal-header">
          <h5 className="modal-title">
            {editingOrder.id ? 'Editar comanda' : 'Crear comanda'}
          </h5>
          <button className="btn-close" onClick={() => setShowModal(false)} />
        </div>

        {{}}
        <div className="modal-body">

          {}
          <input
            type="date"
            className="form-control mb-2"
            value={editingOrder.data}
            onChange={e => setEditingOrder({ ...editingOrder, data: e.target.value })}
          />

          {}
          <select
            className="form-control mb-3"
            value={editingOrder.tipus}
            onChange={e => setEditingOrder({ ...editingOrder, tipus: e.target.value })}
          >
            <option value="">Selecciona tipus</option>
            <option value="entrada">Entrada</option>
            <option value="sortida">Sortida</option>
          </select>

          {}
          {editingOrder.tipus === 'sortida' && (
            <select
              className="form-control mb-3"
              value={editingOrder.client_id}
              onChange={e =>
                setEditingOrder({ ...editingOrder, client_id: e.target.value, proveidor_id: '' })
              }
            >
              <option value="">Selecciona client</option>
              {clients.map(c => (
                <option key={c.id} value={c.id}>{c.nom}</option>
              ))}
            </select>
          )}

          {editingOrder.tipus === 'entrada' && (
            <select
              className="form-control mb-3"
              value={editingOrder.proveidor_id}
              onChange={e =>
                setEditingOrder({ ...editingOrder, proveidor_id: e.target.value, client_id: '' })
              }
            >
              <option value="">Selecciona proveïdor</option>
              {proveidors.map(p => (
                <option key={p.id} value={p.id}>{p.nom}</option>
              ))}
            </select>
          )}

          <hr />

          {}
          <h6>Productes</h6>

          {editingOrder.detalls.map((d, index) => (
            <div key={index} className="d-flex gap-2 mb-2">
              <select
                className="form-control"
                value={d.producte_id}
                onChange={e => {
                  const detalls = [...editingOrder.detalls];
                  detalls[index].producte_id = Number(e.target.value);
                  setEditingOrder({ ...editingOrder, detalls });
                }}
              >
                <option value="">Producte</option>
                {products.map(p => (
                  <option key={p.id} value={p.id}>{p.nom}</option>
                ))}
              </select>

              <input
                type="number"
                min="1"
                className="form-control"
                value={d.quantitat}
                onChange={e => {
                  const detalls = [...editingOrder.detalls];
                  detalls[index].quantitat = Number(e.target.value);
                  setEditingOrder({ ...editingOrder, detalls });
                }}
              />

              <Button
                variant="danger"
                onClick={() => {
                  setEditingOrder({
                    ...editingOrder,
                    detalls: editingOrder.detalls.filter((_, i) => i !== index)
                  });
                }}
              >
                ✕
              </Button>
            </div>
          ))}

          <Button
            variant="secondary"
            onClick={() =>
              setEditingOrder({
                ...editingOrder,
                detalls: [...editingOrder.detalls, { producte_id: '', quantitat: 1 }]
              })
            }
          >
            + Afegir producte
          </Button>
        </div>

        {/* FOOTER */}
        <div className="modal-footer">
          <Button variant="secondary" onClick={() => setShowModal(false)}>
            Cancel·lar
          </Button>
          <Button
            variant="success"
            disabled={
              !editingOrder.tipus ||
              editingOrder.detalls.length === 0 ||
              editingOrder.detalls.some(d => !d.producte_id || !d.quantitat)
            }
            onClick={handleSave}
          >
            Guardar
          </Button>
        </div>

      </div>
    </div>
  </div>
)}

{showProductsModal && (
  <div className="modal show d-block" tabIndex="-1">
    <div className="modal-dialog">
      <div className="modal-content">
        <div className="modal-header">
          <h5 className="modal-title">Productes de la comanda {viewingOrder?.id}</h5>
          <button className="btn-close" onClick={() => setShowProductsModal(false)} />
        </div>
        <div className="modal-body">
          {viewedOrderProducts.length === 0 ? (
            <div>No hi ha productes.</div>
          ) : (
            <ul className="list-group">
              {viewedOrderProducts.map((d, i) => {
                const prod = products.find(p => p.id === d.producte_id) || {};
                const name = d.nom || prod.nom || d.producte_id;
                return (
                  <li key={i} className="list-group-item d-flex justify-content-between align-items-center">
                    <span>{name}</span>
                    <span className="badge bg-secondary">x{d.quantitat}</span>
                  </li>
                );
              })}
            </ul>
          )}
        </div>
        <div className="modal-footer">
          <Button variant="secondary" onClick={() => setShowProductsModal(false)}>Tancar</Button>
        </div>
      </div>
    </div>
  </div>
)}

    </div>
  );
}

export default OrdersList;
