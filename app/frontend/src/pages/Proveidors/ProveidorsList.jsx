import { useEffect, useState, useCallback } from "react";
import { Button, Table, Spinner, Alert } from "react-bootstrap";
import { API_URL } from '../../config';

function ProveidorsList() {
  const [proveidors, setProveidors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [editingProveidor, setEditingProveidor] = useState(null);

  const token = localStorage.getItem('token');

  const fetchProveidors = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      const res = await fetch(`${API_URL}/proveidors`, {
        headers: { Accept: 'application/json', Authorization: token ? `Bearer ${token}` : undefined }
      });
      if (!res.ok) throw new Error('Error al obtenir els proveidors');
      const data = await res.json();
      setProveidors(Array.isArray(data.dades) ? data.dades : []);
    } catch (err) {
      setError(err.message || 'Error al obtenir els proveidors');
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => { fetchProveidors(); }, [fetchProveidors]);

  const openModal = (proveidor = null) => {
    setEditingProveidor(proveidor ? { ...proveidor } : { nom: '', correu: '', telefon: '' });
    setShowModal(true);
  };

  const handleChange = (e) => setEditingProveidor({ ...editingProveidor, [e.target.name]: e.target.value });

  const handleSave = async () => {
    try {
      const isEditing = !!editingProveidor.id;
      const url = isEditing ? `${API_URL}/proveidors/${editingProveidor.id}` : `${API_URL}/proveidors`;
      const res = await fetch(url, {
        method: isEditing ? 'PUT' : 'POST',
        headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
        body: JSON.stringify(editingProveidor)
      });
      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || 'Error guardant proveidor');
      if (isEditing) setProveidors(proveidors.map(p => p.id === editingProveidor.id ? editingProveidor : p));
      else setProveidors([...proveidors, data.dada || editingProveidor]);
      setShowModal(false);
    } catch (err) {
      setError(err.message);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('Segur que vols eliminar aquest proveidor?')) return;
    try {
      const res = await fetch(`${API_URL}/proveidors/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' }
      });
      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || 'Error eliminant proveidor');
      setProveidors(proveidors.filter(p => p.id !== id));
    } catch (err) {
      setError(err.message);
    }
  };

  if (loading) return <div className="text-center mt-5"><Spinner animation="border" /></div>;

  return (
    <div className="mt-4">
      <h2>Llista de Proveidors</h2>
      {error && <Alert variant="danger">{error}</Alert>}
      <Button variant="primary" className="mb-3" onClick={() => openModal()}>Crear Proveidor</Button>

      <Table striped bordered hover>
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
          {proveidors.length === 0
            ? <tr><td colSpan="6" className="text-center">No hi ha proveidors disponibles</td></tr>
            : proveidors.map(p => (
                <tr key={p.id}>
                  <td>{p.id}</td>
                  <td>{p.nom}</td>
                  <td>{p.correu}</td>
                  <td>{p.telefon}</td>
                  <td>{new Date(p.created_at).toLocaleDateString('ca-ES')}</td>
                  <td>
                    <Button variant="warning" size="sm" className="me-2" onClick={() => openModal(p)}>Editar</Button>
                    <Button variant="danger" size="sm" onClick={() => handleDelete(p.id)}>Eliminar</Button>
                  </td>
                </tr>
              ))
          }
        </tbody>
      </Table>

      {showModal && editingProveidor && (
        <div className="modal show d-block" tabIndex="-1">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">{editingProveidor.id ? 'Editar proveidor' : 'Crear proveidor'}</h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)} />
              </div>
              <div className="modal-body">
                <input className="form-control mb-2" name="nom" value={editingProveidor.nom} onChange={handleChange} placeholder="Nom" />
                <input className="form-control mb-2" name="correu" value={editingProveidor.correu} onChange={handleChange} placeholder="Correu" />
                <input className="form-control" name="telefon" value={editingProveidor.telefon} onChange={handleChange} placeholder="Telèfon" />
              </div>
              <div className="modal-footer">
                <Button variant="secondary" onClick={() => setShowModal(false)}>Cancel·lar</Button>
                <Button variant="success" onClick={handleSave}>{editingProveidor.id ? 'Guardar' : 'Crear'}</Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default ProveidorsList;
