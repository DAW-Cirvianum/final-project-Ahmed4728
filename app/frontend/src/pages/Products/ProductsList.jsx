import React, { useEffect, useState, useCallback } from 'react';
import { Table, Button, Spinner } from 'react-bootstrap';
import { API_URL } from '../../config';

function ProductsList() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [currentProduct, setCurrentProduct] = useState(null); 

  const token = localStorage.getItem('token');

  const fetchProducts = useCallback(async () => {
    try {
      const res = await fetch(`${API_URL}/productes`, {
        headers: { Accept: 'application/json', Authorization: token ? `Bearer ${token}` : undefined }
      });
      if (!res.ok) throw new Error('Error al obtenir els productes');
      const data = await res.json();
      setProducts(Array.isArray(data.dades) ? data.dades : []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }, [token]);

  const fetchCategories = useCallback(async () => {
    try {
      const res = await fetch(`${API_URL}/categories`, {
        headers: { Accept: 'application/json', Authorization: token ? `Bearer ${token}` : undefined }
      });
      if (!res.ok) throw new Error('Error al obtenir les categories');
      const data = await res.json();
      setCategories(Array.isArray(data.data) ? data.data : []);
    } catch (err) {
      console.error(err.message);
    }
  }, [token]);

  useEffect(() => {
    fetchProducts();
    fetchCategories();
  }, [fetchProducts, fetchCategories]);

  const handleDelete = async (id) => {
    if (!window.confirm('Segur que vols eliminar aquest producte?')) return;
    try {
      const res = await fetch(`${API_URL}/productes/${id}`, {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}`, Accept: 'application/json' },
      });
      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || 'Error eliminant producte');
      setProducts(products.filter(p => p.id !== id));
    } catch (err) {
      alert(err.message);
    }
  };

  const openModal = (product = null) => {
    setCurrentProduct(product ? { ...product } : { nom: '', referencia: '', descripcio: '', quantitat: 0, categoria_id: '' });
    setShowModal(true);
  };

  const handleChange = (e) => {
    setCurrentProduct({ ...currentProduct, [e.target.name]: e.target.value });
  };

  const handleSave = async () => {
    const isEditing = !!currentProduct.id;
    const url = isEditing ? `${API_URL}/productes/${currentProduct.id}` : `${API_URL}/productes`;
    const method = isEditing ? 'PUT' : 'POST';

    try {
      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
        body: JSON.stringify(currentProduct)
      });
      const data = await res.json();
      if (!res.ok || data.exit === false) throw new Error(data.missatge || 'Error guardant producte');
      if (isEditing) setProducts(products.map(p => p.id === currentProduct.id ? currentProduct : p));
      setShowModal(false);
      setCurrentProduct(null);
    } catch (err) {
      alert(err.message);
    }
  };

  if (loading) return <div className="text-center mt-5"><Spinner animation="border" /></div>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="mt-4">
      <h2>Llista de Productes</h2>
      <Button variant="primary" className="mb-3" onClick={() => openModal()}>Crear Producte</Button>

      <Table striped bordered hover>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Referència</th>
            <th>Descripció</th>
            <th>Quantitat</th>
            <th>Categoria</th>
            <th>Accions</th>
          </tr>
        </thead>
        <tbody>
          {products.length === 0
            ? <tr><td colSpan="7" className="text-center">No hi ha productes disponibles.</td></tr>
            : products.map(product => (
                <tr key={product.id}>
                  <td>{product.id}</td>
                  <td>{product.nom}</td>
                  <td>{product.referencia}</td>
                  <td>{product.descripcio}</td>
                  <td>{product.quantitat}</td>
                  <td>{categories.find(c => c.id === product.categoria_id)?.nom || '—'}</td>
                  <td>
                    <Button variant="warning" size="sm" className="me-2" onClick={() => openModal(product)}>Editar</Button>
                    <Button variant="danger" size="sm" onClick={() => handleDelete(product.id)}>Eliminar</Button>
                  </td>
                </tr>
              ))
          }
        </tbody>
      </Table>

      {showModal && (
        <div className="modal show d-block" tabIndex="-1">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">{currentProduct.id ? 'Editar Producte' : 'Crear Producte'}</h5>
                <button type="button" className="btn-close" onClick={() => setShowModal(false)} />
              </div>
              <div className="modal-body">
                <input className="form-control mb-2" name="nom" value={currentProduct.nom} onChange={handleChange} placeholder="Nom" />
                <input className="form-control mb-2" name="referencia" value={currentProduct.referencia} onChange={handleChange} placeholder="Referència" />
                <textarea className="form-control mb-2" name="descripcio" value={currentProduct.descripcio} onChange={handleChange} placeholder="Descripció" />
                <input type="number" className="form-control mb-2" name="quantitat" value={currentProduct.quantitat} onChange={handleChange} placeholder="Quantitat" />
                <select className="form-control" name="categoria_id" value={currentProduct.categoria_id} onChange={handleChange}>
                  <option value="">-- Selecciona categoria --</option>
                  {categories.map(c => <option key={c.id} value={c.id}>{c.nom}</option>)}
                </select>
              </div>
              <div className="modal-footer">
                <Button variant="secondary" onClick={() => setShowModal(false)}>Cancel·lar</Button>
                <Button variant="success" onClick={handleSave}>{currentProduct.id ? 'Guardar' : 'Crear'}</Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default ProductsList;
