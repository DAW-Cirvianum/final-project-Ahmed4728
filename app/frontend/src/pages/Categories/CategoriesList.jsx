import { useEffect, useState, useCallback } from "react";
import { Button, Table, Spinner, Alert } from "react-bootstrap";
import { API_URL } from '../../config';

function CategoriesList() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const [showModal, setShowModal] = useState(false);
  const [editingCategory, setEditingCategory] = useState(null);

  const token = localStorage.getItem("token");

  const fetchCategories = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);

      const res = await fetch(`${API_URL}/categories`, {
        headers: {
          Accept: "application/json",
          Authorization: token ? `Bearer ${token}` : undefined,
        },
      });

      if (!res.ok) throw new Error("Error al obtenir les categories");

      const json = await res.json();
      setCategories(Array.isArray(json.data) ? json.data : []);

    } catch (err) {
      console.error(err);
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchCategories();
  }, [fetchCategories]);

  const openCreateModal = () => {
    setEditingCategory({ nom: "" });
    setShowModal(true);
  };

  const openEditModal = (category) => {
    setEditingCategory({ ...category });
    setShowModal(true);
  };

  const handleChange = (e) => {
    setEditingCategory({
      ...editingCategory,
      [e.target.name]: e.target.value,
    });
  };

  const handleDelete = async (id) => {
    if (!token) {
      alert("No has iniciat sessio.");
      return;
    }

    if (!window.confirm("Segur que vols eliminar aquesta categoria?")) return;

    try {
      const res = await fetch(`${API_URL}/categories/${id}`, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
      });

      if (res.status === 401) throw new Error("No autorizado");

      if (!res.ok) throw new Error("Error eliminant categoria");

      setCategories((prev) => prev.filter((c) => c.id !== id));
    } catch (err) {
      alert(err.message);
    }
  };

  const handleSave = async () => {
    if (!token) {
      alert("No estás autenticado.");
      return;
    }

    const isEditing = !!editingCategory.id;
    const url = isEditing
      ? `${API_URL}/categories/${editingCategory.id}`
      : `${API_URL}/categories`;

    const method = isEditing ? "PUT" : "POST";

    try {
      const res = await fetch(url, {
        method,
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
          Accept: "application/json",
        },
        body: JSON.stringify(editingCategory),
      });

      if (res.status === 401) throw new Error("No autorizado");

      if (!res.ok) throw new Error("Error guardant categoria");

      const data = await res.json();

      if (isEditing) {
        setCategories((prev) =>
          prev.map((c) => (c.id === data.id ? data : c))
        );
      } else {
        setCategories((prev) => [...prev, data]);
      }

      setShowModal(false);
      setEditingCategory(null);
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
      <h2>Llista de Categories</h2>

      <Button className="mb-3" onClick={openCreateModal}>
        Crear Categoria
      </Button>

      <Table striped bordered hover>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Data de Creació</th>
            <th>Accions</th>
          </tr>
        </thead>
        <tbody>
          {categories.length === 0 && (
            <tr>
              <td colSpan="4" className="text-center">
                No hi ha categories disponibles.
              </td>
            </tr>
          )}

          {categories.map((category) => (
            <tr key={category.id}>
              <td>{category.id}</td>
              <td>{category.nom}</td>
              <td>
                {category.created_at
                  ? new Date(category.created_at).toLocaleDateString("ca-ES")
                  : ""}
              </td>
              <td>
                <Button
                  size="sm"
                  variant="warning"
                  className="me-2"
                  onClick={() => openEditModal(category)}
                >
                  Editar
                </Button>
                <Button
                  size="sm"
                  variant="danger"
                  onClick={() => handleDelete(category.id)}
                >
                  Eliminar
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>

      {showModal && editingCategory && (
        <div className="modal show d-block">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">
                  {editingCategory.id ? "Editar categoria" : "Crear categoria"}
                </h5>
                <button
                  className="btn-close"
                  onClick={() => setShowModal(false)}
                />
              </div>

              <div className="modal-body">
                <input
                  className="form-control"
                  name="nom"
                  value={editingCategory.nom}
                  onChange={handleChange}
                  placeholder="Nom"
                />
              </div>

              <div className="modal-footer">
                <Button variant="secondary" onClick={() => setShowModal(false)}>
                  Cancel·lar
                </Button>
                <Button variant="success" onClick={handleSave}>
                  {editingCategory.id ? "Guardar" : "Crear"}
                </Button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

export default CategoriesList;
