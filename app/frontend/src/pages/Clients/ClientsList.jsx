import { useEffect, useState, useCallback } from "react";
import { Button, Table, Spinner, Alert } from "react-bootstrap";
import { API_URL } from '../../config';

function ClientsList() {
  const [clients, setClients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [editingClient, setEditingClient] = useState(null);

  const token = localStorage.getItem('token');

  const fetchClients = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);

      const res = await fetch(`${API_URL}/clients`, {
        headers: {
          Accept: 'application/json',
          Authorization: token ? `Bearer ${token}` : undefined,
        },
      });

      if (!res.ok) throw new Error('Error al obtenir els clients');

      const data = await res.json();
      setClients(Array.isArray(data.dades) ? data.dades : []);
    } catch (err) {
      setError(err.message || 'Error al obtenir els clients');
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchClients();
  }, [fetchClients]);

  const openCreateModal = () => {
    setEditingClient({ nom: '', correu: '', telefon: '' });
    setShowModal(true);
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Segur que vols eliminar aquest client?')) return;
    try {
      const res = await fetch(`${API_URL}/clients/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        },
      });

      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || 'Error eliminant client');

      setClients(clients.filter(c => c.id !== id));
    } catch (err) {
      alert(err.message);
    }
  };

  const openEditModal = (client) => {
    setEditingClient({ ...client });
    setShowModal(true);
  };

  const handleChange = (e) => {
    setEditingClient({ ...editingClient, [e.target.name]: e.target.value });
  };

  const handleSave = async () => {
    const isEditing = !!editingClient.id;
    const url = isEditing
      ? `${API_URL}/clients/${editingClient.id}`
      : `${API_URL}/clients`;
    const method = isEditing ? 'PUT' : 'POST';

    try {
      const res = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        },
        body: JSON.stringify(editingClient)
      });

      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || (isEditing ? 'Error actualitzant client' : 'Error creant client'));

      if (isEditing) setClients(clients.map(c => c.id === editingClient.id ? editingClient : c));

      setShowModal(false);
      setEditingClient(null);
    } catch (err) {
      alert(err.message);
    }
  };

  if (loading) {
    return (
      <div className="text-center mt-5">
        <Spinner animation="border" />
      </div>
    );
  }

  if (error) {
    return (
      <Alert variant="danger" className="mt-3">
        {error}
      </Alert>
    );
  }

  return (
    <div className="mt-4">
      <h2>Llista de Clients</h2>
      <Button variant="primary" className="mb-3" onClick={openCreateModal}>Crear Client</Button>

      <Table striped bordered hover className="mt-3">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Correu</th>
            <th>Telèfon</th>
            <th>Data de Creació</th>
            <th>Accions</th>
          </tr>
        </thead>
        <tbody>
          {clients.length === 0 && (
            <tr><td colSpan="6" className="text-center">No hi ha clients disponibles.</td></tr>
          )}
          {clients.map((client) => (
            <tr key={client.id}>
              <td>{client.id}</td>
              <td>{client.nom}</td>
              <td>{client.correu}</td>
              <td>{client.telefon}</td>
              <td>{new Date(client.created_at).toLocaleDateString('ca-ES')}</td>
              <td>
                <Button variant="warning" size="sm" className="me-2" onClick={() => openEditModal(client)}>Editar</Button>
                <Button variant="danger" size="sm" onClick={() => handleDelete(client.id)}>Eliminar</Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      {showModal && editingClient && (
        <div className="modal show d-block" tabIndex="-1">
          <div className="modal-dialog">
            <div className="modal-content">

              <div className="modal-header">
                <h5 className="modal-title">{editingClient.id ? 'Editar client' : 'Crear client'}</h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)} />
              </div>

              <div className="modal-body">
                <input className="form-control mb-2" name="nom" value={editingClient.nom} onChange={handleChange} placeholder="Nom" />
                <input className="form-control mb-2" name="correu" value={editingClient.correu} onChange={handleChange} placeholder="Correu" />
                <input className="form-control" name="telefon" value={editingClient.telefon} onChange={handleChange} placeholder="Telèfon" />
              </div>

              <div className="modal-footer">
                <Button variant="secondary" onClick={() => setShowModal(false)}>Cancel·lar</Button>
                <Button variant="success" onClick={handleSave}>{editingClient.id ? 'Guardar' : 'Crear'}</Button>
              </div>

            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default ClientsList;
